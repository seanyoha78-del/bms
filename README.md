# Barangay Management System (BMS)

An elegant, role-based management system for local government units (Barangays). Built with PHP, MySQL, and Bootstrap 5 featuring a dark blue and white professional design.

## Features

- **Multi-Role Authentication**: 5 distinct dashboards (Captain, Kagawad, Secretary, Treasurer, SK Chairman).
- **Elegant UI**: Modern sidebar navigation, statistical cards, and clean data tables.
- **Dynamic Content**: Sidebar links and permissions change based on user role.
- **Project Tracking**: Monitor community concerns and projects.
- **Financial Module**: Track budgets, income, and expenses.
- **Records Management**: Manage resident files and document issuance.

## Prerequisites

- PHP 7.4 or higher
- MySQL / MariaDB (via XAMPP, WAMP, or similar)
- Modern Web Browser (Chrome, Firefox, Edge)

## Installation Steps

1. **Database Setup**:
    - Open phpMyAdmin or your MySQL client.
    - Create a database named `barangay_db`.
    - Import the `sql/schema.sql` file provided in this package.

2. **File Configuration**:
    - Ensure your local server folder (e.g., `htdocs` for XAMPP) contains the project folder.
    - Open `config/db.php` and verify the database credentials (username, password, host).

3. **Running the App**:
    - Start Apache and MySQL from your Control Panel.
    - Navigate to `http://localhost/barangay-system/` (or your specific folder path).

## Default Test Credentials

All accounts use the password: `password123`

- **Barangay Captain**: `captain`
- **Barangay Kagawad**: `kagawad`
- **Barangay Secretary**: `secretary`
- **Barangay Treasurer**: `treasurer`
- **SK Chairman**: `sk_chairman`

## Project Structure

- `config/`: Database connection settings.
- `css/`: Custom styling for the Dark Blue/White theme.
- `includes/`: Reusable components (Auth logic, Header, Footer, Sidebar).
- `dashboard/`: Role-specific templates for each user title.
- `index.php`: The login portal.
- `sql/`: Database schema and initial data.

## Troubleshooting

- **Login Failed**: Ensure you have imported the SQL file correctly and that the PHP `password_verify` function matches the hashed password in the DB.
- **CSS not loading**: Verify the paths in the `<link>` tags in `header.php`.
- **Database Connection Error**: Double-check `config/db.php` settings against your local MySQL config.
