@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-md mb-lg">
    <div>
        <h3 class="font-headline-lg text-headline-lg text-primary">Dashboard Overview</h3>
        <p class="font-body-md text-body-md text-on-surface-variant">Welcome back, {{ auth()->user()?->name ?? 'User' }}. Here is what's happening at the health center today.</p>
    </div>
    <div class="flex gap-sm w-full sm:w-auto">
        <a href="{{ route('register') }}" class="flex-grow sm:flex-initial flex items-center justify-center gap-xs px-md py-sm bg-secondary text-on-secondary rounded-lg font-label-md hover:bg-opacity-90 transition-all soft-drop-shadow active:scale-95">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Add Patient
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-lg">
    <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow border border-outline-variant/10 flex flex-col justify-between h-32 relative overflow-hidden">
        <div>
            <p class="text-label-sm font-label-md text-on-surface-variant uppercase tracking-wider">Total Patients</p>
            <h4 class="text-headline-md font-bold text-primary mt-1">{{ number_format($totalPatients) }}</h4>
        </div>
        <div class="flex items-center gap-1 text-tertiary font-label-md">
            <span class="material-symbols-outlined text-sm">trending_up</span>
            <span>Registered patients</span>
        </div>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-primary/5 opacity-20">groups</span>
    </div>
    <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow border border-outline-variant/10 flex flex-col justify-between h-32 relative overflow-hidden">
        <div>
            <p class="text-label-sm font-label-md text-on-surface-variant uppercase tracking-wider">Total Mothers</p>
            <h4 class="text-headline-md font-bold text-secondary mt-1">{{ number_format($totalMothers) }}</h4>
        </div>
        <div class="flex items-center gap-1 text-tertiary font-label-md">
            <span class="material-symbols-outlined text-sm">trending_up</span>
            <span>Active monitoring</span>
        </div>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-secondary/5 opacity-20">pregnant_woman</span>
    </div>
    <div class="bg-surface-container-lowest p-md rounded-xl soft-drop-shadow border border-outline-variant/10 flex flex-col justify-between h-32 relative overflow-hidden">
        <div>
            <p class="text-label-sm font-label-md text-on-surface-variant uppercase tracking-wider">Children Monitored</p>
            <h4 class="text-headline-md font-bold text-on-surface mt-1">{{ number_format($totalChildren) }}</h4>
        </div>
        <div class="flex items-center gap-1 text-on-surface-variant font-label-md">
            <span class="material-symbols-outlined text-sm">trending_up</span>
            <span>Active records</span>
        </div>
        <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-on-surface/5 opacity-10">child_care</span>
    </div>
</div>
@endsection
