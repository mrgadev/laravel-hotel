@extends('layouts.dahboard_layout')

@section('title', 'My Saldo')

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
                        <p>Data Saldo</p>
                    </div>
                    <h1 class="text-white text-4xl font-medium">
                        Riwayat Saldo dan Penarikan saldo
                    </h1>
                </div>
                <a href="{{route('dashboard.penarikan-saldo.create')}}" class="flex items-center gap-2 mt-10 px-5 py-2 border-2 rounded-md bg-primary-100 p-2 text-primary-700 hover:bg-white transition-all duration-75 hover:text-[#976033] text-base text-center">
                    <i class="bi bi-cash mr-2"></i>
                    <p class="whitespace-nowrap">Tarik Saldo</p>
                </a>
            </div>
        </div>
        <section class="container mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                    <h1 class="text-primary-700 text-2xl font-bold text-center p-3">
                        Data saldo
                    </h1>
                    <table id="selection-table" class="">
                        <thead>
                            <tr>
                                <th>
                                    <span class="flex items-center">
                                        Tanggal
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Keterangan
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Debit
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Kredit
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Saldo akhir
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="flex items-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wallets as $wallet)

                            <tr class="cursor-pointer">

                                <td class="">
                                    <p>{{Carbon\Carbon::parse($wallet->created_at)->isoFormat('dddd, D MMM YYYY')}}</p>
                                </td>
                                <td class="">
                                    <p>{{$wallet->description ?? '-'}}</p>
                                </td>
                                <td class="">
                                    <p class="text-green-700">+ {{number_format($wallet->debit,0,',','.')}}</p>
                                </td>
                                <td class="">
                                    <p><p class="text-red-700">- {{number_format($wallet->credit,0,',','.')}}</p></p>
                                </td>
                                <td class="">
                                    <p >Rp. {{number_format($wallet->amount,0,',','.')}}</p>
                                </td>

                                <td class="flex items-center">
                                    <div class="mr-2">
                                        @if(isset($wallet->transaction))
                                        <a href="{{route('dashboard.user.bookings.detail', $wallet->transaction->invoice)}}" class="py-2 px-2 border-2 rounded-md border-primary-600 text-primary-500 text-center transition-all hover:bg-primary-500 hover:text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- @else
                                        <a href="#" class="py-2 px-2 border-2 rounded-md border-primary-600 text-primary-500 text-center transition-all hover:bg-primary-500 hover:text-white">
                                            <i class="bi bi-eye"></i>
                                        </a> --}}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </main>
        </section>
        <section class="container mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                    <table id="selection-table2" class="">
                        <h1 class="text-primary-700 text-2xl font-bold text-center p-3">
                            Data Penarikan saldo
                        </h1>
                        <thead>
                            <tr>
                                <th>
                                    <span class="flex items-center">
                                        Tanggal
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Nominal
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Status
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th class="flex items-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdrawals as $withdrawal)

                            <tr class="cursor-pointer">
                                <td class="">
                                    <p>{{Carbon\Carbon::parse($withdrawal->created_at)->isoFormat('dddd, D MMM YYYY')}}</p>
                                </td>
                                <td class="">
                                    <p class="text-red-700">- {{number_format($withdrawal->amount,0,',','.')}}</p>
                                </td>
                                @if($withdrawal->status == 'Disetujui')
                                    <td>
                                        <p class="p-2 rounded-lg bg-green-100 border border-green-700 text-green-700 text-sm w-fit">{{$withdrawal->status}}</p>
                                    </td>
                                    @elseif($withdrawal->status == 'Tertunda')
                                    <td>
                                        <p class="p-2 rounded-lg bg-yellow-100 border border-yellow-700 text-yellow-700 text-sm w-fit">{{$withdrawal->status}}</p>
                                    </td>
                                    @elseif($withdrawal->status == 'Dibatalkan')
                                    <td>
                                        <p class="p-2 rounded-lg bg-red-100 border border-red-700 text-red-700 text-sm w-fit">{{$withdrawal->status}}</p>
                                    </td>
                                @endif
                                <td class="flex items-center">
                                    <div class="mr-2">
                                        <a href="{{route('dashboard.penarikan-saldo.show', $withdrawal)}}" class="py-2 px-2 border-2 rounded-md border-primary-600 text-primary-500 text-center transition-all hover:bg-primary-500 hover:text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
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
        if (document.getElementById("selection-table2") && typeof simpleDatatables.DataTable !== 'undefined') {
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

                table = new simpleDatatables.DataTable("#selection-table2", options);

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
