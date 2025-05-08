@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Edit Category: {{ $category->name }}</h2>
        </div>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                <input type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 dark:border-red-500 @enderror"
                    id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 dark:border-red-500 @enderror"
                    id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.categories.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">Cancel</a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md dark:bg-indigo-700 dark:hover:bg-indigo-600">Update</button>
            </div>
        </form>
    </div>
@endsection
