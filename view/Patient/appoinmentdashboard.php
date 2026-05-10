<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        header h1 {
            color: #333;
            font-size: 28px;
        }
        
        .logout-btn {
            background: #ff6b6b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .logout-btn:hover {
            background: #ee5a52;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        .card-title {
            color: #667eea;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .card-content {
            color: #555;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .card-action {
            background: #667eea;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
        }
        
        .card-action:hover {
            background: #764ba2;
        }
        
        .appointments-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            color: #333;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        
        .appointment-item {
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .appointment-item:hover {
            background: #e8f0fe;
        }
        
        .appointment-date {
            color: #667eea;
            font-weight: bold;
            font-size: 16px;
        }
        
        .appointment-doctor {
            color: #555;
            margin-top: 5px;
            font-size: 14px;
        }
        
        .appointment-status {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-completed {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .new-appointment-btn {
            background: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .new-appointment-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 Appointment Dashboard</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>
        
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-title">Upcoming</div>
                <div class="card-content">2</div>
                <button class="card-action">View Details</button>
            </div>
            <div class="card">
                <div class="card-title">Completed</div>
                <div class="card-content">8</div>
                <button class="card-action">View History</button>
            </div>
            <div class="card">
                <div class="card-title">Pending</div>
                <div class="card-content">1</div>
                <button class="card-action">Review</button>
            </div>
        </div>
        
        <div class="appointments-section">
            <div class="section-title">Your Appointments</div>
            
            <div class="appointment-item">
                <div class="appointment-date">📅 December 20, 2024 - 10:00 AM</div>
                <div class="appointment-doctor">👨‍⚕️ Dr. John Smith - General Checkup</div>
                <span class="appointment-status status-confirmed">Confirmed</span>
            </div>
            
            <div class="appointment-item">
                <div class="appointment-date">📅 December 25, 2024 - 2:30 PM</div>
                <div class="appointment-doctor">👩‍⚕️ Dr. Sarah Johnson - Follow-up Visit</div>
                <span class="appointment-status status-pending">Pending</span>
            </div>
            
            <div class="appointment-item">
                <div class="appointment-date">📅 December 15, 2024 - 3:00 PM</div>
                <div class="appointment-doctor">👨‍⚕️ Dr. Michael Brown - Dental Checkup</div>
                <span class="appointment-status status-completed">Completed</span>
            </div>
            
            <button class="new-appointment-btn">+ Book New Appointment</button>
        </div>
    </div>
</body>
</html>
