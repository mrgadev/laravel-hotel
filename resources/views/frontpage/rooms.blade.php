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
@section('title', 'Daftar Kamar')
@section('main')
@include('components.frontpage-navbar')
<header class="lg:px-36 px-12 w-screen lg:h-[60vh] pt-36">
    {{-- <div class="absolute h-screen w-full bg-[#162034] opacity-70 z-10"></div> --}}
    <div class="flex items-center gap-1 text-primary-700">
        <a href="{{route('frontpage.index')}}" class="flex items-center">
            <span class="material-symbols-rounded">home</span>
        </a>
        <span class="material-symbols-rounded">chevron_right</span>
        <p>Daftar Kamar</p>
    </div>
    <div class="flex flex-col gap-8 h-[70%] justify-center">
        <h1 class="text-4xl lg:text-6xl text-primary-700">Jelajahi Kamar Terbaik Kami</h1>
        <p class=" text-primary-500">Dapatkan perjalanan sempurna dan pengalaman baru bersama UNS Inn!<br> Tambahkan kesenangan ke dalamnya dengan promo kami. Jadikan liburan Anda momen yang tak terlupakan.</p>
    </div>
</header>

{{-- Promo List Container --}}
<div class="mx-12 lg:mx-36  " id="promos">
    {{-- <div class="flex flex-col gap-1 my-5 justify-center items-center">
        <p class="text-2xl lg:text-5xl font-medium text-primary-700">Promo Terbaru Kami</p>
        <p class="text-sm text-center text-gray-600">Temukan penawaran eksklusif dan penginapan mewah yang dirancang khusus untuk Anda di hotel kami.<br> Pesan sekarang dan nikmati kenyamanan serta penghematan yang tak tertandingi</p>
    </div> --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 my-12">
        @foreach ($rooms as $room)
        <a href="{{route('frontpage.rooms.detail', $room->slug)}}" class="flex flex-col gap-5">
            <div class="relative">
                {{-- <img src="{{url($room->cover)}}" alt="" class="w-full h-64 object-cover rounded-xl relative"> --}}
                <img src="{{ $room->thumbnail_url }}" 
                        alt="{{ $room->name ?? 'Kamar' }}" 
                        class="w-full h-64 object-cover rounded-t-xl relative"
                        onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                <p class="absolute bottom-5 left-5 flex items-end gap-1 px-3 py-1 rounded-full bg-primary-100 text-primary-600 text-sm">
                   <span class="text-lg">IDR {{number_format($room->price,0,',','.')}}</span>/malam 
                </p>
            </div>
            <div class="flex flex-col gap-2">
                <h3 class="text-xl text-primary-700 hover:underline">{{$room->name}}</h3>
                <div class="flex items-center text-sm text-primary-500">
                @foreach ($room->room_facility as $facility)
                        <span class="material-icons-round scale-75">{{$facility->icon}}</span>
                @endforeach
                </div>
            </div>
        </a>
        @endforeach
    </div>

   
</div>

@include('components.frontpage-footer')

@endsection
@push('addons-script')
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

        const toggleUserMenu = document.getElementById('toggleUserMenu');
        const userMenu = document.getElementById('userMenu');

        toggleUserMenu.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        })
    </script>
@endpush
