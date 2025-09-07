@extends('layouts.base')
@section('title', 'Pembayaran')
@section('main')
<section class="bg-white py-8 antialiased  md:py-16">
    <div class="mx-auto max-w-2xl px-4 2xl:px-0">
        <div class="flex items-center justify-center mb-5">
            <img src="{{asset('images/undraw_confirmed_re_sef7.svg')}}" class="w-44" alt="">
        </div>
        @if($transaction->payment_method == 'Flip')    
        <h2 class="text-xl font-medium text-primary-700 text-center sm:text-2xl mb-2">Transaksi Anda berhasil!</h2>
        <p class="text-primary-500 text-center mb-6 md:mb-8">Untuk selanjutnya kami akan menghubungi Anda untuk melakukan check-in atas kamar yang dipesan</p>
        @elseif($transaction->payment_method == 'Cash')
        <h2 class="text-xl font-medium text-primary-700 text-center sm:text-2xl mb-2">Transaksi Anda berhasil!</h2>
        <p class="text-primary-500 text-center mb-6 md:mb-8">Silahkan lakukan pembayaran sesuai nominal yang tertera di meja kasir untuk menyelesaikan transaksi.</p>
        @endif
        <div class="space-y-4 sm:space-y-2 rounded-lg border border-primary-700 bg-primary-100 p-6 mb-6 md:mb-8">
            <h1 class="font-medium text-2xl text-primary-700">{{$transaction->invoice}}</h1>
            <dl class="sm:flex items-center justify-between gap-4">
                <dt class="font-light mb-1 sm:mb-0 text-primary-700 ">Tanggal Transaksi</dt>
                <dd class="font-medium text-primary-700  sm:text-end">{{$transaction->created_at->isoFormat('dddd, D MMMM YYYY')}}</dd>
            </dl>
            <dl class="sm:flex items-center justify-between gap-4">
                <dt class="font-light mb-1 sm:mb-0 text-primary-700 ">Metode Pembayaran</dt>
                <dd class="font-medium text-primary-700 sm:text-end">{{$transaction->payment_method}}</dd>
            </dl>
            @if($transaction->payment_method == 'Cash')
            <dl class="sm:flex items-center justify-between gap-4">
                <dt class="font-light mb-1 sm:mb-0 text-primary-700 ">Nominal</dt>
                <dd class="font-medium text-primary-700  sm:text-end">{{number_format($transaction->total_price,0,',','.')}}</dd>
            </dl>
            @endif
            <dl class="sm:flex items-center justify-between gap-4">
                <dt class="font-light mb-1 sm:mb-0 text-primary-700 ">Nama</dt>
                <dd class="font-medium text-primary-700  sm:text-end">{{$transaction->name}}</dd>
            </dl>
            <dl class="sm:flex items-center justify-between gap-4">
                <dt class="font-light mb-1 sm:mb-0 text-primary-700 ">Email</dt>
                <dd class="font-medium text-primary-700  sm:text-end">{{$transaction->email}}</dd>
            </dl>
            <dl class="sm:flex items-center justify-between gap-4">
                <dt class="font-light mb-1 sm:mb-0 text-primary-700 ">Nomor Telepon</dt>
                <dd class="font-medium text-primary-700  sm:text-end">{{$transaction->phone}}</dd>
            </dl>
        </div>
        <div class="flex items-center justify-center space-x-4">
            <a href="{{route('frontpage.index')}}" class="py-2.5 px-5 text-sm font-medium text-primary-700 focus:outline-none bg-white rounded-lg border border-primary-700 transition-all hover:bg-primary-700 hover:text-white focus:z-10 focus:ring-4 focus:ring-gray-100 ">Kembali</a>
            <a href="{{route('dashboard.user.bookings.detail', $transaction->invoice)}}" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Detail Transaksi</a>
        </div>
    </div>
  </section>
@endsection