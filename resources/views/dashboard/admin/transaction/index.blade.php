@extends('layouts.dahboard_layout')

@section('title', 'Daftar Transaksi')

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
                            <p>Daftar Transaksi</p>
                        </div>
                        <h1 class="text-white text-4xl font-medium">
                            Daftar Transaksi
                        </h1>
                    </div>
                    <a href="#payment-modal" id="quickActionButton" class="flex hidden items-center mt-10 px-5 py-[0.73rem] ring-2 ring-red-500 rounded-md bg-primary-100 p-2 text-red-500 hover:bg-white transition-all duration-75 hover:text-red-500 text-base text-center">
                        <p class="whitespace-nowrap">Ubah Status Pembayaran</p>
                    </a>
                    <a href="#checkin-modal" id="quickActionButton2" class="flex hidden items-center mt-10 px-5 py-[0.73rem] ring-2 ring-red-500 rounded-md bg-primary-100 p-2 text-red-500 hover:bg-white transition-all duration-75 hover:text-red-500 text-base text-center">
                        <p class="whitespace-nowrap">Ubah Status Checkin</p>
                    </a>
                </div>
            </div>       
            <section class="container mx-auto">
                <main class="col-span-12 md:pt-0">
                    <!-- Filter Section -->
                    <div class="mb-4 p-6 bg-white rounded-xl shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Filter & Search</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Transaksi</label>
                                <input type="text" id="searchInput" placeholder="Cari nama, email, atau invoice..." 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            </div>
                            
                            <!-- Room Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Kamar</label>
                                <select id="roomFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    <option value="">Semua Kamar</option>
                                    @foreach($transactions->unique('room.name')->whereNotNull('room') as $transaction)
                                        <option value="{{ $transaction->room->name }}">{{ $transaction->room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Payment Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                                <select id="paymentStatusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    <option value="">Semua Status</option>
                                    <option value="PAID">PAID</option>
                                    <option value="PENDING">PENDING</option>
                                    <option value="CANCELLED">CANCELLED</option>
                                </select>
                            </div>
                            
                            <!-- Check-in Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Check-in</label>
                                <select id="checkinStatusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    <option value="">Semua Status</option>
                                    <option value="Belum">Belum Check-in</option>
                                    <option value="Sudah Checkin">Sudah Check-in</option>
                                    <option value="Sudah Checkout">Sudah Check-out</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Clear Filters Button -->
                        <div class="mt-4">
                            <button id="clearFilters" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                                Clear Filters
                            </button>
                        </div>
                    </div>

                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        <table id="transaction-table" class="">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-4 py-3">
                                        <input type="checkbox" id="masterCheckbox" class="cursor-pointer w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    </th>
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
                                            Invoice
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Nama Customer
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Email
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Kamar
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Check In
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Check Out
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
                                            Status Check-in
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Metode Pembayaran
                                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex items-center">
                                            Tanggal Transaksi
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
                                @forelse ($transactions as $key => $transaction)
                                    <tr class="cursor-pointer transaction-row" 
                                        data-search="{{ strtolower($transaction->name . ' ' . $transaction->email . ' ' . $transaction->invoice . ' ' . ($transaction->room->name ?? '')) }}"
                                        data-room="{{ $transaction->room->name ?? '' }}"
                                        data-payment-status="{{ $transaction->payment_status }}"
                                        data-checkin-status="{{ $transaction->checkin_status }}">
                                        <td scope="row" class="px-4 pe-0 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            <input type="checkbox" name="selected_transactions[]" class="cursor-pointer child-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" value="{{ $transaction->id }}">
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$key + 1}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <span class="text-primary-600 font-semibold">{{$transaction->invoice}}</span>
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$transaction->name}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$transaction->email}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            @if($transaction->room)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                    {{$transaction->room->name}}
                                                </span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            {{ $transaction->check_in ? $transaction->check_in->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            {{ $transaction->check_out ? $transaction->check_out->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <span class="font-semibold text-green-600">
                                                Rp. {{number_format($transaction->total_price,0,',','.')}}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaction->payment_status == 'PAID')
                                                <span class="px-3 py-1 rounded-full bg-green-100 border border-green-300 text-green-700 text-sm font-medium">
                                                    Lunas
                                                </span>
                                            @elseif($transaction->payment_status == 'PENDING')
                                                <span class="px-3 py-1 rounded-full bg-yellow-100 border border-yellow-300 text-yellow-700 text-sm font-medium">
                                                    Tertunda
                                                </span>
                                            @elseif($transaction->payment_status == 'CANCELLED')
                                                <span class="px-3 py-1 rounded-full bg-red-100 border border-red-300 text-red-700 text-sm font-medium">
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-gray-100 border border-gray-300 text-gray-700 text-sm font-medium">
                                                    {{$transaction->payment_status}}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->checkin_status == 'Sudah Checkin')
                                                <span class="px-3 py-1 rounded-full bg-green-100 border border-green-300 text-green-700 text-sm font-medium">
                                                    Sudah Check-in
                                                </span>
                                            @elseif($transaction->checkin_status == 'Sudah Checkout')
                                                <span class="px-3 py-1 rounded-full bg-blue-100 border border-blue-300 text-blue-700 text-sm font-medium">
                                                    Sudah Check-out
                                                </span>
                                            @elseif($transaction->checkin_status == 'Belum')
                                                <span class="px-3 py-1 rounded-full bg-yellow-100 border border-yellow-300 text-yellow-700 text-sm font-medium">
                                                    Belum
                                                </span>
                                            @elseif($transaction->checkin_status == 'Dibatalkan')
                                                <span class="px-3 py-1 rounded-full bg-red-100 border border-red-300 text-red-700 text-sm font-medium">
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-gray-100 border border-gray-300 text-gray-700 text-sm font-medium">
                                                    {{$transaction->checkin_status}}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            @if($transaction->payment_method)
                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-sm">
                                                    {{$transaction->payment_method}}
                                                </span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <span class="text-sm text-gray-600">
                                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="flex items-center justify-center">
                                            <div class="flex gap-2">
                                                <a href="{{route('dashboard.transaction.show', $transaction)}}" 
                                                   class="py-2 px-3 border-2 rounded-md border-primary-600 text-primary-500 text-center transition-all hover:bg-primary-500 hover:text-white"
                                                   title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($transaction->payment_status !== 'PAID')
                                                <button onclick="quickEditPayment('{{ $transaction->id }}', '{{ $transaction->payment_status }}')"
                                                        class="py-2 px-3 border-2 rounded-md border-green-600 text-green-500 text-center transition-all hover:bg-green-500 hover:text-white"
                                                        title="Edit Status Pembayaran">
                                                    <i class="bi bi-credit-card"></i>
                                                </button>
                                                @endif
                                                @if($transaction->checkin_status !== 'Sudah Checkout')
                                                <button onclick="quickEditCheckin('{{ $transaction->id }}', '{{ $transaction->checkin_status }}')"
                                                        class="py-2 px-3 border-2 rounded-md border-orange-600 text-orange-500 text-center transition-all hover:bg-orange-500 hover:text-white"
                                                        title="Edit Status Check-in">
                                                    <i class="bi bi-door-open"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center py-8 text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="bi bi-inbox text-4xl mb-2"></i>
                                                <p>Tidak ada data transaksi</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </main>
            </section>    
        </main>

        <!-- Payment Status Modal -->
        <div id="payment-modal" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="relative max-w-3xl mx-auto bg-white rounded-lg" onclick="event.stopPropagation()">
                    <a href="#" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    
                    <div class="p-16">
                        <form id="updatePaymentStatusForm" action="{{ route('dashboard.transactions.updateStatus') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-4">
                                <input type="hidden" name="transaction_ids[]" value="" id="selectedTransactionIds">
                                <label for="newPaymentStatus" class="block mb-2">
                                    Pilih Status Pembayaran:
                                    <br>
                                    <small class="text-gray-600">Pilih status pembayaran baru yang akan diterapkan pada transaksi yang dipilih.</small>
                                </label>
                                <select name="payment_status" id="newPaymentStatus" class="block w-full px-4 py-2 border rounded focus:ring-2 focus:ring-primary-500" required>
                                    <option value="">--Pilih Status Pembayaran--</option>
                                    <option value="PAID">PAID (Lunas)</option>
                                    <option value="PENDING">PENDING (Tertunda)</option>
                                    <option value="CANCELLED">CANCELLED (Dibatalkan)</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 w-full text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('Apakah Anda yakin ingin mengubah status pembayaran?')">
                                    Ubah Status Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check-in Status Modal -->
        <div id="checkin-modal" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="relative max-w-3xl mx-auto bg-white rounded-lg" onclick="event.stopPropagation()">
                    <a href="#" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    
                    <div class="p-16">
                        <form id="updateCheckinStatusForm" action="{{ route('dashboard.transactions.updateStatusCheckin') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-4">
                                <input type="hidden" name="transaction_ids[]" value="" id="selectedTransactionIds2">
                                <label for="newCheckinStatus" class="block mb-2">
                                    Pilih Status Check-in:
                                    <br>
                                    <small class="text-gray-600">Pilih status check-in baru yang akan diterapkan pada transaksi yang dipilih.</small>
                                </label>
                                <select name="checkin_status" id="newCheckinStatus" class="block w-full px-4 py-2 border rounded focus:ring-2 focus:ring-primary-500" required>
                                    <option value="">--Pilih Status Check-in--</option>
                                    <option value="Belum">Belum</option>
                                    <option value="Sudah Checkin">Sudah Check-in</option>
                                    <option value="Sudah Checkout">Sudah Check-out</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 w-full text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('Apakah Anda yakin ingin mengubah status check-in?')">
                                    Ubah Status Check-in
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script>
        let table = null;
        
        // Initialize DataTable
        function initializeDataTable() {
            if (table) {
                table.destroy();
            }

            table = new simpleDatatables.DataTable("#transaction-table", {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50],
                searchable: true,
                sortable: true,
                fixedHeight: false,
                columns: [
                    { select: 0, sortable: false }, // Checkbox column
                    { select: [1,2,3,4,5,6,7,8,9,10,11,12,13], sortable: true },
                    { select: 14, sortable: false } // Action column
                ]
            });

            // Re-attach event listeners after table initialization
            setTimeout(attachEventListeners, 100);
            
            // Re-attach after table updates
            table.on('datatable.init', attachEventListeners);
            table.on('datatable.page', attachEventListeners);
            table.on('datatable.sort', attachEventListeners);
            table.on('datatable.search', attachEventListeners);
        }

        // Attach event listeners for checkboxes
        function attachEventListeners() {
            // Master checkbox
            const masterCheckbox = document.getElementById('masterCheckbox');
            if (masterCheckbox) {
                masterCheckbox.removeEventListener('change', handleMasterCheckboxClick);
                masterCheckbox.addEventListener('change', handleMasterCheckboxClick);
            }

            // Child checkboxes
            const childCheckboxes = document.querySelectorAll('.child-checkbox');
            childCheckboxes.forEach(checkbox => {
                checkbox.removeEventListener('change', updateMasterCheckboxState);
                checkbox.addEventListener('change', updateMasterCheckboxState);
                checkbox.removeEventListener('change', toggleQuickActionButtons);
                checkbox.addEventListener('change', toggleQuickActionButtons);
            });
        }

        // Handle master checkbox click
        function handleMasterCheckboxClick(e) {
            const childCheckboxes = document.querySelectorAll('.child-checkbox');
            childCheckboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
            toggleQuickActionButtons();
        }

        // Update master checkbox state based on child checkboxes
        function updateMasterCheckboxState() {
            const masterCheckbox = document.getElementById('masterCheckbox');
            const childCheckboxes = document.querySelectorAll('.child-checkbox');
            
            if (masterCheckbox && childCheckboxes.length > 0) {
                const checkedCount = Array.from(childCheckboxes).filter(cb => cb.checked).length;
                masterCheckbox.checked = checkedCount === childCheckboxes.length;
                masterCheckbox.indeterminate = checkedCount > 0 && checkedCount < childCheckboxes.length;
            }
            toggleQuickActionButtons();
        }

        // Toggle quick action buttons visibility
        function toggleQuickActionButtons() {
            const quickActionButton = document.getElementById('quickActionButton');
            const quickActionButton2 = document.getElementById('quickActionButton2');
            const masterCheckbox = document.getElementById('masterCheckbox');
            const childCheckboxes = document.querySelectorAll('.child-checkbox');
            
            const isAnyCheckboxSelected = masterCheckbox.checked || 
                Array.from(childCheckboxes).some(checkbox => checkbox.checked);
            
            if (isAnyCheckboxSelected) {
                quickActionButton.style.display = 'flex';
                quickActionButton2.style.display = 'flex';
            } else {
                quickActionButton.style.display = 'none';
                quickActionButton2.style.display = 'none';
            }
        }

        // Update hidden inputs for selected transactions
        function updateSelectedTransactionIds() {
            const checkboxes = document.querySelectorAll('input[name="selected_transactions[]"]:checked');
            const selectedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
            
            document.getElementById('selectedTransactionIds').value = selectedValues.join(',');
            document.getElementById('selectedTransactionIds2').value = selectedValues.join(',');
        }

        // Quick edit functions for individual transactions
        function quickEditPayment(transactionId, currentStatus) {
            // You can implement individual quick edit functionality here
            // For now, we'll use the bulk edit modal
            const checkbox = document.querySelector(`input[value="${transactionId}"]`);
            if (checkbox) {
                checkbox.checked = true;
                updateSelectedTransactionIds();
                window.location.href = '#payment-modal';
            }
        }

        function quickEditCheckin(transactionId, currentStatus) {
            // You can implement individual quick edit functionality here
            // For now, we'll use the bulk edit modal
            const checkbox = document.querySelector(`input[value="${transactionId}"]`);
            if (checkbox) {
                checkbox.checked = true;
                updateSelectedTransactionIds();
                window.location.href = '#checkin-modal';
            }
        }

        // Filter functionality
        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const roomFilter = document.getElementById('roomFilter').value;
            const paymentStatusFilter = document.getElementById('paymentStatusFilter').value;
            const checkinStatusFilter = document.getElementById('checkinStatusFilter').value;
            
            const rows = document.querySelectorAll('.transaction-row');
            
            rows.forEach(row => {
                let showRow = true;
                
                // Search filter
                if (searchTerm && !row.dataset.search.includes(searchTerm)) {
                    showRow = false;
                }
                
                // Room filter
                if (roomFilter && row.dataset.room !== roomFilter) {
                    showRow = false;
                }
                
                // Payment status filter
                if (paymentStatusFilter && row.dataset.paymentStatus !== paymentStatusFilter) {
                    showRow = false;
                }
                
                // Check-in status filter
                if (checkinStatusFilter && row.dataset.checkinStatus !== checkinStatusFilter) {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('roomFilter').value = '';
            document.getElementById('paymentStatusFilter').value = '';
            document.getElementById('checkinStatusFilter').value = '';
            
            const rows = document.querySelectorAll('.transaction-row');
            rows.forEach(row => {
                row.style.display = '';
            });
        }

        // Initialize everything when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable
            initializeDataTable();
            
            // Filter event listeners
            document.getElementById('searchInput').addEventListener('input', applyFilters);
            document.getElementById('roomFilter').addEventListener('change', applyFilters);
            document.getElementById('paymentStatusFilter').addEventListener('change', applyFilters);
            document.getElementById('checkinStatusFilter').addEventListener('change', applyFilters);
            document.getElementById('clearFilters').addEventListener('click', clearFilters);
            
            // Checkbox event listeners for updating hidden inputs
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[name="selected_transactions[]"]') || e.target.id === 'masterCheckbox') {
                    updateSelectedTransactionIds();
                }
            });
            
            // Form submission handlers
            document.getElementById('updatePaymentStatusForm').addEventListener('submit', function(e) {
                const selectedIds = document.getElementById('selectedTransactionIds').value;
                if (!selectedIds) {
                    e.preventDefault();
                    alert('Silakan pilih minimal satu transaksi');
                    return false;
                }
            });
            
            document.getElementById('updateCheckinStatusForm').addEventListener('submit', function(e) {
                const selectedIds = document.getElementById('selectedTransactionIds2').value;
                if (!selectedIds) {
                    e.preventDefault();
                    alert('Silakan pilih minimal satu transaksi');
                    return false;
                }
            });
        });

        // Add styles for better visual feedback
        const style = document.createElement('style');
        style.textContent = `
            .transaction-row:hover {
                background-color: rgba(59, 130, 246, 0.05);
            }
            .transaction-row.selected {
                background-color: rgba(59, 130, 246, 0.1);
            }
            .dataTable-table input[type="checkbox"] {
                cursor: pointer;
            }
            .dataTable-table .child-checkbox:checked {
                accent-color: rgb(59, 130, 246);
            }
            
            /* Custom scrollbar for table */
            .dataTable-container {
                overflow-x: auto;
            }
            
            .dataTable-container::-webkit-scrollbar {
                height: 8px;
            }
            
            .dataTable-container::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }
            
            .dataTable-container::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }
            
            .dataTable-container::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        `;
        document.head.appendChild(style);
    </script>
@endpush