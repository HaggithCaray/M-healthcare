<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Admin Midwife',
            'email' => 'admin@maternal.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->regularUser = User::create([
            'name' => 'Regular Patient User',
            'email' => 'patient@maternal.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }

    public function test_guest_cannot_access_protected_routes(): void
    {
        $routes = ['/dashboard', '/records', '/register', '/portal'];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect(route('login'));
        }
    }

    public function test_guest_cannot_access_edit_patient(): void
    {
        $patient = Patient::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'dob' => '1995-05-15',
            'gender' => 'Female',
            'phone' => '1234567890',
            'address' => '123 Street',
            'registration_type' => 'Maternal',
        ]);

        $response = $this->get('/patients/' . $patient->id . '/edit');
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_records_page(): void
    {
        Patient::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'dob' => '1995-05-15',
            'gender' => 'Female',
            'phone' => '1234567890',
            'email' => 'jane@example.com',
            'address' => '123 Street',
            'registration_type' => 'Maternal',
        ]);

        Patient::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'dob' => '2026-01-01',
            'gender' => 'Male',
            'phone' => '1234567890',
            'email' => null,
            'address' => '123 Street',
            'registration_type' => 'Child',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/records');

        $response->assertStatus(200);
        $response->assertViewIs('records');
        $response->assertViewHas('patients');
        $response->assertViewHas('totalPatients', 2);
        $response->assertViewHas('maternalCases', 1);
        $response->assertViewHas('childRecords', 1);
    }

    public function test_admin_can_filter_and_search_records(): void
    {
        Patient::create([
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'dob' => '1995-05-15',
            'gender' => 'Female',
            'phone' => '1111111111',
            'email' => 'alice@example.com',
            'address' => '123 Street',
            'registration_type' => 'Maternal',
        ]);

        Patient::create([
            'first_name' => 'Bob',
            'last_name' => 'Jones',
            'dob' => '2026-01-01',
            'gender' => 'Male',
            'phone' => '2222222222',
            'email' => null,
            'address' => '123 Street',
            'registration_type' => 'Child',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/records?search=Alice');
        $response->assertStatus(200);
        $patients = $response->viewData('patients');
        $this->assertCount(1, $patients);
        $this->assertEquals('Alice', $patients->first()->first_name);

        $response = $this->actingAs($this->adminUser)->get('/records?type=Child');
        $response->assertStatus(200);
        $patients = $response->viewData('patients');
        $this->assertCount(1, $patients);
        $this->assertEquals('Bob', $patients->first()->first_name);
    }

    public function test_admin_can_view_registration_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    public function test_admin_can_register_maternal_patient_with_user(): void
    {
        $data = [
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'dob' => '1998-08-10',
            'gender' => 'Female',
            'phone' => '09123456789',
            'email' => 'maria.santos@example.com',
            'address' => 'Brgy. Bicao, Carmen, Bohol',
            'emergency_contact_name' => 'Juan Santos',
            'emergency_contact_phone' => '09987654321',
            'registration_type' => 'Maternal',
        ];

        $response = $this->actingAs($this->adminUser)->post('/register', $data);

        $response->assertRedirect(route('records'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'maria.santos@example.com',
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('patients', [
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'registration_type' => 'Maternal',
            'barangay' => 'Bicao',
        ]);

        $patient = Patient::where('email', 'maria.santos@example.com')->first();
        $this->assertNotNull($patient->user_id);
    }

    public function test_admin_can_register_child_patient(): void
    {
        $data = [
            'first_name' => 'Juanito',
            'last_name' => 'Santos',
            'dob' => '2026-05-01',
            'gender' => 'Male',
            'phone' => '09123456789',
            'email' => null,
            'address' => 'Brgy. Bicao, Carmen, Bohol',
            'emergency_contact_name' => 'Maria Santos',
            'emergency_contact_phone' => '09987654321',
            'registration_type' => 'Child',
        ];

        $response = $this->actingAs($this->adminUser)->post('/register', $data);

        $response->assertRedirect(route('records'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('patients', [
            'first_name' => 'Juanito',
            'last_name' => 'Santos',
            'registration_type' => 'Child',
        ]);

        $patient = Patient::where('first_name', 'Juanito')->first();
        $this->assertNull($patient->user_id);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $patient = Patient::create([
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'dob' => '1998-08-10',
            'gender' => 'Female',
            'phone' => '09123456789',
            'email' => 'maria@example.com',
            'address' => 'Brgy. Bicao, Carmen, Bohol',
            'emergency_contact_name' => 'Juan Santos',
            'emergency_contact_phone' => '09987654321',
            'registration_type' => 'Maternal',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/patients/' . $patient->id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('patient.edit');
        $response->assertViewHas('patient');
    }

    public function test_admin_can_update_patient(): void
    {
        $patient = Patient::create([
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'dob' => '1998-08-10',
            'gender' => 'Female',
            'phone' => '09123456789',
            'email' => 'maria@example.com',
            'address' => 'Brgy. Bicao, Carmen, Bohol',
            'emergency_contact_name' => 'Juan Santos',
            'emergency_contact_phone' => '09987654321',
            'registration_type' => 'Maternal',
            'status' => 'Active',
        ]);

        $updateData = [
            'first_name' => 'Maria Clara',
            'last_name' => 'Santos-Dizon',
            'dob' => '1998-08-10',
            'gender' => 'Female',
            'phone' => '09123456789',
            'email' => 'maria.clara@example.com',
            'address' => 'Updated Address, Brgy. Bicao',
            'emergency_contact_name' => 'Juan Dizon',
            'emergency_contact_phone' => '09987654321',
            'status' => 'High Risk',
            'occupation' => 'Teacher',
        ];

        $response = $this->actingAs($this->adminUser)->put('/patients/' . $patient->id, $updateData);

        $response->assertRedirect(route('records'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'first_name' => 'Maria Clara',
            'last_name' => 'Santos-Dizon',
            'email' => 'maria.clara@example.com',
            'status' => 'High Risk',
            'occupation' => 'Teacher',
        ]);
    }

    public function test_patient_can_access_portal(): void
    {
        $patient = Patient::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'dob' => '1995-05-15',
            'gender' => 'Female',
            'phone' => '1234567890',
            'address' => '123 Street',
            'registration_type' => 'Maternal',
            'user_id' => $this->regularUser->id,
        ]);

        $response = $this->actingAs($this->regularUser)->get('/portal');
        $response->assertStatus(200);
        $response->assertViewIs('patient.portal');
        $response->assertViewHas('patient');
    }
}
