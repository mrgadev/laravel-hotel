@extends('layouts.dahboard_layout')

@section('title', 'Daftar Kamar')

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
                            <p>Data Kamar</p>
                        </div>
                        <h1 class="text-white text-4xl font-medium">
                            Data Kamar
                        </h1>
                        @if($rooms->count() > 0)
                            <p class="text-white/80 text-sm">
                                Total: {{ $rooms->count() }} kamar
                            </p>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2">
                        <a href="{{route('dashboard.room.create')}}" 
                           class="flex items-center gap-2 mt-10 px-5 py-2 border-2 rounded-md bg-primary-100 p-2 text-primary-700 hover:bg-white transition-all duration-75 hover:text-[#976033] text-base text-center">
                            <i class="bi bi-plus-square mr-2"></i>
                            <p>Tambah Kamar</p>
                        </a>
                        <button
                            type="button"
                            id="quickActionButton"
                            class="flex hidden items-center px-5 py-2 ring-2 ring-red-500 rounded-md bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-75 text-base text-center"
                            onclick="confirmBulkDelete()"
                        >
                            <i class="bi bi-trash mr-2"></i>
                            <p class="whitespace-nowrap">Hapus Terpilih</p>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="container mx-auto mb-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container mx-auto mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                </div>
            @endif

            <section class="container mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        @if($rooms->count() > 0)
                            <div class="overflow-x-auto">
                                <table id="selection-table" class="w-full">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="px-4 py-3">
                                                <input type="checkbox" id="masterCheckbox" class="cursor-pointer w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
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
                                                    Gambar
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
                                                    Harga
                                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                    </svg>
                                                </span>
                                            </th>
                                            <th>
                                                <span class="flex items-center">
                                                    Kamar Tersedia
                                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                    </svg>
                                                </span>
                                            </th>
                                            <th>
                                                <span class="flex items-center">
                                                    Fasilitas
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
                                        @foreach ($rooms as $key => $room)
                                            <tr class="cursor-pointer hover:bg-gray-50">
                                                <td scope="row" class="px-4 pe-0 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                    <input type="checkbox" name="room_ids[]" class="cursor-pointer child-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600" value="{{ $room->id }}">
                                                </td>
                                                <td class="font-medium text-gray-900 whitespace-nowrap">{{$key + 1}}</td>
                                                <td class="">
                                                    <img src="{{ $room->thumbnail_url }}" 
                                                    alt="{{ $room->name ?? 'Kamar' }}" 
                                                    class="w-16 h-16 object-cover rounded-t-xl relative"
                                                    onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                                                </td>
                                                <td class="font-medium text-gray-900">
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold">{{$room->name}}</span>
                                                        @if($room->description)
                                                            <span class="text-sm text-gray-500 truncate max-w-xs">{!!Str::limit($room->description, 50)!!}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="font-medium text-gray-900 whitespace-nowrap">
                                                    <span class="text-lg font-semibold text-green-600">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="font-medium text-gray-900 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <span class="text-sm">
                                                            <span class="font-semibold text-blue-600">{{$room->available_rooms ?? 0}}</span> 
                                                            / 
                                                            <span class="text-gray-600">{{$room->total_rooms ?? 0}}</span>
                                                        </span>
                                                        @if(($room->available_rooms ?? 0) == 0)
                                                            <span class="text-xs text-red-500 font-medium">Penuh</span>
                                                        @elseif(($room->available_rooms ?? 0) <= 2)
                                                            <span class="text-xs text-yellow-500 font-medium">Terbatas</span>
                                                        @else
                                                            <span class="text-xs text-green-500 font-medium">Tersedia</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="font-medium text-gray-900">
                                                    @if($room->room_facility && $room->room_facility->count() > 0)
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($room->room_facility->take(3) as $facility)
                                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                                    {{$facility->name}}
                                                                </span>
                                                            @endforeach
                                                            @if($room->room_facility->count() > 3)
                                                                <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                                                    +{{$room->room_facility->count() - 3}}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 text-sm">Tidak ada fasilitas</span>
                                                    @endif
                                                </td>
                                                <td class="flex items-center gap-2">
                                                    <a href="{{route('frontpage.rooms.detail', $room->slug)}}" target="_blank"
                                                       class="py-2 px-3 border-2 rounded-md border-primary-600 text-primary-500 text-center transition-all hover:bg-primary-500 hover:text-white"
                                                       title="Show">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{route('dashboard.room.edit', $room)}}" 
                                                       class="py-2 px-3 border-2 rounded-md border-primary-600 text-primary-500 text-center transition-all hover:bg-primary-500 hover:text-white"
                                                       title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="{{route('dashboard.room.destroy', $room)}}" 
                                                          class="inline-block" 
                                                          method="POST"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar {{$room->name}}?')">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" 
                                                                class="py-2 px-3 border-2 rounded-md border-red-600 text-red-600 text-center transition-all hover:bg-red-600 hover:text-white"
                                                                title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="bi bi-building text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kamar</h3>
                                <p class="text-gray-500 mb-6">Mulai dengan menambahkan kamar pertama Anda.</p>
                                <a href="{{route('dashboard.room.create')}}" 
                                   class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Tambah Kamar Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </main>
            </section>    
        </main>
    </div>
@endsection

