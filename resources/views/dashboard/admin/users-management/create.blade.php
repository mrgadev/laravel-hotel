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
                    <a href="{{route('dashboard.users-management.index')}}" class="flex items-center hover:underline">
                        <p>Management Users</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Buat Akun</p>
                </div>
                <h1 class="text-white text-4xl font-medium">
                    Buat Akun
                </h1>
            </div>
        </div>
        <section class="container px-6 mx-auto">
            <section class="container px-6 mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        <form action="{{route('dashboard.users-management.store')}}" method="POST" enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            <div class="">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-1 gap-10 sm:grid-cols-2">
                                        <div class="">
                                            <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nama Lengkap</label>
                                            <input placeholder="Name" type="text" name="name" id="name" autocomplete="name" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="">

                                            @if ($errors->has('name'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('name')}}</p>
                                            @endif

                                        </div>
                                        <div class="">
                                            <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Role</label>
                                            <select name="role" id="role" class="block w-full py-3 px-5 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                <option value="admin">Admin</option>
                                                <option value="staff">Staff</option>
                                                <option value="user">User</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <label for="email" class="block mb-3 font-medium text-gray-700 text-md">Alamat Email</label>
                                            <input placeholder="Email" type="email" name="email" id="email" autocomplete="email" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="">

                                            @if ($errors->has('email'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('email')}}</p>
                                            @endif

                                        </div>
                                        <div class="">
                                            <label for="phone" class="block mb-3 font-medium text-gray-700 text-md">Nomor Telepon</label>
                                            <input placeholder="Phone Number" type="number" name="phone" id="phone" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="">

                                            @if ($errors->has('phone'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('phone')}}</p>
                                            @endif
                                        </div>
                                        <div class="">
                                            <label for="password" class="block mb-3 font-medium text-gray-700 text-md">Password</label>
                                            <input placeholder="Password" type="password" name="password" id="password" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="">

                                            @if ($errors->has('password'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('password')}}</p>
                                            @endif
                                        </div>
                                        <div class="">
                                            <label for="password_confirmation" class="block mb-3 font-medium text-gray-700 text-md">Konfirmasi Password</label>
                                            <input placeholder="Password Confirmation" type="password" name="password_confirmation" id="password_confirmation" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="">

                                            @if ($errors->has('password_confirmation'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('password_confirmation')}}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <label for="access" class="block mb-3 font-medium text-gray-700 text-md">Access</label>
                                            <div class="flex items-center space-x-4">
                                                <label class="flex items-center">
                                                    <input type="radio" name="access" value="Yes" class="form-radio h-4 w-4 text-primary-500 focus:ring-primary-500 focus:ring-2">
                                                    <span class="ml-2">Yes</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="access" value="No" class="form-radio h-4 w-4 text-primary-500 focus:ring-primary-500 focus:ring-2">
                                                    <span class="ml-2">No</span>
                                                </label>
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
    </main>
@endsection