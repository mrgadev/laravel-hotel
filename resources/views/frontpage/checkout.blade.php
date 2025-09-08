@extends('layouts.frontpage')
@push('addons-style')
@vite('resources/css/app.css')
<script src="https://cdn.tailwindcss.com"></script>
{{-- <style>
    #mainNavbar.scrolled {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index:1;
        background-color: white;
        /* border-radius: 25px; */
        /* border: 2px solid #976033; */
        width: 100%;
        padding: 1.5rem 9rem;
        transition: all 0.5s ease;
    }

    #mainNavbar.scrolled p {
        color: #976033;
    }
    #mainNavbar.scrolled a {
        color: #333;
        transition: all 0.4s ease;
    }
    #mainNavbar.scrolled a:hover {
        color: #976033;
        transition: all 0.4s ease;
    }

    #mainNavbar.scrolled .auth-button a:first-child {
        background-color: #976033;
        color: #fff
    }

    #mainNavbar.scrolled .auth-button a:nth-child(2) {
        border: 2px solid #976033;
        color: #976033;
        transition: all 0.4s ease;
    }

    #mainNavbar.scrolled .auth-button a:nth-child(2):hover {
        background-color: #976033;
        color: #fff;
        transition: all 0.4s ease;
    }



    @media (max-width: 1024px) {
        #mainNavbar.scrolled {
            padding: 1.5rem 3rem;
        }

        #mainNavbar.scrolled [name="menu-outline"] {
            color: #976033;
        }
    }
</style> --}}
@endpush
@section('title', 'Pemesanan Kamar')
@section('main')
@include('components.frontpage-navbar')

<header>
    <div class="lg:px-36 px-12 w-screen pt-36 flex items-center gap-1 text-sm lg:text-base">
        <a href="{{route('frontpage.index')}}" class="flex items-center text-primary-500">
            <span class="material-symbols-rounded">home</span>
        </a>
        <span class="material-symbols-rounded text-primary-500">chevron_right</span>
        <a href="{{route('frontpage.rooms')}}" class="flex items-center text-primary-500">
            Daftar Kamar
        </a>
        <span class="material-symbols-rounded text-primary-500">chevron_right</span>
        <p class="text-primary-700">Detail Kamar</p>
    </div>
</header>

