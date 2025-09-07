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
                    <p>Pengaturan Halaman Depan</p>
                </div>
                <h1 class="text-white text-4xl font-medium">
                    Pengaturan Halaman Depan
                </h1>
            </div>
        </div>
        <section class="container px-6 mx-auto">
            <div class="container px-6 mx-auto">
                <main class="col-span-12 md:pt-0">
                    <div class="p-10 mt-2 bg-white rounded-xl shadow-lg">
                        <h1 class="text-xl font-medium text-primary-700">Bagian Hero</h1>
                        <form action="{{route('dashboard.site.settings.frontpage.update', $frontpage_site_setting->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="grid lg:grid-cols-2 gap-5 my-5">
                                <div class="flex flex-col gap-5">
                                    <h2>Tagline Situs <span class="text-red-700">*</span></h2>
                                    <textarea name="tagline" id="tagline" cols="30" rows="10">{!!$frontpage_site_setting->tagline!!}</textarea>
                                    @if ($errors->has('tagline'))
                                        <p class="text-red-500 mb-3 text-sm">{{$errors->first('tagline')}}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-5">
                                    <h2>Deskripsi Situs <span class="text-red-700">*</span></h2>
                                    <textarea name="description" id="description" cols="30" rows="10">{!!$frontpage_site_setting->description!!}</textarea>
                                    @if ($errors->has('description'))
                                        <p class="text-red-500 mb-3 text-sm">{{$errors->first('description')}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col gap-5">
                                <h1 class="text-xl font-medium text-primary-700">Bagian Layanan Kami</h1>
                                <label for="our_service_title">Judul <span class="text-red-700">*</span></label>
                                <div class="flex flex-col gap-5">
                                    <input type="text" name="our_service_title" id="our_service_title" value="{{$frontpage_site_setting->our_service_title}}" class="rounded-lg">
                                </div>
                                @if ($errors->has('our_service_title'))
                                    <p class="text-red-500 mb-3 text-sm">{{$errors->first('our_service_title')}}</p>
                                @endif
                            </div>

                            {{-- <div class="grid lg:grid-cols-2 gap-5 my-5"> --}}
                            <div class="flex flex-col gap-5 my-5">
                                <h1 class="text-xl font-medium text-primary-700">Bagian Lokasi Kami</h1>
                                <div class="flex flex-col gap-3">
                                    <label for="our_location_title">Judul <span class="text-red-700">*</span></label>
                                    <input type="text" name="our_location_title" id="our_location_title" value="{{$frontpage_site_setting->our_location_title}}" class="rounded-lg">
                                    @if ($errors->has('our_location_title'))
                                        <p class="text-red-500 mb-3 text-sm">{{$errors->first('our_location_title')}}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label for="ourLocationDesc">Deskripsi</label>
                                    <textarea type="text" name="our_location_desc" id="ourLocationDesc" class="rounded-lg">{!!$frontpage_site_setting->our_location_desc!!} </textarea>
                                </div>
                            </div>

                            <div class="flex flex-col gap-5 my-5">
                                <h1 class="text-xl font-medium text-primary-700">Bagian Fasilitas Kami</h1>
                                <div class="flex flex-col gap-3">
                                    <label for="our_facilities_title">Judul <span class="text-red-700">*</span></label>
                                    <input type="text" name="our_facilities_title" id="our_facilities_title" value="{{$frontpage_site_setting->our_facilities_title}}" class="rounded-lg">
                                    @if ($errors->has('our_facilities_title'))
                                        <p class="text-red-500 mb-3 text-sm">{{$errors->first('our_facilities_title')}}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col gap-5 my-5">
                                <h1 class="text-xl font-medium text-primary-700">Bagian Testimonial</h1>
                                <div class="flex flex-col gap-3">
                                    <label for="testimonial_title">Judul <span class="text-red-700">*</span></label>
                                    <input type="text" name="testimonial_title" value="{{$frontpage_site_setting->testimonial_title}}" id="testimonial_title" class="rounded-lg">
                                    @if ($errors->has('testimonial_title'))
                                        <p class="text-red-500 mb-3 text-sm">{{$errors->first('testimonial_title')}}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="grid lg:grid-cols-2 gap-5 my-5"> --}}
                            <div class="flex flex-col gap-5 my-5">
                                <h1 class="text-xl font-medium text-primary-700">Bagian Penghargaan</h1>
                                <div class="flex flex-col gap-3">
                                    <label for="award_title">Judul <span class="text-red-700">*</span></label>
                                    <input type="text" name="award_title" id="award_title" value="{{$frontpage_site_setting->award_title}}" class="rounded-lg">
                                    @if ($errors->has('award_title'))
                                        <p class="text-red-500 mb-3 text-sm">{{$errors->first('award_title')}}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label for="award_desc">Deskripsi</label>
                                    <textarea type="text" name="award_desc" id="award_desc" class="rounded-lg">{!!$frontpage_site_setting->award_desc!!} </textarea>

                                </div>
                            </div>

                            <div class="flex flex-col gap-5 my-5">
                                <h1 class="text-xl font-medium text-primary-700">Bagian Call to Action</h1>
                                <div class="grid lg:grid-cols-3 gap-5">
                                    <div class="flex flex-col gap-3">
                                        <label for="cta_text">Judul <span class="text-red-700">*</span></label>
                                        <input type="text" name="cta_text" id="cta_text" value="{{$frontpage_site_setting->cta_text}}" class="rounded-lg">
                                        @if ($errors->has('cta_text'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('cta_text')}}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label for="cta_button_text">Teks pada Tombol <span class="text-red-700">*</span></label>
                                        <input type="text" name="cta_button_text" id="cta_button_text" value="{{$frontpage_site_setting->cta_button_text}}" class="rounded-lg">
                                        @if ($errors->has('cta_button_text'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('cta_button_text')}}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label for="cta_button_link">Tautan pada Tombol <span class="text-red-700">*</span></label>
                                        <input type="text" name="cta_button_link" id="cta_button_link" value="{{$frontpage_site_setting->cta_button_link}}" class="rounded-lg">
                                        @if ($errors->has('cta_button_link'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('cta_button_link')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="flex flex-col gap-5 my-5">
                                <h1 class="text-xl font-medium text-primary-700">Gambar Penunjang</h1>
                                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-5">
                                    <div class="flex flex-col gap-3">
                                        <img src="{{url($frontpage_site_setting->hero_cover)}}" alt="" class="h-40 object-cover">
                                        <label for="hero_cover">Bagian Hero</label>
                                        <input type="file" name="hero_cover" id="hero_cover" class="rounded-lg">
                                        @if ($errors->has('hero_cover'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('hero_cover')}}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <img src="{{url($frontpage_site_setting->service_image)}}" alt="" class="h-40 object-cover">
                                        <label for="service_image">Bagian Layanan Kami</label>
                                        <input type="file" name="service_image" id="service_image" class="rounded-lg">
                                        @if ($errors->has('service_image'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('service_image')}}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <img src="{{url($frontpage_site_setting->faq_illustration)}}" alt="" class="h-40 object-cover">
                                        <label for="faq_illustration">Bagian FAQ</label>
                                        <input type="file" name="faq_illustration" id="faq_illustration" class="rounded-lg">
                                        @if ($errors->has('faq_illustration'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('faq_illustration')}}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <img src="{{url($frontpage_site_setting->cta_cover)}}" alt="" class="h-40 object-cover">
                                        <label for="cta_cover">Bagian Call to Action</label>
                                        <input type="file" name="cta_cover" id="cta_cover" class="rounded-lg">
                                        @if ($errors->has('cta_cover'))
                                            <p class="text-red-500 mb-3 text-sm">{{$errors->first('cta_cover')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- </div> --}}

                            {{-- <button type="submit" class="btn btn-primary">Kirim</button> --}}
                            <button type="submit" class="mt-5 px-5 py-3 rounded-lg bg-primary-500 text-white transition-all hover:bg-primary-700">Perbarui data</button>
                        </form>
                    </div>
                </main>
            </div>
        </section>
    </main>
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
            .create(document.querySelector('#ourLocationDesc'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#award_desc'))
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

@endpush
