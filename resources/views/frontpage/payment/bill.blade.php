@extends('layouts.frontpage')
@push('addons-style')
@vite('resources/css/app.css')
<script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('title', 'Pembayaran - ' . $transaction->invoice)

@section('main')
@include('components.frontpage-navbar')

<section class="mx-12 lg:mx-36 pt-36 pb-20">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-primary-600 text-white p-6">
                <h1 class="text-2xl font-bold">Detail Pembayaran</h1>
                <p class="mt-2">Invoice: {{ $transaction->invoice }}</p>
            </div>

            <!-- Payment Status -->
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Status Pembayaran</h2>
                        <div class="mt-2">
                            @if($transaction->payment_status === 'PENDING')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <span class="material-icons-round text-sm mr-1">schedule</span>
                                    Menunggu Pembayaran
                                </span>
                            @elseif($transaction->payment_status === 'PAID')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <span class="material-icons-round text-sm mr-1">check_circle</span>
                                    Pembayaran Berhasil
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <span class="material-icons-round text-sm mr-1">error</span>
                                    {{ $transaction->payment_status }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Batas Waktu Pembayaran</p>
                        <p class="text-lg font-semibold text-red-600" id="countdown">
                            {{ $transaction->payment_deadline->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($transaction->payment_status === 'PENDING')
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h3>
                    
                    @if($transaction->payment_method === 'qris')
                        <!-- QRIS Payment -->
                        <div class="text-center">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Scan QR Code untuk Pembayaran</h4>
                                
                                @if(str_contains($transaction->payment_url, 'qris-basic'))
                                    <!-- Jika URL adalah gambar QR -->
                                    <div class="flex justify-center mb-4">
                                        <img src="{{ $transaction->payment_url }}" alt="QR Code QRIS" class="w-64 h-64 border rounded-lg">
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Scan QR Code di atas menggunakan aplikasi mobile banking atau e-wallet Anda</p>
                                @else
                                    <!-- Jika URL adalah halaman payment -->
                                    <div class="mb-4">
                                        <a href="{{ $transaction->payment_url }}" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                                            <span class="material-icons-round mr-2">qr_code</span>
                                            Buka Halaman Pembayaran QRIS
                                        </a>
                                    </div>
                                @endif
                                
                                <div class="bg-white rounded border p-4 text-left">
                                    <h5 class="font-medium text-gray-900 mb-2">Detail Transaksi:</h5>
                                    <div class="space-y-1 text-sm text-gray-600">
                                        <div class="flex justify-between">
                                            <span>Merchant:</span>
                                            <span>iPaymu</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Total Pembayaran:</span>
                                            <span class="font-medium">{{ $transaction->formatted_total_price }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>ID Transaksi:</span>
                                            <span>{{ $transaction->ipaymu_transaction_id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Other Payment Methods -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">
                                Pembayaran via {{ $transaction->payment_method_display }}
                            </h4>
                            
                            <div class="text-center mb-4">
                                <a href="{{ $transaction->payment_url }}" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                                    <span class="material-icons-round mr-2">{{ $transaction->payment_method_icon }}</span>
                                    Lanjutkan Pembayaran
                                </a>
                            </div>
                            
                            <div class="bg-white rounded border p-4 text-left">
                                <h5 class="font-medium text-gray-900 mb-2">Detail Transaksi:</h5>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Metode Pembayaran:</span>
                                        <span>{{ $transaction->payment_method_display }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Channel:</span>
                                        <span>{{ strtoupper($transaction->payment_method_detail) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Total Pembayaran:</span>
                                        <span class="font-medium">{{ $transaction->formatted_total_price }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>ID Transaksi:</span>
                                        <span>{{ $transaction->ipaymu_transaction_id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Payment Instructions -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="material-icons-round text-blue-400">info</span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Petunjuk Pembayaran</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Lakukan pembayaran sebelum batas waktu yang ditentukan</li>
                                        <li>Pembayaran akan diverifikasi secara otomatis</li>
                                        <li>Setelah pembayaran berhasil, Anda akan mendapat konfirmasi via email</li>
                                        <li>Simpan invoice ini sebagai bukti pemesanan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Booking Details -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pemesanan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Guest Information -->
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Informasi Tamu</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Nama:</span>
                                <span class="font-medium">{{ $transaction->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Email:</span>
                                <span>{{ $transaction->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Telepon:</span>
                                <span>{{ $transaction->phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Room Information -->
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Informasi Kamar</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Kamar:</span>
                                <span class="font-medium">{{ $transaction->room->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Check-in:</span>
                                <span>{{ $transaction->formatted_check_in }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Check-out:</span>
                                <span>{{ $transaction->formatted_check_out }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Malam:</span>
                                <span>{{ $transaction->nights }} malam</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-medium text-gray-900 mb-4">Rincian Harga</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Harga Kamar ({{ $transaction->nights }} malam)</span>
                            <span>{{ $transaction->formatted_base_amount }}</span>
                        </div>
                        
                        @if($transaction->accomodation_plans->count() > 0)
                            @foreach($transaction->accomodation_plans as $plan)
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ $plan->name }}</span>
                                    <span>Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($transaction->promos->count() > 0)
                            @foreach($transaction->promos as $promo)
                                <div class="flex justify-between text-green-600">
                                    <span>Diskon {{ $promo->name }} ({{ $promo->amount }}%)</span>
                                    <span>-Rp {{ number_format(($transaction->base_amount * $promo->amount / 100), 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($transaction->admin_fee > 0)
                            <div class="flex justify-between text-gray-600">
                                <span>Biaya Admin ({{ $transaction->payment_method_display }})</span>
                                <span>{{ $transaction->formatted_admin_fee }}</span>
                            </div>
                        @endif
                        
                        <div class="border-t pt-2 flex justify-between font-semibold">
                            <span>Total</span>
                            <span>{{ $transaction->formatted_total_price }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 pt-6 border-t flex flex-col sm:flex-row gap-4">
                    @if($transaction->payment_status === 'PENDING')
                        <button onclick="checkPaymentStatus()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                            <span class="material-icons-round mr-2 text-sm">refresh</span>
                            Cek Status Pembayaran
                        </button>
                    @endif
                    
                    <a href="{{ route('frontpage.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <span class="material-icons-round mr-2 text-sm">home</span>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('components.frontpage-footer')
@endsection

@push('addons-script')
<script>
    // Countdown timer
    function startCountdown() {
        const deadline = new Date("{{ $transaction->payment_deadline->toISOString() }}").getTime();
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = deadline - now;
            
            if (distance < 0) {
                clearInterval(timer);
                countdownElement.innerHTML = "EXPIRED";
                countdownElement.className = "text-lg font-semibold text-red-600";
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            let timeString = "";
            if (days > 0) timeString += days + "h ";
            timeString += hours.toString().padStart(2, '0') + ":";
            timeString += minutes.toString().padStart(2, '0') + ":";
            timeString += seconds.toString().padStart(2, '0');
            
            countdownElement.innerHTML = timeString;
        }, 1000);
    }

    // Check payment status
    function checkPaymentStatus() {
        const button = event.target;
        const originalText = button.innerHTML;
        
        button.innerHTML = '<span class="material-icons-round mr-2 text-sm animate-spin">refresh</span>Mengecek...';
        button.disabled = true;
        
        fetch(`/payment/check-status/{{ $transaction->invoice }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.status === 'PAID') {
                    window.location.reload();
                } else {
                    alert('Status pembayaran: ' + data.status);
                }
            } else {
                alert('Gagal mengecek status pembayaran');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengecek status pembayaran');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    // Auto refresh every 30 seconds for pending payments
    @if($transaction->payment_status === 'PENDING')
        setInterval(function() {
            fetch(`/payment/check-status/{{ $transaction->invoice }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.status === 'PAID') {
                    window.location.reload();
                }
            })
            .catch(error => console.error('Auto refresh error:', error));
        }, 30000);
    @endif

    // Start countdown on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if($transaction->payment_status === 'PENDING')
            startCountdown();
        @endif
    });
</script>
@endpush