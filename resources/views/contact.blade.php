@extends('user.layouts.app')

@section('content')  
<div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12" data-aos="fade-in">  
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">  
        <div class="absolute inset-0 bg-gradient-to-r from-brown-500 to-brown-800 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>  
        <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">  
            <div class="max-w-md mx-auto">  
                <div class="divide-y divide-gray-200">  
                    <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">  
                        <h2 class="text-3xl font-extrabold text-center text-brown-600">  
                            <i class="fas fa-envelope mr-2"></i>Hubungi Kami  
                        </h2>  
                        
                        @if(session('success'))  
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">  
                                {{ session('success') }}  
                            </div>  
                        @endif  

                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">  
                            @csrf  
                            <div>  
                                <label class="block text-sm font-bold text-gray-700">Nama</label>  
                                <input type="text" name="name"   
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-300 focus:ring focus:ring-brown-200 transition duration-300 ease-in-out transform hover:scale-105"  
                                    required>  
                            </div>  
                            <div>  
                                <label class="block text-sm font-bold text-gray-700">Email</label>  
                                <input type="email" name="email"   
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-300 focus:ring focus:ring-brown-200 transition duration-300 ease-in-out transform hover:scale-105"  
                                    required>  
                            </div>  
                            <div>  
                                <label class="block text-sm font-bold text-gray-700">Pesan</label>  
                                <textarea name="message" rows="4"   
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-300 focus:ring focus:ring-brown-200 transition duration-300 ease-in-out transform hover:scale-105"  
                                    required></textarea>  
                            </div>  
                            <div>  
                                <button type="submit"   
                                    class="w-full bg-brown-600 text-white py-2 px-4 rounded-md hover:bg-brown-700 transition duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center">  
                                    <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan  
                                </button>  
                            </div>  
                        </form>  
                    </div>  
                </div>  
            </div>  
        </div>  
    </div>  
</div>  
<script>  
    document.addEventListener('DOMContentLoaded', function() {  
        // Preloader dengan desain Tailwind dan transisi  
        const preloader = document.createElement('div');  
        preloader.className = "fixed inset-0 bg-white flex items-center justify-center z-50 transition-opacity duration-500 ease-in-out";  
        preloader.innerHTML = `  
            <div class="text-center">  
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-pink-600 mx-auto mb-4"></div>  
                <p class="text-gray-600">Memuat halaman...</p>  
            </div>  
        `;  
        document.body.prepend(preloader);  

        // Fungsi untuk menghilangkan preloader dengan efek fade out  
        function removePreloader() {  
            // Tambahkan kelas opacity-0 untuk memulai fade out  
            preloader.classList.add('opacity-0');  

            // Tunggu animasi selesai sebelum menghapus elemen  
            setTimeout(() => {  
                preloader.remove();  
                // Tambahkan kelas untuk fade in produk  
                document.getElementById('products-container').classList.add('products-loaded');  
            }, 500); // Sesuaikan dengan durasi transisi  
        }  

        // Sembunyikan preloader setelah halaman selesai dimuat  
        window.addEventListener('load', function() {  
            // Tambahkan sedikit delay untuk efek visual yang lebih baik  
            setTimeout(() => {  
                removePreloader();  

                // Initialize AOS  
                AOS.init({  
                    duration: 800,  
                    once: true  
                });  
            }, 1000);  
        });

        AOS.init({  
            duration: 1000,  
            once: true  
        });  
    });  
</script>  
@endsection
