@extends('layouts.dahboard_layout')

@section('title', 'Tambah Kamar')

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto">
            <div class="flex flex-col gap-5 w-full p-6">
                <div class="flex items-center gap-1 bg-primary-100 p-2 text-primary-700 rounded-lg w-fit text-sm">
                    <a href="{{route('dashboard.home')}}" class="flex items-center">
                        <span class="material-symbols-rounded scale-75">home</span>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <a href="{{route('dashboard.room.index')}}" class="flex items-center hover:underline">
                        <p>Daftar Kamar</p>
                    </a>
                    <span class="material-symbols-rounded">chevron_right</span>
                    <p>Buat Kamar</p>
                </div>
        
                <h1 class="text-white text-4xl font-medium">
                    Buat Kamar
                </h1>
            </div>
        </div>
        
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-6 mt-2 bg-white rounded-xl shadow-lg">
                    <form action="{{route('dashboard.room.store')}}" method="POST" enctype="multipart/form-data" id="room-form">
                        @method('POST')
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block mb-2 font-medium text-gray-700 text-md">Nama Kamar</label>
                                    <input placeholder="Nama Kamar" type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="off" 
                                           class="block w-full px-3 py-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="price" class="block mb-2 font-medium text-gray-700 text-md">Harga per Malam (Rp)</label>
                                    <input placeholder="0" type="text" name="price" id="price" value="{{ old('price') }}" autocomplete="off"
                                           class="block w-full px-3 py-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('price') border-red-500 @enderror">
                                    <input type="hidden" name="price_numeric" id="price_numeric" value="{{ old('price') }}">
                                    @error('price')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="total_rooms" class="block mb-2 font-medium text-gray-700 text-md">Total Kamar</label>
                                    <input type="number" name="total_rooms" id="total_rooms" value="{{ old('total_rooms') }}" autocomplete="off" min="1"
                                           class="block w-full px-3 py-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('total_rooms') border-red-500 @enderror">
                                    @error('total_rooms')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Photo Upload Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Gambar Kamar</h2>
                            <div class="space-y-4">
                                <!-- Drag and Drop Area -->
                                <div id="photo-dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary-400 transition-colors cursor-pointer">
                                    <div class="space-y-4">
                                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-lg font-medium text-gray-900">Drag & drop gambar di sini</p>
                                            <p class="text-sm text-gray-500">atau klik untuk memilih file</p>
                                        </div>
                                        <div class="flex justify-center">
                                            <button type="button" class="inline-flex items-center px-4 py-2 border border-primary-600 text-primary-600 rounded-md hover:bg-primary-50 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Pilih Gambar
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="hidden">
                                </div>
                                
                                <p class="text-sm text-gray-500">
                                    Format yang didukung: PNG, JPG, JPEG, WebP, AVIF. Maksimal 2MB per file.
                                </p>
                                
                                @error('photos')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                @error('photos.*')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror

                                <!-- Photo Preview Grid -->
                                <div id="photo-preview-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-6 hidden"></div>
                            </div>
                        </div>

                        <!-- Room Facilities -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Fasilitas Kamar</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($room_facilities as $room_facility)
                                    <div class="relative">
                                        <input type="checkbox" 
                                               name="room_facilities_id[]" 
                                               id="facility_{{ $room_facility->id }}" 
                                               value="{{ $room_facility->id }}" 
                                               class="peer sr-only"
                                               {{ in_array($room_facility->id, old('room_facilities_id', [])) ? 'checked' : '' }}>
                                        <label for="facility_{{ $room_facility->id }}" 
                                               class="flex items-center gap-3 p-3 rounded-lg border-2 border-gray-200 cursor-pointer transition-all hover:border-primary-300 peer-checked:border-primary-500 peer-checked:bg-primary-50">
                                            <span class="material-icons-round text-gray-500 peer-checked:text-primary-600">{{ $room_facility->icon }}</span>
                                            <span class="text-gray-700 peer-checked:text-primary-700 font-medium">{{ $room_facility->name }}</span>
                                            <div class="ml-auto">
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded peer-checked:border-primary-500 peer-checked:bg-primary-500 relative">
                                                    <svg class="w-3 h-3 text-white absolute top-0.5 left-0.5 hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('room_facilities_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi Kamar</h2>
                            <div>
                                <label for="description" class="block mb-2 font-medium text-gray-700 text-md">Deskripsi</label>
                                <textarea name="description" id="description" rows="8" 
                                          class="block w-full px-3 py-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('dashboard.room.index') }}" 
                               class="inline-flex justify-center px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex justify-center px-6 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Kamar
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </section>
    </main>
@endsection

