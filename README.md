# Maternal and Child Health Monitoring System

A web-based health monitoring system designed for barangay health workers to manage maternal and child health records in the community.

## Features

- **Dashboard** — Overview of total patients, maternal cases, and child records
- **Patient Registration** — Multi-step form for registering mothers and children
- **Patient Records** — Searchable and filterable records table with status tracking
- **Edit Patient** — Update personal info, contact details, and health status
- **Patient Portal** — Profile view for patients to check their own records
- **Role-Based Access** — Admin (healthcare worker) and user (patient) roles
- **Audit Logging** — Tracks all system actions for accountability

## Tech Stack

- **Backend:** Laravel 13.x (PHP 8.4)
- **Frontend:** Blade + Tailwind CSS v4 + Vite
- **Database:** MySQL 8.0 (via XAMPP)

## Setup and Installation

### Prerequisites

1. **XAMPP** — Download from https://www.apachefriends.org and install to `C:\xampp`
2. **PHP 8.4** — XAMPP ships with PHP 8.2, you need to upgrade (see below)
3. **Node.js** (v20+) and **NPM** — https://nodejs.org
4. **Composer** — https://getcomposer.org

### Step 0: Add PHP to PATH (one-time setup)

Run this **once** as Administrator so `php` works in any terminal:

```powershell
[System.Environment]::SetEnvironmentVariable("Path", [System.Environment]::GetEnvironmentVariable("Path", "User") + ";C:\xampp\php", "User")
```

Then close and reopen your terminal.

### Step 1: Clone the repo

```bash
git clone https://github.com/HaggithCaray/M-healthcare.git
cd M-healthcare
```

### Step 2: Start XAMPP

Open XAMPP Control Panel and start **MySQL** and **Apache**.

### Step 3: Create the database

Open your browser and go to `http://localhost/phpmyadmin`. Then run this SQL:

```sql
CREATE DATABASE healthcare_base_db;
```

Or use the command line:
```powershell
mysql -u root -e "CREATE DATABASE healthcare_base_db;"
```

### Step 4: Install PHP dependencies

```bash
composer install
```

### Step 5: Set up environment file

```bash
cp .env.example .env
php artisan key:generate
```

### Step 6: Configure database in `.env`

Open `.env` and make sure these values are set:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=healthcare_base_db
DB_USERNAME=root
DB_PASSWORD=
```

### Step 7: Run migrations and seed

```bash
php artisan migrate --seed
```

### Step 8: Install frontend dependencies and build

```bash
npm install
npm run build
```

### Step 9: Start the server

```bash
php artisan serve
```

### Step 10: Open the app

Go to **http://127.0.0.1:8000**

## Default Login

| Role | Email | Password |
|------|-------|----------|
| Healthcare Worker (Admin) | health@example.com | password |
| Patient | patient@example.com | password |

## PHP 8.4 Upgrade (Required)

XAMPP ships with PHP 8.2 but this project requires PHP 8.4. Follow these steps:

1. Stop Apache and MySQL in XAMPP Control Panel

2. Download PHP 8.4 TS (Thread Safe):
   ```
   https://downloads.php.net/~windows/releases/php-8.4.24-Win32-vs17-x64.zip
   ```
   **Important:** Do NOT download the `nts-` version — it won't work with Apache.

3. Rename the existing PHP folder:
   ```
   C:\xampp\php  →  C:\xampp\php8.2_backup
   ```

4. Extract the downloaded PHP 8.4 zip to `C:\xampp\php`

5. Copy php.ini from the backup:
   ```powershell
   Copy-Item "C:\xampp\php8.2_backup\php.ini" "C:\xampp\php\php.ini"
   ```

6. Open `C:\xampp\php\php.ini` and comment out this line (add `;` at the start):
   ```
   ;browscap="C:\xampp\php\extras\browscap.ini"
   ```

7. Make sure these extensions are enabled (no `;` at the start):
   ```ini
   extension=curl
   extension=gd
   extension=mbstring
   extension=mysqli
   extension=pdo_mysql
   extension=pdo_sqlite
   extension=sqlite3
   extension=fileinfo
   extension=bz2
   extension=intl
   extension=sockets
   extension=sodium
   extension=zip
   ```

8. Fix the libssh2 DLL conflict — copy these files:
   ```powershell
   Copy-Item "C:\xampp\php\libssh2.dll" "C:\xampp\apache\bin\libssh2.dll" -Force
   Copy-Item "C:\xampp\php\brotlidec.dll" "C:\xampp\apache\bin\brotlidec.dll" -Force
   Copy-Item "C:\xampp\php\brotlicommon.dll" "C:\xampp\apache\bin\brotlicommon.dll" -Force
   ```

9. Disable SSL config — open `C:\xampp\apache\conf\httpd.conf` and comment out:
   ```
   #Include conf/extra/httpd-ssl.conf
   ```

10. Start Apache and MySQL again

## Troubleshooting

### PHP not recognized
Add PHP to your PATH:
```powershell
$env:Path += ";C:\xampp\php"
```

### Apache won't start (port 80)
Stop IIS if it's running:
```powershell
net stop W3SVC
```

### MySQL login error 1045
Reset the root password:
```powershell
net stop mysql
mysqld --skip-grant-tables
mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY ''; FLUSH PRIVILEGES;"
net start mysql
```

### libssh2_crypto_engine error
Copy the DLLs as shown in Step 8 of the PHP 8.4 upgrade above.

## Related

- Full version (advanced): https://github.com/HaggithCaray/maternal-healthcare
