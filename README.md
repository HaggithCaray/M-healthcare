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
- **Database:** MySQL 8.0
- **Local Server:** XAMPP (Apache + MySQL + PHP)

## System Architecture

The system follows a standard Model-View-Controller (MVC) architecture:

- **Frontend** — Blade templates styled with Tailwind CSS, processed by Vite
- **Backend** — Laravel controllers handling routes and business logic via Eloquent models
- **Database** — MySQL running through XAMPP

## Setup and Installation

### Prerequisites

1. **XAMPP** — Local server with Apache + MySQL + PHP 8.4
2. **Node.js** (v20+) and **NPM** — For building frontend assets
3. **Composer** — PHP dependency manager

### Steps

1. **Clone the repo**
   ```bash
   git clone https://github.com/HaggithCaray/healthcare.git
   cd healthcare
   ```

2. **Start MySQL** — Open XAMPP Control Panel and start **MySQL** and **Apache**

3. **Create the database** — Open phpMyAdmin (`http://localhost/phpmyadmin`) and run:
   ```sql
   CREATE DATABASE healthcare_db;
   ```

4. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

5. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

6. **Update `.env` database settings**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=healthcare_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Run migrations and seed**
   ```bash
   php artisan migrate --seed
   ```

8. **Start the server**
   ```bash
   php artisan serve
   ```

9. **Open the app** — Go to **http://127.0.0.1:8000**

## Default Login

| Role | Email | Password |
|------|-------|----------|
| Healthcare Worker (Admin) | health@example.com | password |
| Patient | patient@example.com | password |
