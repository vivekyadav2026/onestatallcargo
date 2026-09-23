@extends('layouts.admin')
@section('title', 'Billing & COD - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Billing & COD Remittance</h1>
            <p class="text-sm text-gray-500 mt-1">Manage Cash-on-Delivery collections and shipping wallet deductions.</p>
        </div>
    </div>
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-12 text-center text-gray-400">
        <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3 text-gray-200"></i>
        <p>All remittances are up to date. No pending settlements.</p>
    </div>
</div>
@endsection
