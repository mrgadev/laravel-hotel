<aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-primary-100 border-2 border-primary-700 shadow-xl :shadow-none max-w-64 ease-nav-brand xl:ml-6 rounded-2xl xl:left-0 xl:translate-x-0 z-10" aria-expanded="false">
    <div class="h-19">
    <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times  text-slate-400 xl:hidden" sidenav-close></i>
    <a class="block px-8 py-6 m-0 whitespace-nowrap  text-primary-700" href="/" target="_blank">
        <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">UNS Inn Hotel</span>
    </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

    <div class="items-center block w-auto overflow-auto grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.home') ? 'bg-primary-500 text-white' : ''}}" href="{{route('dashboard.home')}}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                    <i class="relative top-0 text-sm leading-normal ni ni-tv-2"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Beranda</span>
                </a>
            </li>
            @role(['admin|staff'])
            {{-- Daftar TRansaksi Menu --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.transaction.*') ? 'bg-primary-500 text-white' : ''}}" href="{{route('dashboard.transaction.index')}}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal ni ni-credit-card"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Daftar Transaksi</span>
                </a>
            </li>
            {{-- Laporan Keuangan --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium justify-between transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.report.*') || Route::is('dashboard.penarikan-saldo.*') ? 'bg-primary-500 text-white' : ''}}" href="#" id="reportToggle">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="ni ni-collection"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease capitalize">Laporan Keuangan</span>
                    </div>
                    <span class="material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul id="reportSubmenu" class="px-4 mx-2 flex flex-col my-3 bg-primary-500 rounded-lg hidden">
                    <li>
                        <a class="py-2.7 text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white" href="{{route('dashboard.report.index')}}">
                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Reservasi Kamar</span>
                        </a>
                    </li>
                    <li>
                        <a class="py-2.7 text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white" href="{{route('dashboard.withdraw.index')}}">
                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Request Penarikan Saldo</span>
                        </a>
                    </li>
                </ul>
                </a>
            </li>
            {{-- Menu Kamar --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium justify-between transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.room_facility.*') || Route::is('dashboard.room.*') || Route::is('dashboard.accomodation-plan.*') || Route::is('dashboard.room-rules.*') ? 'bg-primary-500 text-white' : ''}}" href="#" id="roomToggle">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-base leading-normal bi bi-door-open"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Kamar</span>
                    </div>
                    <span class="material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul class="px-4 mx-2 flex flex-col  my-3 bg-primary-500 rounded-lg hidden" id="roomSubmenu">
                    <li>
                        <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.room.*')  ? 'font-medium' : ''}}" href="{{route('dashboard.room.index')}}">
                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Lihat Semua</span>
                        </a>
                    </li>
                    <li>
                        <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.room_facility.*') ? 'font-medium' : ''}}" href="{{route('dashboard.room-facilities.index')}}">

                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Fasilitas Kamar</span>
                        </a>
                    </li>
                    <li>
                        <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium  transition-all text-white {{Route::is('dashboard.accomodation-plan.*') ? 'font-medium' : ''}}" href="{{route('dashboard.accomodation-plan.index')}}">

                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Rencana Penginapan</span>
                        </a>
                    </li>
                    <li>
                        <a class="py-2.7 text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white" href="{{route('dashboard.room-rules.index')}}">
                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Pengaturan Kamar</span>
                        </a>
                    </li>
                </ul>

            </li>

            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.promo.*') ? 'bg-primary-500 text-white' : ''}}" href="{{route('dashboard.promo.index')}}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-base leading-normal bi bi-percent"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Promo</span>
                </a>
            </li>

            {{-- Menu Layanan Lainnya --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium justify-between transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.service.*') || Route::is('dashboard.service-category.*') ? 'bg-primary-500 text-white' : ''}}" href="#" id="serviceToggle">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="bi bi-grid-fill"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Layanan Lainnya</span>
                    </div>
                    <span class="material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul class="px-4 mx-2 flex flex-col  my-3 bg-primary-500 rounded-lg hidden" id="serviceSubmenu">
                    <li>
                        <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.service.*') ? 'font-medium' : ''}}" href="{{route('dashboard.service.index')}}">

                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Lihat Semua</span>
                        </a>
                    </li>
                    <li>
                        <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.service-category.*') ? 'font-medium' : ''}}" href="{{route('dashboard.service-category.index')}}">

                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Kategori Layanan Lainnya</span>
                        </a>
                    </li>
                </ul>
            </li>


            {{-- Menu Feedback --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium justify-between transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.feedback.*') || Route::is('dashboard.room-review.*') ? 'bg-primary-500 text-white' : ''}}" href="#" id="feedbackToggle">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="bi bi-chat-text-fill"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease capitalize">umpan balik</span>
                    </div>
                    <span class="material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul id="feedbackSubmenu" class="px-4 mx-2 flex flex-col my-3 bg-primary-500 rounded-lg hidden">
                    <li>
                        <a class="py-2.7 text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{ Route::is('dashboard.feedback') ? 'font-medium' : '' }}" href="{{ route('dashboard.feedback') }}">
                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Pesan</span>
                        </a>
                    </li>
                    <li>
                        <a class="py-2.7 text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white" href="{{route('dashboard.room-review.index')}}">
                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Ulasan Pengguna</span>
                        </a>
                    </li>
                </ul>
                </a>
            </li>
            {{-- Menu General Settings --}}
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium justify-between transition-all hover:bg-primary-500 hover:text-white {{ Route::is('dashboard.site.settings.*') || Route::is('dashboard.hotel_services.*') || Route::is('dashboard.hotel-facilities.*') || Route::is('dashboard.hotel-awards.*') || Route::is('dashboard.nearby-location.*') || Route::is('dashboard.faq.*') ? 'bg-primary-500 text-white' : ''}}" href="#" id="settingsToggle">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease capitalize">pengaturan situs</span>
                    </div>
                    <span class="material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul id="settingsSubmenu" class="px-4 mx-2 flex flex-col my-3 bg-primary-500 rounded-lg hidden">
                    <li>
                        <a class="py-2.7 text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white" href="{{route('dashboard.site.settings.edit')}}">
                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Pengaturan Umum</span>
                        </a>
                    </li>

                    <li>
                        <a class="py-2.7 text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white" href="{{route('dashboard.site.settings.frontpage.edit')}}">
                            <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Halaman Depan</span>
                        </a>
                    </li>
                    {{-- Hotel Menu --}}
                    <li class=" w-full">
                        <a class="py-2.7 px-3.5  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium justify-between transition-all bg-primary-500 text-white {{Route::is('dashboard.hotel-facilities.*') || Route::is('dashboard.nearby-location.*') || Route::is('dashboard.faq.*') ? 'bg-primary-500 text-white' : ''}}" href="#" id="hotelToggle">
                            <div class="flex items-center">
                                {{-- <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                                    <i class="bi bi-buildings-fill"></i>
                                </div> --}}
                                <span class="duration-300 opacity-100 pointer-events-none ease">Pengaturan Lainnya</span>
                            </div>
                            <span class="material-symbols-rounded">keyboard_arrow_down</span>
                        </a>
                        <ul class="px-4 mx-2 flex flex-col  my-3 bg-primary-500 rounded-lg hidden" id="hotelSubmenu">
                            <li>
                                <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.hotel-facilities.*') ? 'font-medium' : ''}}" href="{{route('dashboard.hotel-facilities.index')}}">
                                    <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Fasilitas Hotel</span>
                                </a>
                            </li>
                            <li>
                                <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.hotel_services.*') ? 'font-medium' : ''}}" href="{{route('dashboard.hotel-services.index')}}">
                                    <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Layanan Hotel</span>
                                </a>
                            </li>
                            <li>
                                <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.hotel-awards.*') ? 'font-medium' : ''}}" href="{{route('dashboard.hotel-awards.index')}}">
                                    <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Penghargaan Hotel</span>
                                </a>
                            </li>
                            <li>
                                <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.nearby-location.*') ? 'font-medium' : ''}}" href="{{route('dashboard.nearby-location.index')}}">

                                    <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">Lokasi Terdekat</span>
                                </a>
                            </li>
                            <li>
                                <a class="py-2.7  text-sm ease-nav-brand my-0 flex items-center whitespace-nowrap rounded-lg font-medium transition-all text-white {{Route::is('dashboard.faq.*') ? 'font-medium' : ''}}" href="{{route('dashboard.faq.index')}}">
                                    <span class="px-2.5 ml-1 duration-300 opacity-100 pointer-events-none ease">FAQ</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                </a>
            </li>
            @endrole
            @role('admin')
            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.users-management.*') ? 'bg-primary-500 text-white' : ''}}" href="{{route('dashboard.users-management.index')}}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="bi bi-person-fill-gear scale-110"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Kelola Pengguna</span>
                </a>
            </li>
                <li class="w-full mt-4">
                    <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Account pages</h6>
                </li>
            @endrole

            @role('user')
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.user.bookings') || Route::is('dashboard.user.bookings.detail') ? 'bg-primary-500 text-white' : ''}}" href="{{route('dashboard.user.bookings')}}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-base leading-normal bi bi-door-open"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Reservasi</span>
                    </a>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-primary-700  text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium transition-all hover:bg-primary-500 hover:text-white {{Route::is('dashboard.saldo.index') ? 'bg-primary-500 text-white' : ''}}" href="{{route('dashboard.saldo.index')}}">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-base leading-normal bi bi-clock-history"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Riwayat Saldo</span>
                    </a>
                </li>

                <li class="w-full mt-4">
                    <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Account pages</h6>
                </li>
            @endrole

            <li class="mt-0.5 w-full">
                <a class="py-2.7 text-primary-700 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-medium transition-all hover:bg-primary-500 hover:text-white" href="{{route('dashboard.profile.edit')}}">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal ni ni-single-02"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Profile</span>
                </a>
            </li>

            <li class="mt-0.5 w-full ">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-primary-700 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 ">
                <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                    <i class="relative top-0 text-lg leading-normal bi bi-box-arrow-right"></i>
                </div>
                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Logout</span>
                </button>
            </form>
            </li>
        </ul>
    </div>
</aside>
@push('addon-script')
    <script>
        const roomToggle = document.getElementById('roomToggle');
        const roomSubmenu = document.getElementById('roomSubmenu');

        roomToggle.addEventListener('click', function(){
            roomSubmenu.classList.toggle('hidden');
        });

        const hotelToggle = document.getElementById('hotelToggle');
        const hotelSubmenu = document.getElementById('hotelSubmenu');

        hotelToggle.addEventListener('click', function(){
            hotelSubmenu.classList.toggle('hidden');
        });

        document.getElementById('serviceToggle').addEventListener('click', function() {
            const submenu = document.getElementById('serviceSubmenu');
            submenu.classList.toggle('hidden');
        });

        document.getElementById('settingsToggle').addEventListener('click', function() {
            const settingssubmenu = document.getElementById('settingsSubmenu');
            settingssubmenu.classList.toggle('hidden');
        });

        document.getElementById('feedbackToggle').addEventListener('click', function() {
            const feedbacksubmenu = document.getElementById('feedbackSubmenu');
            feedbacksubmenu.classList.toggle('hidden');
        });

        document.getElementById('reportToggle').addEventListener('click', function() {
            const reportSubmenu = document.getElementById('reportSubmenu');
            reportSubmenu.classList.toggle('hidden');
        });
    </script>
@endpush
