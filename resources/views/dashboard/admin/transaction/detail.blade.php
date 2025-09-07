@extends('layouts.dahboard_layout')

@section('title', 'Detail Transaksi')

{{-- @section('breadcrumb')
    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
            <li class="text-sm leading-normal">
            <a class="text-white opacity-90" href="javascript:;">Dashboard</a>
            </li>
            <li class="text-sm pl-2 text-white capitalize leading-normal  before:float-left before:pr-2 before: before:content-['/']" aria-current="page">Kamar Facilities</li>
        </ol>
    <h6 class="mb-0 font-bold text-white capitalize">Data Fasilitas Kamar</h6>
@endsection --}}

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex w-full gap-5 mx-auto justify-between items-center">
                <div class="flex flex-col gap-5 p-6">
                    <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                        <a href="{{route('dashboard.home')}}" class="flex items-center">
                            <span class="material-symbols-rounded scale-75">home</span>
                        </a>
                        <span class="material-symbols-rounded">chevron_right</span>
                        <a href="{{route('dashboard.transaction.index')}}" class="flex items-center underline">
                            Daftar Transaksi
                        </a>
                        <span class="material-symbols-rounded">chevron_right</span>
                        <p>Detail</p>
                    </div>
                    <h1 class="text-white text-4xl font-medium">
                        Detail Transaksi
                    </h1>
                </div>
                <a href="{{route('dashboard.user.bookings.export', $transaction->invoice)}}" class="px-5 py-3 rounded-lg bg-red-100 text-red-700 border-2 border-red-700"><i class="bi bi-file-earmark-pdf"></i> Simpan ke PDF</a>
            </div>
        </div>
        <section class="container mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="grid lg:grid-cols-3 gap-5">
                    {{-- Booking detail card --}}
                    <div class="p-7 mt-2 bg-white rounded-xl shadow-lg col-span-2">
                        {{-- Header card --}}
                        <div class="flex flex-col gap-3 mb-8">
                            @if($transaction->payment_status == 'PAID')
                            <p class="p-2 rounded-lg bg-green-100 border border-green-700 text-green-700 text-sm w-fit">LUNAS</p>
                            @elseif($transaction->payment_status == 'PENDING')
                            <p class="p-2 rounded-lg bg-yellow-100 border border-yellow-700 text-yellow-700 text-sm w-fit">TERTUNDA</p>
                            @endif
                            <h2 class="font-light text-primary-700 text-xl">Booking ID: <span class="font-medium">{{$transaction->invoice}}</span></h2>
                            <p class="flex items-center text-sm gap-1"><span class="material-symbols-rounded">schedule</span> {{$transaction->created_at->isoFormat('dddd, DD MMMM YYYY, H:M')}}</p>
                        </div>

                        {{-- Body card --}}
                        <div class="my-10">
                            <h1 class="text-lg font-medium text-primary-700 mb-5">Data Pemesan</h1>
                            <div class="grid lg:grid-cols-3 gap-6 text-sm">
                                <div class="flex flex-col gap-1">
                                    <p>Nama Lengkap</p>
                                    <p class="font-medium text-primary-700">{{$transaction->name}}</p>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Nomor Telepon</p>
                                    <p class="font-medium text-primary-700">{{$transaction->phone}}</p>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Email</p>
                                    <p class="font-medium text-primary-700">{{$transaction->email}}</p>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Status Check-in</p>
                                    <div class="flex items-center gap-3">
                                        @if($transaction->checkin_status == 'Dibatalkan')
                                        <p class="font-medium text-red-700" id="checkInStatus">
                                            {{$transaction->checkin_status}}
                                        </p>
                                        @else
                                        <p class="font-medium text-primary-700" id="checkInStatus">
                                            {{$transaction->checkin_status}}
                                        </p>
                                        @endif
                                        @if($transaction->payment_status == 'PAID' && $transaction->checkin_status == 'Sudah Check-in')
                                        <button id="editCheckInStatusBtn" class="underline text-primary-700">
                                            Ubah status
                                        </button>
                                        <div id="checkInStatusFormContainer" class="hidden">
                                            <form id="editCheckInStatusForm" method="POST" action="{{route('dashboard.transaction.changeCheckInStatus', $transaction->id)}}">
                                                @csrf
                                                @method('PUT')
                                                {{-- <input type="hidden" name="id"> --}}
                                                {{-- <input type="text" id="inputField" /> --}}
                                                <select name="checkin_status" id="checkInStatusField" class="bg-primary-100 rounded border border-primary-700 text-primary-700 p-2">

                                                    <option value="Sudah Check-in">Sudah Check-in</option>
                                                    <option value="Belum Check-in">Belum Check-in</option>
                                                    <option value="Sudah Check-out">Sudah Check-out</option>
                                                    <option value="Dibatalkan">Dibatalkan</option>
                                                </select>
                                                <div class="mt-3 flex gap-2">
                                                    <button type="submit" class="text-primary-700 underline">Ubah</button>
                                                    <button type="button" id="cancelEditCheckInStatusBtn" class="text-red-700">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Status Pembayaran</p>
                                    @if($transaction->payment_method == 'Xendit'  || $transaction->payment_method == 'Split Payment (Saldo & Xendit)' || $transaction->payment_status == 'PAID')
                                        <p class="font-medium text-primary-700">{{$transaction->payment_status}}</p>
                                    @elseif($transaction->payment_method == 'Cash' || $transaction->payment_method == 'Split Payment (Saldo & Cash)')
                                        <div class="flex items-center gap-3">
                                            <div id="paymentStatusFormContainer" class="">
                                                <form id="editpaymentStatusForm" method="POST" action="{{route('dashboard.transaction.changePaymentStatus', $transaction->id)}}">
                                                    @csrf
                                                    @method('PUT')
                                                    {{-- <input type="hidden" name="id"> --}}
                                                    {{-- <input type="text" id="inputField" /> --}}
                                                    <select name="payment_status" id="paymentStatusField" class="bg-primary-100 rounded border border-primary-700 text-primary-700 p-2">

                                                        <option value="{{$transaction->payment_status}}">Tidak Diubah ({{$transaction->payment_status}})</option>
                                                        <option value="PAID">PAID</option>
                                                        <option value="PENDING">PENDING</option>
                                                        <option value="CENCELLED">CENCELLED</option>
                                                    </select>
                                                    <button type="submit">Submit</button>
                                                    <button type="button" id="cancelEditpaymentStatusBtn">Cancel</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="my-10">
                            <h1 class="text-lg font-medium text-primary-700 mb-5">Rincian Pesanan</h1>
                            <div class="grid lg:grid-cols-3 gap-6 text-sm">
                                <div class="flex flex-col gap-1">
                                    <p>Tipe Kamar</p>
                                    <p class="font-medium text-primary-700">{{$transaction->room->name}}</p>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Nomor Kamar</p>
                                    <p class="font-medium text-primary-700">{{$transaction->room_number}}</p>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Harga per Malam</p>
                                    <p class="font-medium text-primary-700">Rp. {{number_format($transaction->room->price,0,',','.')}}</p>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Check-in</p>
                                    <p class="font-medium text-primary-700">{{Carbon\Carbon::parse($transaction->check_in)->isoFormat('dddd, D MMM YYYY')}}</p>
                                    {{-- <p>09:00</p> --}}
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Check-out</p>
                                    <p class="font-medium text-primary-700">{{Carbon\Carbon::parse($transaction->check_out)->isoFormat('dddd, D MMM YYYY')}}</p>
                                    {{-- <p>07:30</p> --}}
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p>Durasi</p>
                                    @php
                                        $nights = date_diff(date_create($transaction->check_in), date_create($transaction->check_out))->format("%a")
                                    @endphp
                                    <p class="font-medium text-primary-700">{{$nights}} Malam</p>
                                </div>

                                {{-- <div class="flex flex-col gap-1 col-span-3">
                                    <p>Catatan</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam quae odio adipisci aperiam dolorem odit facilis quo esse blanditiis, illum vel, voluptates earum labore? Maiores, tempore. </p>
                                </div> --}}
                            </div>
                        </div>

                        <hr class="h-0.5 my-8 bg-gray-700 border-0">
                        {{-- Footer card --}}
                        <div class="grid lg:grid-cols-3 gap-6 text-sm">
                            <div class="flex flex-col gap-1">
                                <p>Fasilitas Kamar</p>
                                <ul class="flex flex-col gap-1">
                                    @foreach ($transaction->room->room_facility as $room_facility)
                                    <li class="flex items-center gap-1 text-primary-700"><span class="material-icons-round scale-75">{{$room_facility->icon}}</span>{{$room_facility->name}}</li>
                                    @endforeach
                                    {{-- <li class="flex items-center gap-1 text-primary-700"><span class="material-symbols-rounded scale-75">wifi</span>TV Kabel</li>
                                    <li class="flex items-center gap-1 text-primary-700"><span class="material-symbols-rounded scale-75">wifi</span>Air Hangat</li> --}}
                                </ul>
                            </div>

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

                            <div class="flex flex-col gap-1 col-span-3">
                                <p>Catatan Tambahan</p>
                                <p class="bg-primary-100 border border-primary-700 text-primary-700 rounded-lg p-2 font-medium w-fit">
                                    {{$transaction->notes}}
                                </p>
                            </div>
                            {{-- <div class="flex flex-col gap-1">
                                <p>Poin yang Didapatkan</p>
                                <ul class="flex flex-col gap-1">
                                    <li class="flex items-center gap-1 text-primary-700"><span class="material-symbols-rounded scale-75">toll</span>100 Poin</li>
                                </ul>
                            </div> --}}
                        </div>
                        {{-- <button class="p-3 rounded-lg text-white bg-red-700 mt-5">Batalkan pesanan</button> --}}

                    </div>

                    {{-- Room detail card --}}
                    <div class="p-7 mt-2 bg-white rounded-xl shadow-lg col-span-1">
                        <div class="flex flex-col gap-5">
                            <div class="flex items-center justify-between">
                                <p class="text-primary-700 text-lg font-medium">{{$transaction->room->name}}</p>
                                <a href="{{route('frontpage.rooms.detail', $transaction->room->slug)}}" class="text-sm underline">Detail</a>
                            </div>
                            <img src="{{ $transaction->room->thumbnail_url }}" 
                            alt="{{ $room->name ?? 'Kamar' }}" 
                            class="w-full h-64 object-cover rounded-lg relative"
                            onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                        </div>
                        <hr class="h-0.5 my-8 bg-gray-700 border-0">
                        <div class="flex flex-col gap-5">
                            <div class="flex items-center gap-3">
                                <h3 class="text-xl text-primary-700 ">Ringkasan Tagihan</h3>
                                {{-- <p class="px-2 py-1 font-medium rounded text-sm bg-green-100 border border-green-700 text-green-700">{{$transaction->payment_status}}</p> --}}
                            </div>
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
                                <div class="flex items-center justify-between">
                                    <p>Metode pembayaran</p>
                                    <p class="">{{$transaction->payment_method}}</p>
                                </div>
                                <div class="flex items-center justify-between ">
                                    <p class="text-gray-800">Total harga</p>
                                    <p class="bg-primary-100 text-primary-700 font-semibold">Rp. {{number_format($transaction->total_price,0,',','.')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </section>
    </main>
@endsection
@push('addon-script')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
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
    </script>

    <script>
        const editCheckInStatusBtn = document.getElementById('editCheckInStatusBtn');
        const checkInStatus = document.getElementById('checkInStatus');
        const checkInStatusFormContainer = document.getElementById('checkInStatusFormContainer');
        const checkInStatusField = document.getElementById('checkInStatusField');
        const cancelEditCheckInStatusBtn = document.getElementById('cancelEditCheckInStatusBtn');

        editCheckInStatusBtn.addEventListener('click', function() {
            // Get the current text from the <p> element
            const currentCheckInStatus = checkInStatus.textContent;

            // Set the input field value to the current text
            checkInStatusField.value = currentCheckInStatus;

            // Hide the <p> element and show the form
            checkInStatus.style.display = 'none';
            editCheckInStatusBtn.classList.add('hidden');
            checkInStatusFormContainer.classList.remove('hidden');
        });

        cancelEditCheckInStatusBtn.addEventListener('click', function() {
            // Hide the form and show the <p> element again
                editCheckInStatusBtn.classList.remove('hidden');
            checkInStatusFormContainer.classList.add('hidden');
            checkInStatus.style.display = 'block';
        });


        const editPaymentStatusBtn = document.getElementById('editPaymentStatusBtn');
        const paymentStatus = document.getElementById('paymentStatus');
        const paymentStatusFormContainer = document.getElementById('paymentStatusFormContainer');
        const paymentStatusField = document.getElementById('paymentStatusField');
        const cancelEditPaymentStatusBtn = document.getElementById('cancelEditPaymentStatusBtn');

        editPaymentStatusBtn.addEventListener('click', function() {
            // Get the current text from the <p> element
            const currentPaymentStatus = paymentStatus.textContent;

            // Set the input field value to the current text
            paymentStatusField.value = currentPaymentStatus;

            // Hide the <p> element and show the form
            paymentStatus.style.display = 'none';
            editPaymentStatusBtn.classList.add('hidden');
            paymentStatusFormContainer.classList.remove('hidden');
        });

        cancelEditPaymentStatusBtn.addEventListener('click', function() {
            // Hide the form and show the <p> element again
            editPaymentStatusBtn.classList.remove('hidden');
            paymentStatusFormContainer.classList.add('hidden');
            paymentStatus.style.display = 'block';
        });
    </script>
@endpush
