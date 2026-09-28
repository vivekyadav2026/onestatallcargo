@extends('layouts.public')

@section('title', 'Franchise Registration - OneStall Cargo')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Partner with OneStall Cargo
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Become a franchise owner and start booking orders directly!
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">
            
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('franchise.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company/Shop Name</label>
                        <div class="mt-1">
                            <input id="company_name" name="company_name" type="text" required value="{{ old('company_name') }}" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                        </div>
                    </div>
                    
                    <!-- Owner Name -->
                    <div>
                        <label for="owner_name" class="block text-sm font-medium text-gray-700">Owner Name</label>
                        <div class="mt-1">
                            <input id="owner_name" name="owner_name" type="text" required value="{{ old('owner_name') }}" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="mt-1">
                            <input id="phone" name="phone" type="text" required value="{{ old('phone') }}" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" required value="{{ old('email') }}" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                        </div>
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <div class="mt-1">
                            <input id="city" name="city" type="text" required value="{{ old('city') }}" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                        </div>
                    </div>

                    <!-- State -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                        <div class="mt-1">
                            <input id="state" name="state" type="text" required value="{{ old('state') }}" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Full Address</label>
                    <div class="mt-1">
                        <textarea id="address" name="address" rows="3" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Serviceable Pincodes -->
                <div>
                    <label for="serviceable_pincodes" class="block text-sm font-medium text-gray-700">Serviceable Pincodes (Comma separated)</label>
                    <div class="mt-1">
                        <input id="serviceable_pincodes" name="serviceable_pincodes" type="text" placeholder="e.g. 110001, 110002" required value="{{ old('serviceable_pincodes') }}" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Orders for these pincodes will be exclusively routed to your franchise.</p>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#fbb900] focus:border-[#fbb900] sm:text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-[#fbb900] hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#fbb900]">
                        Apply for Franchise
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
