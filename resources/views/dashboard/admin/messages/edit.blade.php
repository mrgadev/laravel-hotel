@extends('layouts.dahboard_layout')

@section('title', 'My Account')
{{-- 
@section('breadcrumb')
    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
            <li class="text-sm leading-normal">
            <a class="text-white opacity-90" href="javascript:;">Dashboard</a>
            </li>
            <li class="text-sm pl-2 text-white capitalize leading-normal  before:float-left before:pr-2 before: before:content-['/']" aria-current="page">Profile</li>
        </ol>
    <h6 class="mb-0 font-bold text-white capitalize">My Account</h6>
@endsection --}}

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex flex-col gap-5 w-full p-6">
                <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                    <a href="{{route('dashboard.home')}}" class="flex items-center">
                        <span class="material-symbols-rounded scale-75">home</span>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <a href="{{route('dashboard.hotel-facilities.index')}}" class="flex items-center hover:underline">
                        <p>Fasilitas Hotel</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Buat Fasilitas Hotel</p>
                </div>
        
                <h1 class="text-white text-4xl font-medium">
                    Buat Fasilitas Hotel
                </h1>
            </div>
            
        </div>
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-2 mt-2 bg-white rounded-xl shadow-lg">
                    <form action="{{route('dashboard.hotel-facilities.update', $hotel_facility)}}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="mt-10">
                                    <div class="grid grid-cols-1 gap-10 sm:grid-cols-2">
                                        <div>
                                            <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nama Fasilitas Hotel</label>
                                            <input type="text" name="name" id="name" value="{{$hotel_facility->name}}" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('name'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('name')}}</p>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <label for="icon" class="block mb-3 font-medium text-gray-700 text-md">Icon Fasilitas Hotel</label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2">
                                                <div class="flex items-center mt-1">
                                                    <a href="#image-modal" class="">
                                                        <img src="{{Storage::url($hotel_facility->icon)}}" alt="icon" class=" w-16 h-16 object-cover object-center rounded-full">
                                                    </a>

                                                    <label for="choose" class="px-3 py-2 ml-5 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 cursor-pointer">Pilih Berkas</label>

                                                    <input type="file" id="choose" name="icon" hidden>
                                                </div>
                                            </div>

                                            @if ($errors->has('icon'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('icon')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 mt-5 items-center">
                                        <div>
                                            <label for="description" class="block mb-3 font-medium text-gray-700 text-md">Deskripsi Fasilitas Hotel</label>
                                            <textarea name="description" id="description" rows="10" cols="80">{!! $hotel_facility->description !!}</textarea>

                                            @error('description')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 text-right sm:px-6">
                                <a href="" type="button" class="inline-flex justify-center px-4 py-2 mr-4 text-sm font-medium text-gray-700 bg-white border border-gray-600 rounded-lg shadow-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300" onclick="return confirm('are you want to cancel?')">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('are you want to submit this data?')">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </section>
    </main>
<div id="image-modal" class="fixed inset-0 z-100 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
    <!-- Link pembungkus untuk close saat klik anywhere -->
    <a href="#" class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative max-w-3xl mx-auto" onclick="event.stopPropagation()">
            <div>
                <img
                    class="w-full max-h-[80vh] object-contain"
                    src="{{Storage::url($hotel_facility->icon)}}"
                />
            </div>
        </div>
    </a>
</div>
@endsection
@push('addon-script')
    <!-- Panggil CKEditor versi 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush