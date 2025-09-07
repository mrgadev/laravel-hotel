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
@section('title', 'Promo')
@section('main')
@include('components.frontpage-navbar')
<header class="lg:px-36 px-12 w-screen lg:h-[65vh] pt-36">
    {{-- <div class="absolute h-screen w-full bg-[#162034] opacity-70 z-10"></div> --}}
    <div class="flex items-center gap-1 text-primary-700">
        <a href="{{route('frontpage.index')}}" class="flex items-center">
            <span class="material-symbols-rounded">home</span>
        </a>
        <span class="material-symbols-rounded">chevron_right</span>
        <p>Promo</p>
    </div>
    <div class="flex flex-col gap-8 h-[90%] justify-center">
        <h1 class="text-4xl lg:text-6xl text-primary-700">Nikmati Promo Spesial Kami!</h1>
        <p class=" text-primary-500">Dapatkan perjalanan sempurna dan pengalaman baru bersama UNS Inn Hotel!<br> Tambahkan kesenangan ke dalamnya dengan promo kami. Jadikan liburan Anda momen yang tak terlupakan.</p>
    </div>
</header>

{{-- Promo List Container --}}
<div class="mx-12 lg:mx-36 my-8 " id="promos">
    {{-- <div class="flex flex-col gap-1 my-5 justify-center items-center">
        <p class="text-2xl lg:text-5xl font-medium text-primary-700">Promo Terbaru Kami</p>
        <p class="text-sm text-center text-gray-600">Temukan penawaran eksklusif dan penginapan mewah yang dirancang khusus untuk Anda di hotel kami.<br> Pesan sekarang dan nikmati kenyamanan serta penghematan yang tak tertandingi</p>
    </div> --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($promos as $promo)    
        <div class="flex flex-col gap-5 mb-10">
            <div class="relative">
                <img src="{{Storage::url($promo->cover)}}" alt="" class="w-full h-48 object-cover rounded-xl relative">
                <div id="copyPromoCode" class="absolute bottom-5 left-5 flex items-center gap-1 px-3 py-1 rounded-full bg-primary-100 text-primary-600 w-fit text-sm">
                    <input type="text" id="promoCode" value="{{$promo->code}}" class="bg-transparent border-none outline-none text-sm ">
                    <button class="" id="copyPromoCodeBtn"><span class="material-symbols-rounded scale-75">file_copy</span></button>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <p class="text-sm flex items-center gap-1 text-primary-500"><span class="material-symbols-rounded">calendar_month</span>{{date('j F Y', strtotime($promo->start_date))}} - {{date('j F Y', strtotime($promo->end_date))}}</p>
                <h3 class="text-xl text-primary-700">{{$promo->name}}</h3>
                <p class="text-primary-500">
                    Berlaku untuk kamar :
                </p>
                <div class="flex">
                    @forelse ($promo->rooms as $room)

                    <div class="flex items-center" style="">
                        <p class="bg-primary-100 me-2 text-primary-600 text-xs rounded-full px-3 py-1 border border-primary-600">{{$room->name}}</p>
                    </div>

                    @empty

                    <div class="flex items-center" style="">
                        <p class="bg-primary-100 me-2 text-primary-600 text-xs rounded-full px-3 py-1 border border-primary-600">Semua Kamar</p>
                    </div>

                    @endforelse
                </div>
            </div>
        </div>
        @endforeach

        
    </div>

   
</div>

@include('components.frontpage-footer')

@endsection
@push('addons-script')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('scroll', function() {
            const mainNavbar = document.getElementById('mainNavbar');
            if(window.scrollY > 0) {
                mainNavbar.classList.add('scrolled');
            } else {
                mainNavbar.classList.remove('scrolled');
            }
        });

        const openMobileMenu = document.getElementById('openMobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        openMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.remove('hidden');
        });

        closeMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
        });

        

        const copyPromoCode = document.getElementById('copyPromoCode');
        const promoCode = document.getElementById('promoCode');
        const copyPromoCodeBtn = document.getElementById('copyPromoCodeBtn');

        copyPromoCodeBtn.addEventListener('click', function() {
            promoCode.select();
            document.execCommand("copy");
        });

        const toggleUserMenu = document.getElementById('toggleUserMenu');
        const userMenu = document.getElementById('userMenu');

        toggleUserMenu.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        })
    </script>
@endpush
