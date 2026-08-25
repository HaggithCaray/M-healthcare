<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Display a listing of patient records with filtering and KPI summaries.
     */
    public function records(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');

        $query = Patient::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($type && $type !== 'All Types') {
            $query->where('registration_type', $type);
        }

        if ($status && $status !== 'All Status') {
            $query->where('status', $status);
        }

        $patients = $query->latest()->get();

        $totalPatients = Patient::count();
        $maternalCases = Patient::where('registration_type', 'Maternal')->count();
        $childRecords = Patient::where('registration_type', 'Child')->count();
        $dueForVisit = Patient::where('status', 'Due for Visit')->count();

        AuditLog::log('view_patient_records', null, [
            'search' => $search,
            'type' => $type,
            'status' => $status,
            'count' => $patients->count(),
        ]);

        return view('records', compact(
            'patients',
            'totalPatients',
            'maternalCases',
            'childRecords',
            'dueForVisit',
            'search',
            'type',
            'status'
        ));
    }

    /**
     * Show registration form or handle patient creation.
     */
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->store($request);
        }

        return view('register');
    }

    /**
     * Store newly created patient record with secure user provisioning.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone' => 'required|string',
            'registration_type' => 'required|in:Maternal,Child',
        ]);

        return DB::transaction(function () use ($request) {
            $userId = null;
            if ($request->email) {
                $user = User::where('email', $request->email)->first();
                if (!$user) {
                    $temporaryPassword = Str::random(16);
                    $user = User::create([
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make($temporaryPassword),
                        'role' => 'user',
                    ]);
                }
                $userId = $user->id;
            }

            $patient = Patient::create([
                'user_id' => $userId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'barangay' => 'Bicao',
                'occupation' => $request->occupation,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'registration_type' => $request->registration_type,
                'status' => 'Active',
            ]);

            AuditLog::log('create_patient', $patient, [
                'registration_type' => $patient->registration_type,
                'name' => $patient->first_name . ' ' . $patient->last_name,
            ]);

            return redirect()->route('records')->with('success', 'Patient registered successfully!');
        });
    }

    /**
     * Show the edit patient form.
     */
    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);

        AuditLog::log('view_edit_patient', $patient);

        return view('patient.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone' => 'required|string',
            'status' => 'required|string|in:Active,Due for Visit,High Risk,Completed',
        ]);

        $patient->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'occupation' => $request->occupation,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'status' => $request->status,
        ]);

        AuditLog::log('update_patient', $patient);

        return redirect()->route('records')->with('success', 'Patient information updated successfully!');
    }

    /**
     * Display patient portal dashboard.
     */
    public function patientPortal()
    {
        $user = auth()->user();
        $patient = Patient::where('user_id', $user->id)->first();

        AuditLog::log('view_patient_portal', $patient);

        return view('patient.portal', compact('patient'));
    }
}
