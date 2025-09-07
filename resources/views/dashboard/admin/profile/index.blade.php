@extends('layouts.dahboard_layout')

@section('title', 'Home')

@section('content')
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

    <section class="container px-6 mx-auto mt-5">
        <div class="grid gap-5 md:grid-cols-12">
            <main class="col-span-12 p-4 md:pt-0">
                <div class="px-2 py-2 mt-2 bg-white rounded-xl">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6">
                                        <div class="flex items-center mt-1">
                                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="photo profile" class="rounded-full w-16 h-16">

                                            <label for="choose" class="px-3 py-2 ml-5 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 cursor-pointer">Choose File</label>

                                            <input type="file" accept="image/*" id="choose" name="photo" hidden>

                                            <a href="" type="button" class="px-3 py-2 ml-5 text-sm font-medium leading-4 text-red-700 bg-transparent rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('are you sure?')">
                                                Delete
                                            </a>
                                        </div>

                                        @if ($errors->has('photo'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('photo')}}</p>
                                        @endif

                                    </div>
                                    <div class="md:col-span-6 lg:col-span-3">
                                        <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Full Name</label>
                                        <input placeholder="Your Name" type="text" name="name" id="name" autocomplete="name" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$user->name}}">

                                        @if ($errors->has('name'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('name')}}</p>
                                        @endif

                                    </div>
                                    <div class="md:col-span-6 lg:col-span-3">
                                        <label for="email" class="block mb-3 font-medium text-gray-700 text-md">Email Address</label>
                                        <input placeholder="Your Email" type="email" name="email" id="email" autocomplete="email" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$user->email}}">

                                        @if ($errors->has('email'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('email')}}</p>
                                        @endif

                                    </div>
                                    <div class="md:col-span-6 lg:col-span-3">
                                        <label for="phone" class="block mb-3 font-medium text-gray-700 text-md">Contact Number</label>
                                        <input placeholder="Your Number" type="number" name="phone" id="phone" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$user->phone}}">

                                        @if ($errors->has('phone'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('phone')}}</p>
                                        @endif

                                    </div>
                                    <div class="md:col-span-6 lg:col-span-3">
                                        <label for="birth" class="block mb-3 font-medium text-gray-700 text-md">Birthday</label>
                                        <input placeholder="Your Number" type="date" name="birth" id="birth" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$user->birth}}">

                                        @if ($errors->has('birth'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('birth')}}</p>
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

                <div class="px-2 py-2 mt-10 bg-white rounded-xl">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="col-span-12">
                                    <h2 class="mt-8 mb-1 text-2xl font-bold text-gray-700">
                                        Change Password
                                    </h2>
                                </div>
                                <div class="mt-10">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="md:col-span-6 lg:col-span-3">
                                            <label for="current_password" class="block mb-3 font-medium text-gray-700 text-md">Current Password</label>
                                            <input placeholder="Your Current Password" type="password" name="current_password" id="password" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('password'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('current_password')}}</p>
                                            @endif
                                        </div>
                                        <div class="md:col-span-6 lg:col-span-3">
                                            <label for="new_password" class="block mb-3 font-medium text-gray-700 text-md">New Password</label>
                                            <input placeholder="Your New Password" type="password" name="new_password" id="password" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('new_password'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('password')}}</p>
                                            @endif
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
        </div>
    </section>
    {{-- <div class="w-full px-6 py-6 mx-auto shadow-xl rounded-xl mt-5">
        <!-- row 1 -->
        <div class="flex flex-wrap -mx-3">
        <!-- card1 -->
        <form class="w-full px-3 mb-6">
            <h2 class="col-span-2 mt-5 font-medium text-gray-700 text-lg">
                Informasi Pribadi
            </h2>

            <div class="grid lg:grid-cols-2 gap-5 my-5">
                <div class="flex flex-col gap-2">
                    <label for="name">Foto Profil</label>
                    <input type="file" name="name" >
                </div>
                <div class="flex flex-col gap-2">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" name="name" value="{{Auth::user()->name}}" class="rounded-lg">
                </div>
            </div>

            <div class="grid lg:grid-cols-2 my-5 gap-5">
                <div class="flex flex-col gap-2">
                    <label for="email">Alamat Email</label>
                    <input type="email" name="email" value="{{Auth::user()->email}}" class="rounded-lg">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="phone">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{Auth::user()->phone}}" class="rounded-lg">
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-5 my-5">
                <div class="flex flex-col gap-2">
                    <label for="birth">Tanggal Lahir</label>
                    <input type="date" name="birth" class="rounded-lg">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="name">Tempat Lahir</label>
                    <select name="regency_id" id="regency_id">
                        @foreach ($regencies as $regency)
                            <option value="{{$regency->id}}">{{$regency->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h2 class="col-span-2 mt-5 font-medium text-gray-700 text-lg">
                Ubah Password
            </h2>
            
            <div class="grid lg:grid-cols-2 gap-5 my-5">
                <div class="flex flex-col gap-2">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="rounded-lg">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="password">Ulangi Password</label>
                    <input type="password" name="password_confirmation" class="rounded-lg">
                </div>
            </div>
        </form>

        


    </div> --}}
@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
        $('#regency_id').select2();
    })
</script>
@endpush