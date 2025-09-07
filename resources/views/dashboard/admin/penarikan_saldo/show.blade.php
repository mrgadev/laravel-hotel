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
                        <p>Permintaan Penarikan Uang</p>
                    </a>
                </div>

                <h1 class="text-white text-4xl font-medium">
                    Detail Permintaan Penarikan Uang
                </h1>
            </div>

        </div>
        @role('admin')
            <section class="container px-6 mx-auto">
                <form action="{{route('dashboard.penarikan-saldo.update', $penarikanSaldo)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <main class="col-span-12 md:pt-0">
                        <div class="p-2 mt-2 bg-white rounded-xl shadow-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="">
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="" class="block mb-3 font-medium text-gray-700 text-md">Nama User</label>
                                            <input placeholder="Nama Layanan Lainnya" type="text" name="" id="" autocomplete="off" class="block cursor-not-allowed w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{$penarikanSaldo->user->name}}">
                                        </div>

                                        <div>
                                            <label for="" class="block mb-3 font-medium text-gray-700 text-md">Tanggal</label>
                                            <input placeholder="Nama Layanan Lainnya" type="text" name="" id="" autocomplete="off" class="block cursor-not-allowed w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{Carbon\Carbon::parse($penarikanSaldo->created_at)->isoFormat('dddd, D MMM YYYY')}}">
                                        </div>

                                        <div>
                                            <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nominal</label>
                                            <input placeholder="Nama Layanan Lainnya" type="text" name="" id="" autocomplete="off" disabled class="block cursor-not-allowed w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="Rp. {{number_format($penarikanSaldo->amount,0,',','.')}}">
                                        </div>

                                        <div>
                                            <label for="bank_number" class="block mb-3 font-medium text-gray-700 text-md">Nomor Rekening</label>
                                            <input disabled placeholder="Nomor Rekening belum diTambahkan" type="number" name="bank_number" id="bank_number" autocomplete="off"
                                                class="cursor-not-allowed block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                value="{{$penarikanSaldo->user->bank_number}}">
                                            @error('bank_number')
                                                <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                                            @enderror
                                        </div>

                                        @if ($user->bank_id)
                                            <div>
                                                <label for="bank_number" class="block mb-3 font-medium text-gray-700 text-md">Nama Bank</label>
                                                <input disabled placeholder="{{ $bankName->name }}" type="number" name="bank_number" id="bank_number" autocomplete="off"
                                                    class="cursor-not-allowed block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                    value="{{ $bankName->name }}">
                                            </div>
                                        @else
                                            <div>
                                                <label for="bank_number" class="block mb-3 font-medium text-gray-700 text-md">Nama Bank</label>
                                                <input disabled placeholder="Nama Bank Belum di Tambahkan" type="number" name="bank_number" id="bank_number" autocomplete="off"
                                                    class="cursor-not-allowed block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                    value="">
                                            </div>
                                        @endif

                                        <div class="">
                                            <label for="status" class="block mb-3 font-medium text-gray-700 text-md">Status Permintaan</label>
                                            <select name="status" id="status" class="block w-full py-3 px-5 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm {{ $penarikanSaldo->status == 'Disetujui' ? 'cursor-not-allowed' : '' }}" {{ $penarikanSaldo->status == 'Disetujui' ? 'disabled' : '' }}>
                                                <option value="Disetujui" {{ $penarikanSaldo->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                <option value="Tertunda" {{ $penarikanSaldo->status == 'Tertunda' ? 'selected' : '' }}>Tertunda</option>
                                                <option value="Dibatalkan" {{ $penarikanSaldo->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                            </select>

                                            @if ($errors->has('status'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('status')}}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label for="image" class="block mb-3 font-medium text-gray-700 text-md">Bukti Transfer</label>
                                            @if($penarikanSaldo->image)
                                                <img src="{{url($penarikanSaldo->image)}}" alt="">
                                            @else
                                            <input type="file" name="image" id="image" autocomplete="off"
                                                class=" block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                value="">
                                            @endif
                                            @if ($errors->has('image'))
                                                <p class="text-red-500 mb-3 text-sm">{{$errors->first('image')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-3 text-right sm:px-6">
                                    @php
                                        $phoneNumber = $penarikanSaldo->user->phone;
                                        if (substr($phoneNumber, 0, 1) === '0') {
                                            $phoneNumber = '62' . substr($phoneNumber, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{$phoneNumber}}" target="_blank" class="inline-flex justify-center px-4 py-2 mr-4 text-sm font-medium text-green-700 bg-white border border-green-600 rounded-lg shadow-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-300">
                                        Hubungi Whatsapp
                                    </a>
                                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('are you want to submit this data?')">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </main>
                </form>
            </section>
        @endrole
        @role('user')
            <section class="container px-6 mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-2 mt-2 bg-white rounded-xl shadow-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="" class="block mb-3 font-medium text-gray-700 text-md">Tanggal</label>
                                        <input placeholder="Nama Layanan Lainnya" type="text" name="" id="" autocomplete="off" class="block cursor-not-allowed w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="{{Carbon\Carbon::parse($penarikanSaldo->created_at)->isoFormat('dddd, D MMM YYYY')}}">
                                    </div>

                                    <div>
                                        <label for="name" class="block mb-3 font-medium text-gray-700 text-md">Nominal</label>
                                        <input placeholder="Nama Layanan Lainnya" type="text" name="" id="" autocomplete="off" disabled class="block cursor-not-allowed w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" value="Rp. {{number_format($penarikanSaldo->amount,0,',','.')}}">
                                    </div>

                                    <div>
                                        <label for="bank_number" class="block mb-3 font-medium text-gray-700 text-md">Nomor Rekening</label>
                                        <input disabled placeholder="Nomor Rekening belum diTambahkan" type="number" name="bank_number" id="bank_number" autocomplete="off"
                                            class="cursor-not-allowed block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                            value="{{$penarikanSaldo->user->bank_number}}">
                                        @error('bank_number')
                                            <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                                        @enderror
                                    </div>

                                    @if ($user->bank_id)
                                        <div>
                                            <label for="bank_number" class="block mb-3 font-medium text-gray-700 text-md">Nama Bank</label>
                                            <input disabled placeholder="{{ $bankName->name }}" type="number" name="bank_number" id="bank_number" autocomplete="off"
                                                class="cursor-not-allowed block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                value="{{ $bankName->name }}">
                                        </div>
                                    @else
                                        <div>
                                            <label for="bank_number" class="block mb-3 font-medium text-gray-700 text-md">Nama Bank</label>
                                            <input disabled placeholder="Nama Bank Belum di Tambahkan" type="number" name="bank_number" id="bank_number" autocomplete="off"
                                                class="cursor-not-allowed block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                value="">
                                        </div>
                                    @endif

                                    @if($penarikanSaldo->image)
                                        <div>
                                            <label for="image" class="block mb-3 font-medium text-gray-700 text-md">Bukti Transfer</label>
                                            <div class="relative flex h-52 w-auto cursor-pointer">
                                                <a href="#image-modal" class="block w-full">
                                                    <img
                                                    class="h-full w-full object-cover object-center rounded-xl"
                                                    src="{{asset($penarikanSaldo->image)}}"
                                                    />
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="px-4 py-3 text-right sm:px-6">
                                <a href="{{route('dashboard.saldo.index')}}" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </section>
        @endrole
    </main>
<div id="image-modal" class="fixed inset-0 z-100 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
    <!-- Link pembungkus untuk close saat klik anywhere -->
    <a href="#" class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative max-w-3xl mx-auto" onclick="event.stopPropagation()">
            <div>
                <img
                    class="w-full max-h-[80vh] object-contain"
                    src="{{asset($penarikanSaldo->image)}}"
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
            .create(document.querySelector('#faqAnswer'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
