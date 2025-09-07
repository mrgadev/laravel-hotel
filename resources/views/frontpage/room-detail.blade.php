@extends('layouts.frontpage')
@push('addons-style')

{{-- <style>
    #mainNavbar.scrolled {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index:1;
        background-color: white;
        /* border-radius: 25px; */
        /* border: 2px solid #976033; */
        width: 100%;
        padding: 1.5rem 9rem;
        transition: all 0.5s ease;
    }

    #mainNavbar.scrolled p {
        color: #976033;
    }
    #mainNavbar.scrolled a {
        color: #333;
        transition: all 0.4s ease;
    }
    #mainNavbar.scrolled a:hover {
        color: #976033;
        transition: all 0.4s ease;
    }

    #mainNavbar.scrolled .auth-button a:first-child {
        background-color: #976033;
        color: #fff
    }

    #mainNavbar.scrolled .auth-button a:nth-child(2) {
        border: 2px solid #976033;
        color: #976033;
        transition: all 0.4s ease;
    }

    #mainNavbar.scrolled .auth-button a:nth-child(2):hover {
        background-color: #976033;
        color: #fff;
        transition: all 0.4s ease;
    }



    @media (max-width: 1024px) {
        #mainNavbar.scrolled {
            padding: 1.5rem 3rem;
        }

        #mainNavbar.scrolled [name="menu-outline"] {
            color: #976033;
        }
    }
</style> --}}
@endpush
@section('title', 'Detail Kamar')
@section('main')
@include('components.frontpage-navbar')
<div class="lg:px-36 px-12 w-screen pt-36 flex items-center gap-1 text-sm lg:text-base">
    <a href="{{route('frontpage.index')}}" class="flex items-center text-primary-500">
        <span class="material-symbols-rounded">home</span>
    </a>
    <span class="material-symbols-rounded text-primary-500">chevron_right</span>
    <a href="{{route('frontpage.rooms')}}" class="flex items-center text-primary-500">
        Daftar Kamar
    </a>
    <span class="material-symbols-rounded text-primary-500">chevron_right</span>
    <p class="text-primary-700">Detail Kamar</p>
