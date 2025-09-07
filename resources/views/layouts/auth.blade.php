<!DOCTYPE html>
<html lang="en">
  <head>
    @include('components.style')
    <title>UNS Inn Hotel | @yield('title')</title>
    {{-- Font Awesome cdn --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  </head>

  <body class="m-0 font-sora antialiased font-normal bg-white text-start text-base leading-default text-slate-500">
    @yield('main')    
  </body>
<!-- plugin for scrollbar  -->
<script src="assets/js/plugins/perfect-scrollbar.min.js" async></script>
<!-- main script file  -->
<script src="assets/js/argon-dashboard-tailwind.js?v=1.0.1" async></script>
<script>
function togglePassword() {
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eye-icon');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    eyeIcon.classList.remove('fa-eye');
    eyeIcon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    eyeIcon.classList.remove('fa-eye-slash');
    eyeIcon.classList.add('fa-eye');
  }
}

function togglePasswordConfirmation() {
  const passwordconfirmationInput = document.getElementById('password_confirmation');
  const eyeIcon = document.getElementById('eye-icon-2');
  
  if (password_confirmation.type === 'password') {
    password_confirmation.type = 'text';
    eyeIcon.classList.remove('fa-eye');
    eyeIcon.classList.add('fa-eye-slash');
  } else {
    password_confirmation.type = 'password';
    eyeIcon.classList.remove('fa-eye-slash');
    eyeIcon.classList.add('fa-eye');
  }
}
</script>
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
</html>