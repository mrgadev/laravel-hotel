@extends('layouts.dahboard_layout') {{-- Pastikan nama layout benar --}}

@section('title', 'Home')

@section('breadcrumbs')
    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent text-white rounded-lg sm:mr-16">
        <li class="text-sm leading-normal">
            <a class="text-white opacity-90" href="#">Pages</a> {{-- Perbaikan href --}}
        </li>
        <li class="text-sm pl-2 text-white capitalize leading-normal before:float-left before:pr-2 before:content-['/']" aria-current="page">Dashboard</li>
    </ol>
    <h6 class="mb-0 font-bold text-white capitalize">Dashboard</h6>
@endsection

@section('content')
    <h1 class="w-full p-6 text-white text-4xl font-medium">
        Halo, {{ Auth::user()->name ?? 'User'}}
    </h1>
    <div class="w-full px-6 py-6 mx-auto">
        @role(['admin|staff'])
        @php
            $total_rooms = App\Models\Room::count() ?? 0;
            $total_reservations = App\Models\Transaction::count() ?? 0;
            $total_check_in = App\Models\Transaction::where('checkin_status', 'Sudah Check-in')->count() ?? 0;
            $total_revenue = App\Models\Transaction::where('checkin_status', 'Sudah Check-in')->sum('total_price') ?? 0;
            
            // Pastikan variabel untuk chart ada
            $bookedRooms = $bookedRooms ?? 0;
            $availableRooms = $availableRooms ?? 0;
        @endphp
        <!-- row 1 -->
        <div class="grid lg:grid-cols-4 gap-6 -mx-3">
            <!-- card1 -->
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase">Jumlah Kamar</p>
                                    <h5 class="mb-2 font-semibold text-xl">{{ $total_rooms }}</h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-circle border-2 border-[#976033]">
                                    <span class="material-symbols-rounded text-primary-500">bed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card2 -->
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase">Jumlah Reservasi</p>
                                    <h5 class="mb-2 font-semibold text-xl">{{ $total_reservations }}</h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-circle  border-2 border-[#976033]">
                                    <span class="material-symbols-rounded text-primary-500">confirmation_number</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card3 -->
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase">Total Pendapatan</p>
                                    <h5 class="mb-2 font-semibold text-xl">Rp. {{ number_format($total_revenue ?? 0,0,',','.') }}</h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-circle  border-2 border-[#976033]">
                                    <span class="material-symbols-rounded text-primary-500">payments</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card4 -->
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase">Total Check-in</p>
                                    <h5 class="mb-2 font-semibold text-xl">{{ $total_check_in }}</h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-circle  border-2 border-[#976033]">
                                    <span class="material-symbols-rounded text-primary-500">event_available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 2 -->
        <div class="grid lg:grid-cols-2 mt-6 gap-6 -mx-3">
            <div class="px-3 ">
                <div slider class="bg-white w-full h-full overflow-hidden rounded-2xl shadow-xl">
                    <div class="p-6 flex flex-col gap-5">
                        <h6 class="font-medium text-lg text-primary-700">Ketersediaan Kamar</h6>
                        <div class="flex items-center justify-center gap-2 w-full">
                            <canvas id="roomChart" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1 px-3 mt-0 w-full max-w-full">
                @php
                    $total_reviews = App\Models\RoomReview::all();
                    $total_rating = 0;
                    $average_rating = 0;
                    if($total_reviews->count() >= 1) {
                        foreach($total_reviews as $review) {
                            $total_rating += $review->rating;
                        }
                        $average_rating = $total_rating / $total_reviews->count();
                    }
                @endphp
                <div class="p-5 border-black/12.5 shadow-xl relative flex min-w-0 flex-col gap-5 break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <h6 class="font-medium text-lg text-primary-700">Keseluruhan Rating</h6>

                    <div class="flex items-center gap-5">
                        <p class="text-2xl p-5 rounded-lg bg-primary-100 text-primary-700 font-medium">{{ round($average_rating,1) }}</p>
                        <div class="flex flex-col">
                            @if($total_reviews->count() > 0)
                                @switch(round($average_rating,0))
                                    @case(1)
                                        <p class="text-primary-700">Buruk</p>
                                        @break
                                    @case(2)
                                        <p class="text-primary-700">Lumayan</p>
                                        @break
                                    @case(3)
                                        <p class="text-primary-700">Bagus</p>
                                        @break
                                    @case(4)
                                        <p class="text-primary-700">Sangat Bagus</p>
                                        @break
                                    @case(5)
                                        <p class="text-primary-700">Sempurna</p>
                                        @break
                                    @default
                                        <p class="text-primary-700">-</p>
                                @endswitch
                                <p class="text-sm">dari {{ $total_reviews->count() }} Pelanggan</p>
                            @else
                                <p class="text-primary-700">-</p>
                                <p class="text-sm">dari 0 Pelanggan</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid mt-6 gap-6 -mx-3">
            <div class=" px-3 mt-0 mb-6 w-full max-w-full">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl border-black-125 rounded-2xl bg-clip-border">
                    <div class="py-4 px-6 pb-0 mb-0 rounded-t-4">
                        <div class="flex justify-between items-center">
                            <h6 class="font-medium text-lg text-primary-700">Reservasi Terbaru</h6>
                            {{-- <a href="#" class="flex items-center px-5 py-2 border-2 rounded-md bg-primary-100 p-2 text-primary-700  transition-all duration-75 hover:text-[#976033] border border-primary-700 text-base text-center"> --}}
                            <a href="{{ route('dashboard.transaction.index') }}" class="flex items-center px-5 py-2 border-2 rounded-md bg-primary-100 p-2 text-primary-700  transition-all duration-75 hover:text-[#976033] border border-primary-700 text-base text-center">
                                <p>Lihat selengkapnya</p>
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto py-5 flex items-center justify-center">
                        <table class="text-center w-full whitespace-nowrap text-sm items-center font-normal">
                            <thead>
                                <tr class="font-semibold">
                                    <th scope="col" class="p-4 font-normal">Id</th>
                                    <th scope="col" class="p-4 font-normal">Nama</th>
                                    <th scope="col" class="p-4 font-normal">Invoice</th>
                                    <th scope="col" class="p-4 font-normal">Tanggal</th>
                                    <th scope="col" class="p-4 font-normal">Status Pembayaran</th>
                                    <th scope="col" class="p-4 font-normal">Status Check in</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1 @endphp
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td class="p-4 font-normal">{{ $i++ }}</td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <h3 class="font-normal">{{ $transaction->user->name }}</h3>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <h3 class="font-normal">{{ $transaction->invoice }}</h3>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <h3 class="font-normal">{{ $transaction->created_at->isoFormat('dddd, DD MMMM YYYY') }}</h3>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col items-center gap-1">
                                                @if($transaction->payment_status == 'PAID')
                                                <h3 class="font-normal p-2 bg-green-100 border border-green-700 text-green-700 rounded-lg w-fit">LUNAS</h3>
                                                @elseif($transaction->payment_status == 'PENDING')
                                                <h3 class="font-normal p-2 bg-yellow-100 border border-yellow-700 text-yellow-700 rounded-lg w-fit">TERTUNDA</h3>
                                                @elseif($transaction->payment_status == 'CANCELLED')
                                                <h3 class="font-normal p-2 bg-red-100 border border-red-700 text-red-700 rounded-lg w-fit">DIBATALKAN</h3>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col items-center gap-1">
                                                @if($transaction->checkin_status == 'Sudah Checkin')
                                                <h3 class="font-normal p-2 bg-green-100 border border-green-700 text-green-700 rounded-lg w-fit">{{ $transaction->checkin_status }}</h3>
                                                @elseif($transaction->checkin_status == 'Sudah Checkout')
                                                <h3 class="font-normal p-2 bg-green-100 border border-green-700 text-green-700 rounded-lg w-fit">{{ $transaction->checkin_status }}</h3>
                                                @elseif($transaction->checkin_status == 'Belum')
                                                <h3 class="font-normal p-2 bg-yellow-100 border border-yellow-700 text-yellow-700 rounded-lg w-fit">{{ $transaction->checkin_status }}</h3>
                                                @elseif($transaction->checkin_status == 'Dibatalkan')
                                                <h3 class="font-normal p-2 bg-red-100 border border-red-700 text-red-700 rounded-lg w-fit">{{ $transaction->checkin_status }}</h3>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        @role('user')
        {{-- @php
            $user_transaction = App\Models\Transaction::where('user_id', Auth::id())->latest()->first();
            $user_transactions = App\Models\Transaction::where('user_id', Auth::id())->latest()->limit(5)->get();
            $wallet = App\Models\Saldo::where('user_id', Auth::id())->latest()->first();
            
            // Pastikan variabel seconds ada
            $seconds = 0;
            if ($user_transaction && $user_transaction->payment_deadline) {
                $seconds = Carbon\Carbon::parse($user_transaction->payment_deadline)->diffInSeconds(now());
            }
        @endphp --}}
        {{-- #1 Row for USer --}}
        <div class="grid lg:grid-cols-2 gap-5">
            <div class="bg-white rounded-2xl shadow-xl p-5">
                <h3 class="text-xl text-primary-600 font-medium">My Wallet</h3>
                <div class="mt-5">
                    <a href="#" class="bg-primary-100 p-5 border border-primary-700 text-primary-700 flex items-center gap-5 rounded-lg">
                    {{-- <a href="{{ route('dashboard.saldo.index') }}" class="bg-primary-100 p-5 border border-primary-700 text-primary-700 flex items-center gap-5 rounded-lg"> --}}
                        <span class="material-symbols-rounded scale-150">wallet</span>
                        <div class="flex flex-col">
                            <p class="text-sm">Saldoku</p>
                            <p class="font-medium text-lg">IDR {{ number_format($wallet->amount ?? 0,0,',','.') }}</p>
                        </div>
                    </a>
                </div>
            </div>

            @if (isset($user_transaction))
                @if($user_transaction->payment_status == 'PENDING' )
                <div class="bg-white rounded-2xl shadow-xl p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium text-lg text-primary-700">Menunggu Pembayaran</h3>
                        <div class="flex flex-col gap-3 ">
                            <div class="flex items-center gap-1" id="timerContainer">
                                <p class="p-2 rounded-lg bg-red-100 border border-red-700 text-red-700" id="hours">03</p>
                                :
                                <p class="p-2 rounded-lg bg-red-100 border border-red-700 text-red-700" id="minutes">25</p>
                                :
                                <p class="p-2 rounded-lg bg-red-100 border border-red-700 text-red-700" id="seconds">01</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-5">
                        <div class="flex flex-col gap-1">
                            @php
                                $nights = date_diff(date_create($user_transaction->check_in), date_create($user_transaction->check_out))->format("%a");
                            @endphp
                            <p class="text-sm text-primary-500">Order ID: {{ $user_transaction->invoice }}</p>
                            <p class="font-medium text-primary-700 text-base">{{ $user_transaction->room->name }} ({{ $nights }} Malam)</p>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-0.5 text-sm">
                                    <span class="material-symbols-rounded scale-75">event</span>
                                    <p>{{ Carbon\Carbon::parse($user_transaction->check_in)->isoFormat('dddd, D MMM YYYY') }} - {{ Carbon\Carbon::parse($user_transaction->check_out)->isoFormat('dddd, D MMM YYYY') }}</p>
                                </div>
                            </div>

                        </div>
                        {{-- <a href="{{ route('payment.bill', $user_transaction->invoice) }}" class="flex px-3 py-2 rounded-lg bg-primary-700 text-white">Bayar sekarang</a> --}}
                        <a href="#" class="flex px-3 py-2 rounded-lg bg-primary-700 text-white">Bayar sekarang</a>
                    </div>

                </div>
                @else
                @if($user_transaction->checkin_status == 'Belum' && $user_transaction->payment_status == 'PAID')
                    <div class="bg-white rounded-2xl shadow-xl p-5">
                        <h3 class="font-medium text-lg text-primary-700">Reservasi Berikutnya</h3>
                        {{-- <a href="{{ route('dashboard.user.bookings.detail', $user_transaction->invoice) }}" class="flex gap-5 mt-5"> --}}
                        <a href="#" class="flex gap-5 mt-5">
                            <img src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" class="h-24 rounded-lg">
                            <div class="flex flex-col gap-1">
                                @php
                                    $nights = date_diff(date_create($user_transaction->check_in), date_create($user_transaction->check_out))->format("%a");
                                @endphp
                                <p class="text-sm text-primary-500">Order ID: {{ $user_transaction->invoice }}</p>
                                <p class="font-medium text-primary-700 text-base">{{ $user_transaction->room->name }} ({{ $nights }} Malam)</p>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-0.5 text-sm">
                                        <span class="material-symbols-rounded scale-75">event</span>
                                        <p>{{ Carbon\Carbon::parse($user_transaction->check_in)->isoFormat('dddd, D MMM YYYY') }} - {{ Carbon\Carbon::parse($user_transaction->check_out)->isoFormat('dddd, D MMM YYYY') }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-xl p-10">
                        <div class="flex gap-5">

                            {{-- <img src="{{ asset('images/undraw_Quitting_time_re_1whp.png') }}" alt="" class="w-24"> --}}
                            <div class="flex flex-col gap-3">
                                <h3 class="font-medium text-xl text-primary-700">Tidak ada reservasi mendatang</h3>
                                <a href="#" class="px-5 py-3 rounded-lg bg-primary-700 text-white w-fit">Lihat riwayat</a>
                                {{-- <a href="{{ route('dashboard.user.bookings') }}" class="px-5 py-3 rounded-lg bg-primary-700 text-white w-fit">Lihat riwayat</a> --}}
                            </div>
                        </div>
                    </div>
                @endif

                @endif

            @else
                <div class="bg-white rounded-2xl shadow-xl p-10">
                    <div class="flex gap-5">

                        {{-- <img src="{{ asset('images/undraw_Quitting_time_re_1whp.png') }}" alt="" class="w-24"> --}}
                        <div class="flex flex-col gap-3">
                            <h3 class="font-medium text-xl text-primary-700">Tidak ada reservasi mendatang</h3>
                            <a href="#" class="px-5 py-3 rounded-lg bg-primary-700 text-white w-fit">Lihat riwayat</a>
                            {{-- <a href="{{ route('dashboard.user.bookings') }}" class="px-5 py-3 rounded-lg bg-primary-700 text-white w-fit">Lihat riwayat</a> --}}
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="grid mt-6 gap-6 -mx-3">
            <div class=" px-3 mt-0 mb-6 w-full max-w-full">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl border-black-125 rounded-2xl bg-clip-border">
                    <div class="py-4 px-6 pb-0 mb-0 rounded-t-4">
                        <div class="flex justify-between items-center">
                            <h6 class="">Reservasi Terbaru</h6>
                            <a href="#" class="flex items-center px-5 py-2 border-2 rounded-md bg-primary-100 p-2 text-primary-700  transition-all duration-75 hover:text-[#976033] border border-primary-700 text-base text-center">
                            {{-- <a href="{{ route('dashboard.user.bookings') }}" class="flex items-center px-5 py-2 border-2 rounded-md bg-primary-100 p-2 text-primary-700  transition-all duration-75 hover:text-[#976033] border border-primary-700 text-base text-center"> --}}
                                <p>Lihat selengkapnya</p>
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto py-5 flex items-center justify-center">
                        <table class="text-center w-full whitespace-nowrap text-sm items-center font-normal">
                            <thead>
                                <tr class="font-semibold">
                                    <th scope="col" class="p-4 font-normal">Nama</th>
                                    <th scope="col" class="p-4 font-normal">Invoice</th>
                                    <th scope="col" class="p-4 font-normal">Tanggal</th>
                                    <th scope="col" class="p-4 font-normal">Status Pembayaran</th>
                                    <th scope="col" class="p-4 font-normal">Status Check in</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1 @endphp
                                @forelse ($user_transactions as $item)
                                    <tr>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <h3 class="font-normal">{{ $item->name }}</h3>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <h3 class="font-normal">{{ $item->invoice }}</h3>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <h3 class="font-normal">{{ $item->created_at->isoFormat('dddd, D MMM YYYY') }}</h3>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <h3 class="font-normal">{{ $item->payment_status }}</h3>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1">
                                                <h3 class="font-normal">{{ $item->checkin_status }}</h3>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endrole
    </div>
@endsection
@push('addon-script')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    if (document.getElementById("selection-table") && typeof simpleDatatables.DataTable !== 'undefined') {
        let multiSelect = true;
        let rowNavigation = false;
        let table = null;

        const resetTable = function() {
            if (table) {
                table.destroy();
            }

            const options = {
                rowRender: (row, tr, _index) => {
                    if (!tr.attributes) {
                        tr.attributes = {};
                    }
                    if (!tr.attributes.class) {
                        tr.attributes.class = "";
                    }
                    if (row.selected) {
                        tr.attributes.class += " selected";
                    } else {
                        tr.attributes.class = tr.attributes.class.replace(" selected", "");
                    }
                    return tr;
                }
            };
            if (rowNavigation) {
                options.rowNavigation = true;
                options.tabIndex = 1;
            }

            table = new simpleDatatables.DataTable("#selection-table", options);

            // Mark all rows as unselected
            table.data.data.forEach(data => {
                data.selected = false;
            });

            table.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                const row = table.data.data[rowIndex];
                if (row.selected) {
                    row.selected = false;
                } else {
                    if (!multiSelect) {
                        table.data.data.forEach(data => {
                            data.selected = false;
                        });
                    }
                    row.selected = true;
                }
                table.update();
            });
        };

        // Row navigation makes no sense on mobile, so we deactivate it and hide the checkbox.
        const isMobile = window.matchMedia("(any-pointer:coarse)").matches;
        if (isMobile) {
            rowNavigation = false;
        }

        resetTable();
    }

    const ctx = document.getElementById('roomChart').getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Kamar Terpesan', 'Kamar Tersedia'],
                datasets: [{
                    data: [{{ $bookedRooms ?? 0 }}, {{ $availableRooms ?? 0 }}],
                    backgroundColor: [
                        '#FF8042',
                        '#00C49F'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ ($bookedRooms ?? 0) + ($availableRooms ?? 0) }};
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${context.label}: ${context.raw} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
</script>

<script>
    var myChart = echarts.init(document.getElementById('revenueChart'));

    var option = {
        title: {
            text: 'ECharts Introduction Example'
        },
        tooltip: {},
        xAxis: {
            data: ["shirt", "cardigan", "chiffon", "pants", "heels", "socks"]
        },
        yAxis: {},
        series: [{
            name: 'Sales',
            type: 'bar',
            data: [5, 20, 36, 10, 10, 20]
        }]
    };

    myChart.setOption(option);
</script>

<script>

    let timeRemaining = Math.abs({{$seconds ?? 0}});

    function updateCountdown() {
        if(timeRemaining <= 0) {
            document.getElementById('countdown').innerHTML = 'Transaksi telah dibatalkan';
        }

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
@endpush
