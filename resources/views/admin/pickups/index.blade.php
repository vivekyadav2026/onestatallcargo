@extends('layouts.admin')
@section('title', 'Pickup Management - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pickup Management</h1>
            <p class="text-sm text-gray-500 mt-1">Track and manage seller pickup manifests and rider assignments.</p>
        </div>
    </div>
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-12 text-center text-gray-400">
        <i class="fa-solid fa-truck-ramp-box text-4xl mb-3 text-gray-200"></i>
        <p>No active pickups requested today.</p>
    </div>
</div>
@endsection