<section class="mx-12 lg:mx-36">
    <form action="#" method="POST" class="mx-auto px-12 2xl:px-0" id="payment-form">
    @csrf
    @method('POST')
    <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
      <div class="min-w-0 flex-1">
        <div class="">
          <h2 class="text-4xl text-primary-700 mb-8">Detail Pembayaran</h2>
            <div class="mb-10">
              <label for="your_name" class="mb-2 block text-base font-medium text-primary-700"> Your name </label>
              @auth
                  <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                  <input type="text" id="your_name" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required autocomplete="off" name="name" value="{{Auth::user()->name}}" />
              @endauth
              @guest
                  <input type="text" id="your_name" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required autocomplete="off" name="name" />
              @endguest
            </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label for="your_email" class="mb-2 block text-base font-medium text-primary-700"> Your email* </label>
              @auth
                  <input type="email" id="your_email" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required autocomplete="off" name="email" value="{{Auth::user()->email}}"  />
              @endauth
              @guest
                <input type="email" id="your_email" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required autocomplete="off" name="email" />
              @endguest
            </div>

            <div>
                <label for="phone-input-3" class="mb-2 block text-base font-medium text-primary-700"> Phone Number </label>
                @auth
                    <input type="text" id="phone-input" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" type="number" required autocomplete="off" name="phone" value="{{Auth::user()->phone}}"  />
                @endauth
                @guest
                    <input type="text" id="phone-input" class="block w-full py-3 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" type="number" required autocomplete="off" name="phone" />
                @endguest
            </div>
          </div>
        </div>

        <div class="space-y-4 mt-10">
            <h3 class="text-2xl text-primary-700">Rencana Penginapan</h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @forelse ($accomodation_plans as $accomodation_plan)
                    <div class="rounded-lg border border-gray-200 p-4 ps-4 w-auto">
                        <div class="flex items-start">
                            <div class="flex h-5 items-center w-auto">
                                <input
                                    id="spa_{{$accomodation_plan->id}}"
                                    type="checkbox"
                                    name="accomodation_plan_id[]"
                                    value="{{ $accomodation_plan->id }}"
                                    class="h-4 w-4 border-gray-300 bg-white text-primary-600 rounded-md focus:ring-2 focus:ring-primary-600"
                                    data-price="{{ $accomodation_plan->price }}"
                                    onchange="updateAccommodationPlans()"/>
                            </div>

                            <div class="ms-4 text-sm">
                                <label for="{{$accomodation_plan->id}}" class="leading-none text-primary-700">
                                    Rp.{{ number_format($accomodation_plan->price, 0, ',', '.') }} - {{ $accomodation_plan->name }}
                                </label>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No accommodation plans available.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-10">
            <h3 class="text-2xl text-primary-700">Pilih Promo</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 mt-5">
                @foreach ($promos as $promo)
                    @php
                        $now = now();
                        $isExpired = $now > $promo->end_date;
                    @endphp
                    <div class="relative {{ $isExpired ? 'opacity-50' : '' }}">
                        <input type="checkbox"
                            id="promo-{{ $promo->id }}"
                            name="promo_id[]"
                            value="{{ $promo->id }}"
                            data-discount="{{ $promo->amount }}"
                            class="promo-checkbox hidden peer"
                            {{ $isExpired ? 'disabled' : '' }}
                            onchange="updateAccommodationPlans()">
                        <label for="promo-{{ $promo->id }}" class="inline-flex items-center justify-between w-full p-5 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary-500 hover:text-gray-600 peer-checked:text-gray-600 hover:bg-gray-50">
                            <div class="block">
                                <p class="w-full text-base text-primary-700">{{ $promo->name }}</p>
                                <p class="w-full text-sm text-primary-700">Potongan harga: {{ $promo->amount }}%</p>
                                @if($isExpired)
                                    <span class="absolute top-2 right-2 text-red-500 text-xs">Kadaluarsa</span>
                                @endif
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
      </div>

        <div class="mt-6 w-full space-y-6 rounded-t-lg shadow-lg sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md mb-10">
            <div class="flow-root">
                <div class="relative flex h-56 w-auto cursor-pointer">
                    <a href="#image-modal" class="block w-full">
                        <img src="{{ $room->thumbnail_url }}" 
                        alt="{{ $room->name ?? 'Kamar' }}" 
                        class="w-full h-64 object-cover rounded-t-xl relative"
                        onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                    </a>
                </div>

                <!-- Modal -->
                <div id="image-modal" class="fixed inset-0 z-50 bg-black bg-opacity-60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 target:opacity-100 target:pointer-events-auto">
                    <a href="#" class="fixed inset-0 flex items-center justify-center p-4">
                        <div class="relative max-w-3xl mx-auto" onclick="event.stopPropagation()">
                            <div>
                                <img src="{{ $room->thumbnail_url }}" 
                                alt="{{ $room->name ?? 'Kamar' }}" 
                                class="w-full h-64 object-cover rounded-t-xl relative"
                                onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                            </div>
                        </div>
                    </a>
                </div>

                <div class="p-5">
                    <h3 class="mt-5 text-xl font-semibold text-gray-900 mb-2">{{ $room->name }}</h3>
                    <div class="items-center justify-between gap-4 py-3 mt-3">
                        <p class="text-base font-light text-gray-900 mb-2">
                            <input type="date" name="check_in" value="{{session('check_in')}}" hidden>
                            <input type="date" name="check_out" value="{{session('check_out')}}" hidden>
                            <input type="text" name="room_id" value="{{$room->id}}" hidden>
                            {{\Carbon\Carbon::parse(session('check_in'))->isoFormat('dddd, D MMM Y')}} -
                            {{\Carbon\Carbon::parse(session('check_out'))->isoFormat('dddd, D MMM YYYY')}}
                            @php
                                $totalDays = date_diff(date_create(session('check_in')), date_create(session('check_out')));
                            @endphp

                            {{-- {{ \Carbon\Carbon::parse(session('check_in'))->format('D, M d, Y') }} -  --}}
                            {{-- {{ \Carbon\Carbon::parse(session('check_out'))->format('D, M d, Y') }} --}}
                        </p>
                    </div>
                </div>

                <div class="-my-3 px-5 divide-y divide-gray-200">
                    <dl class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-base font-normal text-gray-500">Harga Kamar</dt>
                        <div>
                            <!-- Harga kamar -->
                            <dd id="room-price" class="text-base font-medium text-gray-900">Rp.{{ number_format($room->price, 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <dl class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-base font-normal text-gray-500">Durasi</dt>
                        <div>
                            <!-- Harga kamar -->
                            <dd id="room-price" class="text-base font-medium text-gray-900">{{$totalDays->format("%a hari")}}</dd>
                        </div>
                    </dl>
                    <dl class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-base font-normal text-gray-500">Total Harga Kamar</dt>
                        <div>
                            <!-- Harga kamar -->
                            <dd id="room-price" class="text-base font-medium text-gray-900">Rp.{{ number_format($room->price * $totalDays->format("%a"), 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <dl class="flex flex-col gap-4 py-3">
                        <div class="text-left">
                            <dt class="text-base font-medium text-gray-900">Rencana Penginapan</dt>
                        </div>
                        <!-- Tempat untuk menampilkan rencana penginapan yang dipilih -->
                        <div id="accommodation-plans"></div>
                    </dl>

                    <dl class="flex items-center justify-between gap-4 py-3">

                        <dt class="text-base font-normal text-gray-500">Total Diskon <span></span></dt>
                        <div>
                            <!-- Tempat untuk menampilkan total diskon yang diterapkan -->
                        </div>
                        <dd id="discount-total" class="text-base font-medium text-red-600">Rp. 0</dd>
                    </dl>

                    <dl class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-base font-bold text-gray-900">Total</dt>
                        <div>
                            <!-- Total harga keseluruhan -->
                            <dd id="total-price" class="text-base font-bold text-gray-900"></dd>
                        </div>
                    </dl>

                    <dl class="flex flex-col gap-3 py-3">
                        <p>Metode pembayaran</p>
                        <div class="flex items-center gap-5">
                            <div class="flex items-center gap-2 rounded-lg bg-primary-100 text-primary-700 w-fit px-5 py-2 has-[:checked]:border-primary-700  has-[:checked]:border-2 transition-all hover:cursor-pointer">
                                <input type="radio" name="payment_method" id="Cash" value="Cash" class="hidden">
                                {{-- <img src="{{Storage::url($room_facility->icon)}}" class="w-5 h-5" alt=""> --}}
                                {{-- <span class="material-icons-round">{{$room_facility->icon}}</span> --}}
                                <label for="Cash" class="hover:cursor-pointer">Cash</label>
                            </div>
                            <div class="flex items-center gap-2 rounded-lg bg-primary-100 text-primary-700 w-fit px-5 py-2 has-[:checked]:border-primary-700  has-[:checked]:border-2 transition-all hover:cursor-pointer">
                                <input type="radio" name="payment_method" id="Online" value="Online" class="hidden">
                                {{-- <img src="{{Storage::url($room_facility->icon)}}" class="w-5 h-5" alt=""> --}}
                                {{-- <span class="material-icons-round">{{$room_facility->icon}}</span> --}}
                                <label for="Online" class="hover:cursor-pointer">Online</label>
                            </div>
                            {{-- Temporarily hidden credit payment option
                            @auth
                                <div class="flex flex-col items-center gap-2 rounded-lg bg-primary-100 text-primary-700 w-fit px-5 py-2 has-[:checked]:border-primary-700  has-[:checked]:border-2 transition-all hover:cursor-pointer">
                                    <input type="radio" name="payment_method" id="Credit" value="Credit" class="hidden">
                                    <label for="Credit" class="hover:cursor-pointer">Saldo</label>
                                </div>
                            @endauth
                            --}}
                        </div>
                    </dl>

                    {{-- Temporarily hidden credit description section
                    @auth
                        <div id="credit-description" style="display: none">
                            <dl class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-base font-normal text-gray-500">Total Saldo Anda <span></span></dt>
                                <dd class="text-base font-medium" data-saldo="{{ $saldo->amount }}">
                                    Rp. {{ number_format($saldo->amount, 0, ',', '.') }}
                                    <small id="saldo-message">
                                        <!-- Pesan error akan ditampilkan disini oleh JavaScript -->
                                    </small>
                                </dd>
                            </dl>
                        </div>
                        <div class="hidden flex items-center justify-center gap-2" id="add-payment">
                            <small class="flex items-center justify-center gap-5 mt-5">
                                <div class="flex items-center gap-2 text-center rounded-lg bg-primary-100 text-primary-700 w-fit px-5 py-2 has-[:checked]:border-primary-700  has-[:checked]:border-2 transition-all hover:cursor-pointer">
                                    <input type="radio" name="payment_method" id="addCash" value="addCash" class="hidden">
                                    <label for="addCash" class="hover:cursor-pointer">Tambah Menggunakan Cash</label>
                                </div>
                                <div class="flex items-center gap-2 text-center rounded-lg bg-primary-100 text-primary-700 w-fit px-5 py-2 has-[:checked]:border-primary-700  has-[:checked]:border-2 transition-all hover:cursor-pointer">
                                    <input type="radio" name="payment_method" id="addFlip" value="addFlip" class="hidden">
                                    <label for="addFlip" class="hover:cursor-pointer">Tambah Menggunakan Flip</label>
                                </div>
                            </small>
                        </div>
                    @endauth
                    --}}
                {{-- </div>
            </div> --}}

            <div class="space-y-3 p-5">
                <button type="submit" id="payment-button" class="hidden flex w-full items-center bg-[#5b3a1f] justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white" style="background-color: #5b3a1f">
                    Proceed to Payment
                </button>
                {{-- <p class="text-sm font-normal text-gray-500 pb-5">
                    One or more items in your cart require an account.
                    <a href="#" title="" class="font-medium text-primary-700 underline hover:no-underline">Sign in or create an account now.</a>.
                </p> --}}
            </div>
        </div>
    </div>
  </form>
</section>

@include('components.frontpage-footer')

@endsection
@push('addons-script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const openMobileMenu = document.getElementById('openMobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        openMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.remove('hidden');
        })

        closeMobileMenu.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
        })

        const openBookingForm = document.getElementById('openBookingForm');
        const closeBookingForm = document.getElementById('closeBookingForm');
        const bookingForm = document.getElementById('bookingForm');

        openBookingForm.addEventListener('click', function() {
            bookingForm.classList.remove('hidden');
        });

        closeBookingForm.addEventListener('click', function() {
            bookingForm.classList.add('hidden');
        });

        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });


        const roomButtonLeft = document.getElementById('roomButtonLeft');
        const roomButtonRight = document.getElementById('roomButtonRight');
        const roomsContainer = document.getElementById('roomsContainer');

        roomButtonLeft.addEventListener('click', function() {
            roomsContainer.scrollLeft -= 250;
            roomsContainer.style.transition = 'all 0.4s ease';
        });

        roomButtonRight.addEventListener('click', function() {
            roomsContainer.scrollLeft += 250;
            roomsContainer.style.transition = 'all 0.4s ease';
        });

        const toggleUserMenu = document.getElementById('toggleUserMenu');
        const userMenu = document.getElementById('userMenu');

        toggleUserMenu.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        })

        function calculateRoomTotal() {
            const checkInDate = new Date("{{ session('check_in') }}");
            const checkOutDate = new Date("{{ session('check_out') }}");
            const roomPricePerNight = parseFloat(document.getElementById("room-price").textContent.replace(/[^0-9]/g, ""));

            // Hitung jumlah malam
            const nights = Math.max(0, (checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            const roomTotal = nights * roomPricePerNight;

            return roomTotal;
        }

        function updateAccommodationPlans() {
            const selectedPlansContainer = document.getElementById("accommodation-plans");
            const totalPriceElement = document.getElementById("total-price");
            const discountTotalElement = document.getElementById("discount-total");

            let accommodationTotal = 0;
            let selectedPlans = "";

            // Tampilkan rencana penginapan yang dipilih
            document.querySelectorAll('input[name="accomodation_plan_id[]"]:checked').forEach((checkbox) => {
                const price = parseFloat(checkbox.getAttribute("data-price") || "0");
                const name = checkbox.closest('.rounded-lg').querySelector('label').textContent.trim().split('-')[1];

                accommodationTotal += price;
                selectedPlans += `
                    <div class="flex justify-between">
                        <span class="plan-name">${name.trim()}</span>
                        <span class="plan-price">Rp. ${price.toLocaleString("id-ID")}</span>
                    </div>
                `;
            });

            selectedPlansContainer.innerHTML = selectedPlans || "<div class='flex justify-between'><span class='plan-name'>-</span><span class='plan-price'>Rp. 0</span></div>";

            // Hitung total diskon
            const roomTotal = calculateRoomTotal();
            let discountTotal = 0;

            document.querySelectorAll('input[name="promo_id[]"]:checked').forEach((checkbox) => {
                const discountPercentage = parseFloat(checkbox.getAttribute("data-discount") || "0");
                discountTotal += (roomTotal * (discountPercentage / 100));
            });

            // Update tampilan total diskon
            discountTotalElement.textContent = `- Rp. ${discountTotal.toLocaleString("id-ID")}`;

            // Hitung dan update total akhir
            const totalAmount = roomTotal + accommodationTotal - discountTotal;
            totalPriceElement.textContent = `Rp. ${totalAmount.toLocaleString("id-ID")}`;
        }
    </script>
    <script>
        $(document).ready(function() {
            const paymentForm = $('#payment-form');
            const paymentButton = $('#payment-button');
            const onlineRoute = "{{route('payment.online')}}";
            const cashRoute = "{{route('payment.cash')}}";

            $('#Cash').change(function() {
                if($(this).is(':checked')) {
                    // Change to Cash payment
                    paymentButton.text('Bayar tunai');
                    paymentButton.show();
                    paymentForm.attr('action', cashRoute);
                } else {
                    paymentButton.hide();
                }
            });

            $('#Online').change(function() {
                if($(this).is(':checked')) {
                    // Change to Online payment
                    paymentButton.text('Bayar Online');
                    paymentButton.show();
                    paymentForm.attr('action', onlineRoute);
                } else {
                    paymentButton.hide();
                }
            });
        });
    </script>
@endpush