@push('addon-script')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });

        // Price management
        const priceInput = document.getElementById('price');
        const priceNumericInput = document.getElementById('price_numeric');

        // Format price with thousand separators
        function formatPrice(value) {
            // Remove all non-digit characters
            const numericValue = value.replace(/\D/g, '');
            // Add thousand separators (dots)
            return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Get numeric value without separators
        function getNumericPrice(value) {
            return value.replace(/\./g, '');
        }

        // Handle price input formatting
        priceInput.addEventListener('input', function(e) {
            const cursorPosition = e.target.selectionStart;
            const oldValue = e.target.value;
            const newValue = formatPrice(e.target.value);
            
            e.target.value = newValue;
            priceNumericInput.value = getNumericPrice(newValue);
            
            // Adjust cursor position after formatting
            const newCursorPosition = cursorPosition + (newValue.length - oldValue.length);
            e.target.setSelectionRange(newCursorPosition, newCursorPosition);
        });

        // Format initial value if exists
        if (priceInput.value) {
            priceInput.value = formatPrice(priceInput.value);
            priceNumericInput.value = getNumericPrice(priceInput.value);
        }

        // Photo management
        let selectedFiles = [];
        const photoInput = document.getElementById('photos');
        const dropzone = document.getElementById('photo-dropzone');
        const previewGrid = document.getElementById('photo-preview-grid');

        // File persistence functionality
        const STORAGE_KEY = 'room_create_files';

        // Save files to sessionStorage
        function saveFilesToStorage() {
            const fileData = selectedFiles.map(file => ({
                name: file.name,
                size: file.size,
                type: file.type,
                lastModified: file.lastModified
            }));
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(fileData));
        }

        // Restore files from sessionStorage on page load
        function restoreFilesFromStorage() {
            const savedFiles = sessionStorage.getItem(STORAGE_KEY);
            if (savedFiles && document.referrer.includes(window.location.pathname)) {
                // Only restore if coming from same page (validation error)
                try {
                    const fileData = JSON.parse(savedFiles);
                    if (fileData.length > 0) {
                        // Show message that files are being restored
                        const restoreMessage = document.createElement('div');
                        restoreMessage.className = 'bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4';
                        restoreMessage.innerHTML = `
                            <strong>Info:</strong> Gambar yang dipilih sebelumnya telah dipulihkan (${fileData.length} file).
                        `;
                        dropzone.parentNode.insertBefore(restoreMessage, dropzone);
                        
                        // Auto-hide restore message
                        setTimeout(() => {
                            restoreMessage.remove();
                        }, 5000);
                    }
                } catch (e) {
                    console.error('Error restoring files:', e);
                }
            }
        }

        // Clear storage on successful form submission
        function clearFileStorage() {
            sessionStorage.removeItem(STORAGE_KEY);
        }

        // Drag and drop functionality
        dropzone.addEventListener('click', () => photoInput.click());
        
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-primary-400', 'bg-primary-50');
        });

        dropzone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-primary-400', 'bg-primary-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-primary-400', 'bg-primary-50');
            
            const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
            addFiles(files);
        });

        photoInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            addFiles(files);
        });

        function addFiles(files) {
            files.forEach(file => {
                if (file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) { // 2MB limit
                    selectedFiles.push(file);
                }
            });
            updateFileInput();
            renderPreview();
            saveFilesToStorage();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            renderPreview();
            saveFilesToStorage();
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            photoInput.files = dt.files;
        }

        function renderPreview() {
            if (selectedFiles.length === 0) {
                previewGrid.classList.add('hidden');
                return;
            }

            previewGrid.classList.remove('hidden');
            previewGrid.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" 
                             class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <button type="button" 
                                    onclick="removeFile(${index})"
                                    class="opacity-0 group-hover:opacity-100 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                            Baru
                        </div>
                        <div class="absolute bottom-2 left-2 right-2">
                            <p class="text-xs text-white bg-black bg-opacity-50 px-2 py-1 rounded truncate">
                                ${file.name}
                            </p>
                        </div>
                    `;
                    previewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Form validation
        document.getElementById('room-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = priceNumericInput.value;
            const totalRooms = document.getElementById('total_rooms').value;
            
            if (!name) {
                e.preventDefault();
                Swal.fire('Error!', 'Nama kamar tidak boleh kosong', 'error');
                return;
            }
            
            if (!price || price < 0) {
                e.preventDefault();
                Swal.fire('Error!', 'Harga harus diisi dan tidak boleh negatif', 'error');
                return;
            }
            
            if (!totalRooms || totalRooms < 1) {
                e.preventDefault();
                Swal.fire('Error!', 'Total kamar minimal 1', 'error');
                return;
            }

            if (selectedFiles.length === 0) {
                e.preventDefault();
                Swal.fire('Error!', 'Minimal upload 1 gambar kamar', 'error');
                return;
            }
            
            // Check if at least one facility is selected
            const facilities = document.querySelectorAll('input[name="room_facilities_id[]"]:checked');
            if (facilities.length === 0) {
                e.preventDefault();
                Swal.fire('Error!', 'Pilih minimal satu fasilitas kamar', 'error');
                return;
            }

            clearFileStorage();
        });

        document.addEventListener('DOMContentLoaded', function() {
            restoreFilesFromStorage();
        });

        // Make removeFile function global
        window.removeFile = removeFile;
    </script>
@endpush
