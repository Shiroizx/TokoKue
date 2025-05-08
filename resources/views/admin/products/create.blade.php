@extends('admin.layouts.app')

@section('title', 'Add New Product')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Add New Product</h2>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Product Name and Price Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product
                        Name</label>
                    <input type="text"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 dark:border-red-500 @enderror"
                        id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300">Rp</span>
                        <input type="number"
                            class="mt-1 block w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('price') border-red-500 dark:border-red-500 @enderror"
                            id="price" name="price" value="{{ old('price') }}" step="0.01" min="0"
                            required>
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Stock and Weight Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                    <input type="number"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('stock') border-red-500 dark:border-red-500 @enderror"
                        id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weight (grams)</label>
                    <input type="number"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('weight') border-red-500 dark:border-red-500 @enderror"
                        id="weight" name="weight" value="{{ old('weight', 0) }}" min="0" required>
                    @error('weight')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Category Field -->
            <div>
                <label for="category_id"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('category_id') border-red-500 dark:border-red-500 @enderror"
                    id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Seller and Description Fields -->
            <div>
                <label for="seller_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seller</label>
                <select
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('seller_id') border-red-500 dark:border-red-500 @enderror"
                    id="seller_id" name="seller_id" required>
                    <option value="">Select Seller</option>
                    @foreach ($sellers as $seller)
                        <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                            {{ $seller->name }} ({{ $seller->email }})
                        </option>
                    @endforeach
                </select>
                @error('seller_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 dark:border-red-500 @enderror"
                    id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload Section -->
            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Product Images <span class="text-red-600">*</span>
                </label>
                <input type="file" id="images" name="images[]" accept="image/*" multiple
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 dark:file:bg-primary-900/30 dark:file:text-primary-400 hover:file:bg-primary-100 dark:hover:file:bg-primary-900/40 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('images') border-red-500 dark:border-red-500 @enderror">
                <small class="form-text text-muted dark:text-gray-400">Accepted formats: JPEG, PNG, JPG, GIF. Max size:
                    2MB.</small>
                @error('images')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Preview -->
            <div id="image-preview" class="mb-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="preview-container">
                    <!-- Previews will be inserted here dynamically -->
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <button type="button" onclick="window.history.back()"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i> Save Product
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imagesInput = document.getElementById('images');
            const previewContainer = document.getElementById('preview-container');

            imagesInput.addEventListener('change', function(event) {
                const files = event.target.files;
                previewContainer.innerHTML = '';

                Array.from(files).forEach(file => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('w-full', 'h-48', 'object-contain', 'rounded-lg');
                        previewContainer.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                });
            });
        });
    </script>
@endpush