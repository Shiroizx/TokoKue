    $(document).ready(function(){
        // Show initial loading when DOM is ready
        showPageLoading('Loading page...');


        var loadingTimeout = setTimeout(function() {
            hidePageLoading();
            console.log('Fallback: Layar loading disembunyikan oleh timeout');
        }, 2000);

        var originalHideFunction = hidePageLoading;
        hidePageLoading = function() {
            clearTimeout(loadingTimeout);
            originalHideFunction.apply(this, arguments);
        };
        
        // Hide loading effect on page load
        $(window).on('load', function() {
            try {
                hidePageLoading();
                console.log('Layar loading disembunyikan oleh event window.load');
            } catch(e) {
                console.error('Error di event load:', e);
                $('#pageTransition').removeClass('active');
                $('body').removeClass('modal-open');
            }
        });
        
        // Initialize AOS
        AOS.init({ once: true });
        
        // Product image slider with improved features
        $('.product-images').slick({
            dots: true,
            arrows: true,
            fade: true,
            autoplay: false,
            speed: 500,
            cssEase: 'ease-in-out',
            mobileFirst: true,
            prevArrow: '<button class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: true
                    }
                }
            ]
        });
        
        // Thumbnail navigation
        $('.thumbnail-item').click(function() {
            $('.thumbnail-item').removeClass('active');
            $(this).addClass('active');
            $('.product-images').slick('slickGoTo', $(this).index());
        });
        
        // Sync thumbnails with slider
        $('.product-images').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            $('.thumbnail-item').removeClass('active');
            $('.thumbnail-item').eq(nextSlide).addClass('active');
            
            // Scroll thumbnail into view
            const thumbnailContainer = $('.thumbnail-container');
            const activeThumbnail = $('.thumbnail-item').eq(nextSlide);
            const containerWidth = thumbnailContainer.width();
            const scrollLeft = activeThumbnail.offset().left - thumbnailContainer.offset().left + thumbnailContainer.scrollLeft();
            
            thumbnailContainer.animate({
                scrollLeft: scrollLeft - (containerWidth / 2) + (activeThumbnail.width() / 2)
            }, 300);
        });
        
        // Image zoom functionality
        $('.product-image').click(function() {
            const zoomSrc = $(this).data('zoom');
            $('#zoomedImage').attr('src', zoomSrc);
            $('#imageZoomModal').addClass('show');
        });
        
        $('.zoom-close').click(function() {
            $('#imageZoomModal').removeClass('show');
        });
        
        // Close modal on outside click
        $('#imageZoomModal').click(function(e) {
            if (e.target === this) {
                $(this).removeClass('show');
            }
        });
        
        // Close modal on Escape key
        $(document).keydown(function(e) {
            if (e.key === "Escape") {
                $('#imageZoomModal').removeClass('show');
                
                // Juga tutup modal konflik seller jika terbuka
                closeMobileModal();
                $('#replaceCartModal').removeClass('flex').addClass('hidden');
            }
        });
        
        // Tab navigation
        $('.tab-btn').click(function() {
            $('.tab-btn').removeClass('active');
            $(this).addClass('active');
            
            $('.tab-content').addClass('hidden');
            $('#' + $(this).data('target')).removeClass('hidden');
        });

        // Tambahkan timeout pengaman untuk paksa menghilangkan layar loading
        setTimeout(function() {
            $('#pageTransition').removeClass('active').css('opacity', '');
            $('body').removeClass('modal-open');
            console.log('Penghapusan layar loading darurat dieksekusi');
        }, 3000);
        
        // Pastikan footer selalu mengisi penuh halaman
        function adjustFooter() {
            const windowHeight = window.innerHeight;
            const bodyHeight = document.body.offsetHeight;
            const footer = document.querySelector('footer');
            
            // Jika bodyHeight kurang dari windowHeight, sesuaikan footer
            if (bodyHeight < windowHeight && footer) {
                const spacer = document.querySelector('.footer-spacer');
                if (spacer) {
                    spacer.style.height = (windowHeight - bodyHeight) + 'px';
                }
            }
        }
        
        // Panggil fungsi saat halaman dimuat dan saat resize
        adjustFooter();
        $(window).on('resize', adjustFooter);
        
        // Perbaikan untuk mencegah modal muncul saat scroll
        let lastScrollTop = 0;
        let isScrolling;
        
        $(window).on('scroll', function() {
            const st = $(window).scrollTop();
            
            // Clear timeout sepanjang scroll
            clearTimeout(isScrolling);
            
            // Jika scroll ke bawah, pastikan modal tidak muncul
            if (st > lastScrollTop) {
                if (!$('#mobileReplaceCartModal').hasClass('show')) {
                    $('#mobileReplaceCartModal').css({
                        'transform': 'translateY(100%) !important',
                        'visibility': 'hidden'
                    });
                }
            }
            
            lastScrollTop = st;
            
            // Set timeout baru untuk mendeteksi kapan scroll berhenti
            isScrolling = setTimeout(function() {
                // Pastikan modal tetap tersembunyi setelah scrolling
                if (!$('#mobileReplaceCartModal').hasClass('show') && 
                    !$('#dynamicMobileReplaceCartModal').hasClass('show')) {
                    $('#mobileReplaceCartModal').css({
                        'transform': 'translateY(100%) !important',
                        'visibility': 'hidden'
                    });
                }
            }, 100);
        });
        
        // Modal handling untuk konflik seller
        @if(session('seller_conflict'))
            // Tambahkan delay untuk mencegah muncul karena scroll
            setTimeout(function() {
                // Deteksi apakah perangkat mobile
                if (window.innerWidth <= 768) {
                    showMobileModal();
                } else {
                    $('#replaceCartModal').removeClass('hidden').addClass('flex');
                }
            }, 300);
        @endif
        
        // Menutup modal dengan tombol close atau klik di luar
        $('.modal-close, #replaceCartModal').click(function(e) {
            if (e.target === this) {
                $('#replaceCartModal').removeClass('flex').addClass('hidden');
            }
        });
        
        // Modal mobile backdrop click
        $('#mobileReplaceCartBackdrop').click(function() {
            closeMobileModal();
        });
        
        // Enhanced Page Loading Functions
        function showPageLoading(message = 'Loading page...') {
            // Update loading text
            $('#pageTransition .loading-text').text(message);
            
            // Show loading overlay with fade-in effect
            $('#pageTransition').addClass('active');
            
            // Prevent scrolling while loading
            $('body').addClass('modal-open');
        }
        
        
        function hidePageLoading() {
            // Start fade-out
            $('#pageTransition').css('opacity', '0');
            
            // Complete transition and hide
            setTimeout(() => {
                $('#pageTransition').removeClass('active').css('opacity', '');
                
                // Enable scrolling again if no other modals are open
                if (!$('#mobileReplaceCartModal').hasClass('show') && 
                    !$('#dynamicMobileReplaceCartModal').hasClass('show')) {
                    $('body').removeClass('modal-open');
                }
            }, 500);
        }
        
        // Show loading when clicking on navigation links (not anchors or JS links)
        $(document).on('click', 'a[href]:not([href^="#"]):not([href^="javascript"]):not([href^="mailto"]):not([target="_blank"])', function(e) {
            const href = $(this).attr('href');
            if (href) {
                e.preventDefault();
                showPageLoading('Navigating to page...');
                
                // Delayed navigation for better UX
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            }
        });
        
        // Add page transitions for mobile bottom navigation
        $('.mobile-navigation a, .related-products a').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (href) {
                showPageLoading('Loading product...');
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            }
        });
        
        // Fungsi untuk menampilkan modal mobile dengan lebih aman
        function showMobileModal() {
            // Simpan posisi scroll terlebih dahulu
            const scrollPosition = window.pageYOffset;
            
            // Tambahkan backdrop jika belum ada
            if ($('#mobileReplaceCartBackdrop').length === 0) {
                $('body').append('<div id="mobileReplaceCartBackdrop" class="modal-backdrop"></div>');
            }
            
            // Tambahkan class untuk mencegah scroll
            $('body').addClass('modal-open');
            $('body').css('top', `-${scrollPosition}px`);
            
            // Pastikan visibility diatur ke visible sebelum transformasi
            $('#mobileReplaceCartModal').css('visibility', 'visible');
            
            // Tampilkan backdrop dulu
            $('#mobileReplaceCartBackdrop').addClass('show');
            
            // Kemudian tampilkan modal dengan sedikit delay
            setTimeout(function() {
                $('#mobileReplaceCartModal').css('transform', 'translateY(0) !important').addClass('show');
            }, 50);
        }
        
        // Fungsi untuk menutup modal mobile dengan aman
        function closeMobileModal() {
            // Kembalikan modal ke posisi tersembunyi
            $('#mobileReplaceCartModal').css('transform', 'translateY(100%) !important').removeClass('show');
            
            // Kemudian sembunyikan backdrop
            setTimeout(function() {
                $('#mobileReplaceCartBackdrop').removeClass('show');
                $('#mobileReplaceCartModal').css('visibility', 'hidden');
                
                // Kembalikan scroll position
                if ($('body').hasClass('modal-open')) {
                    const scrollY = parseInt($('body').css('top') || '0') * -1;
                    $('body').removeClass('modal-open');
                    $('body').css('top', '');
                    window.scrollTo(0, scrollY);
                }
            }, 300);
        }
        
        // Modifikasi AJAX untuk Add to Cart pada fungsi submit dengan loading animasi
        $('#addToCartForm').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            // Gunakan loading effect yang baru
            showPageLoading('Adding to cart...');
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Update cart counter
                    $('.cart-counter').text(response.cartCount);
                    
                    // Hide loading with delay for better UX
                    setTimeout(function() {
                        hidePageLoading();
                        
                        // Show success message
                        showNotification(response.message, 'success');
                        
                        // Refresh page with loading effect
                        setTimeout(function() {
                            showPageLoading('Refreshing page...');
                            window.location.reload();
                        }, 1000);
                    }, 800);
                },
                error: function(xhr) {
                    // Hide loading
                    hidePageLoading();
                    
                    const response = xhr.responseJSON;
                    
                    // Jika terjadi konflik seller
                    if (xhr.status === 409 && response.conflict) {
                        // Tampilkan modal konfirmasi
                        showSellerConflictModal(response.message, response.product_id, $('#addToCartForm input[name="quantity"]').val());
                    } else {
                        // Tampilkan pesan error biasa
                        showNotification(response.message || 'Terjadi kesalahan', 'error');
                    }
                }
            });
        });

        // Fungsi untuk me-refresh konten halaman tanpa refresh browser
        function silentRefresh() {
            showPageLoading('Updating page...');
            
            // Ambil URL halaman saat ini
            const currentUrl = window.location.href;
            
            // Gunakan AJAX untuk mengambil konten halaman baru
            $.ajax({
                url: currentUrl,
                type: 'GET',
                success: function(response) {
                    // Parse HTML response
                    const newContent = $(response);
                    
                    // Ganti konten .main-content dengan yang baru
                    $('.main-content').html(newContent.find('.main-content').html());
                    
                    // Update cart counter if present
                    if (newContent.find('.cart-counter').length) {
                        $('.cart-counter').text(newContent.find('.cart-counter').text());
                    }
                    
                    // Reinitialize components
                    reinitializeComponents();
                    
                    // Hide loading
                    hidePageLoading();
                    
                    // Scroll to top
                    window.scrollTo(0, 0);
                },
                error: function() {
                    // Hide loading
                    hidePageLoading();
                    
                    // Show error notification
                    showNotification('Failed to update page, trying again...', 'error');
                    
                    // Fallback to normal refresh
                    setTimeout(function() {
                        showPageLoading('Reloading page...');
                        window.location.reload();
                    }, 1000);
                }
            });
        }

        // Fungsi untuk menampilkan modal konflik seller secara dinamis dan lebih aman
        function showSellerConflictModal(message, productId, quantity) {
            // Hapus modal yang mungkin sudah ada sebelumnya
            $('#dynamicReplaceCartModal, #dynamicMobileReplaceCartModal, #dynamicMobileReplaceCartBackdrop').remove();
            
            // Deteksi apakah perangkat mobile
            if (window.innerWidth <= 768) {
                // Buat modal mobile HTML
                const mobileBackdropHtml = `<div id="dynamicMobileReplaceCartBackdrop" class="modal-backdrop"></div>`;
                const mobileModalHtml = `
                    <div id="dynamicMobileReplaceCartModal" class="modal-mobile" data-product-id="${productId}" data-quantity="${quantity}">
                        <div class="modal-mobile-header">
                            <h3 class="font-bold">Konfirmasi Ganti Produk</h3>
                        </div>
                        <div class="modal-mobile-body">
                            <p class="mb-4 text-gray-700">${message}</p>
                            
                            <p class="mb-4 text-gray-700">
                                Apakah Anda ingin mengganti ke produk tersebut lalu menghapus produk sebelumnya?
                            </p>
                        </div>
                        <div class="modal-mobile-footer">
                            <div class="grid grid-cols-2 gap-3 w-full">
                                <button type="button" id="mobileRejectReplace" class="col-span-1 px-4 py-3 border border-gray-300 rounded-md text-gray-600 font-medium">
                                    Tidak
                                </button>
                                
                                <button type="button" id="mobileAcceptReplace" class="col-span-1 px-4 py-3 bg-pink-600 text-white rounded-md font-medium">
                                    Ya, Ganti
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Simpan posisi scroll
                const scrollPosition = window.pageYOffset;
                
                // Tambahkan modal mobile ke body
                $('body').append(mobileBackdropHtml + mobileModalHtml);
                
                // Tambahkan class untuk mencegah scroll
                $('body').addClass('modal-open');
                $('body').css('top', `-${scrollPosition}px`);
                
                // Pastikan visibility diatur ke visible sebelum transformasi
                $('#dynamicMobileReplaceCartModal').css('visibility', 'visible');
                
                // Animasi tampilkan modal
                setTimeout(function() {
                    $('#dynamicMobileReplaceCartBackdrop').addClass('show');
                    setTimeout(function() {
                        $('#dynamicMobileReplaceCartModal').css('transform', 'translateY(0) !important').addClass('show');
                    }, 50);
                }, 10);
                
                // Event handler untuk tombol "Tidak"
                $('#mobileRejectReplace').click(function() {
                    closeDynamicMobileModal();
                });
                
                // Event handler untuk tombol "Ya"
                $('#mobileAcceptReplace').click(function() {
                    // AJAX request untuk mengganti keranjang
                    replacementRequest(productId, quantity);
                });
                
                // Event handler untuk backdrop
                $('#dynamicMobileReplaceCartBackdrop').click(function() {
                    closeDynamicMobileModal();
                });
                
                // Tambahkan handler swipe untuk modal dinamis
                bindSwipeHandlers('#dynamicMobileReplaceCartModal');
                
            } else {
                // Untuk desktop, gunakan modal yang sudah ada sebelumnya
                const modalHtml = `
                    <div id="dynamicReplaceCartModal" class="fixed inset-0 z-50 flex items-center justify-center" data-product-id="${productId}" data-quantity="${quantity}">
                        <div class="absolute inset-0 bg-black opacity-50"></div>
                        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto z-10">
                            <h3 class="text-lg font-bold mb-4">Konfirmasi Ganti Produk di Keranjang</h3>
                            
                            <p class="mb-4 text-gray-700">${message}</p>
                            
                            <p class="mb-4 text-gray-700">
                                Apakah Anda ingin mengganti ke produk tersebut lalu menghapus produk sebelumnya?
                            </p>
                            
                            <div class="flex justify-end gap-3 mt-4">
                                <button type="button" id="btnRejectReplace" class="px-4 py-2 border border-gray-300 rounded text-gray-600">
                                    Tidak, saya ingin melihat-lihat saja
                                </button>
                                
                                <button type="button" id="btnAcceptReplace" class="px-4 py-2 bg-pink-600 text-white rounded ml-2">
                                    Ya, saya ingin menggantinya
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Tambahkan modal ke body
                $('body').append(modalHtml);
                
                // Event handler untuk tombol "Tidak"
                $('#btnRejectReplace').click(function() {
                    $('#dynamicReplaceCartModal').remove();
                });
                
                // Event handler untuk tombol "Ya"
                $('#btnAcceptReplace').click(function() {
                    // AJAX request untuk mengganti keranjang
                    replacementRequest(productId, quantity);
                });
                
                // Event handler untuk klik di luar modal
                $('#dynamicReplaceCartModal').click(function(e) {
                    if (e.target === this) {
                        $(this).remove();
                    }
                });
            }
        }
        
        // Fungsi untuk bind handler swipe pada elemen modal
        function bindSwipeHandlers(modalSelector) {
            let touchStartY = 0;
            let touchEndY = 0;
            
            $(modalSelector).on('touchstart', function(e) {
                touchStartY = e.originalEvent.touches[0].clientY;
            });
            
            $(modalSelector).on('touchmove', function(e) {
                // Hanya proses touch jika di bagian atas modal dan scroll ke bawah
                if ($(this).scrollTop() <= 0) {
                    touchEndY = e.originalEvent.touches[0].clientY;
                    
                    // Jika swipe ke bawah (touchEndY > touchStartY), tambahkan transformasi
                    if (touchEndY > touchStartY) {
                        // Hanya transformasi sebagian untuk efek pegas
                        const translateY = Math.min(100, (touchEndY - touchStartY) / 2);
                        $(this).css('transform', `translateY(${translateY}px)`);
                        e.preventDefault(); // Cegah scroll body
                    }
                }
            });
            
            $(modalSelector).on('touchend', function(e) {
                // Jika swipe ke bawah cukup jauh, tutup modal
                if ($(this).scrollTop() <= 0 && touchEndY - touchStartY > 100) {
                    if ($(this).attr('id') === 'dynamicMobileReplaceCartModal') {
                        closeDynamicMobileModal();
                    } else {
                        closeMobileModal();
                    }
                } else {
                    // Reset posisi
                    $(this).css('transform', '');
                }
            });
        }
        
        // Bind swipe handler untuk modal dari session
        bindSwipeHandlers('#mobileReplaceCartModal');
        
        // Fungsi untuk menutup dynamic mobile modal dengan aman
        function closeDynamicMobileModal() {
            $('#dynamicMobileReplaceCartModal').css('transform', 'translateY(100%) !important').removeClass('show');
            
            setTimeout(function() {
                $('#dynamicMobileReplaceCartBackdrop').removeClass('show');
                setTimeout(function() {
                    $('#dynamicMobileReplaceCartModal').css('visibility', 'hidden');
                    $('#dynamicMobileReplaceCartModal, #dynamicMobileReplaceCartBackdrop').remove();
                    
                    // Kembalikan scroll position
                    if ($('body').hasClass('modal-open')) {
                        const scrollY = parseInt($('body').css('top') || '0') * -1;
                        $('body').removeClass('modal-open');
                        $('body').css('top', '');
                        window.scrollTo(0, scrollY);
                    }
                }, 200);
            }, 300);
        }

        // Add this function inside $(document).ready
        // Pastikan desktop-only disembunyikan pada tampilan mobile
        function updateButtonVisibility() {
            if (window.innerWidth <= 768) {
                $('.desktop-only').css('display', 'none');
                $('.mobile-buttons').css('display', 'flex');
            } else {
                $('.desktop-only').css('display', 'grid');
                $('.mobile-buttons').css('display', 'none');
            }
        }

        // Panggil saat halaman dimuat
        updateButtonVisibility();

        // Dan saat ukuran layar berubah
        $(window).resize(function() {
            updateButtonVisibility();
        });
        
        // Fungsi untuk mengirim request penggantian keranjang - dengan loading dan refresh
        function replacementRequest(productId, quantity) {
            // Use enhanced loading effect
            showPageLoading('Updating cart...');
            
            $.ajax({
                url: $('#addToCartForm').attr('action'),
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    replace_cart: 'yes',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    // Update cart counter
                    $('.cart-counter').text(response.cartCount);
                    
                    // Remove desktop modal
                    $('#dynamicReplaceCartModal').remove();
                    
                    // Close mobile modal if open
                    closeDynamicMobileModal();
                    
                    // Hide loading with delay for better UX
                    setTimeout(function() {
                        hidePageLoading();
                        
                        // Show success message
                        showNotification(response.message, 'success');
                        
                        // Refresh page with loading effect
                        setTimeout(function() {
                            showPageLoading('Refreshing page...');
                            window.location.reload();
                        }, 1000);
                    }, 800);
                },
                error: function(xhr) {
                    // Hide loading
                    hidePageLoading();
                    
                    const response = xhr.responseJSON;
                    showNotification(response.message || 'Terjadi kesalahan', 'error');
                    
                    // Remove desktop modal
                    $('#dynamicReplaceCartModal').remove();
                    
                    // Close mobile modal if open
                    closeDynamicMobileModal();
                }
            });
        }

        // Fungsi untuk reinisialisasi komponen setelah refresh konten
        function reinitializeComponents() {
            // Reinitialize AOS
            AOS.refreshHard();
            
            // Reinitialize Slick Slider
            if ($('.product-images').length) {
                // Destroy existing slick
                if ($('.product-images').hasClass('slick-initialized')) {
                    $('.product-images').slick('unslick');
                }
                
                // Initialize new slick
                $('.product-images').slick({
                    dots: true,
                    arrows: true,
                    fade: true,
                    autoplay: false,
                    speed: 500,
                    cssEase: 'ease-in-out',
                    mobileFirst: true,
                    prevArrow: '<button class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                arrows: true
                            }
                        }
                    ]
                });
            }
            
            // Reattach thumbnail click events
            $('.thumbnail-item').click(function() {
                $('.thumbnail-item').removeClass('active');
                $(this).addClass('active');
                $('.product-images').slick('slickGoTo', $(this).index());
            });
            
            // Reattach tab navigation
            $('.tab-btn').click(function() {
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
                
                $('.tab-content').addClass('hidden');
                $('#' + $(this).data('target')).removeClass('hidden');
            });
            
            // Update button visibility
            updateButtonVisibility();
        }
        
        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type) {
            // Hapus notifikasi yang mungkin sudah ada
            $('.notification-toast').remove();
            
            const notifClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            let position;
            
            // Posisi notifikasi berdasarkan perangkat
            if (window.innerWidth <= 768) {
                position = 'top-4 inset-x-4 text-center';
            } else {
                position = 'top-4 right-4';
            }
            
            const html = `
                <div class="notification-toast fixed ${position} z-50 ${notifClass} text-white px-4 py-2 rounded shadow-lg transition-opacity duration-300">
                    ${message}
                </div>
            `;
            
            const $notif = $(html).appendTo('body');
            
            // Otomatis hilangkan notifikasi setelah 3 detik
            setTimeout(function() {
                $notif.fadeTo(500, 0, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
        // Window resize handler untuk beralih antara modal desktop dan mobile
        $(window).resize(function() {
            // Jika ada modal konflik seller yang sedang aktif dari session
            if ($('#replaceCartModal').hasClass('flex') || $('#mobileReplaceCartModal').hasClass('show')) {
                if (window.innerWidth <= 768) {
                    // Beralih ke modal mobile
                    $('#replaceCartModal').removeClass('flex').addClass('hidden');
                    showMobileModal();
                } else {
                    // Beralih ke modal desktop
                    closeMobileModal();
                    $('#replaceCartModal').removeClass('hidden').addClass('flex');
                }
            }
            
            // Jika ada modal konflik seller yang sedang aktif dari AJAX
            if ($('#dynamicReplaceCartModal').length || $('#dynamicMobileReplaceCartModal').length) {
                const productId = $('#dynamicReplaceCartModal').data('product-id') || $('#dynamicMobileReplaceCartModal').data('product-id');
                const quantity = $('#dynamicReplaceCartModal').data('quantity') || $('#dynamicMobileReplaceCartModal').data('quantity');
                const message = $('#dynamicReplaceCartModal .text-gray-700').first().text() || 
                            $('#dynamicMobileReplaceCartModal .text-gray-700').first().text();
                
                // Hapus modal yang ada
                $('#dynamicReplaceCartModal, #dynamicMobileReplaceCartModal, #dynamicMobileReplaceCartBackdrop').remove();
                
                // Kembalikan body ke normal jika dalam mode modal
                if ($('body').hasClass('modal-open')) {
                    const scrollY = parseInt($('body').css('top') || '0') * -1;
                    $('body').removeClass('modal-open');
                    $('body').css('top', '');
                    window.scrollTo(0, scrollY);
                }
                
                // Tampilkan modal yang sesuai dengan ukuran layar
                setTimeout(function() {
                    showSellerConflictModal(message, productId, quantity);
                }, 100);
            }
        });
        
        // Deteksi pull-to-refresh pada halaman
        let touchStartY = 0;
        let touchEndY = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });
        
        document.addEventListener('touchmove', function(e) {
            touchEndY = e.touches[0].clientY;
            
            // Jika pull-to-refresh di bagian atas halaman
            if ($(window).scrollTop() === 0 && touchStartY < 50 && touchEndY - touchStartY > 70) {
                // Tampilkan indikator pull-to-refresh
                $('.pull-to-refresh').addClass('visible');
            }
        }, { passive: true });
        
        document.addEventListener('touchend', function(e) {
            if ($(window).scrollTop() === 0 && touchStartY < 50 && touchEndY - touchStartY > 100) {
                // Show loading effect
                showPageLoading('Refreshing...');
                
                // Refresh halaman setelah pull cukup jauh
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            } else {
                // Sembunyikan indikator jika tidak cukup jauh
                $('.pull-to-refresh').removeClass('visible');
            }
        }, { passive: true });
        
        // Pastikan mobile buttons terlihat pada perangkat mobile
        if (window.innerWidth <= 768) {
            $('.mobile-buttons').removeClass('hidden').css('display', 'flex');
        }
    });

    // Quantity controls
    function changeQuantity(change) {
        const input = document.querySelector('input[name="quantity"]');
        const newValue = parseInt(input.value) + change;
        const max = parseInt(input.max) || 10;
        
        if (newValue >= 1 && newValue <= max) {
            input.value = newValue;
        }
    }