{{-- @extends('layouts.dahboard_layout') --}}
@include('components.style')
@section('title', 'Detail Booking')

    <main class="overflow-hidden mx-auto font-sora w-a4 h-a4">
        <h1 class="text-primary-700 text-4xl font-medium">
            Informasi Booking
        </h1> 
        <section class="container ">
            <main class="col-span-12 md:pt-0">
                <div class="grid lg:grid-cols-3 gap-5">
                    {{-- Booking detail card --}}
                    <div class="mt-2  col-span-2">
                        {{-- Header card --}}
                        <div class="flex flex-col gap-3 mb-8">
                            @if($transaction->payment_status == 'PAID')
                            <p class="p-2 rounded-lg bg-green-100 border border-green-700 text-green-700 text-xs w-fit font-medium">{{$transaction->payment_status}}</p>
                            @elseif($transaction->payment_status == 'PENDING')
                            <p class="p-2 rounded-lg bg-yellow-100 border border-yellow-700 text-yellow-700 text-xs w-fit font-medium">{{$transaction->payment_status}}</p>
                            @endif
                            <h2 class="font-light text-primary-700 text-xl">Booking ID: <span class="font-medium">{{$transaction->invoice}}</span></h2>
                            <p class="flex items-center text-gray-700 text-sm gap-1"><span class="material-symbols-rounded">schedule</span> {{$transaction->created_at->isoFormat('dddd, D MMMM YYYY, H:M')}}</p>
                        </div>

                        {{-- Body card --}}
                        <div class="flex flex-col gap-6 text-sm">
                            <h3 class="text-xl text-primary-700">Detail Pemesan</h3>
                            <div class="grid grid-cols-2 gap-5">
                                <div class="flex flex-col gap-1">
                                    <p>Nama</p>
                                    <p class="font-medium text-primary-700">{{$transaction->user->name}}</p>
                                </div>
    
                                <div class="flex flex-col gap-1">
                                    <p>Email</p>
                                    <p class="font-medium text-primary-700">{{$transaction->user->email}}</p>
                                </div>
                                
                                <div class="flex flex-col gap-1">
                                    <p>Telepon</p>
                                    <p class="font-medium text-primary-700">{{$transaction->user->phone ?? $transaction->phone}}</p>
                                </div>
                            </div>
                            
                            <h3 class="text-xl text-primary-700">Detail Pesanan</h3>
                            <div class="grid grid-cols-2 gap-5">
                                <div class="flex flex-col gap-1">
                                    <p>Nama Kamar</p>
                                    <p class="font-medium text-primary-700">{{$transaction->room->name}}</p>
                                </div>
    
                                <div class="flex flex-col gap-1">
                                    <p>Nomor Kamar</p>
                                    <p class="font-medium text-primary-700">{{$transaction->room_number}}</p>
                                </div>
                            </div>
                            @php
                                $nights = date_diff(date_create($transaction->check_in), date_create($transaction->check_out))->format("%a")
                            @endphp
                            <div class="flex flex-col gap-1 col-span-2">
                                <p>Durasi</p>
                                <p class="font-medium text-primary-700">{{Carbon\Carbon::parse($transaction->check_in)->isoFormat('dddd, D MMM YYYY')}} - {{Carbon\Carbon::parse($transaction->check_out)->isoFormat('dddd, D MMM YYYY')}} ({{$nights}} Malam)</p>
                                {{-- <p>09:00</p> --}}
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 my-5 text-sm">
                            <div class="flex flex-col gap-1">
                                <p>Tambahan</p>
                                <ul class="flex flex-col gap-1">
                                    @foreach ($transaction->accomodation_plans as $accomodation_plan)                                        
                                    <li class="flex items-center gap-1 text-primary-700">{{$accomodation_plan->name}} (Rp. {{number_format($accomodation_plan->price,0,',','.')}})</li>
                                    @endforeach
                                </ul>
                            </div>
                            @php
                                $discount_amount = 0;
                                foreach($transaction->promos as $promo) {
                                    $discount_amount += $promo->amount;
                                }
                                $discount_amount = $discount_amount / 100;
                                $discounted_price = $discount_amount * ($transaction->room->price * $nights);
                            @endphp
                            <div class="flex flex-col gap-1">
                                <p>Promo yang Dipakai</p>
                                <ul class="flex flex-col gap-1">
                                    @foreach ($transaction->promos as $promo)                                        
                                    <li class="flex items-center gap-1 text-primary-700">{{$promo->name}} ({{$promo->amount}}%)</li>
                                    <li class="flex items-center gap-1 text-red-700 text-sm font-medium">-Rp. {{number_format(($promo->amount / 100) * ($transaction->room->price * $nights),0,',','.')}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <hr class="h-0.5 my-8 bg-gray-700 border-0">
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl text-primary-700 ">Ringkasan Tagihan</h3>
                            @if($transaction->payment_status == 'PAID')
                            <p class="p-2 rounded-lg bg-green-100 border border-green-700 text-green-700 text-xs w-fit font-medium">{{$transaction->payment_status}}</p>
                            @elseif($transaction->payment_status == 'PENDING')
                            <p class="p-2 rounded-lg bg-yellow-100 border border-yellow-700 text-yellow-700 text-xs w-fit font-medium">{{$transaction->payment_status}}</p>
                            @endif                            </div>
                        <div class="flex flex-col gap-3 text-sm">
                            <div class="flex items-center justify-between">
                                <p>Biaya kamar</p>
                                <p class="text-primary-700">Rp. {{number_format($transaction->room->price,0,',','.')}}</p>
                            </div>
                            @php
                                $accomodation_plan_amount = 0;
                                foreach ($transaction->accomodation_plans as $accomodation_plan) {
                                    $accomodation_plan_amount += $accomodation_plan->price;
                                }
                            @endphp
                            <div class="flex items-center justify-between">
                                <p>Biaya tambahan</p>
                                <p class="text-primary-700">Rp. {{number_format($accomodation_plan_amount,0,',','.')}}</p>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <p>Potongan harga</p>
                                <p class="text-red-700">-Rp. {{number_format($discounted_price,0,',','.')}}</p>
                            </div>
                            <div class="flex items-center justify-between ">
                                <p class="text-gray-800">Total harga</p>
                                <p class="bg-primary-100 text-primary-700 font-semibold">Rp. {{number_format($transaction->total_price,0,',','.')}}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <p>Metode pembayaran</p>
                                <p class="">{{$transaction->payment_method}}</p>
                            </div>
                            @if($transaction->checkin_status == 'Belum')
                            <a href="#" class="px-5 py-2 rounded-lg text-white bg-primary-700 text-center">Check-in</a>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </section>    
    </main>
{{-- @endsection --}}
