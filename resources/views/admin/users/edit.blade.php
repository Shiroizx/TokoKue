@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Edit User: {{ $user->name }}</h2>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">  
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 dark:border-red-500 @enderror"
                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 dark:border-red-500 @enderror"
                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password (leave
                        blank to keep current)</label>
                    <input type="password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 dark:border-red-500 @enderror"
                        id="password" name="password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <select
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('role') border-red-500 dark:border-red-500 @enderror"
                        id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="seller" {{ old('role', $user->role) == 'seller' ? 'selected' : '' }}>Seller</option>
                        <option value="buyer" {{ old('role', $user->role) == 'buyer' ? 'selected' : '' }}>Buyer</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                <input type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('phone') border-red-500 dark:border-red-500 @enderror"
                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                <textarea
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('address') border-red-500 dark:border-red-500 @enderror"
                    id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">Cancel</a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md dark:bg-indigo-700 dark:hover:bg-indigo-600">Update</button>
            </div>

            <div class="mb-6">  
                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Photo</label>  
                <div class="mt-1 flex items-center space-x-4">  
                    @php  
                        $userPhoto = App\Models\ProfileImage::where('user_id', $user->id)->first();  
                    @endphp  

                    @if($userPhoto)  
                        <img src="{{ asset('storage/' . $userPhoto->image_path) }}"   
                            alt="{{ $user->name }}"   
                            class="h-20 w-20 rounded-full object-cover">  
                    @else  
                        <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">  
                            <span class="text-gray-400">No Photo</span>  
                        </div>  
                    @endif  
                    
                    <input type="file"   
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100   
                        @error('photo') border-red-500 @enderror"  
                        id="photo"   
                        name="photo"   
                        accept="image/*">  
                </div>  
                @error('photo')  
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>  
                @enderror  
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // JavaScript to add some interactivity or validations can go here
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                passwordInput.addEventListener('focus', function() {
                    passwordInput.setAttribute('title', 'Leave blank to keep current password');
                });
            });
        </script>
    @endpush
@endsection
