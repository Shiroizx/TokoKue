@extends('seller.layouts.app')

@section('title', 'Product Images')

@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <h2 class="text-3xl font-semibold text-gray-800 dark:text-white mb-6">Images for Product: {{ $product->name }}</h2>

        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('seller.products.index') }}"
                class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white py-2 px-6 rounded-md transition duration-300 ease-in-out transform hover:scale-105 dark:bg-gray-700 dark:hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i> Back to Products
            </a>

            <form action="{{ route('seller.products.upload-images.store', $product->id) }}" method="POST"
                enctype="multipart/form-data" id="uploadForm">
                @csrf
                <input type="file" name="new_image[]" id="new_image" class="hidden" accept="image/*" multiple
                    onchange="this.form.submit()">
                <button type="button" onclick="document.getElementById('new_image').click();"
                    class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-6 rounded-md transition duration-300 ease-in-out transform hover:scale-105 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                    <i class="fas fa-upload mr-2"></i> Upload New Images
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($images as $image)
                <div
                    class="relative border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden group hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105">
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image"
                        class="w-full h-48 object-cover mb-3 group-hover:opacity-80 transition duration-300">
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-b-lg">
                        <div class="flex justify-between items-center mb-4">
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $image->is_primary ? 'Primary' : 'Secondary' }}</span>
                        </div>

                        <div class="flex justify-between items-center space-x-4">
                            @if (!$image->is_primary)
                                <form
                                    action="{{ route('seller.products.update-primary-image', [$product->id, $image->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full py-2 px-4 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-700 rounded-md hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Set as Primary
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('seller.products.destroy-image', [$product->id, $image->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full py-2 px-4 text-sm font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-700 rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script>
            // Optional: Add JavaScript to handle tooltips or additional interactivity
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach((tooltip) => {
                tooltip.addEventListener('mouseenter', function() {
                    const tooltipText = this.getAttribute('title');
                    const tooltipElement = document.createElement('div');
                    tooltipElement.classList.add('absolute', 'bg-black', 'text-white', 'text-xs', 'rounded',
                        'px-2', 'py-1', 'shadow-lg');
                    tooltipElement.innerText = tooltipText;
                    tooltipElement.style.top = `${this.offsetTop - 30}px`;
                    tooltipElement.style.left = `${this.offsetLeft + 20}px`;
                    document.body.appendChild(tooltipElement);

                    tooltip.addEventListener('mouseleave', function() {
                        tooltipElement.remove();
                    });
                });
            });
        </script>
    @endpush
@endsection
