@extends('layouts.dahboard_layout')

@section('title', 'Umpan Balik Pengguna')
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
                    <a href="{{route('dashboard.feedback')}}" class="flex items-center hover:underline">
                        <p>Umpan Balik Pengguna</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Detail</p>
                </div>
        
                <h1 class="text-white text-4xl font-medium">
                    {{ucfirst($message->title)}}
                </h1>
            </div>
            
        </div>
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-5 mt-2 bg-white rounded-xl shadow-lg">
                    <div class="grid lg:grid-cols-2 gap-5 lg:w-1/2">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-person-circle text-lg"></i>
                            {{$message->name}}
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-envelope-at-fill"></i>
                            {{$message->email}}
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-telephone"></i>
                            {{$message->phone}}
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar-event"></i>
                            {{$message->created_at->isoFormat('dddd, D MMMM YYYY')}}
                        </div>
                    </div>

                    <div class="mt-5">
                        <h2 class="mb-3 font-medium text-xl text-primary-600">Isi Pesan</h2>
                        <p>{{$message->message}}</p>
                    </div> 
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