# Online Clinic Appointment Booking System

ICT312 Advanced Web Information Systems - Assignment 01 project prototype.

## Requirements
- XAMPP (Apache + MySQL)
- PHP 8+ recommended
- A web browser

## Installation
1. Copy the `clinic_booking_system` folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Import `database.sql` into phpMyAdmin.
5. Open `http://localhost/clinic_booking_system/`.

## Patient test
Register a new patient from Patient Registration and log in.

## Clinic management test
Open `http://localhost/clinic_booking_system/clinic/login.php`
Username: admin
Password: admin123

The clinic dashboard can confirm, cancel or complete appointment requests.

## Main database tables
- patients
- clinics
- appointments

## Notes
This is a student project prototype for assignment development. For a real healthcare deployment, stronger authentication, role management, HTTPS, audit logs, secure secret configuration, backups and additional privacy controls would be required.
