@props([
    'modalId', // ID unik untuk modal
    'title', // Judul modal
    'description', // Deskripsi/pesan konfirmasi
    'confirmButtonText', // Teks tombol konfirmasi
    'cancelButtonText', // Teks tombol batal
    'confirmAction', // URL route untuk form action
    'method', // Metode HTTP untuk form (POST, PATCH, DELETE)
])

{{-- Pastikan Alpine.js sudah tersedia --}}

<div x-data="{ showModal: false }" x-show="showModal"
    x-on:open-modal.window="showModal = ($event.detail === '{{ $modalId }}')" {{-- Listen for custom event --}}
    x-on:close-modal.window="showModal = false" x-on:keydown.escape.window="showModal = false" {{-- Close on escape key --}}
    class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
    style="display: none;"> {{-- Hidden by default with style --}}

    <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

        {{-- This element is to trick the browser into centering the modal contents. --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal panel --}}
        <div x-show="showModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    {{-- Optional: Icon based on action --}}
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full {{ $method === 'DELETE' ? 'bg-red-100' : 'bg-indigo-100' }} sm:mx-0 sm:h-10 sm:w-10">
                        @if ($method === 'DELETE')
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i> {{-- Warning icon for delete --}}
                        @else
                            <i class="fas fa-question-circle text-indigo-600 text-xl"></i> {{-- Info icon for other actions --}}
                        @endif
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-300">
                                {{ $description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="{{ $modalId }}-form" action="{{ $confirmAction }}" method="POST"
                    class="inline-block w-full sm:w-auto">
                    @csrf
                    @method($method)
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-{{ $method === 'DELETE' ? 'red' : 'indigo' }}-600 text-base font-medium text-white hover:bg-{{ $method === 'DELETE' ? 'red' : 'indigo' }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $method === 'DELETE' ? 'red' : 'indigo' }}-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                        {{ $confirmButtonText }}
                    </button>
                </form>
                <button type="button" x-on:click="showModal = false"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 dark:text-gray-200 text-base font-medium text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out">
                    {{ $cancelButtonText }}
                </button>
            </div>
        </div>
    </div>
</div>
