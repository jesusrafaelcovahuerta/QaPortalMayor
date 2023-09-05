<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="expires" content="Fri, 18 Jul 2014 1:00:00 GMT" />

  <title>Portal de la Fundación Conecta Mayor - Panel de Control</title>

  <!-- Custom fonts for this template-->
  <link href="{{ asset('backend/vendor/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ mix('css/backendapp.css') }}" rel="stylesheet" type="text/css">
  

  <!-- Custom styles for this template-->
  <link href="https://cdn.jsdelivr.net/npm/animate.css@3.5.1" rel="stylesheet" type="text/css">

  <script>
    window.App = {!! json_encode([
        'apiToken' => Auth::user()->api_token
    ]) !!};
  </script>

</head>

<body id="page-top">
  <div id="app">
    <!-- Page Wrapper -->
    <div id="wrapper">

      <!-- Sidebar -->
      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" style="background-color: #0A2787 !important;" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a style="background-color: #fff;" class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/account') }}">
          <img src="{{ url('backend/img/logo.jpg') }}" id="logo" height="40">
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <menu-component></menu-component>

        <!-- Divider -->
        <hr class="sidebar-divider">

      </ul>
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
          <!-- Topbar -->
          <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </button>



          </nav>
          <!-- End of Topbar -->

          @yield('content')

          

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; {{ date('Y') }} Fundación Conecta Mayor</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->

      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="application/javascript" src="{{ mix('js/app.js') }}"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script href="{{ asset('backend/js/sb-admin-2.js') }}"></script>
</body>

</html>
