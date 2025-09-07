@extends('layouts.dahboard_layout')

@section('title', 'Pengaturan Situs')

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex flex-col gap-5 w-full p-6">
                <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                    <a href="{{route('dashboard.home')}}" class="flex items-center">
                        <span class="material-symbols-rounded scale-75">home</span>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Pengaturan Situs</p>
                </div>
                <h1 class="text-white text-4xl font-medium">
                    Pengaturan Situs
                </h1>
            </div>
        </div>
        <section class="container px-6 mx-auto">
            <div class="container px-6 mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        {{-- <h1 class="text-xl font-medium text-primary-700">Pengaturan Dasar</h1> --}}
                        <form action="{{route('dashboard.site.settings.update', $site_setting->id)}}" method="POST">
                            @csrf
                        @method('PUT')
                            {{-- <div class="grid lg:grid-cols-2 gap-5 my-5">
                                <div class="flex flex-col gap-5">
                                    <h2>Tagline Situs</h2>
                                <textarea name="tagline" id="tagline" cols="30" rows="10">{!!$site_setting->tagline!!}</textarea>
                                </div>
                                <div class="flex flex-col gap-5">
                                    <h2>Deskripsi Situs</h2>
                                    <textarea name="description" id="description" cols="30" rows="10">{!!$site_setting->description!!}</textarea>
                                </div>
                                <div class="flex flex-col gap-5">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="number" name="phone" class="rounded-lg" value="{{$site_setting->phone}}">
                                </div>
                                <div class="flex flex-col gap-5">
                                    <label for="phone">Teks Nomor Telepon</label>
                                    <input type="text" name="phone_text" value="{{$site_setting->phone_text}}" class="rounded-lg">
                                </div>
                            </div> --}}
                            <div class="flex flex-col gap-5">
                                <h1 class="text-xl font-medium text-primary-700">Kontak dan Alamat</h1>
                                <div class="grid lg:grid-cols-2 gap-3">
                                    <div class="flex flex-col gap-5">
                                        <label for="">Nomor Telepon</label>
                                        <input type="text" name="phone" value="{{$site_setting->phone}}" class="rounded-lg">

                                        @if ($errors->has('phone'))
                                        <p  class="text-red-500 mt-3 text-sm">{{$errors->first('phone')}}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-5">
                                        <label for="">Teks Nomor Telepon</label>
                                        <input type="text" name="phone_text" value="{{$site_setting->phone_text}}" class="rounded-lg">

                                        @if ($errors->has('phone_text'))
                                        <p  class="text-red-500 mt-3 text-sm">{{$errors->first('phone_text')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label for="">Tautan Google Maps</label>
                                    <div class="flex flex-col gap-5">
                                        <input type="text" name="maps_link" value="{{$site_setting->maps_link}}" class="rounded-lg">
                                        @if ($errors->has('maps_link'))
                                        <p  class="text-red-500 mt-3 text-sm">{{$errors->first('maps_link')}}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-5">
                                        <label for="">Alamat Lengkap</label>
                                        <textarea name="address" id="address" cols="30" rows="10">{!!$site_setting->address!!}</textarea>
                                        @if ($errors->has('address'))
                                        <p  class="text-red-500 mt-3 text-sm">{{$errors->first('address')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <h1 class="text-xl font-medium text-primary-700 mt-5">Pengaturan Lanjutan</h1>
                            <div class="grid lg:grid-cols-3 gap-5 my-5">
                                <div class="flex flex-col gap-5">
                                    <label for="">Tenggat Waktu Transaksi</label>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="payment_deadline" value="{{$site_setting->payment_deadline}}" class="rounded-lg">
                                        <p>Jam</p>
                                    </div>
                                    @if ($errors->has('payment_deadline'))
                                    <p  class="text-red-500 mt-3 text-sm">{{$errors->first('payment_deadline')}}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-5">
                                    <label for="">Waktu Check In</label>
                                    <div class="flex items-center gap-3">
                                        <input type="time" name="checkin_time" value="{{$site_setting->checkin_time}}" class="rounded-lg">
                                        {{-- <p>Jam</p> --}}
                                        @if ($errors->has('checkin_time'))
                                        <p  class="text-red-500 mt-3 text-sm">{{$errors->first('checkin_time')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col gap-5">
                                    <label for="">Waktu Checkout</label>
                                    <div class="flex items-center gap-3">
                                        <input type="time" name="checkout_time" value="{{$site_setting->checkout_time}}" class="rounded-lg">
                                        {{-- <p>Jam</p> --}}
                                        @if ($errors->has('checkout_time'))
                                        <p  class="text-red-500 mt-3 text-sm">{{$errors->first('checkout_time')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- <button type="submit" class="btn btn-primary">Kirim</button> --}}
                            <button type="submit" class="mt-5 px-5 py-3 rounded-lg bg-primary-500 text-white transition-all hover:bg-primary-700">Perbarui data</button>
                        </form>
                    </div>

                    <div class="p-10 mt-5 bg-white rounded-xl shadow-lg">
                        <div class="mb-5 flex items-center justify-between">
                            <h1 class="text-xl font-medium text-primary-700">Partner Kami</h1>
                            <a href="#image-modal" id="" class="bg-primary-100 text-primary-700 px-5 py-2 rounded-lg border-2 border-primary-700">
                                <p class="whitespace-nowrap">Tambah Baru</p>
                            </a>
                        </div>
                        <table id="selection-table" class="">
                            <thead>
                                <tr>
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
                                            Logo
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
                                            Tautan
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
                                @forelse ($partners as $key => $partner)
                                    <tr class="cursor-pointer">
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$key + 1}}</td>
                                        <td class="">
                                            <img src="{{url($partner->logo)}}" alt="" class=" w-10 object-cover object-top transition duration-500 mb-2">
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$partner->name}}</td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">{{$partner->link}}</td>
                                        <td class="flex items-center">
                                            <div class="mr-2">
                                                <a href="{{route('dashboard.partners.edit', $partner)}}" class="editItemButton py-2 px-2 border-2 rounded-md border-primary-600 text-primary-500 text-center transition-all hover:bg-primary-500 hover:text-white" data-id="{{ $partner->id }}" data-name="{{ $partner->name }}" data-link="{{$partner->link}}" data-logo="{{$partner->logo}}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>

                                            </div>
                                            <form action="{{route('dashboard.partners.destroy', $partner)}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('DELETE')
                                                <button class="deleteItemButton p-2 border-2 rounded-md border-red-600 text-red-600 text-center transition-all hover:bg-red-600 hover:text-white">
                                            </form>

                                        <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        <div class="mb-5 flex items-center justify-between">
                            <h1 class="text-xl font-medium text-primary-700">Daftar Bank</h1>
                            <a href="#image-modal2" id="" class="bg-primary-100 text-primary-700 px-5 py-2 rounded-lg border-2 border-primary-700">
                                <p class="whitespace-nowrap">Tambah Baru</p>
                            </a>
                        </div>
                        <table id="selection-table2" class="">
                            <thead>
                                <tr>
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
                                    <th class="flex items-center">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banks as $key => $bank)

                                <tr class="cursor-pointer">
                                    <td>
                                        {{$key + 1}}
                                    </td>
                                    <td class="">
                                        <p>{{$bank->name}}</p>
                                    </td>
                                    <td class="flex items-center">
                                        <div class="mr-2">
                                            <a href="{{route('dashboard.bank.edit', $bank)}}" class="editItemButton py-2 px-2 border-2 rounded-md border-primary-600 text-primary-500 text-center transition-all hover:bg-primary-500 hover:text-white" data-id="{{ $bank->id }}" data-name="{{ $bank->name }}" data-link="{{$bank->link}}" data-logo="{{$bank->logo}}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                        </div>
                                        <form action="{{route('dashboard.bank.destroy', $bank)}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('DELETE')
                                            <button class="deleteItemButton p-2 border-2 rounded-md border-red-600 text-red-600 text-center transition-all hover:bg-red-600 hover:text-white">
                                        </form>

                                        <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </section>
    </main>
    <div id="image-modal" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <!-- Modal content -->
            <div class="p-10 mt-2 bg-white rounded-xl shadow-lg"  onclick="event.stopPropagation()">
                <a href="#" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>

                <h3 class="flex items-center gap-1 text-primary-700 font-medium text-2xl">
                    Buat Partner
                    <br>
                </h3>
                <form action="{{ route('dashboard.partners.store') }}" class="mt-3" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="flex items-center gap-3">
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <label for="" class="flex items-center gap-1 text-primary-700 font-light text-sm">Nama Partner</label>
                            <input type="text" name="name" value="" placeholder="Nama partner" class="bg-primary-100 border border-primary-700 rounded-lg text-primary-700" id="">
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <label for="" class="flex items-center gap-1 text-primary-700 font-light text-sm">Link Partner</label>
                            <input type="text" name="link" value="" placeholder="Link Partner" class="bg-primary-100 border border-primary-700 rounded-lg text-primary-700" id="">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 w-full mt-8">
                        <label for="" class="flex items-center gap-1 text-primary-700 font-light text-sm">Logo Partner</label>
                        <input type="file" name="logo" value="" id="logo" placeholder="Link Partner" class="bg-primary-100 border border-primary-700 rounded-lg text-primary-700" id="">
                    </div>

                    <div class="flex items-center gap-2 mt-8">
                        <a href="#" class="px-5 py-2 border border-primary-500 text-primary-500 rounded-full hover:bg-primary-500 hover:text-white transition-all" id="">Batal</a>
                        <button type="submit" class="bg-primary-500 text-white px-5 py-2 rounded-full hover:bg-primary-700 transition-all">Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="image-modal2" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <!-- Modal content -->
            <div class="p-10 mt-2 bg-white rounded-xl shadow-lg"  onclick="event.stopPropagation()">
                <a href="#" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>

                <h3 class="flex items-center gap-1 text-primary-700 font-bold text-2xl">
                    Tambahkan Bank
                    <br>
                </h3>
                <form action="{{ route('dashboard.bank.store') }}" class="mt-3" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="flex items-center gap-3">
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <label for="" class="flex items-center gap-1 text-primary-700 font-light text-sm">Nama Bank</label>
                            <input autocomplete="off" type="text" name="name" value="" placeholder="Nama Bank" class="bg-primary-100 border border-primary-700 rounded-lg text-primary-700" id="">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-8">
                        <a href="#" class="px-5 py-2 border border-primary-500 text-primary-500 rounded-full hover:bg-primary-500 hover:text-white transition-all" id="">Batal</a>
                        <button type="submit" class="bg-primary-500 text-white px-5 py-2 rounded-full hover:bg-primary-700 transition-all">Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('addon-script')
    <script>
        $(document).ready(function() {
            $('#regency_id').select2();
        })
    </script>
    <!-- Panggil CKEditor versi 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#tagline'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#address'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        $(document).ready(function() {
            $('#icon').iconpicker();

            $('#iconPickerBtn').on('click', function() {
                $('#icon').iconpicker('show');
            });
        });
    </script>
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
            const actionUrl = "{{ route('dashboard.room.bulkDelete') }}";

            deleteButton.addEventListener('click', function () {
                const roomIds = Array.from(document.querySelectorAll('input[name="room_ids[]"]:checked'))
                    .map(checkbox => checkbox.value);

                if (roomIds.length === 0) {
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
                    body: JSON.stringify({ room_ids: roomIds })
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
