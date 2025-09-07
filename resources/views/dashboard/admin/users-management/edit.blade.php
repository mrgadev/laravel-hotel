@extends('layouts.dahboard_layout')

@section('title', 'My Account')

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
                    <p>Edit Akun</p>
                </div>
                <h1 class="text-white text-4xl font-medium">
                    Edit Akun
                </h1>
            </div>
        </div>
        
        <section class="container px-6 mx-auto">
            <div class="grid gap-8">
                <!-- Profile Update Form -->
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Akun</h2>
                    </div>
                    
                    <form id="profile-form" action="{{route('dashboard.users-management.update', $user->id)}}" method="POST" enctype="multipart/form-data" class="p-6">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nama Lengkap</label>
                                <input placeholder="Name" type="text" name="name" id="name" autocomplete="name" 
                                    class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                                    value="{{$user->name}}">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="role" class="block mb-3 font-medium text-gray-700 text-md">Role</label>
                                <select name="role" id="role" 
                                    class="block w-full py-3 px-5 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    @foreach($user->roles as $role)
                                        <option value="{{ $role->name }}" class="capitalize">Tidak Diubah ({{ $role->name }})</option>
                                    @endforeach
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                    <option value="user">User</option>
                                </select>
                            </div>

                            <div>
                                <label for="email" class="block mb-3 font-medium text-gray-700 text-md">Alamat Email</label>
                                <input placeholder="Email" type="email" name="email" id="email" autocomplete="email" 
                                    class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                                    value="{{$user->email}}">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block mb-3 font-medium text-gray-700 text-md">Nomor Telepon</label>
                                <input placeholder="Phone Number" type="number" name="phone" id="phone" autocomplete="off" 
                                    class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                                    value="{{$user->phone}}">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nomor_rekening" class="block mb-3 font-medium text-gray-700 text-md">Nomor Rekening</label>
                                <input placeholder="Nomor Rekening" type="number" name="nomor_rekening" id="nomor_rekening" autocomplete="off" 
                                    class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                                    value="{{$user->nomor_rekening}}">
                                @error('nomor_rekening')
                                    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bank_id" class="block mb-3 font-medium text-gray-700 text-md">Nama Bank</label>
                                <select name="bank_id" id="bank_id" 
                                    class="block w-full py-3 px-5 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    @if ($user->bank_id)
                                        <option value="{{ $user->bank->id }}" class="capitalize">Tidak Diubah ({{ $user->bank->name }})</option>
                                    @else
                                        <option value="">-- Pilih Bank --</option>
                                    @endif
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="access" class="block mb-3 font-medium text-gray-700 text-md">Access</label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="access" value="Yes" class="form-radio h-4 w-4 text-primary-500 focus:ring-primary-500 focus:ring-2" 
                                            {{ $user->access === 'yes' ? 'checked' : '' }}>
                                        <span class="ml-2">Yes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="access" value="No" class="form-radio h-4 w-4 text-primary-500 focus:ring-primary-500 focus:ring-2" 
                                            {{ $user->access === 'no' ? 'checked' : '' }}>
                                        <span class="ml-2">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 space-x-4">
                            <button type="button" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-600 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300"
                                onclick="return confirm('Are you sure you want to cancel?')">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                onclick="return confirm('Are you sure you want to save these changes?')">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Update Form -->
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-6 border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Ubah Password</h2>
                    </div>

                    <form id="password-form" action="{{route('dashboard.users-management.updatePassword', $user->id)}}" method="POST" class="p-6">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="current_password" class="block mb-3 font-medium text-gray-700 text-md">Password Saat Ini</label>
                                <input placeholder="Your Current Password" type="password" name="current_password" id="current_password" 
                                    class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="new_password" class="block mb-3 font-medium text-gray-700 text-md">Password Baru</label>
                                <input placeholder="Your New Password" type="password" name="new_password" id="new_password" 
                                    class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                @error('new_password')
                                    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 space-x-4">
                            <button type="button" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-600 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300"
                                onclick="return confirm('Are you sure you want to cancel?')">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                onclick="return confirm('Are you sure you want to change your password?')">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection