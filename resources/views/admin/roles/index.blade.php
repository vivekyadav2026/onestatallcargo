@extends('layouts.admin')
@section('title', 'Roles & Permissions - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">All Users & Roles</h1>
            <p class="text-sm text-gray-500 mt-1">Manage system access for Admins, Sellers, Hub Managers, and Riders.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors"><i class="fa-solid fa-user-plus"></i> Add User</a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-3xl border border-gray-200 p-4 shadow-sm">
        <form action="{{ route('admin.roles.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
            </div>
            <div class="w-full md:w-48">
                <select name="role" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="operations" {{ request('role') == 'operations' ? 'selected' : '' }}>Operations</option>
                    <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                    <option value="franchise" {{ request('role') == 'franchise' ? 'selected' : '' }}>Franchise</option>
                    <option value="rider" {{ request('role') == 'rider' ? 'selected' : '' }}>Rider</option>
                </select>
            </div>
            <div class="w-full md:w-48">
                <select name="status" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-[var(--gold)] outline-none">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-black transition"><i class="fa-solid fa-filter mr-1"></i> Filter</button>
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-200 transition">Clear</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-x-auto">
                        <div class="overflow-x-auto w-full">
<table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Role</th><th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700">{{ $user->role }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->status === 'active')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700">Active</span>
                        @elseif($user->status === 'pending')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-yellow-50 text-yellow-700">Pending</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.roles.edit', $user->id) }}" class="text-[var(--gold-deep)] hover:text-yellow-600 font-bold text-xs"><i class="fa-solid fa-pen"></i> Manage Role</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
</div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection

