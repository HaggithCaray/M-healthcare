<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Health Worker',
            'email' => 'health@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $elenaUser = User::create([
            'name' => 'Elena Dela Cruz',
            'email' => 'patient@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        Patient::create([
            'user_id' => $elenaUser->id,
            'first_name' => 'Elena',
            'last_name' => 'Dela Cruz',
            'dob' => '1995-05-12',
            'gender' => 'Female',
            'phone' => '09171234567',
            'email' => 'patient@example.com',
            'address' => 'Purok 2, Barangay Bicao',
            'barangay' => 'Bicao',
            'occupation' => 'Housewife',
            'emergency_contact_name' => 'Juan Dela Cruz',
            'emergency_contact_phone' => '09187654321',
            'registration_type' => 'Maternal',
            'status' => 'Active',
        ]);

        Patient::create([
            'first_name' => 'Liam',
            'last_name' => 'Andres',
            'dob' => '2025-10-05',
            'gender' => 'Male',
            'phone' => '09171234567',
            'email' => null,
            'address' => 'Purok 2, Barangay Bicao',
            'barangay' => 'Bicao',
            'registration_type' => 'Child',
            'status' => 'Active',
        ]);

        Patient::create([
            'first_name' => 'Sofia',
            'last_name' => 'Garcia',
            'dob' => '2026-05-02',
            'gender' => 'Female',
            'phone' => '09171234567',
            'email' => null,
            'address' => 'Purok 2, Barangay Bicao',
            'barangay' => 'Bicao',
            'registration_type' => 'Child',
            'status' => 'Due for Visit',
        ]);

        Patient::create([
            'first_name' => 'Maria',
            'last_name' => 'Santos-Dizon',
            'dob' => '1995-02-15',
            'gender' => 'Female',
            'phone' => '09159998888',
            'email' => 'maria@example.com',
            'address' => 'Purok 4, Barangay Bicao',
            'barangay' => 'Bicao',
            'occupation' => 'Teacher',
            'emergency_contact_name' => 'Pedro Dizon',
            'emergency_contact_phone' => '09161112222',
            'registration_type' => 'Maternal',
            'status' => 'High Risk',
        ]);
    }
}
