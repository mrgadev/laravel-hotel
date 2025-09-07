@extends('layouts.dahboard_layout')

@section('title', 'Kategori Layanan Lainnya')
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
                    <a href="{{route('dashboard.service-category.index')}}" class="flex items-center hover:underline">
                        <p>Riwayat Saldo</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Penarikan Saldo</p>
                </div>
        
                <h1 class="text-white text-4xl font-medium">
                    Penarikan Saldo
                </h1>
            </div>
            
        </div>
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-2 mt-2 bg-white rounded-xl shadow-lg">
                    <form action="{{route('dashboard.penarikan-saldo.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="mt-10">
                                    <div class="grid grid-cols-1 gap-4 items-center">
                                        <div>
                                            <label for="amount" class="block mb-3 font-medium text-gray-700 text-md">Nominal Penarikan</label>
                                            <small>Saldo Anda : Rp.{{number_format($saldo->amount,0,',','.')}}</small>
                                            <input placeholder="Nominal Penarikan" type="number" name="amount" id="amount" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">

                                            @if ($errors->has('amount'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('amount')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 mt-5">
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
                                    </div>
                                    <div class="grid grid-cols-1 mt-5 items-center">
                                        <div>
                                            <label for="notes" class="block mb-3 font-medium text-gray-700 text-md">Keterangan/Catatan</label>
                                            <textarea name="notes" id="notes" rows="10" cols="80"></textarea>

                                            @error('notes')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 text-right sm:px-6">
                                <a href="" type="button" class="inline-flex justify-center px-4 py-2 mr-4 text-sm font-medium text-gray-700 bg-white border border-gray-600 rounded-lg shadow-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300" onclick="return confirm('are you want to cancel?')">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('are you want to submit this data?')">
                                    Kirim Request
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
            .create(document.querySelector('#notes'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush