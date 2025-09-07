@extends('layouts.frontpage')
@push('addons-style')
<style>
    #mainNavbar.scrolled {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index:1;
        background-color: white;
        padding: 1.5rem 9rem;
        transition: all 0.5s ease;
    }

    #mainNavbar.scrolled p,
    #mainNavbar.scrolled button {
        color: #976033;
    }
    #mainNavbar.scrolled a {
        color: #976033;
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

    #mainNavbar.scrolled p.user-role {
        background: #976033;
        color: white;
    }

    option {
        padding: 2rem;
    }

    header {
        background: 
            linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), /* overlay gelap */
            url('https://unsinnsolo.co.id/wp-content/uploads/2024/12/Resto1_11zon-scaled.jpg');
        background-size: cover; /* bisa ganti contain jadi cover supaya full */
        background-position: center;
    }

    .cta-card {
        background: url({{ $frontpage_site_setting && $frontpage_site_setting->cta_cover ? url($frontpage_site_setting->cta_cover) : '' }});
        background-position: center;
        background-size: cover;
    }

    @media (max-width: 1024px) {
        #mainNavbar.scrolled {
            padding: 1.5rem 3rem;
        }

        #mainNavbar.scrolled [name="menu-outline"] {
            color: #976033;
        }
    }
</style>
@endpush
@section('title', 'Beranda')
@section('main')
<header class="xl:px-36 px-12 bg-fixed relative w-screen h-screen">
    <nav class=" duration-500 transition-all flex items-center justify-between py-6 text-white">
    {{-- <nav class=" duration-500 transition-all flex items-center justify-between py-6 text-white" id="mainNavbar"> --}}
        <a href="{{route('frontpage.index')}}"  class="text-lg font-medium"><img src="{{asset('assets/img/Logo-Inn-UNS-White.svg')}}" class="w-16" alt=""></a>
        <div class="xl:flex gap-8 font-light hidden">
            <a href="{{route('frontpage.index')}}" class="hover:font-medium {{(Route::is('frontpage.index') ? 'font-medium' : '')}}">Beranda</a>
            <a href="{{route('frontpage.rooms')}}" class="hover:font-medium {{(Route::is('frontpage.rooms') ? 'font-medium' : '')}}">Kamar</a>
            <a href="{{route('frontpage.promo')}}" class="hover:font-medium {{(Route::is('frontpage.promo') ? 'font-medium' : '')}}">Promo</a>
            <a href="{{route('frontpage.services')}}" class="hover:font-medium {{(Route::is('frontpage.services') ? 'font-medium' : '')}}">Layanan Lainnya </a>
            <a href="{{route('frontpage.about')}}" class="hover:font-medium {{(Route::is('frontpage.about') ? 'font-medium' : '')}}">Tentang Kami</a>
        </div>
        <ion-icon name="menu-outline" class="xl:hidden text-4xl" id="openMobileMenu"></ion-icon>
        @auth
            <div class="hidden xl:flex items-center gap-2">
                @if(Auth::user()->avatar && url(Auth::user()->avatar))
                <img src="{{url(Auth::user()->avatar)}}" class="w-12 h-12 object-cover object-center rounded-full" alt="">
                @else
                <span class="material-symbols-rounded">account_circle</span>
                @endif
                <div class="flex flex-col gap-1">
                    <button class="text-lg" id="toggleUserMenu">{{Auth::user()->name ?? 'User'}}</button>
                    <p class="user-role text-xs px-2 py-1 bg-white text-primary-700 rounded-md w-fit">
                        {{ Auth::user()->roles->first() ? ucfirst(Auth::user()->roles->first()->name) : 'Member' }}
                    </p>
                </div>
                <div id="userMenu" class="bg-white absolute z-10 right-28 top-28 border border-primary-700 rounded-xl p-3 hidden">
                    <div class="flex flex-col gap-2">
                        <a href="{{route('dashboard.home')}}" class="text-primary-700">Dashboard</a>
                        <hr>
                        <form action="{{route('logout')}}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="text-red-600 z-20">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>

        @endauth
        @guest

        <div class=" items-center gap-3 auth-button hidden xl:flex">
            @auth
                <a href="{{route('admin.dashboard')}}" class="px-5 py-2 border border-white text-white rounded-full hover:bg-white hover:text-primary-500 transition-all">Dashboard</a>
            @endauth
            @guest
                <a href="{{route('register')}}" class="bg-white text-primary-500 px-5 py-2 rounded-full hover:bg-white transition-all">Daftar</a>
                <a href="{{route('login')}}" class="px-5 py-2 border border-white text-white rounded-full hover:bg-white hover:text-primary-500 transition-all">Masuk</a>
            @endguest
        </div>
        @endguest

    </nav>
    <nav class="duration-500 bg-white w-screen h-screen fixed hidden top-0 left-0 right-0 z-10 px-12" id="mobileMenu">
        <div class="flex items-center justify-between py-6 text-primary-500">
            <a href="{{route('frontpage.index')}}"  class="text-lg font-medium"><img src="{{asset('assets/img/Logo-Inn-UNS-White.svg')}}" alt=""></a>
            <span class="material-symbols-rounded" id="closeMobileMenu">close</span>
        </div>

        <div class="flex flex-col gap-8 mt-8 font-light">
            <a href="{{route('frontpage.index')}}" class="hover:font-medium {{(Route::is('frontpage.index') ? 'font-medium' : '')}}">Beranda</a>
            <a href="{{route('frontpage.rooms')}}" class="hover:font-medium {{(Route::is('frontpage.rooms') ? 'font-medium' : '')}}">Kamar </a>
            <a href="{{route('frontpage.promo')}}" class="hover:font-medium {{(Route::is('frontpage.promo') ? 'font-medium' : '')}}">Promo</a>
            <a href="{{route('frontpage.services')}}" class="hover:font-medium {{(Route::is('frontpage.services') ? 'font-medium' : '')}}">Layanan Lainnya </a>
            <a href="{{route('frontpage.about')}}" class="hover:font-medium {{(Route::is('frontpage.about') ? 'font-medium' : '')}}">Tentang Kami</a>
            @guest
            <a href="{{route('login')}}" class="px-5 py-3 rounded-full bg-primary-500 text-white w-fit">Masuk / Daftar</a>
            @endguest
            @auth
            <a href="{{route('dashboard.home')}}" class="px-5 py-3 rounded-full bg-primary-500 text-white w-fit">Dashboard</a>
            <form action="{{route('logout')}}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" class="text-red-600 z-20">Keluar</button>
            </form>
            @endauth
        </div>
    </nav>

    <section class="main h-[90%] flex flex-col justify-center gap-3 relative">
        <h1 class="text-4xl xl:text-6xl text-white">
            {!! $frontpage_site_setting && $frontpage_site_setting->tagline ? $frontpage_site_setting->tagline : 'Selamat Datang di UNS Inn Hotel' !!}
        </h1>
        <div class="text-white">
            {!! $frontpage_site_setting && $frontpage_site_setting->description ? $frontpage_site_setting->description : 'Hotel terbaik untuk pengalaman menginap yang tak terlupakan' !!}
        </div>

        <form action="{{route('frontpage.search')}}" id="reservationForm" class="hidden mt-5 bg-white py-5 ps-10 pe-5 xl:flex items-center justify-between w-3/5 rounded-full" method="POST">
            @csrf
            <div class="flex items-center gap-2">
                <div class="grid grid-cols-1 gap-2">
                    <label for="" class="text-sm px-3">Pilih Kamar</label>
                    <select name="room_id" class="outline-none border-none text-lg px-3" id="">
                        <option value="" class="p-2 ">-- Pilih Kamar --</option>
                        @if($rooms && count($rooms) > 0)
                            @foreach ($rooms as $room)
                                <option value="{{$room->id}}">{{$room->name ?? 'Kamar'}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <label for="checkIn" class="text-sm px-3">Check-in</label>
                    <input type="date" id="checkIn" name="check_in" class="outline-none border-none text-lg px-3">
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <label for="checkOut" class="text-sm px-3">Check-out</label>
                    <input type="date" id="checkOut" name="check_out" class="outline-none border-none text-lg px-3" name="" id="">
                </div>
            </div>
            <button class="text-white bg-primary-500 w-fit px-5 me-3 py-3 rounded-full">
                Pesan
            </button>
        </form>

        {{-- Mobile booking form --}}
        <button class="text-white bg-primary-500 w-fit px-5 py-3 rounded-full block xl:hidden" id="openBookingForm">Pesan sekarang</button>
        <div class="inset-0 z-20 bg-gray-500 bg-opacity-65 fixed flex items-center justify-center xl:hidden hidden min-h-screen w-screen " id="bookingForm">
            <form action="{{route('frontpage.search')}}" id="reservationFormMobile" method="POST" class="flex flex-col gap-5 justify-center bg-white w-[90%] rounded-xl p-5">
                @csrf
                <h2 class="text-2xl font-medium mb-5 text-primary-500">Pesan Kamar</h2>
                <div class="grid grid-cols-1 gap-2 w-full">
                    <label for="#rooms" class="flex items-center gap-1 text-primary-700 font-light text-sm"><span class="material-symbols-rounded scale-75">meeting_room</span> Pilih Kamar</label>

                    <select name="room_id" id="rooms" class="p-2 bg-primary-100 border border-primary-700 rounded-lg text-primary-700">
                        <option value="">Pilih kamar</option>
                        @if($rooms && count($rooms) > 0)
                            @foreach ($rooms as $room)
                            <option value="{{$room->id}}">{{$room->name ?? 'Kamar'}}</option>
                            @endforeach
                        @endif

                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <label for="#rooms" class="flex items-center gap-1 text-primary-700 font-light text-sm"><span class="material-symbols-rounded scale-75">meeting_room</span> Check-in</label>
                        <input type="date" name="check_in" id="checkInMobile" class="p-2 bg-primary-100 border border-primary-700 rounded-lg text-primary-700" id="">
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <label for="#rooms" class="flex items-center gap-1 text-primary-700 font-light text-sm"><span class="material-symbols-rounded scale-75">meeting_room</span> Check-out</label>
                        <input type="date" name="check_out" id="checkOutMobile" class="p-2 bg-primary-100 border border-primary-700 rounded-lg text-primary-700" id="">
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-3">
                    <button type="button" class="px-5 py-2 border border-primary-500 text-primary-500 rounded-full hover:bg-primary-500 hover:text-white transition-all" id="closeBookingForm">Batal</button>
                    <button class="bg-primary-500 text-white px-5 py-2 rounded-full hover:bg-primary-700 transition-all">Pesan</button>
                </div>
            </form>

        </div>
    </section>
</header>
{{-- Our Rooms Section --}}
<div class="px-12 xl:px-36 py-6">
    <div class="flex items-center justify-between">
        <div class="flex flex-col gap-1 my-5">
            <p class="text-sm  text-primary-500">Temukan</p>
            <p class="text-2xl font-medium text-primary-700">Kamar Terbaik Kami</p>
        </div>
        <a href="{{route('frontpage.rooms')}}" class="px-5 py-3 rounded-full text-white bg-primary-500 transition-all hover:bg-primary-700">Lihat semua</a>
    </div>

    <div class="" id="roomsContainer">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @if($rooms && count($rooms) > 0)
            @foreach ($rooms as $room)
            <a href="{{route('frontpage.rooms.detail', $room->slug ?? '#')}}" class="flex flex-col rounded-xl shadow-xl">
                {{-- Menggunakan thumbnail dari array photos atau fallback ke placeholder --}}
                <img src="{{ $room->thumbnail_url }}" 
                        alt="{{ $room->name ?? 'Kamar' }}" 
                        class="w-full h-64 object-cover rounded-t-xl relative"
                        onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                
                <div class="flex flex-col gap-2 p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl text-primary-700 hover:underline">{{$room->name ?? 'Kamar'}}</h3>
                    </div>
                    <div class="text-sm flex items-center gap-1 text-primary-500">
                        @if($room->room_facility && count($room->room_facility) > 0)
                            @foreach ($room->room_facility as $facility)
                                <div class="flex items-center gap-1">
                                    <span class="material-icons-round scale-50">{{ $facility->icon ?? 'home' }}</span>
                                    <p class="text-xs">{{ $facility->name ?? 'Fasilitas' }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <p class="text-lg mt-3 text-primary-500">Rp. {{ $room->price ? number_format($room->price,0,',','.') : '0' }}/malam</p>
                </div>
            </a>
            @endforeach
        @else
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500">Belum ada kamar tersedia</p>
            </div>
        @endif
        </div>
    </div>

</div>


{{-- Our Services Section --}}
<div class="px-12 xl:px-36 py-12">
    <div class="flex flex-col gap-1 justify-center items-center">
        <p class="font-medium text-primary-400">Kenali</p>
        <h2 class="text-3xl font-medium text-primary-700">
            {{ $frontpage_site_setting && $frontpage_site_setting->our_service_title ? $frontpage_site_setting->our_service_title : 'Layanan Kami' }}
        </h2>
    </div>
    <div class="grid xl:grid-cols-2 gap-5 my-11">
        {{-- <img src="https://via.placeholder.com/600x400?text=Service+Image" alt=""> --}}
        <img src="{{ $frontpage_site_setting && $frontpage_site_setting->service_image ? url($frontpage_site_setting->service_image) : 'https://via.placeholder.com/600x400?text=Service+Image' }}" class="lg:w-1/2" alt="">
        <div class="flex flex-col gap-8">
            @if($hotel_services && count($hotel_services) > 0)
                @foreach ($hotel_services as $hotel_service)
                <div class="flex flex-col xl:flex-row gap-8 sm:items-start xl:items-center">
                    <span class="material-icons-round text-primary-400 scale-[200%]">{{ $hotel_service->icon ?? 'star' }}</span>
                    <div class="flex flex-col gap-1">
                        <h2 class="text-2xl">{{ $hotel_service->name ?? 'Layanan Hotel' }}</h2>
                        <p class="font-light">{!! $hotel_service->description ?? 'Deskripsi layanan hotel' !!}</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-10">
                    <p class="text-gray-500">Belum ada layanan tersedia</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Our Location Section --}}
<div class="px-12 xl:px-36 my-10">
    <div class="flex flex-col gap-1">
        <p class="font-medium text-primary-400">Lokasi Kami</p>
        <h2 class="text-3xl font-medium text-primary-700">
            {{ $frontpage_site_setting && $frontpage_site_setting->our_location_title ? $frontpage_site_setting->our_location_title : 'Lokasi Strategis' }}
        </h2>
    </div>

    <div class="grid xl:grid-cols-2 gap-5">
        <div class="flex flex-col gap-3">
            <p class="my-5">
                {!! $frontpage_site_setting && $frontpage_site_setting->our_location_desc ? $frontpage_site_setting->our_location_desc : 'Hotel kami terletak di lokasi yang strategis' !!}
            </p>
            <div class="grid xl:grid-cols-2 gap-8">
                @if($nearby_locations && count($nearby_locations) > 0)
                    @foreach ($nearby_locations as $nearby_location)
                    <div class="flex items-center gap-5">
                        <span class="material-icons-round text-primary-400 scale-[150%]">{{ $nearby_location->icon ?? 'location_on' }}</span>
                        <div class="flex flex-col">
                            <p class="text-lg text-gray-700">{{ $nearby_location->name ?? 'Lokasi' }}</p>
                            <p class="text-primary-400">{{ $nearby_location->distance ?? '0' }} M</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-2 text-center py-5">
                        <p class="text-gray-500">Belum ada lokasi terdekat yang tersedia</p>
                    </div>
                @endif
            </div>
        </div>
        @if($site_setting && $site_setting->address)
            <iframe width="600" height="450" loading="lazy" allowfullscreen src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q={{ urlencode($site_setting->address) }}&zoom=15&maptype=roadmap"></iframe>
        @else
            <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded">
                <p class="text-gray-500">Peta tidak tersedia</p>
            </div>
        @endif
    </div>
</div>

{{-- Facilities --}}
<div class="px-12 xl:px-36 my-14">
    <div class="flex flex-col gap-1 items-center justify-center">
        <p class="font-medium text-primary-400">Fasilitas Kami</p>
        <h2 class="text-3xl font-medium text-primary-700">
            {{ $frontpage_site_setting && $frontpage_site_setting->our_facilities_title ? $frontpage_site_setting->our_facilities_title : 'Fasilitas Hotel' }}
        </h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5 my-10">
        @if($hotel_facilities && count($hotel_facilities) > 0)
            @foreach ($hotel_facilities as $hotel_facility)
            <div class="flex items-center justify-center px-5 py-2 rounded-xl border border-primary-500 gap-5 bg-primary-100 text-primary-500">
                <span class="material-icons-round text-primary-700">{{ $hotel_facility->icon ?? 'home' }}</span>
                <p>{{ $hotel_facility->name ?? 'Fasilitas' }}</p>
            </div>
            @endforeach
        @else
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500">Belum ada fasilitas tersedia</p>
            </div>
        @endif
    </div>
</div>

{{-- Testimonial Section --}}
{{-- Testimonial Section --}}
<div class="px-12 xl:px-36 my-16">
    <div class="flex flex-col lg:flex-row items-center justify-between">
        <div class="flex flex-col gap-1">
            <p class="font-medium text-primary-400">Testimonial</p>
            <h2 class="text-3xl font-medium text-primary-700">
                {{ $frontpage_site_setting && $frontpage_site_setting->testimonial_title ? $frontpage_site_setting->testimonial_title : 'Apa Kata Tamu Kami' }}
            </h2>
        </div>
        <div class="flex items-center gap-2 mt-4 lg:mt-0">
            <button id="prevTestimonial" class="p-2 rounded-full bg-primary-100 text-primary-700 hover:bg-primary-500 hover:text-white transition-all">
                <span class="material-icons-round">chevron_left</span>
            </button>
            <button id="nextTestimonial" class="p-2 rounded-full bg-primary-100 text-primary-700 hover:bg-primary-500 hover:text-white transition-all">
                <span class="material-icons-round">chevron_right</span>
            </button>
        </div>
    </div>

    <div class="mt-10 overflow-hidden">
        <div id="testimonialContainer" class="flex gap-5 transition-transform duration-500 cursor-grab select-none" style="width: calc(400px * 6 + 20px * 5);">
            {{-- Testimonial 1 --}}
            <div class="flex-shrink-0 w-96 flex flex-col gap-5 rounded-lg border border-primary-500 p-5 transition-all hover:shadow-xl bg-white">
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill text-yellow-500"></i>
                    @endfor
                </div>
                <h3 class="font-medium text-primary-700 text-xl">Pelayanan Luar Biasa!</h3>
                <div class="text-sm text-primary-800">
                    "Hotel ini benar-benar memberikan pengalaman menginap yang tak terlupakan. Staff sangat ramah dan profesional, kamar bersih dan nyaman, serta fasilitas yang lengkap. Sangat merekomendasikan untuk teman dan keluarga!"
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">AS</span>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-medium text-primary-700">Ahmad Santoso</p>
                        <p class="text-sm text-primary-500">Pengusaha</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 2 --}}
            <div class="flex-shrink-0 w-96 flex flex-col gap-5 rounded-lg border border-primary-500 p-5 transition-all hover:shadow-xl bg-white">
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill text-yellow-500"></i>
                    @endfor
                </div>
                <h3 class="font-medium text-primary-700 text-xl">Lokasi Strategis</h3>
                <div class="text-sm text-primary-800">
                    "Lokasi hotel sangat strategis, dekat dengan berbagai tempat wisata dan pusat perbelanjaan. Kamar yang luas dan bersih, sarapan yang beragam dan lezat. Pasti akan menginap lagi di sini!"
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">SR</span>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-medium text-primary-700">Sari Rahayu</p>
                        <p class="text-sm text-primary-500">Travel Blogger</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 3 --}}
            <div class="flex-shrink-0 w-96 flex flex-col gap-5 rounded-lg border border-primary-500 p-5 transition-all hover:shadow-xl bg-white">
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 4; $i++)
                        <i class="bi bi-star-fill text-yellow-500"></i>
                    @endfor
                    <i class="bi bi-star text-yellow-500"></i>
                </div>
                <h3 class="font-medium text-primary-700 text-xl">Perfect untuk Keluarga</h3>
                <div class="text-sm text-primary-800">
                    "Hotel ini sangat cocok untuk liburan keluarga. Anak-anak senang dengan fasilitas kolam renang dan area bermain. Kamar family suite nya luas dan nyaman. Staff juga sangat membantu dengan kebutuhan anak-anak."
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">BP</span>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-medium text-primary-700">Budi Prasetyo</p>
                        <p class="text-sm text-primary-500">Ayah dari 2 anak</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 4 --}}
            <div class="flex-shrink-0 w-96 flex flex-col gap-5 rounded-lg border border-primary-500 p-5 transition-all hover:shadow-xl bg-white">
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill text-yellow-500"></i>
                    @endfor
                </div>
                <h3 class="font-medium text-primary-700 text-xl">Fasilitas Lengkap</h3>
                <div class="text-sm text-primary-800">
                    "Fasilitas hotel sangat lengkap, mulai dari gym, spa, hingga restoran dengan menu yang lezat. Kamar executive suite sangat mewah dengan pemandangan kota yang menawan. Value for money yang sangat baik!"
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">DF</span>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-medium text-primary-700">Dewi Fortuna</p>
                        <p class="text-sm text-primary-500">Manager</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 5 --}}
            <div class="flex-shrink-0 w-96 flex flex-col gap-5 rounded-lg border border-primary-500 p-5 transition-all hover:shadow-xl bg-white">
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill text-yellow-500"></i>
                    @endfor
                </div>
                <h3 class="font-medium text-primary-700 text-xl">Pengalaman Business Trip Terbaik</h3>
                <div class="text-sm text-primary-800">
                    "Hotel ini menjadi pilihan utama saya untuk business trip. WiFi cepat, meeting room yang modern, dan business center yang lengkap. Lokasi juga strategis dekat dengan kawasan bisnis. Highly recommended!"
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">RK</span>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-medium text-primary-700">Rian Kurnia</p>
                        <p class="text-sm text-primary-500">Business Consultant</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 6 --}}
            <div class="flex-shrink-0 w-96 flex flex-col gap-5 rounded-lg border border-primary-500 p-5 transition-all hover:shadow-xl bg-white">
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 4; $i++)
                        <i class="bi bi-star-fill text-yellow-500"></i>
                    @endfor
                    <i class="bi bi-star text-yellow-500"></i>
                </div>
                <h3 class="font-medium text-primary-700 text-xl">Honeymoon yang Romantis</h3>
                <div class="text-sm text-primary-800">
                    "Kami memilih hotel ini untuk bulan madu dan tidak menyesal! Kamar deluxe dengan jacuzzi pribadi sangat romantis. Service excellent, makanan enak, dan view sunset dari balkon kamar sangat indah. Terima kasih!"
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">MA</span>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-medium text-primary-700">Maya & Andi</p>
                        <p class="text-sm text-primary-500">Pengantin Baru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation dots --}}
    <div class="flex items-center justify-center gap-2 mt-6">
        <div class="testimonial-dot w-3 h-3 rounded-full bg-primary-500 cursor-pointer" data-index="0"></div>
        <div class="testimonial-dot w-3 h-3 rounded-full bg-primary-200 cursor-pointer" data-index="1"></div>
        <div class="testimonial-dot w-3 h-3 rounded-full bg-primary-200 cursor-pointer" data-index="2"></div>
        <div class="testimonial-dot w-3 h-3 rounded-full bg-primary-200 cursor-pointer" data-index="3"></div>
    </div>
