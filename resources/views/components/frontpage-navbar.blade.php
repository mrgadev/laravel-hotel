<nav class="fixed w-full px-12 lg:px-36 z-20 bg-white border-b border-primary-300 duration-500 transition-all flex items-center justify-between py-6 text-primary-500" id="mainNavbar">
    <a href="{{route('frontpage.index')}}" class="text-lg font-medium">UNS Inn Hotel</a>
    <div class="lg:flex gap-8 font-light hidden">
        <a href="{{route('frontpage.index')}}" class="hover:font-medium {{(Route::is('frontpage.index') ? 'font-medium' : '')}}">Beranda</a>
        <a href="{{route('frontpage.rooms')}}" class="hover:font-medium {{(Route::is('frontpage.rooms') ? 'font-medium' : '')}}">Kamar</a>
        <a href="{{route('frontpage.promo')}}" class="hover:font-medium {{(Route::is('frontpage.promo') ? 'font-medium' : '')}}">Promo</a>
        <a href="{{route('frontpage.services')}}" class="hover:font-medium {{(Route::is('frontpage.services') ? 'font-medium' : '')}}">Layanan Lainnya </a>
        <a href="{{route('frontpage.about')}}" class="hover:font-medium {{(Route::is('frontpage.about') ? 'font-medium' : '')}}">Tentang Kami</a>
    </div>
    <ion-icon name="menu-outline" class="lg:hidden text-4xl" id="openMobileMenu"></ion-icon>
    @guest
    <div class=" items-center gap-3 auth-button hidden lg:flex">
        <a href="{{route('register')}}" class="bg-primary-500 text-white px-5 py-2 rounded-full hover:bg-white transition-all">Daftar</a>
        <a href="{{route('login')}}" class="px-5 py-2 border border-primary-500 text-primary-500 rounded-full hover:bg-primary-500 hover:text-white transition-all">Masuk</a>
    </div>
    @endguest

    @auth
            <div class="hidden lg:flex items-center gap-2">
                @if(Auth::user()->avatar)
                <img src="{{url(Auth::user()->avatar)}}" class="w-14 h-14 object-cover rounded-full" alt="">
                @else
                <span class="material-symbols-rounded">account_circle</span>
                @endif
                <div class="flex flex-col gap-1">
                    <button class="text-lg" id="toggleUserMenu">{{Auth::user()->name}}</button>
                    <p class="user-role text-xs px-2 py-1 bg-primary-700 text-white rounded-md w-fit">{{ucfirst(Auth::user()->roles->first()->name)}}</p>
                </div>
                <div id="userMenu" class="bg-white absolute right-28 top-28 border border-primary-700 rounded-xl p-3 hidden">
                    <div class="flex flex-col gap-2">
                        <a href="{{route('dashboard.home')}}" class="text-primary-700">Dashboard</a>
                        <hr>
                        <form action="{{route('logout')}}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="text-red-600 z-20">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>

        @endauth
</nav>
<nav class="duration-500 bg-white w-screen h-screen fixed hidden top-0 left-0 right-0 z-30 px-12" id="mobileMenu">
    <div class="flex items-center justify-between py-6 text-primary-500">
        <a href="{{route('frontpage.index')}}" class="text-lg font-medium">UNS Inn Hotel</a>
        <span class="material-symbols-rounded cursor-pointer" id="closeMobileMenu">close</span>
    </div>

    <div class="flex flex-col gap-8 mt-8 font-light">
        <a href="{{route('frontpage.index')}}" class="hover:font-medium {{(Route::is('frontpage.index') ? 'font-medium' : '')}}">Beranda</a>
        <a href="{{route('frontpage.rooms')}}" class="hover:font-medium {{(Route::is('frontpage.rooms') ? 'font-medium' : '')}}">Kamar</a>
        <a href="{{route('frontpage.promo')}}" class="hover:font-medium {{(Route::is('frontpage.promo') ? 'font-medium' : '')}}">Promo</a>
        <a href="{{route('frontpage.services')}}" class="hover:font-medium {{(Route::is('frontpage.services') ? 'font-medium' : '')}}">Layanan Lainnya </a>
        <a href="{{route('frontpage.about')}}" class="hover:font-medium {{(Route::is('frontpage.about') ? 'font-medium' : '')}}">Tentang Kami</a>
        <a href="#" class="px-5 py-3 rounded-full bg-primary-500 text-white w-fit">Masuk / Daftar</a>
    </div>
</nav>
