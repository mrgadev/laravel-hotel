<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="false">
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
        <div class="sm:hidden lg:block items-center gap-3">
            @yield('breadcrumb')
        </div>
        <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto gap-5">
            <div class="flex items-center gap-1 md:ml-auto md:pr-4 bg-primary-100 text-primary-700 p-2.5 rounded-lg text-sm">
                <span class="material-symbols-rounded">event</span>{{ now()->translatedFormat('l, d F Y') }}
            </div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                <!-- online builder btn  -->
                <!-- <li class="flex items-center">
                <a class="inline-block px-8 py-2 mb-0 mr-4 text-xs font-bold text-center text-blue-500 uppercase align-middle transition-all ease-in bg-transparent border border-blue-500 border-solid rounded-lg shadow-none cursor-pointer leading-pro hover:-translate-y-px active:shadow-xs hover:border-blue-500 active:bg-blue-500 active:hover:text-blue-500 hover:text-blue-500 tracking-tight-rem hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent" target="_blank" href="https://www.creative-tim.com/builder/soft-ui?ref=navbar-dashboard&amp;_ga=2.76518741.1192788655.1647724933-1242940210.1644448053">Online Builder</a>
                </li> -->
                <li class="flex items-center">
                    <div class="relative">
                        <button onclick="document.getElementById('dropdownAvatar').classList.toggle('hidden')"
                                class="flex items-center gap-3 px-3 py-1.5 text-sm bg-primary-100 text-primary-700 font-medium rounded-full focus:ring-4 focus:ring-gray-300"
                                type="button">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 rounded-full overflow-hidden">
                                <img src="#" class="w-12 h-12 rounded-full object-cover" alt="User avatar">
                                {{-- <img src="{{ url(Auth::user()->avatar) }}" class="w-12 h-12 rounded-full object-cover" alt="User avatar"> --}}
                            </div>
                            <span class="text-gray-700 pe-2">{{Auth::user()->name}}</span>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdownAvatar"
                            class="absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                            <div class="px-4 py-3 text-sm text-gray-900 flex flex-col gap-2">
                                <div class="font-medium truncate">{{Auth::user()->email}}</div>
                                <div class="text-xs px-2 py-1 rounded-md bg-primary-500 text-white w-fit">{{ucfirst(Auth::user()->roles->first()->name)}}</div>
                            </div>
                            <ul class="py-2 text-sm text-gray-700">

                                <li>
                                    <a href="{{route('dashboard.profile.edit')}}" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                                </li>
                            </ul>
                            <div class="py-2">
                                <form action="{{route('logout')}}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="block px-4 py-2 text-sm text-red-700 hover:bg-gray-100">Keluar</button>
                                    {{-- <a href="#" class="block px-4 py-2 text-sm text-red-700 hover:bg-gray-100">Keluar</a> --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="flex items-center pl-4 xl:hidden">
                <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand" sidenav-trigger>
                    <div class="w-4.5 overflow-hidden">
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                    <i class="ease relative block h-0.5 rounded-sm bg-white transition-all"></i>
                    </div>
                </a>
                </li>
                <li class="flex items-center px-4">
                
                </li>

                <!-- notifications -->

                <li class="relative flex items-center pr-2">
                <p class="hidden transform-dropdown-show"></p>
                

                
            </ul>
        </div>
    </div>
</nav>
