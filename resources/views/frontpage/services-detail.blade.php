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
@section('title', $service->name)
@section('main')
@include('components.frontpage-navbar')
<div class="lg:px-36 px-12 w-screen pt-36 flex items-center gap-1 text-sm lg:text-base">
    <a href="{{route('frontpage.index')}}" class="flex items-center text-primary-500 hover:text-primary-700">
        <span class="material-symbols-rounded">home</span>
    </a>
    <span class="material-symbols-rounded text-primary-500">chevron_right</span>
    <a href="{{route('frontpage.services')}}" class="flex items-center text-primary-500 hover:text-primary-700">
        Layanan Lainnya
    </a>
    <span class="material-symbols-rounded text-primary-500">chevron_right</span>
    <p class="text-primary-700">{{ $service->name }}</p>
</div>

<header class="lg:px-36 px-12 py-11 w-screen grid lg:grid-cols-2 gap-8">
    <!-- Main Cover Image -->
    <div class="grid gap-3">
        <div class="relative flex w-auto cursor-pointer">
            <a href="#cover-modal" class="block w-full">
                @if($service->cover)
                    <img class="h-full w-full object-cover object-center rounded-xl" 
                         src="https://unsinnsolo.co.id/wp-content/uploads/2024/12/Madukara-Room_1_11zon-scaled.jpg" 
                         {{-- src="{{ asset('storage/' . $service->cover) }}"  --}}
                         alt="{{ $service->name }}"
                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder-service.jpg') }}';">
                @else
                    <div class="h-96 w-full bg-gray-200 flex items-center justify-center rounded-xl">
                        <span class="text-gray-500">No Cover Image</span>
                    </div>
                @endif
            </a>
        </div>
    </div>
    
    <!-- Gallery Images -->
    {{-- <div class="grid grid-cols-2 gap-5">
        @php
            $images = json_decode($service->image, true);
        @endphp
        @if (is_array($images) && !empty($images))
            @foreach (array_slice($images, 0, 4) as $index => $image)
                <div class="relative flex h-40 cursor-pointer overflow-hidden rounded-xl">
                    <a href="#image-modal-{{ $index }}" class="block h-full w-full">
                        <img class="h-full w-full object-cover object-center hover:scale-105 transition-transform duration-300"
                             src="https://unsinnsolo.co.id/wp-content/uploads/2024/12/Madukara-Room_1_11zon-scaled.jpg"
                             alt="{{ $service->name }} - Image {{ $index + 1 }}"
                             onerror="this.onerror=null; this.src='{{ asset('images/placeholder-service.jpg') }}';">
                    </a>
                    @if(count($images) > 4 && $index === 3)
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <span class="text-white text-xl font-semibold">+{{ count($images) - 4 }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="col-span-2 h-40 bg-gray-200 flex items-center justify-center rounded-xl">
                <span class="text-gray-500">Tidak ada gambar galeri tersedia</span>
            </div>
        @endif
    </div> --}}
</header>

<!-- Service Details Section -->
<div class="mx-12 lg:mx-36">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center justify-between mb-8">
        <div class="flex flex-col gap-1.5">
            <h1 class="text-4xl text-primary-700">{{$service->name}}</h1>
            <p class="text-xl text-primary-500">Mulai dari IDR {{number_format($service->price,0,',','.')}}</p>
            <div class="text-sm flex items-center gap-1 text-primary-500 mt-2">
                <span class="material-symbols-rounded scale-75">category</span>
                {{$service->serviceCategory->name}}
            </div>
        </div>

        <a href="#" class="px-7 py-3 rounded-full bg-primary-700 hover:bg-primary-800 text-white w-fit transition-colors duration-300">
            Pesan sekarang
        </a>
    </div>

    <div class="flex flex-col gap-2 mb-5">
        <h2 class="text-2xl text-primary-700 font-medium mb-3">Deskripsi Layanan</h2>
        <div class="text-gray-600 font-light prose max-w-none">
            {!! $service->description !!}
        </div>
    </div>
</div>

<!-- Cover Image Modal -->
<div id="cover-modal" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
    <a href="#" class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative max-w-4xl mx-auto" onclick="event.stopPropagation()">
            <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <span class="material-symbols-rounded text-3xl">close</span>
            </button>
            @if($service->cover)
                <img class="w-full max-h-[90vh] object-contain rounded-lg"
                     src="{{ asset('storage/' . $service->cover) }}" 
                     alt="{{ $service->name }}">
            @endif
        </div>
    </a>
</div>

<!-- Gallery Image Modals -->
@if (is_array($images) && !empty($images))
    @foreach ($images as $index => $image)
        <div id="image-modal-{{ $index }}" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
            <a href="#" class="fixed inset-0 flex items-center justify-center p-4">
                <div class="relative max-w-4xl mx-auto" onclick="event.stopPropagation()">
                    <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                        <span class="material-symbols-rounded text-3xl">close</span>
                    </button>
                    <img class="w-full max-h-[90vh] object-contain rounded-lg"
                         src="{{ asset('storage/' . $image) }}" 
                         alt="{{ $service->name }} - Image {{ $index + 1 }}">
                </div>
            </a>
        </div>
    @endforeach
@endif

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

            // Close modal when clicking close button
            document.querySelectorAll('[id$="-modal"] button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location.hash = '#';
                });
            });
        });
    </script>
@endpush