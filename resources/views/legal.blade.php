@extends('layouts.public')
@section('content')
    <div class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-extrabold text-brand-navy mb-8">{{ $title }}</h1>
        <div class="prose max-w-none text-gray-600">
            <p>Welcome to OneStall Cargo. This is a boilerplate legal document placeholder for {{ $title }}.</p>
            <p>Please update this document with your official company legal statements before going to production.</p>
            <h2 class="text-2xl font-bold mt-8 mb-4">1. General Information</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula.</p>
        </div>
    </div>
@endsection