</div>

<div class="px-12 xl:px-36 my-28">
    <div class="flex flex-col gap-1 items-center justify-center">
        <h2 class="text-3xl font-medium text-center text-primary-700">
            {{ $frontpage_site_setting && $frontpage_site_setting->award_title ? $frontpage_site_setting->award_title : 'Penghargaan Kami' }}
        </h2>
        <p class=" text-primary-800">
            {!! $frontpage_site_setting && $frontpage_site_setting->award_desc ? $frontpage_site_setting->award_desc : 'Berbagai penghargaan yang telah kami terima' !!}
        </p>
    </div>

    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-8 my-10">
        @if($hotel_awards && count($hotel_awards) > 0)
            @foreach ($hotel_awards as $hotel_award)
            <div class="flex items-center gap-3">
                <img src="{{ $hotel_award->badge ? url($hotel_award->badge) : 'https://via.placeholder.com/100x100?text=Award' }}" class="w-24 h-24 rounded-full" alt="">
                <div class="flex flex-col gap-1">
                    <p>{{ $hotel_award->name ?? 'Penghargaan' }}</p>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500">Belum ada penghargaan tersedia</p>
            </div>
        @endif
    </div>
</div>

{{-- FAQ Section --}}
<div class="px-12 xl:px-36 my-28">
    <div class="flex flex-col gap-1 justify-center items-center">
        <p class="font-medium text-primary-500">Frequently Asked Questions</p>
        <h2 class="text-3xl font-medium text-primary-700">Pertanyaan yang Sering Diajukan</h2>
    </div>

    <div class="grid xl:grid-cols-2 gap-5 mt-10">
        <img src="{{ $frontpage_site_setting && $frontpage_site_setting->faq_illustration ? url($frontpage_site_setting->faq_illustration) : 'https://via.placeholder.com/600x400?text=FAQ+Illustration' }}" class="lg:w-1/2" alt="">
        <div>
            @if($faqs && count($faqs) > 0)
                @foreach($faqs as $faq)
                    <x-faq-item :faq="$faq" :isFirst="$loop->first" />
                @endforeach
            @else
                <div class="text-center py-10">
                    <p class="text-gray-500">Belum ada FAQ tersedia</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Featured by --}}
<div class="px-12 xl:px-36 my-28 flex flex-col gap-16">
    <h2 class="text-3xl font-medium text-primary-700 text-center">Partner Kami</h2>
    @if($partners && count($partners) > 0)
        <div class="w-full inline-flex flex-nowrap overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-200px),transparent_100%)]">
            <ul class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll">
                @foreach ($partners as $partner)
                <li>
                    <a href="{{ $partner->link ?? '#' }}" title="{{ $partner->name ?? 'Partner' }}">
                        <img src="{{ $partner->logo ? url($partner->logo) : 'https://via.placeholder.com/100x50?text=Partner' }}" target="_blank" class="h-14 grayscale hover:grayscale-0" alt="">
                    </a>
                </li>
                @endforeach
            </ul>

            <ul class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll">
                @foreach ($partners as $partner)
                <li>
                    <a href="{{ $partner->link ?? '#' }}" title="{{ $partner->name ?? 'Partner' }}">
                        <img src="{{ $partner->logo ? url($partner->logo) : 'https://via.placeholder.com/100x50?text=Partner' }}" target="_blank" class="h-14 grayscale hover:grayscale-0" alt="">
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-10">
            <p class="text-gray-500">Belum ada partner tersedia</p>
        </div>
    @endif
</div>

<div class="mx-12 xl:mx-36 my-28">
    <div class="cta-card w-full rounded-xl h-60 px-14 flex flex-col justify-center gap-5 relative">
        <div class="absolute w-full h-full bg-primary-500 left-0 top-0 rounded-xl opacity-60">

        </div>
        <div class="flex flex-col gap-5 z-10">
            <h1 class="text-3xl text-white">
                {{ $frontpage_site_setting && $frontpage_site_setting->cta_text ? $frontpage_site_setting->cta_text : 'Pesan Sekarang dan Dapatkan Pengalaman Terbaik!' }}
            </h1>
            <a href="{{ $frontpage_site_setting && $frontpage_site_setting->cta_button_link ? $frontpage_site_setting->cta_button_link : route('frontpage.rooms') }}" class="text-lg px-8 rounded-full bg-white text-primary-500 py-4 w-fit">
                {{ $frontpage_site_setting && $frontpage_site_setting->cta_button_text ? $frontpage_site_setting->cta_button_text : 'Pesan Sekarang' }}
            </a>
        </div>
    </div>
