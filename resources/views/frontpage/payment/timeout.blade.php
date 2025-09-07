@extends('layouts.base')
@section('title', 'Pembayaran')
@section('main')
<section class="bg-white py-8 antialiased  md:py-16">
    <div class="mx-auto max-w-2xl px-4 2xl:px-0 flex flex-col items-center justify-center gap-5">
        <div class="flex items-center justify-center mb-5">
            <img src="{{asset('images/undraw_cancel_re_pkdm.svg')}}" class="w-44" alt="">
        </div>
        <h2 class="text-xl font-medium text-primary-700 text-center sm:text-2xl mb-2">Transaksi Anda Gagal!</h2>
        <p class="text-primary-500 text-center mb-6 md:mb-8">Transaksi telah dibatalkan secara otomatis, karena telah melewati tenggat waktu yang diberikan. Silahkan reservasi ulang</p>
        <a href="{{route('frontpage.index')}}" class="px-5 py-3 rounded-lg bg-primary-700 text-white">Kembali ke Beranda</a>
    </div>
  </section>
@endsection