@push('addon-script')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize DataTable only if there are rooms
        @if($rooms->count() > 0)
        if (document.getElementById("selection-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            let table = null;

            const resetTable = function() {
                if (table) {
                    table.destroy();
                }

                // Initialize DataTable
                table = new simpleDatatables.DataTable("#selection-table", {
                    perPage: 10,
                    perPageSelect: [10, 25, 50],
                    searchable: true,
                    sortable: true,
                    fixedHeight: false,
                    columns: [
                        { select: 0, sortable: false }, // Checkbox column
                        { select: [1,2,3,4,5,6], sortable: true },
                        { select: 7, sortable: false } // Action column
                    ],
                    labels: {
                        placeholder: "Cari kamar...",
                        searchTitle: "Cari dalam tabel",
                        pageTitle: "Halaman {page}",
                        perPage: "entri per halaman",
                        noRows: "Tidak ada data ditemukan",
                        info: "Menampilkan {start} sampai {end} dari {rows} entri",
                        noResults: "Tidak ada hasil yang cocok dengan pencarian Anda"
                    }
                });

                // Function to handle master checkbox state
                const updateMasterCheckboxState = () => {
                    const masterCheckbox = document.querySelector('#masterCheckbox');
                    const childCheckboxes = document.querySelectorAll('.child-checkbox:not([style*="display: none"])');
                    
                    if (masterCheckbox && childCheckboxes.length > 0) {
                        const checkedCount = Array.from(childCheckboxes).filter(cb => cb.checked).length;
                        masterCheckbox.checked = checkedCount === childCheckboxes.length;
                        masterCheckbox.indeterminate = checkedCount > 0 && checkedCount < childCheckboxes.length;
                    }
                    
                    toggleQuickActionButton();
                };

                // Function to handle master checkbox click
                const handleMasterCheckboxClick = (e) => {
                    const childCheckboxes = document.querySelectorAll('.child-checkbox:not([style*="display: none"])');
                    childCheckboxes.forEach(checkbox => {
                        checkbox.checked = e.target.checked;
                    });
                    toggleQuickActionButton();
                };

                // Function to attach event listeners
                const attachEventListeners = () => {
                    // Master checkbox
                    const masterCheckbox = document.querySelector('#masterCheckbox');
                    if (masterCheckbox) {
                        masterCheckbox.removeEventListener('click', handleMasterCheckboxClick);
                        masterCheckbox.addEventListener('click', handleMasterCheckboxClick);
                    }

                    // Child checkboxes
                    const childCheckboxes = document.querySelectorAll('.child-checkbox');
                    childCheckboxes.forEach(checkbox => {
                        checkbox.removeEventListener('change', updateMasterCheckboxState);
                        checkbox.addEventListener('change', updateMasterCheckboxState);
                    });
                };

                // Attach event listeners after table operations
                table.on('datatable.init', attachEventListeners);
                table.on('datatable.page', attachEventListeners);
                table.on('datatable.sort', attachEventListeners);
                table.on('datatable.search', attachEventListeners);

                // Initial attachment
                setTimeout(attachEventListeners, 100);
            };

            resetTable();
        }
        @endif

        // Function to toggle quick action button visibility
        const toggleQuickActionButton = () => {
            const quickActionButton = document.getElementById('quickActionButton');
            const childCheckboxes = document.querySelectorAll('.child-checkbox:not([style*="display: none"])');
            
            // Check if any visible checkbox is selected
            const isAnyCheckboxSelected = Array.from(childCheckboxes).some(checkbox => checkbox.checked);
            
            // Show/hide quick action button based on selection
            if (isAnyCheckboxSelected) {
                quickActionButton.style.display = 'flex';
                quickActionButton.classList.remove('hidden');
            } else {
                quickActionButton.style.display = 'none';
                quickActionButton.classList.add('hidden');
            }
        };

        // Function to confirm bulk delete
        const confirmBulkDelete = () => {
            const checkedBoxes = document.querySelectorAll('.child-checkbox:checked');
            const roomIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (roomIds.length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Peringatan",
                    text: "Tidak ada kamar yang dipilih",
                    toast: true,
                    position: "top-end",
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${roomIds.length} kamar yang dipilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkDelete(roomIds);
                }
            });
        };

        // Function to perform bulk delete
        const performBulkDelete = (roomIds) => {
            const actionUrl = "{{ route('dashboard.room.bulkDelete') }}";
            const csrfToken = "{{ csrf_token() }}";

            // Show loading state
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ room_ids: roomIds })
            })
            .then(async response => {
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan pada server');
                }
                
                return data;
            })
            .then(data => {
                Swal.fire({
                    icon: data.success ? "success" : "error",
                    title: data.success ? "Berhasil!" : "Gagal!",
                    text: data.message,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: true
                }).then(() => {
                    if (data.success) {
                        location.reload();
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat menghapus data.',
                    showConfirmButton: true
                });
            });
        };

        // Initially hide the quick action button
        document.addEventListener('DOMContentLoaded', function() {
            const quickActionButton = document.getElementById('quickActionButton');
            if (quickActionButton) {
                quickActionButton.style.display = 'none';
                quickActionButton.classList.add('hidden');
            }
        });

        // Auto-hide alert messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => {
                            alert.remove();
                        }, 500);
                    }
                }, 5000);
            });
        });
    </script>
@endpush