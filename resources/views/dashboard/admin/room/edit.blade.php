@extends('layouts.dahboard_layout')

@section('title', 'Ubah Kamar')

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
                    <p>Ubah Kamar</p>
                </div>
        
                <h1 class="text-white text-4xl font-medium">
                    Ubah Kamar: {{ $room->name }}
                </h1>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="container mx-auto mb-4 px-6">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mx-auto mb-4 px-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
            </div>
        @endif
        
        <section class="container px-6 mx-auto">
            <main class="col-span-12 md:pt-0">
                <div class="p-6 mt-2 bg-white rounded-xl shadow-lg">
                    <form action="{{route('dashboard.room.update', $room->id)}}" method="POST" enctype="multipart/form-data" id="room-form">
                        @method('PUT')
                        @csrf
                        
                        <!-- Basic Info Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block mb-2 font-medium text-gray-700 text-md">Nama Kamar</label>
                                    <input placeholder="Nama Kamar" type="text" name="name" id="name" value="{{old('name', $room->name)}}" autocomplete="off" 
                                           class="block w-full px-3 py-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="price" class="block mb-2 font-medium text-gray-700 text-md">Harga per Malam (Rp)</label>
                                    <input placeholder="0" type="text" name="price" value="{{old('price', $room->price)}}" id="price" autocomplete="off"
                                           class="block w-full px-3 py-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('price') border-red-500 @enderror">
                                    <input type="hidden" name="price_numeric" id="price_numeric" value="{{old('price', $room->price)}}">
                                    @error('price')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="total_rooms" class="block mb-2 font-medium text-gray-700 text-md">Total Kamar</label>
                                    <input type="number" name="total_rooms" id="total_rooms" autocomplete="off" 
                                           value="{{old('total_rooms', $room->total_rooms)}}" min="1"
                                           class="block w-full px-3 py-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('total_rooms') border-red-500 @enderror">
                                    @error('total_rooms')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-sm text-gray-500 mt-1">Kamar tersedia saat ini: {{ $room->available_rooms }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Photos Management Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Manajemen Gambar Kamar</h2>
                            
                            <!-- Existing Photos -->
                            @if($room->photos && is_array($room->photos) && count($room->photos) > 0)
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-700 mb-3">Gambar Yang Ada ({{ count($room->photos) }})</h3>
                                    <div id="existing-photos-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($room->photos as $index => $photo)
                                            <div class="relative group existing-photo" data-photo-path="{{ $photo }}" data-photo-index="{{ $index }}">
                                                <img src="{{ asset($photo) }}" 
                                                     alt="Room photo {{ $index + 1 }}" 
                                                     class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                    <div class="opacity-0 group-hover:opacity-100 flex space-x-2 transition-opacity">
                                                        <button type="button" 
                                                                onclick="openImageModal('{{ asset($photo) }}', 'Room Photo {{ $index + 1 }}')"
                                                                class="bg-white text-gray-800 px-3 py-1 rounded text-sm hover:bg-gray-100">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                        </button>
                                                        <button type="button" 
                                                                onclick="markExistingPhotoForRemoval('{{ $photo }}', {{ $index }})"
                                                                class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="absolute top-2 right-2 hidden remove-indicator" id="remove-indicator-{{ $index }}">
                                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Akan Dihapus</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="photos-to-remove-container"></div>
                                </div>
                            @endif

                            <!-- Add New Photos -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-700">Tambah Gambar Baru</h3>
                                
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

                                <!-- New Photos Preview Grid -->
                                <div id="new-photos-preview-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-6 hidden"></div>
                            </div>
                        </div>

                        <!-- Facilities Section -->
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
                                               {{ $room->room_facility->contains($room_facility->id) ? 'checked' : '' }}>
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

                        <!-- Description Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi Kamar</h2>
                            <div>
                                <label for="description" class="block mb-2 font-medium text-gray-700 text-md">Deskripsi</label>
                                <textarea name="description" id="description" rows="8" 
                                          class="block w-full px-3 py-3 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $room->description) }}</textarea>
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
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </section>
    </main>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" 
                    class="absolute top-4 right-4 z-10 bg-white rounded-full p-2 hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="/placeholder.svg" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            <div id="modalTitle" class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-3 py-2 rounded"></div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize CKEditor
        let editor;
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
            .then(createdEditor => {
                editor = createdEditor;
            })
            .catch(error => {
                console.error(error);
            });

        // Photo management variables
        let selectedNewFiles = [];
        let photosToRemove = [];
        
        const photoInput = document.getElementById('photos');
        const dropzone = document.getElementById('photo-dropzone');
        const newPhotosPreviewGrid = document.getElementById('new-photos-preview-grid');
        const photosToRemoveContainer = document.getElementById('photos-to-remove-container');

        // Price management variables
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

        // File persistence functionality
        const STORAGE_KEY = 'room_edit_files_{{ $room->id }}';

        // Save files to sessionStorage
        function saveFilesToStorage() {
            const fileData = selectedNewFiles.map(file => ({
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
                            <strong>Info:</strong> Gambar baru yang dipilih sebelumnya telah dipulihkan (${fileData.length} file).
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
            addNewFiles(files);
        });

        photoInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            addNewFiles(files);
        });

        // Add new files
        function addNewFiles(files) {
            files.forEach(file => {
                if (file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) { // 2MB limit
                    selectedNewFiles.push(file);
                }
            });
            updateNewFileInput();
            renderNewPhotosPreview();
            saveFilesToStorage();
        }

        // Remove new file
        function removeNewFile(index) {
            selectedNewFiles.splice(index, 1);
            updateNewFileInput();
            renderNewPhotosPreview();
            saveFilesToStorage();
        }

        // Update file input for new files
        function updateNewFileInput() {
            const dt = new DataTransfer();
            selectedNewFiles.forEach(file => dt.items.add(file));
            photoInput.files = dt.files;
        }

        // Render new photos preview
        function renderNewPhotosPreview() {
            if (selectedNewFiles.length === 0) {
                newPhotosPreviewGrid.classList.add('hidden');
                return;
            }

            newPhotosPreviewGrid.classList.remove('hidden');
            newPhotosPreviewGrid.innerHTML = '';

            selectedNewFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="New photo ${index + 1}" 
                             class="w-full h-32 object-cover rounded-lg border-2 border-green-200">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <button type="button" 
                                    onclick="removeNewFile(${index})"
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
                    newPhotosPreviewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Mark existing photo for removal
        function markExistingPhotoForRemoval(photoPath, index) {
            const photoDiv = document.querySelector(`[data-photo-index="${index}"]`);
            const indicator = document.getElementById(`remove-indicator-${index}`);
            
            if (photosToRemove.includes(photoPath)) {
                // Remove from removal list
                photosToRemove = photosToRemove.filter(path => path !== photoPath);
                photoDiv.style.opacity = '1';
                indicator.classList.add('hidden');
            } else {
                // Add to removal list
                photosToRemove.push(photoPath);
                photoDiv.style.opacity = '0.5';
                indicator.classList.remove('hidden');
            }
            
            updateRemovalInputs();
        }

        // Update hidden inputs for photo removal
        function updateRemovalInputs() {
            photosToRemoveContainer.innerHTML = '';
            
            photosToRemove.forEach(photo => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_photos[]';
                input.value = photo;
                photosToRemoveContainer.appendChild(input);
            });
        }

        // Image modal functions
        function openImageModal(src, title = '') {
            document.getElementById('modalImage').src = src;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Form validation
        document.getElementById('room-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = priceNumericInput.value; // Use numeric value for validation
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
            
            // Check if at least one facility is selected
            const facilities = document.querySelectorAll('input[name="room_facilities_id[]"]:checked');
            if (facilities.length === 0) {
                e.preventDefault();
                Swal.fire('Error!', 'Pilih minimal satu fasilitas kamar', 'error');
                return;
            }

            // Check if there will be photos remaining after removal
            const existingPhotosCount = {{ $room->photos ? count($room->photos) : 0 }};
            const remainingExistingPhotos = existingPhotosCount - photosToRemove.length;
            const totalPhotosAfterUpdate = remainingExistingPhotos + selectedNewFiles.length;
            
            if (totalPhotosAfterUpdate === 0) {
                e.preventDefault();
                Swal.fire('Error!', 'Kamar harus memiliki minimal 1 gambar', 'error');
                return;
            }

            clearFileStorage();
        });

        // Auto-hide alert messages
        document.addEventListener('DOMContentLoaded', function() {
            restoreFilesFromStorage();
            
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

        // Make functions global
        window.removeNewFile = removeNewFile;
        window.markExistingPhotoForRemoval = markExistingPhotoForRemoval;
        window.openImageModal = openImageModal;
        window.closeImageModal = closeImageModal;
    </script>
@endpush
