@extends('layouts.auth')
@section('title', 'Verifikasi')
@section('main')
<div class="container sticky top-0 z-sticky">
  <div class="flex flex-wrap -mx-3">
    <div class="w-full max-w-full px-3 flex-0">
    </div>
  </div>
</div>
<main class="mt-0 transition-all duration-200 ease-in-out">
  <section>
    <div class="relative flex items-center min-h-screen p-0 overflow-hidden bg-center bg-cover">
      <div class="container z-1">
        <div class="flex flex-wrap -mx-3">
          <div class="flex flex-col w-full max-w-full px-3 mx-auto lg:mx-0 shrink-0 md:flex-0 md:w-7/12 lg:w-5/12 xl:w-4/12">
            <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none lg:py4 rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0">
                <h4 class="font-medium text-3xl mb-5x text-primary-700">Verifikasi</h4>
                {{-- <p class="mb-0 text-[#976033]">Masuk ke UNS Inn Hotel untuk melakukan reservasi kamar</p> --}}
              </div>
              <div class="flex-auto p-6">
                <form role="form" method="POST" action="{{route('verify.process')}}">
                    @csrf
                    @method('POST')
                    <div class="mb-4">
                        <label class="font-medium text-sm mb-5x text-primary-700">Kode OTP</label>
                        <input type="number" name="otp" autocomplete="off" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
                        @if ($errors->has('otp'))
                          <p  class="text-red-500 mt-3 text-sm">{{$errors->first('otp')}}</p>
                        @endif
                        <input type="hidden" name="phone">
                    </div>
                    <div class="text-center flex flex-col gap-3">
                        <button type="submit" class="inline-block w-full px-5 py-2.5 font-medium text-center text-white align-middle transition-all bg-primary-500 rounded-lg cursor-pointer hover:shadow-xs leading-normal text-sm  hover:bg-primary-700">Verifikasi</button>
                        {{-- <a href="{{route('resend')}}" >Kirim ulang</a> --}}
                      </div>
                    </form>
                    <form action="{{route('resend')}}" class="mt-3" method="POST">
                      @method('POST')
                      @csrf
                      <input type="hidden" name="phone" value="{{$user->phone}}">
                      <button type="submit" class="inline-block w-full px-5 py-2.5 font-medium text-center text-primary-500 border border-primary-500 align-middle transition-all hover:bg-primary-500 rounded-lg cursor-pointer hover:shadow-xs leading-normal text-sm hover:text-white">Kirim ulang</button>
                    </form>
              </div>
            </div>
          </div>
          <div class="absolute top-0 right-0 flex-col justify-center hidden w-6/12 h-full max-w-full px-3 pr-0 my-auto text-center flex-0 lg:flex">
            <div class="relative flex flex-col justify-center h-full bg-cover px-24 m-4 overflow-hidden bg-[url('https://images.unsplash.com/photo-1549294413-26f195200c16?q=80&w=1528&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] rounded-xl">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
@push('addon-script')

  
@endpush