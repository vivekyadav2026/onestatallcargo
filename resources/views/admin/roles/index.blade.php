@extends('layouts.admin')
@section('title', 'Roles & Permissions - OneStall Cargo')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Roles & Permissions</h1>
            <p class="text-sm text-gray-500 mt-1">Manage system access for Admins, Sellers, Hub Managers, and Riders.</p>
        </div>
        <button class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[var(--gold)] text-gray-900 shadow-md hover:bg-[var(--gold-deep)] transition-colors"><i class="fa-solid fa-user-plus"></i> Add User</button>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Role</th>
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
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.roles.edit', $user->id) }}" class="text-[var(--gold-deep)] hover:text-yellow-600 font-bold text-xs"><i class="fa-solid fa-pen"></i> Manage Role</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
