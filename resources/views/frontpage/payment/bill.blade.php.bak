@extends('layouts.base')
@section('title', 'Pembayaran')
@section('main')
<section class="bg-white p-8 antialiased lg:py-16">
    <div class="mx-auto max-w-2xl p-5 mb-5 bg-primary-100 flex items-center justify-between border border-primary-700 rounded-lg">
        <h1 class="text-lg lg:text-xl font-medium text-primary-700">UNS Inn Hotel</h1>
        <div class="flex items-center gap-3">
            {{-- <a href="#" class="flex items-center gap-2 text-primary-700 font-medium"><i class="bi bi-file-earmark-pdf"></i> <span class="hidden lg:inline">Simpan PDF</span></a> --}}
            {{-- <a href="#" class="px-3 py-2 rounded-lg bg-primary-700 text-white">Bayar sekarang</a> --}}
        </div>
    </div>
    <div class="mx-auto max-w-2xl p-5 bg-primary-100 border border-primary-700 rounded-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="flex flex-col gap-3">
                <p class="bg-yellow-700 w-fit text-sm text-white px-2 py-1 rounded-lg">{{$transaction->payment_status}}</p>
                <h1 class="text-2xl text-primary-700 font-semibold">#{{$transaction->invoice}}</h1>
            </div>

            <div class="flex flex-col gap-3 ">
                <p class="text-primary-700">Lakukan pembayaran sebelum</p>
                <div class="flex items-center gap-1" id="timerContainer">
                    <p class="p-2 rounded-lg bg-red-700 text-white" id="hours">03</p>
                    :
                    <p class="p-2 rounded-lg bg-red-700 text-white" id="minutes">25</p>
                    :
                    <p class="p-2 rounded-lg bg-red-700 text-white" id="seconds">01</p>
                </div>
            </div>
        </div>

        <div class="my-5">
            <p class="text-sm text-primary-500 font-medium">Data Pemesan</p>
            <h3 class="text-lg text-primary-700 font-medium my-3">{{$transaction->user->name}}</h3>
            <p class="text-sm text-primary-700">{{$transaction->user->phone}}</p>
            <p class="text-sm text-primary-700">{{$transaction->user->email}}</p>
        </div>

        <div class="mb-5">
            <p class="text-sm text-primary-500 font-medium">Data Pesanan</p>
            <div class="grid lg:grid-cols-4 gap-3">
                @php
                    $nights = date_diff(date_create($transaction->check_in), date_create($transaction->check_out))->format("%a");
                @endphp
                <div class="col-span-3 flex flex-col">
                    <p>{{$transaction->room->name}}</p>
                    <p class="text-sm">{{$nights}} Malam</p>
                </div>
                <div class="grid">
                    <p>Rp. {{number_format($transaction->room->price * $nights, 0, ',', '.')}}</p>
                </div>
                @foreach ($transaction->accomodation_plans as $accomodation_plan)    
                <div class="col-span-3 flex flex-col">
                    <p class="text-sm">Layanan tambahan</p>
                    <p>{{$accomodation_plan->name}}</p>
                </div>
                <div class="grid">
                    <p>Rp. {{number_format($accomodation_plan->price,0,',','.')}}</p>
                </div>
                @endforeach
                @php
                    $discount_amount = 0;
                    foreach($transaction->promos as $promo) {
                        $discount_amount += $promo->amount;
                    }
                    $discount_amount = $discount_amount / 100;
                    $discounted_price = $discount_amount * ($transaction->room->price * $nights);
                @endphp
                @foreach ($transaction->promos as $promo)    
                <div class="col-span-3 flex flex-col">
                    <p class="text-sm">Promo yang Digunakan</p>
                    <p>{{$promo->name}} ({{$promo->amount}}%)</p>
                </div>
                <div class="grid">
                    <p class="text-red-700 font-medium">- Rp. {{number_format(($promo->amount / 100) * ($transaction->room->price * $nights),0,',','.')}}</p>
                </div>
                @endforeach
                <div class="col-span-3 flex flex-col">
                    <p>Metode Pembayaran</p>
                </div>
                <div class="grid">
                    <p>{{$transaction->payment_method}}</p>
                </div>

            </div>    
            
            <div class="col-span-3 mt-5 text-xl text-primary-700 font-medium flex flex-col lg:hidden">
                <p>Total</p>
                <p>Rp. {{number_format($transaction->total_price,0,',','.')}}</p>
            </div>
        </div>
        @if($transaction->payment_method == 'Flip' || $transaction->payment_method == 'Split Payment (Saldo & Flip)')
        <a href="{{$transaction->payment_url}}" class="text-white p-5 bg-primary-700 grid lg:grid-cols-4 rounded-lg items-center active:ring-4 active:ring-primary-700">
            <p class="lg:col-span-3 text-xl text-center lg:text-left">Bayar sekarang</p>
            <div class="hidden lg:flex flex-col gap-1">
                <p class="text-sm">Total</p>
                <p >Rp. {{number_format($transaction->total_price,0,',','.')}}</p>
            </div>
        </a>
        @elseif($transaction->payment_method == 'Cash' || $transaction->payment_method == 'Saldo' || $transaction->payment_method == 'Split Payment (Saldo & Cash)')
        <a href="{{route('payment.success', $transaction->invoice)}}" class="text-white p-5 bg-primary-700 grid lg:grid-cols-4 rounded-lg items-center active:ring-4 active:ring-primary-700">
            <p class="lg:col-span-3 text-xl text-center lg:text-left">Bayar sekarang</p>
            <div class="hidden lg:flex flex-col gap-1">
                <p class="text-sm">Total</p>
                <p >Rp. {{number_format($transaction->total_price,0,',','.')}}</p>
            </div>
        </a>
        @endif
    </div>
  </section>
  @php
      $seconds = Carbon\Carbon::parse($transaction->payment_deadline)->diffInSeconds(now());
  @endphp
  {{-- {{$seconds}} --}}
