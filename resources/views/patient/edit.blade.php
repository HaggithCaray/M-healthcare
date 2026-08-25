@extends('layouts.app')

@section('title', 'Edit Patient - ' . $patient->first_name . ' ' . $patient->last_name)

@section('content')
<header class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-lg">
    <div>
        <div class="flex items-center gap-sm mb-xs">
            <a href="{{ route('records') }}" class="p-xs text-on-surface-variant hover:bg-surface-variant rounded-lg transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="font-headline-lg text-headline-lg text-primary">Edit Patient</h1>
        </div>
        <p class="text-on-surface-variant max-w-2xl ml-10">Editing record for <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong> &mdash; ID: #BC-{{ \Carbon\Carbon::parse($patient->created_at)->format('Y') }}-{{ sprintf('%03d', $patient->id) }}</p>
    </div>
    <div class="flex items-center gap-sm">
        @if($patient->registration_type === 'Maternal')
        <span class="flex items-center gap-xs px-sm py-xs rounded-full text-xs font-bold bg-secondary-container/20 text-secondary border border-secondary/20">
            <span class="material-symbols-outlined text-[16px]">pregnant_woman</span> Maternal
        </span>
        @else
        <span class="flex items-center gap-xs px-sm py-xs rounded-full text-xs font-bold bg-primary-container/20 text-primary border border-primary/20">
            <span class="material-symbols-outlined text-[16px]">child_care</span> Child
        </span>
        @endif
    </div>
</header>

@if($errors->any())
<div class="bg-error-container/30 border border-error/20 p-md rounded-xl mb-lg flex items-start gap-sm">
    <span class="material-symbols-outlined text-error">error</span>
    <div>
        <p class="font-label-md text-label-md text-error">Please fix the following errors:</p>
        <ul class="mt-xs text-sm text-on-error-container list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="{{ route('patients.update', $patient->id) }}" id="editPatientForm">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
        <aside class="lg:col-span-3">
            <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow sticky top-24">
                <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-md">Sections</h3>
                <nav class="flex flex-col gap-sm" id="sectionNav">
                    <a href="#personal" class="flex items-center gap-md px-sm py-xs rounded-lg text-primary font-bold bg-secondary-container/30 transition-colors section-link" data-section="personal">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                        <span class="font-label-md text-label-md">Personal Info</span>
                    </a>
                    <a href="#contact" class="flex items-center gap-md px-sm py-xs rounded-lg text-on-surface-variant hover:bg-surface-variant/50 transition-colors section-link" data-section="contact">
                        <span class="material-symbols-outlined text-[20px]">contact_phone</span>
                        <span class="font-label-md text-label-md">Contact & Address</span>
                    </a>
                </nav>
                <div class="mt-xl pt-md border-t border-outline-variant/30">
                    <div class="bg-primary-container/20 p-sm rounded-lg flex gap-sm">
                        <span class="material-symbols-outlined text-primary" data-icon="info">info</span>
                        <p class="text-xs text-primary leading-tight">Changes are saved immediately upon submission.</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="lg:col-span-9 flex flex-col gap-gutter">
            <div id="personal" class="bg-surface-container-lowest p-gutter rounded-xl soft-drop-shadow">
                <div class="mb-lg">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface mb-xs">Personal Information</h2>
                    <p class="text-body-sm text-on-surface-variant">Update the patient's basic demographics.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">First Name *</label>
                        <input name="first_name" value="{{ old('first_name', $patient->first_name) }}" required class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" type="text">
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Last Name *</label>
                        <input name="last_name" value="{{ old('last_name', $patient->last_name) }}" required class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" type="text">
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Date of Birth *</label>
                        <input name="dob" value="{{ old('dob', $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('Y-m-d') : '') }}" required class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" type="date">
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Gender *</label>
                        <div class="flex gap-md mt-xs">
                            <label class="flex items-center gap-xs cursor-pointer">
                                <input name="gender" value="Female" {{ old('gender', $patient->gender) === 'Female' ? 'checked' : '' }} class="text-primary focus:ring-primary" type="radio">
                                <span>Female</span>
                            </label>
                            <label class="flex items-center gap-xs cursor-pointer">
                                <input name="gender" value="Male" {{ old('gender', $patient->gender) === 'Male' ? 'checked' : '' }} class="text-primary focus:ring-primary" type="radio">
                                <span>Male</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Occupation</label>
                        <input name="occupation" value="{{ old('occupation', $patient->occupation) }}" class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" placeholder="e.g. Housewife, Teacher, Vendor" type="text">
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Status *</label>
                        <select name="status" required class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base cursor-pointer">
                            @foreach(['Active', 'Due for Visit', 'High Risk', 'Completed'] as $statusOption)
                            <option value="{{ $statusOption }}" {{ old('status', $patient->status) === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div id="contact" class="bg-surface-container-lowest p-gutter rounded-xl soft-drop-shadow">
                <div class="mb-lg">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface mb-xs">Contact & Location</h2>
                    <p class="text-body-sm text-on-surface-variant">Ensure we have the correct information to reach the patient in case of emergencies.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Phone Number *</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-outline bg-surface-container text-on-surface-variant text-sm">+63</span>
                            <input name="phone" value="{{ old('phone', $patient->phone) }}" required class="w-full rounded-r-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" placeholder="917 123 4567" type="tel">
                        </div>
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Email Address</label>
                        <input name="email" value="{{ old('email', $patient->email) }}" class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" placeholder="example@email.com" type="email">
                    </div>
                    <div class="md:col-span-2 flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Permanent Address *</label>
                        <textarea name="address" required class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" placeholder="House number, Street, Purok..." rows="3">{{ old('address', $patient->address) }}</textarea>
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Barangay</label>
                        <input class="w-full rounded-lg border-outline bg-surface-container text-on-surface-variant px-sm py-base" readonly value="{{ $patient->barangay }}" type="text">
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Emergency Contact Person *</label>
                        <input name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" required class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" placeholder="Name of Relative" type="text">
                    </div>
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant">Emergency Contact Phone *</label>
                        <input name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" required class="w-full rounded-lg border-outline focus:ring-2 focus:ring-primary focus:border-primary px-sm py-base" placeholder="Phone of emergency contact" type="tel">
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow flex items-center justify-between gap-md">
                <a href="{{ route('records') }}" class="flex items-center gap-xs px-md py-sm rounded-lg text-on-surface-variant font-label-md text-label-md hover:bg-surface-variant transition-all">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Cancel
                </a>
                <button type="submit" class="flex items-center gap-xs px-xl py-sm rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container hover:shadow-lg active:scale-95 transition-all whitespace-nowrap">
                    <span class="material-symbols-outlined">save</span>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.section-link').forEach(link => {
        link.addEventListener('click', function(e) {
            document.querySelectorAll('.section-link').forEach(l => {
                l.classList.remove('text-primary', 'font-bold', 'bg-secondary-container/30');
                l.classList.add('text-on-surface-variant');
            });
            this.classList.add('text-primary', 'font-bold', 'bg-secondary-container/30');
            this.classList.remove('text-on-surface-variant');
        });
    });
</script>
@endpush
