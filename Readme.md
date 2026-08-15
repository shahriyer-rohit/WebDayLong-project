# 🏥 MediBook

A modern **Hospital Appointment Booking System** built with **PHP, MySQL, HTML, CSS, JavaScript, and AJAX**. MediBook provides a streamlined platform for patients, doctors, and administrators to manage appointments, doctor schedules, specializations, and hospital operations.

## ✨ Features

### 👤 Patient

* Patient registration and login
* Browse doctors
* Filter doctors by specialization
* View doctor profiles
* View doctor availability
* Check available appointment slots
* Book appointments
* View appointment history
* Track appointment status
* Manage personal profile

### 👨‍⚕️ Doctor

* Doctor dashboard
* View appointments
* Manage appointment status
* View appointment schedules
* Manage availability
* Doctor profile management
* View patient appointment information

### 🛡️ Admin

* Admin dashboard
* Manage users
* Manage doctors
* Add and manage specializations
* Manage appointments
* Activate/deactivate user accounts
* View doctor statistics
* View appointment statistics
* Revenue dashboard
* Revenue reports

### ⚡ Additional Features

* Role-based access control
* Appointment status management
* Dynamic doctor filtering
* Dynamic appointment slot availability
* AJAX-powered interactions
* Form validation
* Secure password hashing
* Responsive user interface
* Custom error pages
* Session-based authentication

## 🛠️ Tech Stack

* **PHP**
* **MySQL**
* **HTML5**
* **CSS3**
* **JavaScript**
* **AJAX**
* **MVC Architecture**

## 🚀 Setup

### Prerequisites

Make sure you have:

* PHP 8+
* MySQL
* Apache / XAMPP / WAMP
* phpMyAdmin
* Web browser

### 1. Clone the Repository

```bash
git clone <repository-url>
cd WebDayLong-project-main
```

### 2. Create the Database

Open **phpMyAdmin** and import:

```text
config/schema.sql
```

This creates the `hospital_db` database along with the required tables and initial data.

### 3. Configure Database

Open:

```text
config/database.php
```

Configure your MySQL connection according to your local environment.

### 4. Configure Application

Open:

```text
config/app.php
```

Set the application URL and upload configuration according to your local server setup.

### 5. Start the Server

If using XAMPP:

1. Start **Apache**
2. Start **MySQL**
3. Place the project inside the `htdocs` directory
4. Open the application in your browser

Example:

```text
http://localhost/WebDayLong-project-main/
```


