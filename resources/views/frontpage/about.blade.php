@extends('layouts.frontpage')
@push('addons-style')

@endpush
@section('title', 'Tentang Kami')
@section('main')
@include('components.frontpage-navbar')
@php
    $site_settings = App\Models\SiteSettings::where('id',1)->first();
@endphp
<header class="lg:px-36 px-12 pt-36 lg:h-screen flex flex-col justify-center items-center">
    <div class="flex flex-col gap-8 h-[70%] items-center justify-center">
        <h1 class="text-4xl lg:text-6xl text-center text-primary-700">Akomodasi Sempurna untuk<br> Perjalanan Sempurna</h1>
        <p class=" text-primary-500 text-center text-sm">Berwisata bukan hanya sekedar datang ke suatu kota, mengunjungi suatu tempat, dan mudik. Perjalanan sebenarnya adalah saat Anda menjadi bagian dari penduduk setempat, mencicipi makanan lokal, dan merasakan budaya lokal. Sejak tahun 2017, UNS Inn hadir untuk menawarkan pengalaman berbeda bagi wisatawan untuk menikmati perjalanan yang sempurna.</p>
    </div>

    {{-- Featured by --}}
    <div class="px-12 lg:px-36 my-14 flex flex-col gap-16">
        <div class="w-full inline-flex flex-nowrap overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-200px),transparent_100%)]">
            <ul class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll">
                <li><img src="{{asset('images/about (1).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (2).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (3).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (4).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (5).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (6).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (7).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (8).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
            </ul>

            <ul class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll">
                <li><img src="{{asset('images/about (1).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (2).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (3).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (4).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (5).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (6).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (7).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
                <li><img src="{{asset('images/about (8).avif')}}" class="h-64 lg:h-96 rounded-xl" alt=""></li>
            </ul>
        </div>
    </div>
</header>

<div class="mx-12 lg:mx-36 mb-16 flex flex-col lg:flex-row lg:justify-between">

</div>


<div class="mx-12 mb-16 lg:mx-36 flex flex-col lg:flex-row lg:justify-between">
    <form action="{{route('frontpage.message.store')}}" class="" method="POST">
        @csrf
        @method('POST')
        <h2 class="mb-5 text-xl text-primary-700 font-medium">Kirim Pesan</h2>
        <div class="grid lg:grid-cols-2 gap-5 text-gray-800 font-light">
            <div class="flex flex-col gap-2">
                <label for="firstName" class="text-sm">Judul pesan</label>
                <input type="text" class="rounded-lg" name="title">
                @if ($errors->has('title'))
                    <p  class="text-red-500 mt-3 text-sm">{{$errors->first('title')}}</p>
                @endif
            </div>
            <div class="flex flex-col gap-2">
                <label for="lastName" class="text-sm">Nama lengkap</label>
                @guest
                <input type="text" class="rounded-lg" name="name">
                @endguest
                @auth
                <input type="text" class="rounded-lg" name="name" value="{{Auth::user()->name}}">
                @endauth
                @if ($errors->has('name'))
                    <p  class="text-red-500 mt-3 text-sm">{{$errors->first('name')}}</p>
                @endif
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-5 my-5 text-gray-800 font-light">
            <div class="flex flex-col gap-2">
                <label for="email" class="text-sm">Email</label>
                @guest
                <input type="email" class="rounded-lg" name="email">
                @endguest
                @auth
                <input type="email" class="rounded-lg" name="email" value="{{Auth::user()->email}}">
                @endauth
                @if ($errors->has('email'))
                    <p  class="text-red-500 mt-3 text-sm">{{$errors->first('email')}}</p>
                @endif
            </div>
            <div class="flex flex-col gap-2">
                <label for="phone" class="text-sm">Nomor telepon</label>
                @guest
                <input type="text" class="rounded-lg" name="phone">
                @endguest
                @auth
                <input type="text" class="rounded-lg" name="phone" value="{{Auth::user()->phone}}">
                @endauth
                @if ($errors->has('phone'))
                    <p  class="text-red-500 mt-3 text-sm">{{$errors->first('phone')}}</p>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-2 text-gray-800 font-light">
            <label for="message" class="text-sm">Pesan</label>
            <textarea name="message" id="" cols="30" rows="10" class="rounded-lg"></textarea>
            @if ($errors->has('message'))
                <p  class="text-red-500 mt-3 text-sm">{{$errors->first('message')}}</p>
            @endif
        </div>

        <button type="submit" class="px-7 py-3 rounded-full border border-primary-700 mt-8 text-primary-700 w-full transition-all hover:bg-primary-700 hover:text-white">Kirim Pesan</button>
    </form>

    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-3">
            <h3 class="text-xl text-primary-700 font-medium">Hubungi Kami</h3>
            <p class="text-gray-800">Hubungi tim kami setiap hari dari 08:00 sampai 16:00</p>
            <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-primary-500">call</span>
                <a href="tel:{{$site_settings->phone}}" class="underline text-gray-700">{!!$site_settings->phone_text!!}</a>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <h3 class="text-xl text-primary-700 font-medium">Mengobrol dengan Kami</h3>
            <p class="text-gray-800">Ngobrol dengan tim kami yang ramah via live chat</p>
            <div class="flex items-center gap-2">
                <i class="bi bi-whatsapp text-primary-500"></i>
                <a href="tel:wa.me/6281398848566" class="underline text-gray-700">Hubungi via WhatsApp</a>
            </div>
            <div class="flex items-center gap-2">
                <i class="bi bi-envelope-at-fill text-primary-500"></i>
                <a href="mailto:cs@mahirhotel.com" class="underline text-gray-700">Kirim Email</a>
            </div>
            <div class="flex items-center gap-2">
                <i class="bi bi-twitter text-primary-500"></i>
                <a href="https://x.com/mrizqighana" class="underline text-gray-700">Kirim Pesan Twitter</a>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <h3 class="text-xl text-primary-700 font-medium">Alamat Kami</h3>
            <p class="text-gray-800">Ingin menyampaikan secara personal? Bisa langsung ke kantor kami</p>
            <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-primary-500">corporate_fare</span>

                <a href="{{$site_settings->maps_link}}" class="underline text-gray-700">{!!$site_settings->address!!}</a>
            </div>
        </div>
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
