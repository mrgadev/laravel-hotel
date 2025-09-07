@extends('layouts.dahboard_layout')

@section('title', 'User Management')

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
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex w-full gap-5 mx-auto justify-between items-center">
                <div class="flex flex-col gap-5 w-full p-6">
                    <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                        <a href="{{route('dashboard.home')}}" class="flex items-center">
                            <span class="material-symbols-rounded scale-75">home</span>
                        </a>
                        <span class="material-symbols-rounded">chevron_right</span>
                        <p>Data User</p>
                    </div>
                    <h1 class="text-white text-4xl font-medium">
                        Data User
                    </h1>
                </div>
                <a href="{{route('dashboard.users-management.create')}}" class="mt-10 px-5 py-3 border-2 rounded-md bg-primary-100 p-2 text-primary-700 hover:bg-white transition-all duration-75 hover:text-[#976033] text-base text-center">
                    <p class="flex"><span><i class="bi bi-plus-square mr-2"></i></span> Tambah</p>
                </a>
                <a href="#image-modal" id="quickActionButton" class="flex hidden items-center mt-10 px-5 py-[0.73rem] ring-2 ring-red-500 rounded-md bg-primary-100 p-2 text-red-500 hover:bg-white transition-all duration-75 hover:text-red-500 text-base text-center">
                    <p class="whitespace-nowrap">Quick Action</p>
                </a>
            </div>
        </div>
        <section class="container mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                    <table id="selection-table" class="">
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
                                        Name
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Role
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                        </svg>
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Access
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
                            @forelse ($users as $key => $user)
                                <tr class="cursor-pointer">
                                    <td scope="row" class="px-4 pe-0 py-4 font-medium text-gray-900 whitespace-nowrap">
                                         <input type="checkbox" name="selected_user_ids[]" class="cursor-pointer child-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600" value="{{ $user->id }}">
                                    </td>
                                    <td class="font-medium text-gray-900 whitespace-nowrap">{{$key + 1}}</td>
                                    <td class="font-medium text-gray-900 whitespace-nowrap">{{$user->name}}</td>
                                    <td class="font-medium text-gray-900 whitespace-nowrap">
                                        @foreach($user->roles as $role)
                                            {{ $role->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </td>
                                    <td class="font-medium text-gray-900 whitespace-nowrap">{{$user->access}}</td>
                                    <td class="flex items-center">
                                        <div class="mr-2">
                                            <a href="{{ route('dashboard.users-management.edit', $user->id) }}" class="py-2 px-2 border-2 rounded-md border-yellow-600 text-yellow-600 text-center">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </div>
                                        <form action="{{ route('dashboard.users-management.destroy', $user->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="p-1 border-2 rounded-md border-red-600 text-red-600 text-center">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                </table>
                </div>
            </main>
        </section>
    </main>
    <div id="image-modal" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <!-- Modal content -->
            <div class="relative max-w-3xl mx-auto bg-white rounded-lg" onclick="event.stopPropagation()">
                <!-- Close button -->
                <a href="#" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>

                <!-- Modal content -->
                <div class="p-16">
                    <form id="" action="{{ route('dashboard.users-management.updateRole') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-4">
                            <input type="hidden" name="user_ids[]" value="" id="selectedUserIds">
                            <label for="newStatus" class="block mb-2">
                                Pilih Role:
                                <br>
                                <small class="text-red-700">Silakan pilih peran (role) baru yang ingin Anda terapkan. Pilihan peran ini akan menentukan hak akses dan tanggung jawab pengguna di dalam sistem.</small>
                            </label>
                            <select name="role_id" id="newStatus" class="block w-full px-4 py-2 border rounded" required>
                                <option value="">--Pilih Role--</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex justify-center px-4 py-2 w-full text-sm font-medium text-white bg-primary-500 border border-transparent rounded-lg shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" onclick="return confirm('are you want to submit this data?')">
                                Ubah Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('input[name="selected_user_ids[]"]');
            const hiddenInput = document.getElementById('selectedUserIds');

            function updateHiddenInput() {
                const selectedValues = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);
                hiddenInput.value = selectedValues.join(',');
            }

            // Attach the event listener to each checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateHiddenInput);
            });

            // Run the update function on load in case any checkboxes are pre-selected
            updateHiddenInput();
        });
    </script>
@endpush
