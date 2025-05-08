@extends('seller.layouts.app')

@section('title', 'Product Details')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
                <h5 class="text-xl font-semibold text-gray-800 dark:text-white">Product Details</h5>
                <div>
                    <a href="{{ route('seller.products.edit', $product) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Edit Product
                    </a>
                    <a href="{{ route('seller.products.index') }}"
                        class="inline-flex items-center px-4 py-2 ml-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Products
                    </a>
                </div>
            </div>

            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @if ($product->images()->where('is_primary', true)->exists())
                            @php
                                // Get the primary image for the product
                                $primaryImage = $product->images()->where('is_primary', true)->first();
                            @endphp

                            <div class="card mb-4">
                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                    class="w-full h-64 object-contain rounded-lg" alt="{{ $product->name }}">
                            </div>
                        @else
                            <div class="card mb-4 flex justify-center items-center">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-5x mb-3"></i>
                                    <p>No image available</p>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">{{ $product->name }}</h3>

                        <div class="mb-3">
                            <span
                                class="badge bg-blue-500 text-white text-sm">{{ $product->category->name ?? 'No Category' }}</span>
                        </div>

                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-xl text-primary-600">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                            <div>
                                <span class="badge bg-{{ $product->stock > 0 ? 'green' : 'red' }} text-white">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                                <span class="ml-2 text-gray-700">{{ $product->stock }} units</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h5 class="text-lg font-medium">Description</h5>
                            <p class="text-gray-600 dark:text-gray-400">
                                {!! nl2br(e($product->description)) ?: '<em>No description provided</em>' !!}
                            </p>
                        </div>

                        <div class="mb-6">
                            <h5 class="text-lg font-medium">Product Information</h5>
                            <table class="w-full table-auto border-collapse border border-gray-200 dark:border-gray-700">
                                <tbody>
                                    <tr>
                                        <th class="text-left py-2 px-3 bg-gray-100 dark:bg-gray-700">Product ID</th>
                                        <td class="py-2 px-3">{{ $product->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left py-2 px-3 bg-gray-100 dark:bg-gray-700">Category</th>
                                        <td class="py-2 px-3">{{ $product->category->name ?? 'Not categorized' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left py-2 px-3 bg-gray-100 dark:bg-gray-700">Added on</th>
                                        <td class="py-2 px-3">{{ $product->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left py-2 px-3 bg-gray-100 dark:bg-gray-700">Last updated</th>
                                        <td class="py-2 px-3">{{ $product->updated_at->format('M d, Y, h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex space-x-4">
                            <a href="{{ route('seller.products.edit', $product) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Edit Product
                            </a>
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                onclick="toggleModal()">
                                <i class="fas fa-trash mr-2"></i> Delete Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Confirm Deletion</h5>
            <p>Are you sure you want to delete this product?</p>
            <p class="mb-4"><strong>{{ $product->name }}</strong></p>
            <p class="text-red-600 mb-4">This action cannot be undone.</p>

            <div class="flex justify-between space-x-4">
                <button type="button"
                    class="px-4 py-2 bg-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 transition-colors"
                    onclick="toggleModal()">Cancel</button>
                <form action="{{ route('seller.products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function toggleModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.toggle('hidden');
        }
    </script>
@endpush
