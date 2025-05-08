@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">User Details</h2>
            <div class="space-x-2">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md text-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md text-sm dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="text-left p-2">ID</th>
                            <td class="p-2">{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th class="text-left p-2">Name</th>
                            <td class="p-2">{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-left p-2">Email</th>
                            <td class="p-2">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th class="text-left p-2">Role</th>
                            <td class="p-2">
                                @if ($user->role == 'admin')
                                    <span class="bg-red-500 text-white py-1 px-3 rounded-md">Admin</span>
                                @elseif($user->role == 'seller')
                                    <span class="bg-blue-500 text-white py-1 px-3 rounded-md">Seller</span>
                                @else
                                    <span class="bg-gray-500 text-white py-1 px-3 rounded-md">Buyer</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left p-2">Phone</th>
                            <td class="p-2">{{ $user->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="text-left p-2">Address</th>
                            <td class="p-2">{{ $user->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="text-left p-2">Registered</th>
                            <td class="p-2">{{ $user->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Profile Photo</h3>

                    @php
                        $userPhoto = App\Models\ProfileImage::where('user_id', $user->id)->first();
                    @endphp

                    @if($userPhoto)
                        <img src="{{ asset('storage/' . $userPhoto->image_path) }}" alt="Profile Photo"
                             class="w-32 h-32 rounded-full object-cover">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No Photo</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