</div>
<header class="lg:px-36 px-12 py-11 w-screen grid lg:grid-cols-2 gap-8">
    <div class="grid gap-3">
        <div class="relative">
            @if(isset($room->thumbnail_url) && !empty($room->thumbnail_url) && is_string($room->thumbnail_url))
                {{-- <img src="{{ url($room->cover) }}" class="rounded-2xl h-96 object-cover object-center w-full" alt="{{ $room->name ?? 'Room cover' }}"> --}}
                <img src="{{ $room->thumbnail_url }}" 
                        alt="{{ $room->name ?? 'Kamar' }}" 
                        class="w-full h-64 object-cover rounded-t-xl relative"
                        onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
            @else
                <div class="rounded-2xl h-96 bg-gray-200 flex items-center justify-center w-full">
                    <span class="text-gray-500">Foto tidak tersedia</span>
                </div>
            @endif
            
            @if(isset($room) && !empty($room->photos))
            <button class="absolute bottom-5 left-5 bg-primary-100 px-5 py-2 rounded-full border border-primary-700 flex items-center gap-1 text-primary-700 transition-all hover:bg-primary-700 hover:text-primary-100" id="galleryBtn">
                <ion-icon name="images-outline"></ion-icon> Lihat foto lainnya
            </button>
            @endif

            @if(isset($room) && !empty($room->photos))
                <div class="flex flex-col justify-center items-center gap-8 px-12 lg:px-36 w-screen h-screen hidden fixed bg-gray-800/75 z-20  top-0 left-0" id="gallery">
                    <button class="top-14 absolute right-14 text-white" id="closeGallery">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                    <h1 class="text-3xl text-white">Galeri Kamar</h1>
                    <div class="image-container-wrapper flex items-center gap-8 ">
                        <button class="text-primary-700 bg-primary-100 flex items-center justify-center p-3 rounded-full slider-button" id="galleryBack">
                            <span class="material-symbols-rounded">arrow_back</span>
                        </button>

                        <div class="image-container max-h-lvh max-w-2xl w-full">
                            <div class="carousel flex overflow-hidden transition-all">
                                {{-- @php
                                    $photos = $room->photos ? explode(',', $room->photos) : [];
                                @endphp --}}
                                @forelse($room->photos as $photo)
                                    @if(!empty($photo) && is_string($photo))
                                        <img src="{{url($photo)}}" class="rounded-xl img" alt="Room photo">
                                    @endif
                                @empty
                                    <div class="rounded-xl bg-gray-200 flex items-center justify-center min-w-full h-96">
                                        <span class="text-gray-500">Foto tidak tersedia</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <button class="text-primary-700 bg-primary-100 flex items-center justify-center p-3 rounded-full slider-button" id="galleryForward">
                            <span class="material-symbols-rounded">arrow_forward</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="flex flex-col gap-5">
        <div class="flex items-center justify-between">
            <div class="flex flex-col gap-1.5">
                <div class="flex items-center gap-1 font-light text-gray-700">
                    <i class="bi bi-star-fill text-primary-500"></i>
                    5  (4 Ulasan)
                    {{-- 5 ({{$reviews ? $reviews->count() : 0}} Ulasan) --}}
                </div>
                <h1 class="text-4xl text-primary-700">{{$room->name ?? 'Nama kamar tidak tersedia'}}</h1>
                <p class="text-2xl text-primary-500">
                    @if($room && $room->price)
                        Rp. {{number_format($room->price,0,',','.')}} <span class="text-sm">/malam</span>
                    @else
                        <span class="text-gray-500">Harga belum tersedia</span>
                    @endif
                </p>
            </div>
        </div>
        
        @if($room && $room->room_facility && count($room->room_facility) > 0)
            <div class="flex flex-col lg:flex-row lg:items-center gap-5 font-light text-primary-700">
                @foreach ($room->room_facility as $facility)
                    <p class="flex flex-col gap-1">
                        <span class="material-icons-round">{{$facility->icon ?? 'room_service'}}</span>
                        {{$facility->name ?? 'Fasilitas'}}
                    </p>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Informasi fasilitas belum tersedia</p>
        @endif

        @if($room)
            {{-- Modified reservation form to handle authentication check with improved UX --}}
            <form action="#" class="hidden mt-5 py-3 ps-10 w-fit pe-3 lg:flex items-center gap-8 bg-primary-100 border border-primary-700 text-primary-700 rounded-full" method="GET" id="reservationForm">
                <div class="flex items-center gap-3">
                    <div class="grid grid-cols-1 gap-2">
                        <label for="checkIn" class="text-sm">Check-in</label>
                        <input type="date" name="check_in" id="checkIn" value="{{ session('check_in') }}" class="outline-none border-none bg-transparent text-lg p-0">
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        <label for="checkOut" class="text-sm">Check-out</label>
                        <input type="date" name="check_out" id="checkOut" value="{{ session('check_out') }}" class="outline-none border-none bg-transparent text-lg p-0">
                    </div>
                </div>
                <button type="button" id="bookingBtn" class="text-white bg-primary-500 w-fit px-5 py-3 rounded-full hover:bg-primary-600 transition-colors">
                    Pesan
                </button>
            </form>
            {{-- Modified mobile booking button to handle authentication check --}}
            <button id="mobileBookingBtn" class="lg:hidden text-white bg-primary-500 w-fit px-5 py-3 rounded-full hover:bg-primary-600 transition-colors">Pesan sekarang</button>
        @else
            <p class="text-gray-500">Kamar tidak tersedia</p>
        @endif
    </div>
</header>

