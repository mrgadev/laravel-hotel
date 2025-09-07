<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png" />
{{-- <link rel="icon" type="image/png" href="/assets/img/favicon.png" /> --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Sora:wght@100..800&display=swap" rel="stylesheet">
{{-- Google Icons --}}
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- Ionicons --}}
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

{{-- Autocomma CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.7/autoNumeric.js" integrity="sha512-+TD1crYQC9Nz7fwpqyNFx2bUUmqLuV+H3lanJbWAy2i4MFcBMDmiixepJvC++HmBRsIzDljVlxzUwEKlTSlynw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- Flowbite CDN --}}
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
<!-- Nucleo Icons -->
<link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
<!-- Popper -->
<script src="https://unpkg.com/@popperjs/core@2"></script>
<!-- Main Styling -->
<link href="/assets/css/argon-dashboard-tailwind.css?v=1.0.1" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Include Select2 CSS CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Icon Picker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/css/bootstrap-iconpicker.min.css">

<!-- Bootstrap Icon Picker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/js/bootstrap-iconpicker.bundle.min.js"></script>
<!-- Include Select2 JS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.3.3/echarts.min.js"></script>
<style>
    .dt-layout-row:has(.dt-search),
.dt-layout-row:has(.dt-length),
.dt-layout-row:has(.dt-paging) {
  display: none !important;
}
</style>
{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="./assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="./assets/vendor/datatables.net/js/dataTables.min.js"></script>
@vite(['resources/css/app.css','resources/css/app.js'])