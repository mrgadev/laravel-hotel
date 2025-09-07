@extends('layouts.dahboard_layout')

@section('title', 'My Profile')

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex flex-col gap-5 w-full p-6">
                <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                    <a href="{{route('admin.dashboard')}}" class="flex items-center">
                        <span class="material-symbols-rounded scale-75">home</span>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>My Profile</p>
                </div>
        
                <h1 class="text-white text-4xl font-medium">
                    My Profile
                </h1>
            </div>
            
        </div>
        <section class="container px-6 mx-auto mt-5">
            <div class="grid gap-5 md:grid-cols-12">
                <form class="col-span-12 p-4 md:pt-0" action="{{route('dashboard.profile.update', Auth::user()->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="px-2 py-2 mt-2 bg-white rounded-xl shadow-2xl">
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="col-span-12">
                                    <h2 class="mt-8 mb-1 text-2xl font-semibold text-gray-700">
                                        Informasi Pribadi
                                    </h2>
                                </div>
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6">
                                        <div class="flex items-center mt-1">
                                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="photo profile" class="rounded-full w-16 h-16">

                                            <label for="choose" class="px-3 py-2 ml-5 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 cursor-pointer">Pilih File</label>

                                            <input type="file" accept="image/*" id="choose" name="photo" hidden>

                                            <a href="" type="button" class="px-3 py-2 ml-5 text-sm font-medium leading-4 text-red-700 bg-transparent rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('are you sure?')">
                                                Hapus
                                            </a>
                                        </div>

                                        @if ($errors->has('photo'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('photo')}}</p>
                                        @endif

                                    </div>
                                    <div class="md:col-span-6 lg:col-span-3">
                                        <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nama Lengkap</label>
                                        <input placeholder="Your Name" type="text" name="name" id="name" autocomplete="name" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{Auth::user()->name}}">

                                        @if ($errors->has('name'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('name')}}</p>
                                        @endif

                                    </div>
                                    <div class="md:col-span-6 lg:col-span-3">
                                        <label for="email" class="block mb-3 font-medium text-gray-700 text-md">Alamat Email</label>
                                        <input placeholder="Your Email" type="email" name="email" id="email" autocomplete="email" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{Auth::user()->email}}">

                                        @if ($errors->has('email'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('email')}}</p>
                                        @endif

                                    </div>
                                    <div class="md:col-span-6 lg:col-span-3">
                                        <label for="phone" class="block mb-3 font-medium text-gray-700 text-md">Nomor Telepon</label>
                                        <input placeholder="Your Number" type="number" name="phone" id="phone" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{Auth::user()->phone}}">

                                        @if ($errors->has('phone'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('phone')}}</p>
                                        @endif

                                    </div>
                                    <div class="md:col-span-6 lg:col-span-3">
                                        <label for="birth" class="block mb-3 font-medium text-gray-700 text-md">Tanggal Lahir</label>
                                        <input placeholder="Your Number" type="date" name="birth" id="birth" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{Auth::user()->birth}}">

                                        @if ($errors->has('birth'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('birth')}}</p>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-2 py-2 mt-10 bg-white rounded-xl shadow-2xl">
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="col-span-12">
                                    <h2 class="mt-8 mb-1 text-2xl font-bold text-gray-700">
                                        Ubah Password
                                    </h2>
                                </div>
                                <div class="mt-10">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="md:col-span-6 lg:col-span-3">
                                            <label for="current_password" class="block mb-3 font-medium text-gray-700 text-md">Password Baru</label>
                                            <input placeholder="Your Current Password" type="password" name="current_password" id="password" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('password'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('current_password')}}</p>
                                            @endif
                                        </div>
                                        <div class="md:col-span-6 lg:col-span-3">
                                            <label for="new_password" class="block mb-3 font-medium text-gray-700 text-md">Ulangi Password</label>
                                            <input placeholder="Ulangi Password" type="password" name="new_password" id="password" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('new_password'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('password')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-5 text-right">
                        <a href="" type="button" class="inline-flex justify-center px-4 py-2 mr-4 text-sm font-medium text-gray-700 bg-white border border-gray-600 rounded-lg shadow-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300" onclick="return confirm('are you want to cancel?')">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('are you want to submit this data?')">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
@push('addon-script')
    <script>
        $(document).ready(function() {
            $('#regency_id').select2();
        })
    </script>
@endpush