@endsection
@push('addons-script')
    <script>
        let timeRemaining = Math.abs({{$seconds}});
        // let paymentStatus = {{$transaction->payment_status}};
        function handlePaymentTimeout() {
            window.location.href = "{{route('payment.timeout', $transaction->invoice)}}";
        }
        //setTimeout(handlePaymentTimeout(), timeRemaining)

        function updateCountdown() {
            if(timeRemaining <= 0  ) {
                handlePaymentTimeout();
                return;
            }
            // } else if(paymentStatus == 'PAID') {
            //     window.location.href = "{{route('payment.success', $transaction->invoice)}}";
            //     return;
            // }

            const hours = Math.floor((timeRemaining % (24 * 60 * 60)) / (60 * 60));
            const minutes = Math.floor((timeRemaining % (60 * 60)) / 60);
            const seconds = Math.floor(timeRemaining % 60);

            document.getElementById('hours').textContent = String(hours).padStart(2,'0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2,'0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2,'0');

            timeRemaining--;
        }

        updateCountdown();
        setInterval(updateCountdown,1000);
    </script>
    {{-- <script>
        let timeRemaining = Math.abs({{$seconds}});
        // Function to handle redirection when time expires
        function handlePaymentTimeout() {
            window.location.href = "{{ route('payment.timeout', $transaction->invoice) }}";
        }

        // Set timeout for redirection
        setTimeout(handlePaymentTimeout, timeRemaining);

        // Display countdown timer
        function updateCountdown() {
            const deadline = new Date("{{ $transaction->payment_deadline }}").getTime();
            
            const timer = setInterval(function() {
                const now = new Date().getTime();
                const timeLeft = deadline - now;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    handlePaymentTimeout();
                    return;
                }

                // Calculate time units
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                // Update the countdown display
                document.getElementById('hours').textContent = String(hours).padStart(2,'0');
                document.getElementById('minutes').textContent = String(minutes).padStart(2,'0');
                document.getElementById('seconds').textContent = String(seconds).padStart(2,'0');
            }, 1000);
        }

        // Start countdown when page loads
        document.addEventListener('DOMContentLoaded', updateCountdown);
    </script> --}}
@endpush