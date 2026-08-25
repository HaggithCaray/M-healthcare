@extends('layouts.app')

@section('title', 'My Portal')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-md mb-lg">
    <div>
        <h3 class="font-headline-lg text-headline-md md:text-headline-lg text-primary">Welcome, {{ auth()->user()?->name ?? 'Patient' }}</h3>
        <p class="font-body-md text-body-md text-on-surface-variant">Here's your health profile overview.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-lg">
    <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow border border-outline-variant/10 flex flex-col justify-between h-32 relative overflow-hidden">
        <div>
            <p class="text-label-sm font-label-md text-on-surface-variant uppercase tracking-wider">Registration Type</p>
            <h4 class="text-headline-md font-bold text-primary mt-1">{{ $patient->registration_type ?? 'N/A' }}</h4>
        </div>
        <div class="flex items-center gap-1 text-on-surface-variant font-label-md">
            <span class="material-symbols-outlined text-sm">badge</span>
            <span>Patient category</span>
        </div>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-primary/5 opacity-20">badge</span>
    </div>
    <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow border border-outline-variant/10 flex flex-col justify-between h-32 relative overflow-hidden">
        <div>
            <p class="text-label-sm font-label-md text-on-surface-variant uppercase tracking-wider">Status</p>
            <h4 class="text-headline-md font-bold text-secondary mt-1">{{ $patient->status ?? 'N/A' }}</h4>
        </div>
        <div class="flex items-center gap-1 text-tertiary font-label-md">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <span>Current status</span>
        </div>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-secondary/5 opacity-20">check_circle</span>
    </div>
    <div class="bg-primary-container p-md rounded-xl soft-drop-shadow flex flex-col justify-between h-32 relative overflow-hidden">
        <div>
            <p class="text-label-sm font-label-md text-on-primary-container uppercase tracking-wider">Patient ID</p>
            <h4 class="text-headline-md font-bold text-on-primary-container mt-1">#BC-{{ $patient->created_at->format('Y') }}-{{ sprintf('%03d', $patient->id) }}</h4>
        </div>
        <div class="flex items-center gap-1 text-on-primary-container font-label-md opacity-80">
            <span class="material-symbols-outlined text-sm">qr_code</span>
            <span>Unique identifier</span>
        </div>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-on-primary/10">qr_code</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
    <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow border border-outline-variant/10">
        <h4 class="font-headline-sm text-on-surface mb-md flex items-center gap-sm">
            <span class="material-symbols-outlined text-primary">person</span>
            Personal Information
        </h4>
        <div class="space-y-sm">
            <div class="flex justify-between text-body-sm py-xs border-b border-outline-variant/10">
                <span class="text-on-surface-variant">Full Name</span>
                <span class="font-bold text-on-surface">{{ $patient->first_name }} {{ $patient->last_name }}</span>
            </div>
            <div class="flex justify-between text-body-sm py-xs border-b border-outline-variant/10">
                <span class="text-on-surface-variant">Date of Birth</span>
                <span class="font-bold text-on-surface">{{ \Carbon\Carbon::parse($patient->dob)->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between text-body-sm py-xs border-b border-outline-variant/10">
                <span class="text-on-surface-variant">Gender</span>
                <span class="font-bold text-on-surface">{{ $patient->gender }}</span>
            </div>
            <div class="flex justify-between text-body-sm py-xs border-b border-outline-variant/10">
                <span class="text-on-surface-variant">Phone</span>
                <span class="font-bold text-on-surface">{{ $patient->phone }}</span>
            </div>
            <div class="flex justify-between text-body-sm py-xs border-b border-outline-variant/10">
                <span class="text-on-surface-variant">Email</span>
                <span class="font-bold text-on-surface">{{ $patient->email ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between text-body-sm py-xs">
                <span class="text-on-surface-variant">Address</span>
                <span class="font-bold text-on-surface">{{ $patient->address }}</span>
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow border border-outline-variant/10">
        <h4 class="font-headline-sm text-on-surface mb-md flex items-center gap-sm">
            <span class="material-symbols-outlined text-error">emergency</span>
            Emergency Contact
        </h4>
        <div class="space-y-sm">
            <div class="flex justify-between text-body-sm py-xs border-b border-outline-variant/10">
                <span class="text-on-surface-variant">Contact Name</span>
                <span class="font-bold text-on-surface">{{ $patient->emergency_contact_name }}</span>
            </div>
            <div class="flex justify-between text-body-sm py-xs border-b border-outline-variant/10">
                <span class="text-on-surface-variant">Contact Phone</span>
                <span class="font-bold text-on-surface">{{ $patient->emergency_contact_phone }}</span>
            </div>
            <div class="flex justify-between text-body-sm py-xs border-b border-outline-variant/10">
                <span class="text-on-surface-variant">Barangay</span>
                <span class="font-bold text-on-surface">{{ $patient->barangay }}</span>
            </div>
            <div class="flex justify-between text-body-sm py-xs">
                <span class="text-on-surface-variant">Occupation</span>
                <span class="font-bold text-on-surface">{{ $patient->occupation ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
