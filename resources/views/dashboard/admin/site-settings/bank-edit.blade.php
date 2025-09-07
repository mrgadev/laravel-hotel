@extends('layouts.dahboard_layout')

@section('title', 'Pengaturan Situs')

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex flex-col gap-5 w-full p-6">
                <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                    <a href="{{route('dashboard.home')}}" class="flex items-center">
                        <span class="material-symbols-rounded scale-75">home</span>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <a href="{{route('dashboard.site.settings.edit', $site_setting->id)}}" class="flex items-center">
                        Pengaturan Situs
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Ubah Data Bank</p>
                </div>
                <h1 class="text-white text-4xl font-medium">
                    Ubah Data Bank
                </h1>
            </div>
        </div>
        <section class="container px-6 mx-auto">
            <div class="container px-6 mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        {{-- <h1 class="text-xl font-medium text-primary-700">Pengaturan Dasar</h1> --}}
                        <form action="{{route('dashboard.bank.update', $bank->id)}}" method="POST">
                            @csrf
                        @method('PUT')

                            <div class="flex flex-col gap-5">
                                <label for="">Nama Bank</label>
                                <input type="text" name="name" value="{{$bank->name}}" class="rounded-lg">

                                @if ($errors->has('name'))
                                <p  class="text-red-500 mt-3 text-sm">{{$errors->first('name')}}</p>
                                @endif

                            {{-- <button type="submit" class="btn btn-primary">Kirim</button> --}}
                            <button type="submit" class="mt-5 px-5 py-3 rounded-lg bg-primary-500 text-white transition-all hover:bg-primary-700">Perbarui data</button>
                        </form>
                    </div>
                </main>
            </div>
        </section>
    </main>
@endsection
