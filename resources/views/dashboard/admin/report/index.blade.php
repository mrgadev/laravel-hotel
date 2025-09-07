@extends('layouts.dahboard_layout')

@section('title', 'Laporan Keuangan')

{{-- @section('breadcrumb')
    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
            <li class="text-sm leading-normal">
            <a class="text-white opacity-90" href="javascript:;">Dashboard</a>
            </li>
            <li class="text-sm pl-2 text-white capitalize leading-normal  before:float-left before:pr-2 before: before:content-['/']" aria-current="page">Hotel Facilities</li>
        </ol>
    <h6 class="mb-0 font-bold text-white capitalize">Data Fasilitas Hotel</h6>
@endsection --}}

@section('content')
    <div id="bulkDeleteContainer">
        <main class="h-full overflow-y-auto">
            <div class="container mx-auto">
                <div class="flex w-full gap-5 mx-auto justify-between items-center">
                    <div class="flex flex-col gap-5 w-full p-6">
                        <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                            <a href="{{route('dashboard.home')}}" class="flex items-center">
                                <span class="material-symbols-rounded scale-75">home</span>
                            </a>
                            <span class="material-symbols-rounded">chevron_right</span>
                            <p>Laporan Keuangan</p>
                        </div>
                        <h1 class="text-white text-4xl font-medium">
                            Laporan Keuangan
                        </h1>
                    </div>
                    @if(isset($transactions) && $transactions->isNotEmpty())
                        <form action="{{route('dashboard.export-transactions')}}" method="POST">
                            @csrf
                            <input type="hidden" name="years" id="years" value="{{request('year')}}">
                            <input type="hidden" name="months" id="months" value="{{request('month')}}">
                            <button type="submit" class="ring-green-800 flex items-center gap-2 mt-10 px-5 py-2 ring-2  rounded-md bg-primary-100 p-2 text-green-700 hover:bg-white transition-all duration-75 hover:text-green-700 text-base text-center">
                                <i class="bi bi-file-earmark-spreadsheet"></i>
                                <p class=" whitespace-nowrap">Export Data</p>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <section class="container mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        <div class="mb-4">
                            <form action="{{ route('dashboard.report.index') }}" method="GET" class="flex gap-4">
                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700">Pilih Tahun:</label>
                                    <select name="year" onchange="this.form.submit()" class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="w-full">
                            <canvas id="transactionChart" height="100"></canvas>
                        </div>
                    </div>
                </main>
            </section>

            <section class="container mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        <h3 for="" class="mb-3 text-primary-700 text-2xl font-bold">Filter Data Transaksi</h3>
                        <form action="{{ route('dashboard.report.index') }}" method="GET">
                            <div class="flex items-center gap-3">
                                <div class="grid grid-cols-1 gap-2 w-full">
                                    <label for="year" class="flex items-center gap-1 text-primary-700 font-light text-sm">Tahun</label>
                                    <select name="year" id="year" class="p-2 bg-primary-100 border border-primary-700 rounded-lg text-primary-700">
                                        <option value="">-- Pilih Tahun --</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-1 gap-2 w-full">
                                    <label for="month" class="flex items-center gap-1 text-primary-700 font-light text-sm">Bulan</label>
                                    <select name="month" id="month" class="p-2 bg-primary-100 border border-primary-700 rounded-lg text-primary-700">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="january" {{ request('month') == 'january' ? 'selected' : '' }}>Januari</option>
                                        <option value="february" {{ request('month') == 'february' ? 'selected' : '' }}>Februari</option>
                                        <option value="march" {{ request('month') =='march' ? 'selected' : '' }}>Maret</option>
                                        <option value="april" {{ request('month') == 'april' ? 'selected' : '' }}>April</option>
                                        <option value="may" {{ request('month') == 'may' ? 'selected' : '' }}>Mei</option>
                                        <option value="june" {{ request('month') == 'june' ? 'selected' : '' }}>Juni</option>
                                        <option value="july" {{ request('month') == 'july' ? 'selected' : '' }}>Juli</option>
                                        <option value="august" {{ request('month') == 'august' ? 'selected' : '' }}>Agustus</option>
                                        <option value="september" {{ request('month') == 'september' ? 'selected' : '' }}>September</option>
                                        <option value="october" {{ request('month') == 'october' ? 'selected' : '' }}>Oktober</option>
                                        <option value="november" {{ request('month') == 'november' ? 'selected' : '' }}>November</option>
                                        <option value="december" {{ request('month') == 'december' ? 'selected' : '' }}>Desember</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mt-8">
                                <a href="{{route('dashboard.report.index')}}" class="px-5 py-2 border border-primary-500 text-primary-500 rounded-full hover:bg-primary-500 hover:text-white transition-all" id="">Reset</a>
                                <button type="submit" class="bg-primary-500 text-white px-5 py-2 rounded-full hover:bg-primary-700 transition-all">Pilih</button>
                            </div>
                        </form>
                    </div>
                </main>
            </section>

            <section class="container mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        <table id="selection-table" class="">
                            <thead>
                                <tr>
                                    {{-- <th scope="col" class="px-4 py-3">
                                        <input type="checkbox" id="masterCheckbox" class="cursor-pointer w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                                    </th> --}}
                                    <th>
                                        <span class="flex items-center">
                                            No
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Nama
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Jenis
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Nomor Invoice
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Total Biaya
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Status Pembayaran
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Metode Pambayaran
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $key => $transaction)
                                    <tr class="cursor-pointer">
                                        {{-- <td scope="row" class="px-4 pe-0 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            <input type="checkbox" name="service_ids[]" class="cursor-pointer child-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600" value="{{ $service->id }}">
                                        </td> --}}
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$key + 1}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$transaction->name}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">Kamar Hotel</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$transaction->invoice}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">Rp. {{number_format($transaction->total_price,0,',','.')}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$transaction->payment_status}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$transaction->payment_method}}</td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </main>
            </section>

            <section class="container mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg flex items-center justify-between">
                        <p class="font-bold text-primary-700 whitespace-nowrap">Total Transaksi</p>
                        <p class="font-bold text-primary-700 whitespace-nowrap">Rp. {{number_format($totalAmount,0,',','.')}}</p>
                    </div>
                </main>
            </section>
        </main>
    </div>
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

                // Initialize DataTable first
                table = new simpleDatatables.DataTable("#selection-table", {
                    perPage: 10,
                    perPageSelect: [10, 25, 50],
                    searchable: true,
                    sortable: true,
                    fixedHeight: false,
                    columns: [
                        { select: 0, sortable: false }, // Checkbox column
                        { select: [1,2,3], sortable: true },
                        { select: 4, sortable: false } // Action column
                    ]
                });

                // Function to handle master checkbox state
                const updateMasterCheckboxState = () => {
                    const masterCheckbox = document.querySelector('thead input[type="checkbox"]');
                    const childCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

                    if (masterCheckbox && childCheckboxes.length > 0) {
                        const checkedCount = Array.from(childCheckboxes).filter(cb => cb.checked).length;
                        masterCheckbox.checked = checkedCount === childCheckboxes.length;
                        masterCheckbox.indeterminate = checkedCount > 0 && checkedCount < childCheckboxes.length;
                    }
                };

                // Function to handle master checkbox click
                const handleMasterCheckboxClick = (e) => {
                    const childCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                    childCheckboxes.forEach(checkbox => {
                        checkbox.checked = e.target.checked;
                    });
                };

                // Function to attach event listeners
                const attachEventListeners = () => {
                    // Master checkbox
                    const masterCheckbox = document.querySelector('thead input[type="checkbox"]');
                    if (masterCheckbox) {
                        masterCheckbox.addEventListener('click', handleMasterCheckboxClick);
                    }

                    // Child checkboxes
                    const childCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                    childCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', updateMasterCheckboxState);
                    });
                };

                // Attach event listeners after table is initialized and after any table update
                table.on('datatable.init', attachEventListeners);
                table.on('datatable.page', attachEventListeners);
                table.on('datatable.sort', attachEventListeners);
                table.on('datatable.search', attachEventListeners);

                // Initial attachment of event listeners
                setTimeout(attachEventListeners, 100);
            };

            resetTable();
        }

        // Add styles to handle selected rows
        const style = document.createElement('style');
        style.textContent = `
            .dataTable-table tbody tr.selected {
                background-color: rgba(var(--primary-rgb), 0.1);
            }
            .dataTable-table input[type="checkbox"] {
                cursor: pointer;
            }
        `;
        document.head.appendChild(style);

        // Function to toggle quick action button visibility
        const toggleQuickActionButton = () => {
            const quickActionButton = document.getElementById('quickActionButton');
            const masterCheckbox = document.getElementById('masterCheckbox');
            const childCheckboxes = document.querySelectorAll('.child-checkbox');

            // Check if any checkbox is selected (either master or any child)
            const isAnyCheckboxSelected = masterCheckbox.checked ||
                Array.from(childCheckboxes).some(checkbox => checkbox.checked);

            // Show/hide quick action button based on selection
            if (isAnyCheckboxSelected) {
                quickActionButton.style.display = 'flex';
            } else {
                quickActionButton.style.display = 'none';
            }
        };

        // Add event listeners to master checkbox
        document.getElementById('masterCheckbox').addEventListener('change', toggleQuickActionButton);

        // Add event listeners to all child checkboxes
        document.querySelectorAll('.child-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', toggleQuickActionButton);
        });

        // Initially hide the quick action button
        document.getElementById('quickActionButton').style.display = 'none';

        document.addEventListener('DOMContentLoaded', function () {
            const deleteButton = document.getElementById('quickActionButton');
            const actionUrl = "{{ route('dashboard.faq.bulkDelete') }}";

            deleteButton.addEventListener('click', function () {
                const faqIds = Array.from(document.querySelectorAll('input[name="faq_ids[]"]:checked'))
                    .map(checkbox => checkbox.value);

                if (faqIds.length === 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Tidak ada data yang dipilih",
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                    return;
                }

                // CSRF token
                const csrfToken = "{{ csrf_token() }}";

                fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ faq_ids: faqIds })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: data.success ? "success" : "error",
                        title: data.message,
                        toast: true,
                        position: "top-end",
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });

                    // Reload the page if the operation was successful
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('transactionChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: `Total Transaksi Tahun {{ $selectedYear }}`,
                    data: @json($monthlyData),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Grafik Transaksi Bulanan'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
