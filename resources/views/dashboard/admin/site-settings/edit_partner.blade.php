@extends('layouts.dahboard_layout')

@section('title', 'Ubah Data Partner')
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
                    <a href="{{route('dashboard.site.settings.edit')}}" class="flex items-center hover:underline">
                        <p>Pengaturan Situs</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Ubah Data</p>
                </div>

                <h1 class="text-white text-4xl font-medium">
                   Ubah Data Partner
                </h1>
            </div>

        </div>
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-2 mt-2 bg-white rounded-xl shadow-lg">
                    <form action="{{route('dashboard.partners.update', $partner)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="mt-">
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 items-center my-5">
                                        <div>
                                            <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nama Partner</label>
                                            <input placeholder="Nama Kamar" type="text" name="name" id="name" value="{{$partner->name}}" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('name'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('name')}}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label for="link" class="block mb-3 font-medium text-gray-700 text-md">Tautan</label>
                                            <input placeholder="Link Partner" type="text" name="link" value="{{$partner->link}}" id="link" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('link'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('link')}}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-10">
                                            <label for="logo" class="mt-5 block mb-3 font-medium text-gray-700 text-md">Logo</label>
                                            <div>
                                                <div class="grid grid-cols-3 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                                    <div class="relative flex h-40 cursor-pointer">
                                                        <a href="#image-modal-{{$partner->logo}}" class="block h-full w-full">
                                                            <img
                                                                class="h-full w-full object-cover object-center rounded-xl"
                                                                src="{{url($partner->logo)}}"
                                                            />
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <label for="logo" class="px-3 py-2 text-sm font-medium leading-4 text-primary-700 bg-primary-100 border border-primary-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 cursor-pointer">
                                                        Pilih Berkas
                                                    </label>
                                                    <input type="file" id="logo" name="logo" hidden>
                                                </div>
                                            </div>

                                            @if ($errors->has('logo'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('logo')}}</p>
                                            @endif
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

<div id="image-modal-{{$partner->logo}}" class="fixed inset-0 z-100 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
    <!-- Link pembungkus untuk close saat klik anywhere -->
    <a href="#" class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative max-w-3xl mx-auto" onclick="event.stopPropagation()">
            <div>
                <img
                    class="w-full max-h-[80vh] object-contain"
                    src="{{Storage::url($partner->logo)}}"
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
            .create(document.querySelector('#faqAnswer'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
