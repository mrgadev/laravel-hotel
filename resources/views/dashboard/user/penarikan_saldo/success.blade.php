@extends('layouts.base')
@section('title', 'Pembayaran')
@section('main')
<section class="bg-white py-8 antialiased  md:py-16">
    <div class="mx-auto max-w-2xl px-4 2xl:px-0">
        <div class="flex items-center justify-center mb-5">
            <img src="{{asset('images/undraw_confirmed_re_sef7.svg')}}" class="w-44" alt="">
        </div>
        <h2 class="text-xl font-medium text-primary-700 text-center sm:text-2xl mb-2">Penarikan saldo anda Akan di Proses</h2>
        <p class="text-primary-500 text-center mb-6 md:mb-8">Untuk selanjutnya kami akan menghubungi Anda Jika telah Di setujui Oleh Admin</p>
        <div class="space-y-4 sm:space-y-2 rounded-lg border border-primary-700 bg-primary-100 p-6 mb-6 md:mb-8">
            <dl class="sm:flex items-center justify-between gap-4">
                <dt class="font-light mb-1 sm:mb-0 text-primary-700 ">Tanggal Penarikan</dt>
                <dd class="font-medium text-primary-700  sm:text-end">{{$saldo->created_at->isoFormat('dddd, D MMMM YYYY')}}</dd>
            </dl>
            <dl class="sm:flex items-center justify-between gap-4">
                <dt class="font-light mb-1 sm:mb-0 text-primary-700 ">Nominal</dt>
                <dd class="font-medium text-primary-700  sm:text-end">{{number_format($saldo->amount,0,',','.')}}</dd>
            </dl>
        </div>
        <div class="flex items-center justify-center space-x-4">
            <a href="{{route('dashboard.saldo.index')}}" class="py-2.5 px-5 text-sm font-medium text-primary-700 focus:outline-none bg-white rounded-lg border border-primary-700 transition-all hover:bg-primary-700 hover:text-white focus:z-10 focus:ring-4 focus:ring-gray-100 ">Kembali</a>
        </div>
    </div>
  </section>
@endsection