<div class="mx-12 lg:mx-36">
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl text-primary-700">Deskripsi</h1>
        <div class="text-gray-700 font-light">
            @if($room && $room->description)
                {!!$room->description!!}
            @else
                <p class="text-gray-500">Deskripsi belum tersedia.</p>
            @endif
        </div>

        <h1 class="text-2xl text-primary-700 mt-5">Peraturan dan Tata Tertib</h1>
        <div class="text-gray-700 font-light flex flex-col gap-2">
            {{-- @if(isset($site_setting))
                @if($site_setting->checkin_time)
                    <p><i class="bi bi-calendar2-check"></i> Checkin hanya dibolehkan di hari yang sama diatas jam {{Carbon\Carbon::parse($site_setting->checkin_time)->format('H:i')}}</p>
                @endif
                @if($site_setting->checkout_time)
                    <p><i class="bi bi-calendar2-x"></i> Adapun checkout hanya dibolehkan diatas jam {{Carbon\Carbon::parse($site_setting->checkout_time)->format('H:i')}}</p>
                @endif
            @endif --}}
            
            @php
                $global_rules = App\Models\RoomRule::where('room_id', NULL)->get();
                $specific_rules = $room ? App\Models\RoomRule::where('room_id', $room->id)->get() : collect();
            @endphp
            
            @forelse ($global_rules as $global_rule)
                <p><span class="material-icons">{{$global_rule->icon ?? 'rule'}}</span> {{$global_rule->rule ?? 'Peraturan'}}</p>
            @empty
                @if($specific_rules->isEmpty())
                    <p class="text-gray-500">Peraturan belum tersedia.</p>
                @endif
            @endforelse
{{--             
            @if($specific_rules && $specific_rules->count() >= 1)
                @foreach ($specific_rules as $specific_rule)
                    <p><span class="material-icons">{{$specific_rule->icon ?? 'rule'}}</span> {{$specific_rule->rule ?? 'Peraturan khusus'}}</p>
                @endforeach
            @endif --}}
        </div>
    </div>
    <div class="my-12 grid lg:grid-cols-3 gap-10">
        {{-- <div class="flex flex-col gap-8 col-span-2">
            <div class="flex flex-col gap-3">
                <h2 class="text-2xl text-primary-700">Ulasan</h2>
                <p class="flex items-center gap-2 text-gray-700">
                    @if($reviews && $reviews->count() >= 1)
                        @php
                            $total_rating = 0;
                            foreach($reviews as $review) {
                                $total_rating += $review->rating ?? 0;
                            }
                            $average_rating = $total_rating / $reviews->count();
                        @endphp
                        <ion-icon name="star" class="text-primary-500"></ion-icon>
                        {{number_format($average_rating, 1)}} ({{$reviews->count()}} pelanggan)
                    @else
                        <span class="text-gray-500">Belum ada ulasan</span>
                    @endif
                </p>
            </div>

            @if($reviews && $reviews->count() >= 1)
                @foreach ($reviews as $review)
                    <div class="flex flex-col gap-5 bg-primary-100 lg:w-1/2 p-5 rounded-xl border border-primary-700">
                        <div class="flex items-center gap-5">
                            @if($review->user && $review->user->avatar)
                                <img src="{{url($review->user->avatar)}}" class="w-14 h-14 rounded-full" alt="User avatar">
                            @else
                                <div class="w-14 h-14 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-500 text-xs">No Avatar</span>
                                </div>
                            @endif
                            <div class="flex flex-col ">
                                <p class="text-lg text-primary-700 font-medium">{{$review->user->name ?? 'User'}}</p>
                                <p class="font-light text-primary-600 text-sm">
                                    {{Carbon\Carbon::parse($review->created_at)->isoFormat('dddd, D MMM YYYY')}}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-primary-700">{{$review->title ?? 'Ulasan'}}</p>
                            <div class="text-gray-700">
                                {!!$review->description ?? 'Tidak ada deskripsi'!!}
                            </div>
                            <p class="flex items-center gap-2 text-primary-700">
                                <ion-icon name="star" class="text-primary-500"></ion-icon> {{$review->rating ?? 0}}
                            </p>
                        </div>
                    </div>
                @endforeach
                @if(method_exists($reviews, 'links'))
                    {{$reviews->links()}}
                @endif
            @else
                <p class="text-gray-500">Belum ada ulasan untuk kamar ini.</p>
            @endif
        </div> --}}

        <div class="col-span-1 flex flex-col gap-10 order-first lg:order-last">
            <div class="flex flex-col gap-3">
                <h2 class="text-2xl text-primary-700">Kamar Lainnya</h2>
            </div>
            @if(isset($other_room) && count($other_room) > 0)
                @foreach ($other_room as $item)
                    <a href="{{route('frontpage.rooms.detail',$item->slug)}}" class="flex flex-col gap-3">
                        {{-- @if($item->cover)
                            <img src="{{url($item->cover)}}" class="h-56 w-full object-cover rounded-lg" alt="{{$item->name}}">
                        @else
                            <div class="h-56 w-full bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">Foto tidak tersedia</span>
                            </div>
                        @endif --}}
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col gap-2">
                                <h3 class="text-xl text-primary-700">{{$item->name ?? 'Nama kamar tidak tersedia'}}</h3>
                                @if($item->room_facility && count($item->room_facility) > 0)
                                    <div class="flex gap-1 items-center">
                                        @foreach ($item->room_facility as $facility)
                                            <div class="flex items-center text-sm text-primary-500">
                                                <span class="material-icons-round scale-75">{{$facility->icon ?? 'room_service'}}</span>
                                                <p>{{$facility->name ?? 'Fasilitas'}}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <p class="text-lg text-primary-500">
                                @if($item->price)
                                    Rp. {{number_format($item->price,0,',','.')}}
                                @else
                                    <span class="text-gray-500 text-sm">Harga belum tersedia</span>
                                @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            @else
                <p class="text-gray-500">Tidak ada kamar lain tersedia.</p>
            @endif
        </div>
    </div>
