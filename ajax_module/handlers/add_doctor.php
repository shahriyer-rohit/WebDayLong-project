<?php
// AJAX handler for Add Doctor form
// This file is intentionally self-contained and lives under /ajax_module
session_start();

// Load app config and helpers
require_once __DIR__ . '/../../config/app.php';
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/models/UserModel.php';
require_once BASE_PATH . '/models/DoctorModel.php';
require_once BASE_PATH . '/models/SpecializationModel.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Invalid request method.'], 405);
}

// Basic check: expect Ajax but allow direct POST (fallback)
// Collect inputs (works with FormData including file)
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$spec_id  = (int)($_POST['specialization_id'] ?? 0);
$bio      = trim($_POST['bio'] ?? '');
$fee      = trim($_POST['consultation_fee'] ?? '');
$days     = $_POST['available_days'] ?? [];
$errors   = [];

// Reuse same validation rules as AdminController::createDoctor
if (empty($name))          $errors['name']     = 'Name is required.';
if (empty($email))              $errors['email']    = 'Email is required.';
elseif (!validateEmail($email)) $errors['email']    = 'Enter a valid email.';
if (empty($password))      $errors['password'] = 'Password is required.';
elseif (strlen($password) < 6) $errors['password'] = 'Password must be at least 6 characters.';
if ($spec_id <= 0)         $errors['specialization_id'] = 'Select a specialization.';
if (empty($fee) || !is_numeric($fee) || (float)$fee < 0)
    $errors['consultation_fee'] = 'Enter a valid consultation fee.';

// Photo upload handling (same rules as AdminController)
$photoPath = null;
if (!empty($_FILES['photo']['name'])) {
    $file = $_FILES['photo'];
    $uploadError = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($uploadError !== UPLOAD_ERR_OK) {
        if ($uploadError === UPLOAD_ERR_INI_SIZE || $uploadError === UPLOAD_ERR_FORM_SIZE) {
            $errors['photo'] = 'Photo exceeds upload limit. Set upload_max_filesize and post_max_size to at least 5M in php.ini.';
        } elseif ($uploadError === UPLOAD_ERR_NO_FILE) {
            $errors['photo'] = 'Please select a photo to upload.';
        } else {
            $errors['photo'] = 'Failed to upload photo.';
        }
    } else {
        if (($file['size'] ?? 0) > MAX_PHOTO_SIZE) {
            $errors['photo'] = 'Photo must be under 5MB.';
        } else {
            $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png'];
            if (!in_array($ext, $allowedExt, true)) {
                $errors['photo'] = 'Only JPG, JPEG, and PNG images are allowed.';
            } else {
                $mime = '';
                if (function_exists('mime_content_type')) {
                    $mime = (string)mime_content_type($file['tmp_name']);
                }
                $allowedMime = ['image/jpeg', 'image/pjpeg', 'image/jpg', 'image/png', 'image/x-png'];
                if ($mime !== '' && !in_array($mime, $allowedMime, true)) {
                    $errors['photo'] = 'Only JPG, JPEG, and PNG images are allowed.';
                } else {
                    // ensure upload dir
                    if (!is_dir(UPLOAD_PATH)) {
                        @mkdir(UPLOAD_PATH, 0775, true);
                    }
                    if (!is_writable(UPLOAD_PATH)) {
                        $errors['photo'] = 'Upload folder is not writable.';
                    } else {
                        $normalizedExt = ($ext === 'jpeg') ? 'jpg' : $ext;
                        $filename = 'doc_' . time() . '_' . rand(100, 999) . '.' . $normalizedExt;
                        if (!move_uploaded_file($file['tmp_name'], UPLOAD_PATH . $filename)) {
                            $errors['photo'] = 'Failed to upload photo.';
                        } else {
                            $photoPath = $filename;
                        }
                    }
                }
            }
        }
    }
}

// instantiate models
$userModel   = new UserModel();
$doctorModel = new DoctorModel();
$specModel   = new SpecializationModel();

if (!$errors && $userModel->findByEmail($email)) {
    $errors['email'] = 'This email is already registered.';
}

if ($errors) {
    jsonResponse(['success' => false, 'errors' => $errors], 422);
}

// Create user and doctor records
$userId = $userModel->create(['name'=>$name,'email'=>$email,'password'=>$password,'role'=>'doctor']);
$availDays = implode(',', array_filter($days, fn($d) => in_array($d, ['Monday','Tuesday','Wednesday','Thursday','Friday'])));
$newDoctorId = $doctorModel->create([
    'user_id' => $userId,
    'specialization_id' => $spec_id,
    'bio' => $bio,
    'consultation_fee' => (float)$fee,
    'photo_path' => $photoPath,
    'available_days' => $availDays
]);

// Prepare response row data
$spec = $specModel->findById($spec_id);
$doctorRow = [
    'id' => (int)$newDoctorId,
    'name' => $name,
    'email' => $email,
    'specialization_name' => $spec ? $spec['name'] : '',
    'consultation_fee' => number_format((float)$fee, 2),
    'available_days' => $availDays ?: 'Not set',
    'is_active' => 1,
    'total_appointments' => 0,
    'photo_url' => $photoPath ? UPLOAD_URL . $photoPath : null
];

jsonResponse(['success' => true, 'doctor' => $doctorRow]);
