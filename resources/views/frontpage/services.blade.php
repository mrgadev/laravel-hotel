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
@section('title', 'Daftar Layanan')
@section('main')
@include('components.frontpage-navbar')
<header class="lg:px-36 px-12 w-screen lg:h-[60vh] pt-36">
    {{-- <div class="absolute h-screen w-full bg-[#162034] opacity-70 z-10"></div> --}}
    <div class="flex items-center gap-1 text-primary-700">
        <a href="{{route('frontpage.index')}}" class="flex items-center">
            <span class="material-symbols-rounded">home</span>
        </a>
        <span class="material-symbols-rounded">chevron_right</span>
        <p>Layanan Lainnya</p>
    </div>
    <div class="flex flex-col gap-8 h-[70%] justify-center">
        <h1 class="text-4xl lg:text-6xl text-primary-700">Layanan Lainnya</h1>
        <p class=" text-primary-500">Selain kamar hotel, kami juga menyediakan layanan tambahan lain seperti acara pernikahan, ballroom untuk pertemuan, event dan lainnya.</p>
    </div>
</header>

{{-- Services List Container --}}
<div class="mx-12 lg:mx-36  " id="services">
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('frontpage.services') }}" class="px-5 py-2 rounded-full bg-primary-100 text-primary-700 border border-primary-700 transition-all hover:bg-primary-700 hover:text-white{{ $selectedCategory === 'Semua' ? ' font-medium bg-primary-700 text-white' : '' }}">
            Semua
        </a>
        @foreach ($serviceCategories as $serviceCategory)
            <a href="{{ route('frontpage.services', ['category' => $serviceCategory->name]) }}" class="px-5 py-2 rounded-full bg-primary-100 text-primary-700 border border-primary-700 transition-all hover:bg-primary-700 hover:text-white{{ $selectedCategory === $serviceCategory->name ? ' font-medium bg-primary-700 text-white' : '' }}">
                {{ $serviceCategory->name }}
            </a>
        @endforeach
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 my-12">
        @foreach ($services as $service)
            <a href="{{route('frontpage.services.detail', $service->id)}}" class="flex flex-col gap-5 group">
                <div class="relative overflow-hidden rounded-xl">
                    @if($service->cover)
                        <img src="{{Storage::url($service->cover)}}" alt="{{ $service->name }}" 
                             class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105"
                             onerror="this.onerror=null; this.src='{{ asset('images/placeholder-service.jpg') }}';">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-xl">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif
                    <div class="absolute bottom-5 left-5 flex items-end gap-1 px-3 py-1 rounded-full bg-primary-100 text-primary-600 text-sm backdrop-blur-sm">
                        <span class="text-lg font-semibold">IDR {{number_format($service->price,0,',','.')}}</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <h3 class="text-xl text-primary-700 group-hover:underline transition-all duration-300">{{$service->name}}</h3>
                    <div class="text-sm flex items-center gap-1 text-primary-500">
                        {{-- <span class="material-symbols-rounded scale-75">category</span> --}}
                        {{$service->serviceCategory->name}}
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
        document.addEventListener('DOMContentLoaded', function() {
            const openMobileMenu = document.getElementById('openMobileMenu');
            const closeMobileMenu = document.getElementById('closeMobileMenu');
            const mobileMenu = document.getElementById('mobileMenu');

            if (openMobileMenu && mobileMenu) {
                openMobileMenu.addEventListener('click', function() {
                    mobileMenu.classList.remove('hidden');
                });
            }

            if (closeMobileMenu && mobileMenu) {
                closeMobileMenu.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            }

            const toggleUserMenu = document.getElementById('toggleUserMenu');
            const userMenu = document.getElementById('userMenu');

            if (toggleUserMenu && userMenu) {
                toggleUserMenu.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
@endpush