</div>


@include('components.frontpage-footer')
@include('components.auth-popup')
@endsection
@push('addons-script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const openMobileMenu = document.getElementById('openMobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        openMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.remove('hidden');
        });

        closeMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
        });

        const galleryBtn = document.getElementById('galleryBtn');
        const galleryContainer = document.getElementById('gallery');
        galleryBtn.addEventListener('click', function() {
            galleryContainer.classList.remove('hidden');
        });

        const galleryBack = document.getElementById('galleryBack');
        const galleryForward = document.getElementById('galleryForward');
        const gallery = document.querySelector('.carousel');
        const closeGallery = document.getElementById('closeGallery');
        galleryBack.addEventListener('click', function() {
            gallery.scrollLeft -= 672;
            // gallery.style.transition = 'all 0.4s ease';
        });

        galleryForward.addEventListener('click', function() {
            gallery.scrollLeft += 672;
            // gallery.style.transition = 'all 0.4s ease';
        });

        closeGallery.addEventListener('click', function() {
            galleryContainer.classList.add('hidden');
        });

        // document.onclick = function(e){
        //     if (!galleryContainer.contains(e.target)) {
        //         galleryContainer.classList.add("hidden");
        //         // box.classList.remove("active_box");
        //     }
        // }
        const toggleUserMenu = document.getElementById('toggleUserMenu');
        const userMenu = document.getElementById('userMenu');

        toggleUserMenu.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const authModal = document.getElementById('authModal');
            const closeAuthModal = document.getElementById('closeAuthModal');
            const bookingBtn = document.getElementById('bookingBtn');
            const mobileBookingBtn = document.getElementById('mobileBookingBtn');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const otpForm = document.getElementById('otpForm');
            const showRegister = document.getElementById('showRegister');
            const showLogin = document.getElementById('showLogin');
            const modalTitle = document.getElementById('modalTitle');

            let currentUserPhone = '';
            let currentUserId = '';
            let bookingData = {};

            // Check if user is authenticated
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

            // Get current protocol and host for AJAX requests
            const baseUrl = window.location.protocol + '//' + window.location.host;

            // Handle booking button clicks
            function handleBooking() {
                const checkIn = document.getElementById('checkIn').value;
                const checkOut = document.getElementById('checkOut').value;

                // Validate dates for desktop form
                if (bookingBtn && (!checkIn || !checkOut)) {
                    alert('Silakan pilih tanggal check-in dan check-out terlebih dahulu!');
                    return;
                }

                // Store booking data
                bookingData = {
                    room_id: {{ $room->id }},
                    check_in: checkIn || new Date().toISOString().split('T')[0],
                    check_out: checkOut || new Date(Date.now() + 86400000).toISOString().split('T')[0]
                };

                if (isAuthenticated) {
                    // User is already logged in, redirect to checkout
                    redirectToCheckout();
                } else {
                    // Show authentication modal
                    authModal.classList.remove('hidden');
                }
            }

            // Event listeners for booking buttons
            if (bookingBtn) {
                bookingBtn.addEventListener('click', handleBooking);
            }
            if (mobileBookingBtn) {
                mobileBookingBtn.addEventListener('click', handleBooking);
            }

            // Close modal
            closeAuthModal.addEventListener('click', function() {
                authModal.classList.add('hidden');
                resetForms();
            });

            // Close modal when clicking outside
            authModal.addEventListener('click', function(e) {
                if (e.target === authModal) {
                    authModal.classList.add('hidden');
                    resetForms();
                }
            });

            // Switch between login and register forms
            showRegister.addEventListener('click', function() {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                modalTitle.textContent = 'Buat Akun Baru';
            });

            showLogin.addEventListener('click', function() {
                registerForm.classList.add('hidden');
                otpForm.classList.add('hidden');
                loginForm.classList.remove('hidden');
                modalTitle.textContent = 'Masuk ke Akun Anda';
            });

            // Handle login form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                // Add booking data to login request
                Object.keys(bookingData).forEach(key => {
                    formData.append(key, bookingData[key]);
                });
                
                const loginError = document.getElementById('loginError');
                loginError.classList.add('hidden');
                
                fetch(baseUrl + '/ajax/login', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        authModal.classList.add('hidden');
                        // Redirect to checkout with booking data
                        window.location.href = data.redirect_url;
                    } else {
                        loginError.textContent = data.message || 'Login gagal. Silakan coba lagi.';
                        loginError.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                    loginError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    loginError.classList.remove('hidden');
                });
            });

            // Handle register form submission
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const registerError = document.getElementById('registerError');
                registerError.classList.add('hidden');
                
                // Validate password confirmation
                const password = document.getElementById('registerPassword').value;
                const passwordConfirm = document.getElementById('registerPasswordConfirm').value;
                
                if (password !== passwordConfirm) {
                    registerError.textContent = 'Password dan konfirmasi password tidak cocok.';
                    registerError.classList.remove('hidden');
                    return;
                }
                
                fetch(baseUrl + '/ajax/register', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentUserPhone = formData.get('phone');
                        currentUserId = data.user_id;
                        document.getElementById('otpUserId').value = currentUserId;
                        registerForm.classList.add('hidden');
                        otpForm.classList.remove('hidden');
                        modalTitle.textContent = 'Verifikasi OTP';
                    } else {
                        registerError.textContent = data.message || 'Registrasi gagal. Silakan coba lagi.';
                        registerError.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Register error:', error);
                    registerError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    registerError.classList.remove('hidden');
                });
            });

            // Handle OTP verification
            otpForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                // Add booking data to OTP verification request
                Object.keys(bookingData).forEach(key => {
                    formData.append(key, bookingData[key]);
                });
                
                const otpError = document.getElementById('otpError');
                otpError.classList.add('hidden');
                
                fetch(baseUrl + '/ajax/verify-otp', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        authModal.classList.add('hidden');
                        // Redirect to checkout with booking data
                        window.location.href = data.redirect_url;
                    } else {
                        otpError.textContent = data.message || 'Kode OTP salah. Silakan coba lagi.';
                        otpError.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('OTP verification error:', error);
                    otpError.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    otpError.classList.remove('hidden');
                });
            });

            // Handle resend OTP
            document.getElementById('resendOtp').addEventListener('click', function() {
                const formData = new FormData();
                formData.append('phone', currentUserPhone);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                fetch(baseUrl + '/ajax/resend-otp', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Kode OTP berhasil dikirim ulang!');
                    } else {
                        alert('Gagal mengirim ulang OTP. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Resend OTP error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
            });

            // Redirect to checkout with booking data
            function redirectToCheckout() {
                const params = new URLSearchParams(bookingData);
                window.location.href = `{{ route('frontpage.checkout', $room->id) }}?${params.toString()}`;
            }

            // Reset all forms
            function resetForms() {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                otpForm.classList.add('hidden');
                modalTitle.textContent = 'Masuk ke Akun Anda';
                
                // Clear error messages
                document.getElementById('loginError').classList.add('hidden');
                document.getElementById('registerError').classList.add('hidden');
                document.getElementById('otpError').classList.add('hidden');
                
                // Reset form fields
                loginForm.reset();
                registerForm.reset();
                otpForm.reset();
            }

            // Set minimum date for date inputs
            const today = new Date().toISOString().split('T')[0];
            const checkInInput = document.getElementById('checkIn');
            const checkOutInput = document.getElementById('checkOut');
            
            if (checkInInput) {
                checkInInput.setAttribute('min', today);
                checkInInput.addEventListener('change', function() {
                    const checkInDate = new Date(this.value);
                    checkInDate.setDate(checkInDate.getDate() + 1);
                    if (checkOutInput) {
                        checkOutInput.setAttribute('min', checkInDate.toISOString().split('T')[0]);
                    }
                });
            }
            
            if (checkOutInput) {
                checkOutInput.setAttribute('min', today);
                checkOutInput.addEventListener('change', function() {
                    if (checkInInput && new Date(this.value) <= new Date(checkInInput.value)) {
                        alert('Tanggal checkout harus setelah tanggal checkin!');
                        this.value = '';
                    }
                });
            }
        });
    </script>
@endpush
