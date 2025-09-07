<!DOCTYPE html>
<html>
  <head>
	<title>@yield('title') | Dashboard</title>
	@include('components.style')
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-[#976033] min-h-75"></div>
    <!-- sidenav  -->
    @include('components.user-sidebar')

    <!-- end sidenav -->

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <!-- Navbar -->
      @include('components.user-navbar')

      <!-- end Navbar -->

      <!-- cards -->
      <div class="w-full py-6 mx-auto" style="padding: 5%">
        @yield('content')
      </div>
      <!-- end cards -->
    </main>
    @if (session('success'))
    <script>
        const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });
        Toast.fire({
        icon: "success",
        title: "{{session('success')}}",
        });
    </script>
  @elseif (session('error'))
    <script>
      const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
      }
      });
      Toast.fire({
      icon: "error",
      title: "{{session('error')}}",
      });
    </script>
  @endif
  </body>
  @include('components.script')
  @stack('addon-script')
</html>
