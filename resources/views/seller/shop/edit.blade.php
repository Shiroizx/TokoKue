@extends('seller.layouts.app')

@section('title', 'Edit Shop')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Shop Settings</h1>
            </div>

            <div class="p-6">
                <!-- Shop Summary -->
                <div class="flex flex-col md:flex-row gap-6 mb-8">
                    <div class="md:w-1/4 flex justify-center">
                        @if ($shop->logo)
                            <img src="{{ asset('storage/' . $shop->logo) }}" alt="{{ $shop->name }}"
                                class="rounded-lg object-cover h-36 w-36">
                        @else
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center h-36 w-36">
                                <i class="fas fa-store text-4xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                        @endif
                    </div>

                    <div class="md:w-3/4">
                        <h2 class="text-xl font-medium text-gray-800 dark:text-white mb-2">{{ $shop->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            {{ $shop->description ?: 'No description available' }}
                        </p>
                        <div class="flex space-x-6">
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <i class="fas fa-box mr-2 text-primary-600 dark:text-primary-400"></i>
                                <span>{{ $productsCount }} Products</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Shop Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $shop->name) }}"
                                required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('name') border-red-500 dark:border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Shop Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $shop->email) }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('email') border-red-500 dark:border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="phone_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Shop Phone Number
                            </label>
                            <input type="text" id="phone_number" name="phone_number"
                                value="{{ old('phone_number', $shop->phone_number) }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('phone_number') border-red-500 dark:border-red-500 @enderror">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Shop Logo
                            </label>
                            <input type="file" id="logo" name="logo"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 dark:file:bg-primary-900/30 dark:file:text-primary-400 hover:file:bg-primary-100 dark:hover:file:bg-primary-900/40 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('logo') border-red-500 dark:border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Recommended size: 200x200 pixels (JPG,
                                PNG, GIF)</p>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="banner" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Shop Banner
                        </label>
                        @if ($shop->banner)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $shop->banner) }}" alt="Shop Banner"
                                    class="rounded-lg h-24 object-cover">
                            </div>
                        @endif
                        <input type="file" id="banner" name="banner"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 dark:file:bg-primary-900/30 dark:file:text-primary-400 hover:file:bg-primary-100 dark:hover:file:bg-primary-900/40 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('banner') border-red-500 dark:border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Recommended size: 1200x300 pixels (JPG,
                            PNG, GIF)</p>
                        @error('banner')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Shop Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('description') border-red-500 dark:border-red-500 @enderror">{{ old('description', $shop->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 my-6 pt-6">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Shop Address</h2>

                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Street Address
                            </label>
                            <input type="text" id="address" name="address"
                                value="{{ old('address', $shop->address) }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('address') border-red-500 dark:border-red-500 @enderror">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="city"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    City
                                </label>
                                <input type="text" id="city" name="city" value="{{ old('city', $shop->city) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('city') border-red-500 dark:border-red-500 @enderror">
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="province"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Province
                                </label>
                                <input type="text" id="province" name="province"
                                    value="{{ old('province', $shop->province) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('province') border-red-500 dark:border-red-500 @enderror">
                                @error('province')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Postal Code
                                </label>
                                <input type="text" id="postal_code" name="postal_code"
                                    value="{{ old('postal_code', $shop->postal_code) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('postal_code') border-red-500 dark:border-red-500 @enderror">
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
