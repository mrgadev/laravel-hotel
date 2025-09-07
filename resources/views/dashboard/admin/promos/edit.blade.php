@extends('layouts.dahboard_layout')

@section('title', 'Ubah Promo')
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
                    <a href="{{route('dashboard.promo.index')}}" class="flex items-center hover:underline">
                        <p>Promo</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Ubah Promo</p>
                </div>

                <h1 class="text-white text-4xl font-medium">
                    Ubah Promo
                </h1>
            </div>

        </div>
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-2 mt-2 bg-white rounded-xl shadow-lg">
                    <form action="{{route('dashboard.promo.update', $promo)}}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="">
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 items-center">
                                        <div>
                                            <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nama Promo</label>
                                            <input placeholder="Nama Promo" type="text" name="name" id="name" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$promo->name}}">

                                            @if ($errors->has('name'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('name')}}</p>
                                            @endif
                                        </div>

                                        <div class="">
                                            <label for="cover" class="mt-5 block mb-3 font-medium text-gray-700 text-md">Cover Promo</label>
                                            <div class="relative flex h-20 w-[20] cursor-pointer">
                                                <a href="#image-modal" class="block h-20 w-[20]">
                                                    <img
                                                    class="h-full w-full object-cover object-center rounded-xl"
                                                    src="{{url($promo->cover)}}"
                                                    />
                                                </a>
                                                <div class="block">
                                                    <label for="choose" class="px-3 py-2 ml-5 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 cursor-pointer">Pilih Berkas</label>

                                                    <input type="file" id="choose" name="cover" hidden>
                                                </div>
                                            </div>

                                            @if ($errors->has('cover'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('cover')}}</p>
                                            @endif

                                        </div>

                                        <div>
                                            <label for="code" class="block mb-3 font-medium text-gray-700 text-md">Kode</label>
                                            <input placeholder="Promo" type="text" name="code" id="code" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$promo->code}}">

                                            @if ($errors->has('code'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('code')}}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label for="amount" class="block mb-3 font-medium text-gray-700 text-md">Potongan Harga</label>
                                            <input placeholder="Potongan Harga" type="number" name="amount" id="amount" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$promo->amount}}">

                                            @if ($errors->has('amount'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('amount')}}</p>
                                            @endif
                                        </div>

                                        <div class="mt-5">
                                            <label for="start_date" class="block mb-3 font-medium text-gray-700 text-md">Tanggal Mulai</label>
                                            <input placeholder="Tanggal Mulai" type="date" name="start_date" id="start_date" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$promo->start_date}}">

                                            @if ($errors->has('start_date'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('start_date')}}</p>
                                            @endif
                                        </div>

                                        <div class="mt-5">
                                                <label for="end_date" class="block mb-3 font-medium text-gray-700 text-md">Tanggal Berakhir</label>
                                                <input placeholder="Tanggal Berakhir" type="date" name="end_date" id="end_date" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$promo->end_date}}">

                                                @if ($errors->has('end_date'))
                                                    <p class="text-red-500 mb-3 text-sm">{{$errors->first('end_date')}}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-10 ms-2">
                                            <label for="is_all" class="block mb-3 font-medium text-gray-700 text-md">Kategori Promo</label>
                                            <div class="flex items-center space-x-4">
                                                <label class="flex items-center">
                                                    <input type="radio" name="is_all" value="Yes" class="form-radio h-4 w-4 text-primary-500 focus:ring-primary-500 focus:ring-2"
                                                        {{ $promo->is_all === 1 ? 'checked' : '' }}>
                                                    <span class="ml-2">Semua</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="is_all" value="No" class="form-radio h-4 w-4 text-primary-500 focus:ring-primary-500 focus:ring-2"
                                                        {{ $promo->is_all === 0 ? 'checked' : '' }}>
                                                    <span class="ml-2">Per Kamar</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-5 my-5 mt-10">
                                            <h2 class="text-md font-medium">Pilih Kamar</h2>
                                            <div class="flex flex-wrap gap-5">
                                                @foreach ($rooms as $room)
                                                <div class="flex items-center gap-2 rounded-full bg-primary-100 text-primary-700 w-fit px-5 py-2 has-[:checked]:border-primary-700  has-[:checked]:border-2 transition-all hover:cursor-pointer">
                                                    <input type="checkbox" name="room_id[]" id="room_id{{$loop->iteration}}" value="{{$room->id}}" class="hidden" {{ $promo->rooms->contains($room->id) ? 'checked' : '' }} onclick="console.log('{{$room->name}}')">
                                                    <img src="{{url($room->cover)}}" class="w-5 h-5 object-cover object-center" alt="">
                                                    <label for="room_id{{$loop->iteration}}" class="hover:cursor-pointer">{{$room->name}}</label>
                                                </div>
                                                @endforeach
                                            </div>
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
                    src="{{Storage::url($promo->cover)}}"
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

        // Get necessary DOM elements
        const roomSelectionSection = document.querySelector('.grid.grid-cols-1.gap-5');
        const promoTypeRadios = document.getElementsByName('is_all');
        const roomCheckboxes = document.querySelectorAll('input[name="room_id[]"]');

        // Function to toggle room selection visibility
        function toggleRoomSelection() {
            const selectedValue = document.querySelector('input[name="is_all"]:checked').value;
            if (selectedValue === 'No') { // Per Kamar
                roomSelectionSection.classList.remove('hidden');
            } else { // Semua
                roomSelectionSection.classList.add('hidden');
                // Uncheck all room checkboxes when hiding
                roomCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        }

        // Add event listeners to radio buttons
        promoTypeRadios.forEach(radio => {
            radio.addEventListener('change', toggleRoomSelection);
        });

        // Initial state setup based on existing selection
        window.addEventListener('DOMContentLoaded', () => {
            // Hide room selection if "Semua" is checked initially
            if (document.querySelector('input[name="is_all"][value="Yes"]').checked) {
                roomSelectionSection.classList.add('hidden');
            }
        });
    </script>
@endpush
