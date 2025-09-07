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
@section('title', 'Kontak Kami')
@section('main')
@include('components.frontpage-navbar')
<header class="lg:px-36 px-12 w-screen lg:h-[60vh] pt-36">
    {{-- <div class="absolute h-screen w-full bg-[#162034] opacity-70 z-10"></div> --}}
    <div class="flex items-center gap-1 text-primary-700">
        <a href="{{route('frontpage.index')}}" class="flex items-center">
            <span class="material-symbols-rounded">home</span>
        </a>
        <span class="material-symbols-rounded">chevron_right</span>
        <p>Kontak</p>
    </div>
    <div class="flex flex-col gap-8 h-[70%] justify-center">
        <h1 class="text-4xl lg:text-6xl text-primary-700">Kontak Kami</h1>
        <p class=" text-primary-500">Punya pertanyaan tentang produk atau penskalaan di platform kami? Kami siap membantu.<br> Ngobrol dengan tim kami yang ramah 24/7 dan bergabunglah dalam waktu kurang dari 5 menit.</p>
    </div>
</header>


<div class="mx-12 mb-16 lg:mx-36 flex flex-col lg:flex-row lg:justify-between">
    <form action="#" class="">
        <h2 class="mb-5 text-2xl text-primary-700">Kirim Pesan</h2>
        <div class="grid lg:grid-cols-2 gap-5 text-gray-800 font-light">
            <div class="flex flex-col gap-2">
                <label for="firstName">Nama depan</label>
                <input type="text" class="rounded-lg">
            </div>
            <div class="flex flex-col gap-2">
                <label for="lastName">Nama belakang</label>
                <input type="text" class="rounded-lg">
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-5 my-5 text-gray-800 font-light">
            <div class="flex flex-col gap-2">
                <label for="email">Email</label>
                <input type="email" class="rounded-lg">
            </div>
            <div class="flex flex-col gap-2">
                <label for="phone">Nomor telepon</label>
                <input type="text" class="rounded-lg">
            </div>
        </div>

        <div class="flex flex-col gap-2 text-gray-800 font-light">
            <label for="message">Pesan</label>
            <textarea name="" id="" cols="30" rows="10" class="rounded-lg"></textarea>
        </div>

        <button type="submit" class="px-7 py-3 rounded-full bg-primary-100 text-white">Kirim Pesan</button>
    </form>

    <div class="">
        <div class="">

        </div>
        <h2 class="mb-5 text-2xl text-primary-700">Mengobrol dengan Kami</h2>
        <p class="font-light text-gray-700 mb-5">Bertanya ke tim kami via kanal berikut ini</p>
        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-3 text-lg">
                <i class="bi bi-whatsapp text-primary-500 scale-125"></i>
                <a href="#" class="text-gray-800 underline">Mulai chat via WhatsApp</a>
            </div>
            <div class="flex items-center gap-3 text-lg">
                <i class="bi bi-envelope text-primary-500 scale-125"></i>
                <a href="#" class="text-gray-800 underline">Kirim Email</a>
            </div>
            <div class="flex items-center gap-3 text-lg">
                <i class="bi bi-twitter-x text-primary-500 scale-125"></i>
                <a href="#" class="text-gray-800 underline">Kirim pesan via Twitter (X)</a>
            </div>
        </div>
    </div>
</div>

<footer class="w-screen bg-primary-100 flex justify-between px-12 lg:px-36 py-14">
    <div class="flex flex-col gap-5">
        <h2 class="text-3xl text-primary-500">UNS Inn</h2>
        <p class="text-primary-800">Jl. H. R. Rasuna Said No.4 Blok Kav. B<br> Kuningan, Setia Budi, Kota Jakarta Selatan<br>DKI Jakarta 12910</p>
        <div class="flex items-center gap-5 text-2xl text-primary-800">
            <a href="#" class=""><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter"></i></a>
            <a href="#"><i class="bi bi-tiktok"></i></a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8">
        <div class="flex flex-col gap-5">
            <h2 class="text-primary-700 text-xl font-medium">Perusahaan</h2>
            <div class="flex flex-col gap-1">
                <a href="#">Tentang Kami</a>
                <a href="#">Karir</a>
                <a href="#">Kontak</a>
                <a href="#"></a>
                <a href=""></a>
            </div>
        </div>
    </div>
</footer>
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
    </script>
@endpush
