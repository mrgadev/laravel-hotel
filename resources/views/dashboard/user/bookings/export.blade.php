<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $transaction->invoice }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url({{ storage_path('fonts/DejaVuSans.ttf') }}) format('truetype');
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .text-primary-700 { color: #976033; }
        .bg-green-100 { background-color: #dcfce7; }
        .border-green-700 { border: 1px solid #15803d; }
        .text-green-700 { color: #15803d; }
        .bg-yellow-100 { background-color: #fef9c3; }
        .border-yellow-700 { border: 1px solid #a16207; }
        .text-yellow-700 { color: #a16207; }
        .text-red-700 { color: #b91c1c; }
        .bg-primary-100 { background-color: #f3e8d9; }
        .container { width: 100%; }
        .grid { display: table; width: 100%; }
        .grid-cols-2 { display: table; width: 100%; }
        .grid-cols-2 > div { display: table-cell; width: 50%; vertical-align: top; }
        .flex { display: block; }
        .flex-col { display: block; }
        .items-center { vertical-align: middle; }
        .justify-between { display: table; width: 100%; }
        .justify-between > * { display: table-cell; }
        .gap-1 { margin-bottom: 4px; }
        .gap-3 { margin-bottom: 12px; }
        .gap-5 { margin-bottom: 20px; }
        .gap-6 { margin-bottom: 24px; }
        .mb-8 { margin-bottom: 32px; }
        .my-5 { margin: 20px 0; }
        .my-8 { margin: 32px 0; }
        .p-2 { padding: 8px; }
        .p-5 { padding: 20px; }
        .px-5 { padding-left: 20px; padding-right: 20px; }
        .py-2 { padding-top: 8px; padding-bottom: 8px; }
        .rounded-lg { border-radius: 8px; }
        .text-xs { font-size: 12px; }
        .text-sm { font-size: 14px; }
        .text-xl { font-size: 20px; }
        .text-4xl { font-size: 36px; line-height: 1.2; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-light { font-weight: 300; }
        .w-fit { width: auto; }
        .border { border: 1px solid; }
        .border-0 { border: none; }
        hr { 
            height: 1px; 
            background-color: #6b7280; 
            border: none;
            margin: 32px 0; 
        }
        ul { padding-left: 20px; margin: 8px 0; }
        li { margin-bottom: 4px; }
        .col-span-2 { width: 100%; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.layout-fixed { table-layout: fixed; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <main style="width: 100%; max-width: 210mm; margin: 0 auto;">
        <h1 class="text-primary-700 text-4xl font-medium" style="margin-bottom: 24px;">
            Informasi Booking
        </h1> 
        
        <section class="container">
            {{-- Header card --}}
            <div class="mb-8">
                @if($transaction->payment_status == 'PAID')
                <p class="p-2 rounded-lg bg-green-100 border-green-700 text-green-700 text-xs w-fit font-medium" style="display: inline-block;">{{$transaction->payment_status}}</p>
                @elseif($transaction->payment_status == 'PENDING')
                <p class="p-2 rounded-lg bg-yellow-100 border-yellow-700 text-yellow-700 text-xs w-fit font-medium" style="display: inline-block;">{{$transaction->payment_status}}</p>
                @endif
                
                <h2 class="font-light text-primary-700 text-xl" style="margin: 8px 0;">
                    Booking ID: <span class="font-medium">{{$transaction->invoice}}</span>
                </h2>
                
                <p class="text-gray-700 text-sm" style="margin: 4px 0;">
                    {{$transaction->created_at->isoFormat('dddd, D MMMM YYYY, H:m')}}
                </p>
            </div>

            {{-- Body card --}}
            <div style="margin-bottom: 24px;">
                <h3 class="text-xl text-primary-700" style="margin-bottom: 16px;">Detail Pemesan</h3>
                
                <table class="layout-fixed">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                            <div>
                                <p style="margin: 4px 0;">Nama</p>
                                <p class="font-medium text-primary-700" style="margin: 4px 0;">{{$transaction->user->name}}</p>
                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top;">
                            <div>
                                <p style="margin: 4px 0;">Email</p>
                                <p class="font-medium text-primary-700" style="margin: 4px 0;">{{$transaction->user->email}}</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 16px;">
                            <div>
                                <p style="margin: 4px 0;">Telepon</p>
                                <p class="font-medium text-primary-700" style="margin: 4px 0;">{{$transaction->user->phone ?? $transaction->phone}}</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-bottom: 24px;">
                <h3 class="text-xl text-primary-700" style="margin-bottom: 16px;">Detail Pesanan</h3>
                
                <table class="layout-fixed">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                            <div>
                                <p style="margin: 4px 0;">Nama Kamar</p>
                                <p class="font-medium text-primary-700" style="margin: 4px 0;">{{$transaction->room->name}}</p>
                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top;">
                            <div>
                                <p style="margin: 4px 0;">Nomor Kamar</p>
                                <p class="font-medium text-primary-700" style="margin: 4px 0;">{{$transaction->room_number}}</p>
                            </div>
                        </td>
                    </tr>
                </table>
                
                @php
                    $nights = date_diff(date_create($transaction->check_in), date_create($transaction->check_out))->format("%a")
                @endphp
                
                <div style="margin-top: 16px;">
                    <p style="margin: 4px 0;">Durasi</p>
                    <p class="font-medium text-primary-700" style="margin: 4px 0;">
                        {{Carbon\Carbon::parse($transaction->check_in)->isoFormat('dddd, D MMM YYYY')}} - 
                        {{Carbon\Carbon::parse($transaction->check_out)->isoFormat('dddd, D MMM YYYY')}} 
                        ({{$nights}} Malam)
                    </p>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <table class="layout-fixed">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                            <div>
                                <p style="margin: 4px 0; font-weight: 500;">Tambahan</p>
                                <ul>
                                    @foreach ($transaction->accomodation_plans as $accomodation_plan)                                        
                                    <li class="text-primary-700" style="margin-bottom: 4px;">
                                        {{$accomodation_plan->name}} (Rp. {{number_format($accomodation_plan->price,0,',','.')}})
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top;">
                            @php
                                $discount_amount = 0;
                                foreach($transaction->promos as $promo) {
                                    $discount_amount += $promo->amount;
                                }
                                $discount_amount = $discount_amount / 100;
                                $discounted_price = $discount_amount * ($transaction->room->price * $nights);
                            @endphp
                            
                            <div>
                                <p style="margin: 4px 0; font-weight: 500;">Promo yang Dipakai</p>
                                <ul>
                                    @foreach ($transaction->promos as $promo)                                        
                                    <li class="text-primary-700" style="margin-bottom: 4px;">
                                        {{$promo->name}} ({{$promo->amount}}%)
                                    </li>
                                    <li class="text-red-700 text-sm font-medium" style="margin-bottom: 4px;">
                                        -Rp. {{number_format(($promo->amount / 100) * ($transaction->room->price * $nights),0,',','.')}}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <hr>
            
            <div>
                <div style="margin-bottom: 20px;">
                    <h3 class="text-xl text-primary-700" style="display: inline-block; margin-right: 12px; vertical-align: middle;">
                        Ringkasan Tagihan
                    </h3>
                    
                    @if($transaction->payment_status == 'PAID')
                    <p class="p-2 rounded-lg bg-green-100 border-green-700 text-green-700 text-xs w-fit font-medium" style="display: inline-block; vertical-align: middle;">
                        {{$transaction->payment_status}}
                    </p>
                    @elseif($transaction->payment_status == 'PENDING')
                    <p class="p-2 rounded-lg bg-yellow-100 border-yellow-700 text-yellow-700 text-xs w-fit font-medium" style="display: inline-block; vertical-align: middle;">
                        {{$transaction->payment_status}}
                    </p>
                    @endif
                </div>
                
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 8px 0;">Biaya kamar</td>
                        <td class="text-right text-primary-700" style="padding: 8px 0;">
                            Rp. {{number_format($transaction->room->price,0,',','.')}}
                        </td>
                    </tr>
                    
                    @php
                        $accomodation_plan_amount = 0;
                        foreach ($transaction->accomodation_plans as $accomodation_plan) {
                            $accomodation_plan_amount += $accomodation_plan->price;
                        }
                    @endphp
                    
                    <tr>
                        <td style="padding: 8px 0;">Biaya tambahan</td>
                        <td class="text-right text-primary-700" style="padding: 8px 0;">
                            Rp. {{number_format($accomodation_plan_amount,0,',','.')}}
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding: 8px 0;">Potongan harga</td>
                        <td class="text-right text-red-700" style="padding: 8px 0;">
                            -Rp. {{number_format($discounted_price,0,',','.')}}
                        </td>
                    </tr>
                    
                    <tr style="border-top: 1px solid #ddd;">
                        <td style="padding: 12px 0; font-weight: 500;">Total harga</td>
                        <td class="text-right bg-primary-100 text-primary-700 font-semibold" style="padding: 12px 0;">
                            Rp. {{number_format($transaction->total_price,0,',','.')}}
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding: 8px 0;">Metode pembayaran</td>
                        <td class="text-right" style="padding: 8px 0;">{{$transaction->payment_method}}</td>
                    </tr>
                </table>
                
                @if($transaction->checkin_status == 'Belum')
                <div class="text-center" style="margin-top: 20px;">
                    <span style="display: inline-block; padding: 8px 20px; background-color: #976033; color: white; border-radius: 8px;">
                        Check-in
                    </span>
                </div>
                @endif
            </div>
        </section>    
    </main>
</body>
</html>