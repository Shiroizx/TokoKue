@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Edit Product: {{ $product->name }}</h2>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product
                        Name</label>
                    <input type="text"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 dark:border-red-500 @enderror"
                        id="name" name="name" value="{{ old('name', $product->name) }}" required>
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
                            id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01"
                            min="0" required>
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                    <input type="number"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('stock') border-red-500 dark:border-red-500 @enderror"
                        id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weight (grams)</label>
                    <input type="number"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('weight') border-red-500 dark:border-red-500 @enderror"
                        id="weight" name="weight" value="{{ old('weight', $product->weight) }}" min="0" required>
                    @error('weight')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('category_id') border-red-500 dark:border-red-500 @enderror"
                        id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="seller_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seller</label>
                    <select
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('seller_id') border-red-500 dark:border-red-500 @enderror"
                        id="seller_id" name="seller_id" required>
                        <option value="">Select Seller</option>
                        @foreach ($sellers as $seller)
                            <option value="{{ $seller->id }}"
                                {{ old('seller_id', $product->seller_id) == $seller->id ? 'selected' : '' }}>
                                {{ $seller->name }} ({{ $seller->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('seller_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 dark:border-red-500 @enderror"
                    id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status
                </label>
                <select id="is_active" name="is_active"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('status') border-red-500 dark:border-red-500 @enderror">
                    <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>
                        Active</option>
                    <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>
                        Inactive</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">Cancel</a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md dark:bg-indigo-700 dark:hover:bg-indigo-600">Update</button>
            </div>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Store the original seller ID when the page loads
        const originalSellerId = document.getElementById('seller_id').value;
        
        // Add submit event listener to the form
        document.querySelector('form').addEventListener('submit', function(event) {
            const currentSellerId = document.getElementById('seller_id').value;
            
            // Check if the seller has been changed
            if (currentSellerId !== originalSellerId) {
                // Show confirmation dialog
                if (!confirm('Apakah anda yakin ingin mengubah produk seller tersebut? Jika iya maka semua data order nya akan pindah ke seller baru')) {
                    // Prevent form submission if user cancels
                    event.preventDefault();
                }
            }
        });
    });
</script>
@endsection



