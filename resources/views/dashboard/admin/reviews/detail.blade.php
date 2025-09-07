@extends('layouts.dahboard_layout')

@section('title', 'Detail Ulasan')

{{-- @section('breadcrumb')
    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
            <li class="text-sm leading-normal">
            <a class="text-white opacity-90" href="javascript:;">Dashboard</a>
            </li>
            <li class="text-sm pl-2 text-white capitalize leading-normal  before:float-left before:pr-2 before: before:content-['/']" aria-current="page">Kamar Facilities</li>
        </ol>
    <h6 class="mb-0 font-bold text-white capitalize">Data Fasilitas Kamar</h6>
@endsection --}}

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex w-full gap-5 mx-auto justify-between items-center">
                <div class="flex flex-col gap-5 p-6">
                    <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                        <a href="{{route('dashboard.home')}}" class="flex items-center">
                            <span class="material-symbols-rounded scale-75">home</span>
                        </a>
                        <span class="material-symbols-rounded">chevron_right</span>
                        <a href="{{route('dashboard.room-review.index')}}" class="flex items-center underline">
                            Daftar Ulasan
                        </a>
                        <span class="material-symbols-rounded">chevron_right</span>
                        <p>Detail</p>
                    </div>
                    <h1 class="text-white text-4xl font-medium">
                        Detail Ulasan
                    </h1>
                </div>
                {{-- <a href="{{route('dashboard.user.bookings.export', $transaction->invoice)}}" class="px-5 py-3 rounded-lg bg-red-100 text-red-700 border-2 border-red-700"><i class="bi bi-file-earmark-pdf"></i> Simpan ke PDF</a> --}}
            </div>
        </div>       
        <section class="container mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                    <div class="flex items-center gap-5">
                        <img src="{{Storage::url($room_review->user->avatar)}}" alt="" class="w-16 h-16 rounded-full">
                        <div class="flex flex-col">
                            <p class="text-gray-700">{{$room_review->user->name}}</p>
                            <p class="text-primary-700 text-sm">{{$room_review->rating}} ({{$room_review->rating_text}})</p>
                        </div>
                    </div>
                    <div class="mt-10 flex flex-col gap-5">
                        <h2 class="text-xl font-medium text-primary-700">{{$room_review->title}}</h2>
                        <div class="flex flex-col gap-2">
                            {!!$room_review->description!!}
                            <div class="flex items-center gap-3">
                                <p class="px-3 py-1 rounded-lg bg-primary-100 text-primary-700 border border-primary-700"><i class="bi bi-clock"></i> {{Carbon\Carbon::parse($room_review->created_at)->isoFormat('dddd, D MMM YYYY')}}</p>
                                <p class="px-3 py-1 rounded-lg bg-primary-100 text-primary-700 border border-primary-700"><i class="bi bi-buildings"></i> {{$room_review->room->name}}</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </main>
        </section>    
    </main>
@endsection