</div>

@include('components.frontpage-footer')

@endsection
@push('addons-script')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        const openMobileMenu = document.getElementById('openMobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        openMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.remove('hidden');
        })

        closeMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
        })

        const openBookingForm = document.getElementById('openBookingForm');
        const closeBookingForm = document.getElementById('closeBookingForm');
        const bookingForm = document.getElementById('bookingForm');

        openBookingForm.addEventListener('click', function() {
            bookingForm.classList.remove('hidden');
        });

        closeBookingForm.addEventListener('click', function() {
            bookingForm.classList.add('hidden');
        });

        document.addEventListener('scroll', function() {
            const mainNavbar = document.getElementById('mainNavbar');
            if(window.scrollY > 0) {
                mainNavbar.classList.add('scrolled');
            } else {
                mainNavbar.classList.remove('scrolled');
            }
        });

        const toggleUserMenu = document.getElementById('toggleUserMenu');
        const userMenu = document.getElementById('userMenu');

        if (toggleUserMenu && userMenu) {
            toggleUserMenu.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            })
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkInInput = document.getElementById('checkIn');
            const checkOutInput = document.getElementById('checkOut');
            const reservationForm = document.getElementById('reservationForm');

            if (checkInInput && checkOutInput) {
                // Set minimum date for check-in to today
                const today = new Date().toISOString().split('T')[0];
                checkInInput.setAttribute('min', today);

                // Enable check-out input and set its min date when check-in is selected
                checkInInput.addEventListener('change', function() {
                    // Enable check-out input
                    checkOutInput.disabled = false;
                    checkOutInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    checkOutInput.classList.add('bg-white', 'cursor-default');

                    // Set minimum date for check-out to the selected check-in date
                    checkOutInput.setAttribute('min', this.value);

                    // Reset check-out input
                    checkOutInput.value = '';
                });

                // Ensure check-out is after check-in
                checkOutInput.addEventListener('change', function() {
                    if (new Date(this.value) <= new Date(checkInInput.value)) {
                        alert('Tanggal checkout harus setelah tanggal checkin!');
                        this.value = '';
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkInInputMobile = document.getElementById('checkInMobile');
            const checkOutInputMobile = document.getElementById('checkOutMobile');
            const reservationFormMobile = document.getElementById('reservationFormMobile');

            if (checkInInputMobile && checkOutInputMobile) {
                // Set minimum date for check-in to today
                const today = new Date().toISOString().split('T')[0];
                checkInInputMobile.setAttribute('min', today);
                checkOutInputMobile.setAttribute('min', today);

                // Dynamically update check-out min date based on check-in date
                checkInInputMobile.addEventListener('change', function() {
                    checkOutInputMobile.setAttribute('min', this.value);

                    // If current check-out date is before new check-in date, reset it
                    if (new Date(checkOutInputMobile.value) < new Date(this.value)) {
                        checkOutInputMobile.value = this.value;
                    }
                });

                // Form submission handler
                if (reservationFormMobile) {
                    reservationFormMobile.addEventListener('submit', function(e) {
                        const checkInDateMobile = checkInInputMobile.value;
                        const checkOutDateMobile = checkOutInputMobile.value;

                        // Basic validation
                        if (!checkInDateMobile || !checkOutDateMobile) {
                            e.preventDefault();
                            alert('Tolong tentukan waktu checkin dan checkout!');
                            return;
                        }
                    });
                }
            }
        });
        </script>
        <script>
            // Testimonial Slider dengan drag functionality
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('testimonialContainer');
                const prevBtn = document.getElementById('prevTestimonial');
                const nextBtn = document.getElementById('nextTestimonial');
                const dots = document.querySelectorAll('.testimonial-dot');
                
                if (!container) return;

                let currentIndex = 0;
                const totalSlides = 4; // Menampilkan 4 set (6 cards dibagi dalam 4 slide)
                const slideWidth = 420; // 400px card width + 20px gap
                const cardsPerSlide = window.innerWidth >= 1280 ? 3 : window.innerWidth >= 768 ? 2 : 1;
                
                let isDown = false;
                let startX = 0;
                let scrollLeft = 0;
                let isDragging = false;

                // Mouse drag functionality
                container.addEventListener('mousedown', (e) => {
                    isDown = true;
                    container.classList.add('cursor-grabbing');
                    container.classList.remove('cursor-grab');
                    startX = e.pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                    isDragging = false;
                });

                container.addEventListener('mouseleave', () => {
                    isDown = false;
                    container.classList.remove('cursor-grabbing');
                    container.classList.add('cursor-grab');
                });

                container.addEventListener('mouseup', () => {
                    isDown = false;
                    container.classList.remove('cursor-grabbing');
                    container.classList.add('cursor-grab');
                    
                    // Snap to closest slide after drag
                    if (isDragging) {
                        const dragDistance = Math.abs(container.scrollLeft - scrollLeft);
                        if (dragDistance > 50) {
                            const direction = container.scrollLeft > scrollLeft ? 1 : -1;
                            currentIndex = Math.max(0, Math.min(totalSlides - 1, currentIndex + direction));
                            updateSlider();
                        }
                        isDragging = false;
                    }
                });

                container.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    isDragging = true;
                    const x = e.pageX - container.offsetLeft;
                    const walk = (x - startX) * 2;
                    container.scrollLeft = scrollLeft - walk;
                });

                // Touch events for mobile
                container.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                    isDragging = false;
                });

                container.addEventListener('touchmove', (e) => {
                    if (!startX) return;
                    isDragging = true;
                    const x = e.touches[0].pageX - container.offsetLeft;
                    const walk = (x - startX) * 2;
                    container.scrollLeft = scrollLeft - walk;
                });

                container.addEventListener('touchend', () => {
                    if (isDragging) {
                        const dragDistance = Math.abs(container.scrollLeft - scrollLeft);
                        if (dragDistance > 50) {
                            const direction = container.scrollLeft > scrollLeft ? 1 : -1;
                            currentIndex = Math.max(0, Math.min(totalSlides - 1, currentIndex + direction));
                            updateSlider();
                        }
                    }
                    startX = 0;
                    isDragging = false;
                });

                // Button navigation
                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        currentIndex = Math.max(0, currentIndex - 1);
                        updateSlider();
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        currentIndex = Math.min(totalSlides - 1, currentIndex + 1);
                        updateSlider();
                    });
                }

                // Dot navigation
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentIndex = index;
                        updateSlider();
                    });
                });

                function updateSlider() {
                    const translateX = -currentIndex * slideWidth * cardsPerSlide;
                    container.style.transform = `translateX(${translateX}px)`;
                    
                    // Update dots
                    dots.forEach((dot, index) => {
                        if (index === currentIndex) {
                            dot.classList.add('bg-primary-500');
                            dot.classList.remove('bg-primary-200');
                        } else {
                            dot.classList.add('bg-primary-200');
                            dot.classList.remove('bg-primary-500');
                        }
                    });

                    // Update button states
                    if (prevBtn) {
                        prevBtn.disabled = currentIndex === 0;
                        if (currentIndex === 0) {
                            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }

                    if (nextBtn) {
                        nextBtn.disabled = currentIndex === totalSlides - 1;
                        if (currentIndex === totalSlides - 1) {
                            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }
                }

                // Auto-play functionality
                let autoPlayInterval = setInterval(() => {
                    if (currentIndex < totalSlides - 1) {
                        currentIndex++;
                    } else {
                        currentIndex = 0;
                    }
                    updateSlider();
                }, 5000);

                // Pause auto-play on hover
                container.addEventListener('mouseenter', () => {
                    clearInterval(autoPlayInterval);
                });

                container.addEventListener('mouseleave', () => {
                    autoPlayInterval = setInterval(() => {
                        if (currentIndex < totalSlides - 1) {
                            currentIndex++;
                        } else {
                            currentIndex = 0;
                        }
                        updateSlider();
                    }, 5000);
                });

                // Initialize
                updateSlider();

                // Responsive handling
                window.addEventListener('resize', () => {
                    const newCardsPerSlide = window.innerWidth >= 1280 ? 3 : window.innerWidth >= 768 ? 2 : 1;
                    if (newCardsPerSlide !== cardsPerSlide) {
                        location.reload(); // Simple solution for responsive changes
                    }
                });
            });
        </script>
@endpush