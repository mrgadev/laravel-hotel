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
                        <p>Promo</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Buat Promo</p>
                </div>
        
                <h1 class="text-white text-4xl font-medium">
                    Buat Promo
                </h1>
            </div>
            
        </div>
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-2 mt-2 bg-white rounded-xl shadow-lg">
                    <form action="{{route('dashboard.promo.store')}}" method="POST" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="">
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 items-center">
                                        <div>
                                            <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nama Promo</label>
                                            <input placeholder="Nama Promo" type="text" name="name" id="name" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('name'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('name')}}</p>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <label for="cover" class="block mb-3 font-medium text-gray-700 text-md">Cover Promo</label>
                                            <input placeholder="" type="file" name="cover" id="cover" autocomplete="off" class="block border  w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('cover'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('cover')}}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label for="code" class="block mb-3 font-medium text-gray-700 text-md">Kode</label>
                                            <input placeholder="Promo" type="text" name="code" id="code" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('code'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('code')}}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label for="amount" class="block mb-3 font-medium text-gray-700 text-md">Potongan Harga</label>
                                            <input placeholder="Potongan Harga" type="number" name="amount" id="amount" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('amount'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('amount')}}</p>
                                            @endif
                                        </div>

                                        <div class="mt-5">
                                            <label for="start_date" class="block mb-3 font-medium text-gray-700 text-md">Tanggal Mulai</label>
                                            <input placeholder="Tanggak Mulai" type="date" name="start_date" id="start_date" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('start_date'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('start_date')}}</p>
                                            @endif
                                        </div>

                                        <div class="mt-5">
                                            <label for="end_date" class="block mb-3 font-medium text-gray-700 text-md">Tanggal Berakhir</label>
                                            <input placeholder="Tanggak Berakhir" type="date" name="end_date" id="end_date" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('end_date'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('end_date')}}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-8">
                                        <label class="block mb-2 font-medium text-gray-700">Kategori Promo</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input type="radio" name="is_all" value="1" 
                                                    class="form-radio h-4 w-4 text-primary-500 focus:ring-primary-500 focus:ring-2">
                                                <span class="ml-2">Semua</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="is_all" value="0" 
                                                    class="form-radio h-4 w-4 text-primary-500 focus:ring-primary-500 focus:ring-2">
                                                <span class="ml-2">Per Kamar</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="room_selection" class="grid grid-cols-1 gap-5 hidden mt-10">
                                        <h2 class="text-md font-medium">Pilih Kamar</h2>
                                        <div class="flex flex-wrap gap-5">
                                            @foreach ($rooms as $room)
                                            <div class="flex items-center gap-2 rounded-full bg-primary-100 text-primary-700 w-fit px-5 py-2 has-[:checked]:border-primary-700  has-[:checked]:border-2 transition-all hover:cursor-pointer">
                                                <input type="checkbox" name="room_id[]" id="room_id{{$loop->iteration}}" value="{{$room->id}}" class="hidden" onclick="console.log('{{$room->name}}')">
                                                {{-- <img src="{{url($room->cover)}}" class="w-5 h-5 object-cover object-center" alt=""> --}}
                                                <label for="room_id{{$loop->iteration}}" class="hover:cursor-pointer">{{$room->name}}</label>
                                            </div>
                                        
                                            @endforeach
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

        // Get necessary DOM elements
        const roomSelectionSection = document.getElementById('room_selection');
        const promoTypeRadios = document.getElementsByName('is_all');
        const roomCheckboxes = document.querySelectorAll('input[name="room_id[]"]');

        // Add event listeners to radio buttons
        promoTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === '0') { // Jika value 0 (Per Kamar)
                    // Show room selection section
                    roomSelectionSection.classList.remove('hidden');
                    
                    // Reset all checkboxes when showing
                    roomCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                } else { // Jika value 1 (Semua)
                    // Hide room selection section
                    roomSelectionSection.classList.add('hidden');
                    
                    // Uncheck all room checkboxes when hiding
                    roomCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            });
        });

        // Optional: Add click handler for room selection
        roomCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                console.log(`Room ${this.value} ${this.checked ? 'selected' : 'unselected'}`);
            });
        });
    </script>
@endpush