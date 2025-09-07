@extends('layouts.dahboard_layout')

@section('title', 'Detail Booking')

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
                <div class="flex flex-col gap-5 w-full p-6">
                    <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                        <a href="{{route('dashboard.home')}}" class="flex items-center">
                            <span class="material-symbols-rounded scale-75">home</span>
                        </a>
                        <span class="material-symbols-rounded">chevron_right</span>
                        <a href="{{route('dashboard.user.bookings')}}" class="flex items-center underline">
                            Data Booking
                        </a>
                        <span class="material-symbols-rounded">chevron_right</span>
                        <p>Detail</p>
                    </div>
                    <h1 class="text-white text-4xl font-medium">
                        Informasi Booking
                    </h1>
                </div>
            </div>
        </div>       
        <section class="container mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="grid lg:grid-cols-3 gap-5">
                    {{-- Booking detail card --}}
                    <div class="p-7 mt-2 bg-white rounded-xl shadow-lg col-span-2">
                        {{-- Header card --}}
                        <div class="flex flex-col gap-3 mb-8">
                            <p class="p-2 rounded-lg bg-green-100 border border-green-700 text-green-700 text-xs w-fit font-medium">Pesanan dikonfirmasi</p>
                            <h2 class="font-light text-primary-700 text-xl">Booking ID: <span class="font-medium">INN-37289</span></h2>
                            <p class="flex items-center text-sm gap-1"><span class="material-symbols-rounded">schedule</span> 17 November 2024, 09:56</p>
                        </div>

                        {{-- Body card --}}
                        <div class="grid lg:grid-cols-3 gap-6 text-sm">
                            <div class="flex flex-col gap-1">
                                <p>Tipe Kamar</p>
                                <p class="font-medium text-primary-700">Standard Single</p>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p>Nomor Kamar</p>
                                <p class="font-medium text-primary-700">101</p>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p>Harga per Malam</p>
                                <p class="font-medium text-primary-700">Rp. 975.000</p>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p>Check-in</p>
                                <p class="font-medium text-primary-700">17 Nov 2024</p>
                                <p>09:00</p>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p>Check-out</p>
                                <p class="font-medium text-primary-700">20 Nov 2024</p>
                                <p>07:30</p>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p>Durasi</p>
                                <p class="font-medium text-primary-700">3 Malam</p>
                            </div>

                            <div class="flex flex-col gap-1 col-span-3">
                                <p>Catatan</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam quae odio adipisci aperiam dolorem odit facilis quo esse blanditiis, illum vel, voluptates earum labore? Maiores, tempore. </p>
                            </div>
                        </div>
    
                        <hr class="h-0.5 my-8 bg-gray-700 border-0">
                        {{-- Footer card --}}
                        <div class="grid lg:grid-cols-3 gap-6 text-sm">
                            <div class="flex flex-col gap-1">
                                <p>Fasilitas Kamar</p>
                                <ul class="flex flex-col gap-1">
                                    <li class="flex items-center gap-1 text-primary-700"><span class="material-symbols-rounded scale-75">wifi</span>Wifi Kencang</li>
                                    <li class="flex items-center gap-1 text-primary-700"><span class="material-symbols-rounded scale-75">wifi</span>TV Kabel</li>
                                    <li class="flex items-center gap-1 text-primary-700"><span class="material-symbols-rounded scale-75">wifi</span>Air Hangat</li>
                                </ul>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p>Tambahan</p>
                                <ul class="flex flex-col gap-1">
                                    <li class="flex items-center gap-1 text-primary-700"><span class="material-symbols-rounded scale-75">wifi</span>Pijat Refleksi</li>
                                </ul>
                            </div>

                            <div class="flex flex-col gap-1">
                                <p>Poin yang Didapatkan</p>
                                <ul class="flex flex-col gap-1">
                                    <li class="flex items-center gap-1 text-primary-700"><span class="material-symbols-rounded scale-75">toll</span>100 Poin</li>
                                </ul>
                            </div>
                            <button class="p-3 rounded-lg text-white bg-red-700">Batalkan pesanan</button>
                        </div>

                    </div>

                    {{-- Room detail card --}}
                    <div class="p-7 mt-2 bg-white rounded-xl shadow-lg col-span-1">
                        <div class="flex flex-col gap-5">
                            <div class="flex items-center justify-between">
                                <p class="text-primary-700 text-lg font-medium">Single Standard</p>
                                <a href="#" class="text-sm underline">Detail</a>
                            </div>
                            <img src="https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" class="rounded-lg">
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 text-sm">
                                    <span class="material-symbols-rounded">square_foot</span>
                                    15 m2
                                </div>
                                <div class="flex items-center gap-1 text-sm">
                                    <span class="material-symbols-rounded">single_bed</span>
                                    Single Bed
                                </div>
                                <div class="flex items-center gap-1 text-sm">
                                    <span class="material-symbols-rounded">group</span>
                                    1 Guest
                                </div>
                            </div>
                        </div>
                        <hr class="h-0.5 my-8 bg-gray-700 border-0">
                        <div class="flex flex-col gap-5">
                            <div class="flex items-center gap-3">
                                <h3 class="text-xl text-primary-700 ">Ringkasan Tagihan</h3>
                                <p class="px-2 py-1 font-medium rounded text-sm bg-green-100 border border-green-700 text-green-700">Lunas</p>
                            </div>
                            <div class="flex flex-col gap-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <p>Biaya kamar</p>
                                    <p class="text-primary-700">Rp. 2.925.000</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p>Biaya tambahan</p>
                                    <p class="text-primary-700">Rp. 25.000</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p>Potongan harga</p>
                                    <p class="text-red-700">-Rp. 150.000</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p>PPN 10%</p>
                                    <p class="text-red-700">Rp. -280.000</p>
                                </div>
                                <div class="flex items-center justify-between ">
                                    <p class="text-gray-800">Total harga</p>
                                    <p class="bg-primary-100 text-primary-700 font-semibold">Rp. 2.580.000</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p>Metode pembayaran</p>
                                    <p class="">Online merchant (Xendit)</p>
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
@endpush