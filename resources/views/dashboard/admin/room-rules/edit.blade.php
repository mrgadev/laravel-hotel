@extends('layouts.dahboard_layout')

@section('title', 'Ubah Peraturan Kamar')
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
                    <a href="{{route('dashboard.room-rules.index')}}" class="flex items-center hover:underline">
                        <p>Peraturan Kamar</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Ubah Data</p>
                </div>

                <h1 class="text-white text-4xl font-medium">
                    Ubah Data
                </h1>
            </div>

        </div>
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-5 mt-2 bg-white rounded-xl shadow-lg">
                    <form action="{{route('dashboard.room-rules.update', $room_rule->id)}}" method="POST" class="flex flex-col gap-5">
                        @csrf
                        @method('PUT')
                        {{-- <h3 class="text-lg font-medium text-primary-700">Ubah data baru</h3> --}}
                        <div class="grid lg:grid-cols-3 gap-5">
                            <div class="flex flex-col gap-3">
                                <label for="room">Pilih Icon</label>
                                <x-icon-picker></x-icon-picker>
                            </div>
                            <div class="flex flex-col gap-3">
                                <label for="room">Pilih Kamar</label>
                                <select name="room_id" id="room" class="p-2 rounded-lg border border-primary-700 bg-primary-100 text-primary-700">
                                    <option value="{{$room_rule->room_id ?? 'Semua Kamar'}}">{{$room_rule->room->name ?? 'Semua Kamar'}}</option>
                                    <option value="Semua Kamar">Semua Kamar</option>
                                    @foreach ($rooms as $room)
                                    <option value="{{$room->id}}">{{$room->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-3">
                                <label for="rule">Peraturan</label>
                                <input name="rule" type="text" id="rule" value="{{$room_rule->rule}}" class="p-2 rounded-lg border border-primary-700 bg-primary-100 text-primary-700">
                            </div>
                        </div>
                        <button type="submit" class="w-fit px-5 py-2 rounded-lg bg-primary-700 text-white">Kirim</button>
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
    </script>
@endpush
