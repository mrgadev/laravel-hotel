@php
    $site_settings = App\Models\SiteSettings::where('id',1)->first();
@endphp
<footer class="">
    <div class="w-screen px-12 lg:px-36 py-14 bg-primary-100 flex justify-between flex-col lg:flex-row">
        <div class="flex flex-col gap-5">
            <h2 class="text-2xl text-primary-700">UNS Inn Hotel</h2>
            <div class="text-primary-800 flex flex-col gap-3">
                {{-- <a href="#" class="underline-offset-0">Jl. Nama Jalan</a> --}}
                <a href="tel:0271641756" class="underline-offset-0"><i class="bi bi-telephone"></i> (0271) 641756</a>
                <a href="https://wa.me/6285102390002?text=Hallo%20UNS%20Inn,%20saya%20mau%20..." target="_blank" class="underline-offset-0"><i class="bi bi-whatsapp"></i> +62 851-0239-0002</a>
                <a href="{{$site_settings->maps_link}}" class="underline-offset-0">{!!$site_settings->address!!}</a>
            </div>
            {{-- <p >Jl. H. R. Rasuna Said No.4 Blok Kav. B<br> Kuningan, Setia Budi, Kota Jakarta Selatan<br>DKI Jakarta 12910</p> --}}
            <div class="flex items-center gap-5 text-2xl text-primary-800">
                <a href="https://www.instagram.com/unsinn_solo/profilecard/?igsh=b3ZiczU4emRxNXVm" target="_blank" class=""><i class="bi bi-instagram"></i></a>
                <a href="https://www.tiktok.com/@unsinnhotel?_t=8ru6SPtvf3X&_r=1" target="_blank"><i class="bi bi-tiktok"></i></a>
            </div>
        </div>
    
        <div class="grid lg:grid-cols-2 gap-8 mt-5">
            <div class="flex flex-col gap-5">
                <h2 class="text-primary-700 text-xl font-medium">Perusahaan</h2>
                <div class="flex flex-col gap-1 font-light">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Karir</a>
                    <a href="#">Kontak</a>
                    <a href="#"></a>
                    <a href=""></a>
                </div>
            </div>
    
            <div class="flex flex-col gap-5">
                <h2 class="text-primary-700 text-xl font-medium">Dukungan</h2>
                <div class="flex flex-col gap-1 font-light">
                    <a href="#">Bantuan</a>
                    <a href="#">Knowledge Base</a>
                </div>
            </div>
        </div>
    </div>

    <div class="w-screen px-12 lg:px-36 py-8 flex items-center bg-primary-700 text-white justify-between">
        <p>&copy; {{date("Y")}} UNS Inn Hotel</p>
        <a href="#">Kembali ke atas</a>
    </div>
</footer>