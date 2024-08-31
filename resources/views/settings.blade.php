@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="container mx-auto mt-8 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Settings</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card for Users -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Manage Users</h2>
                    <p class="text-gray-600 mb-4">Add, edit, or delete users from your system.</p>
                    <a href="{{ route('users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                        Manage Users
                    </a>
                </div>
            </div>

            <!-- Card for Roles -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Manage Roles</h2>
                    <p class="text-gray-600 mb-4">Define roles and permissions for your users.</p>
                    <a href="{{ route('roles.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                        Manage Roles
                    </a>
                </div>
            </div>

            <!-- Card for Events -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Manage Events</h2>
                    <p class="text-gray-600 mb-4">Manage events and schedules within your application.</p>
                    <a href="{{ route('events.admin.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                        Manage Events
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
