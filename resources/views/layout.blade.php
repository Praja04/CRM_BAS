<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Relationship Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Layout config Js -->
    <script src="{{ asset('material/assets/js/layout.js') }}"></script>

    <!-- jQuery should be included before DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables script -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <link rel="shortcut icon" href="{{ asset('material/assets/images/favicon.ico') }}" />
    <link href="{{ asset('material/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('material/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('material/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('material/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

<body>
    <div id="layout-wrapper">
        <!-- Topbar -->
        @include('component.topbar')

        <!-- sidebar -->
        @include('component.sidebar')
        <!-- end sidebar -->
        <div class="main-content">
            <!-- content -->
            @yield('content')
            <!-- end content -->

            <!-- footer -->
            @include('component.footer')
            <!-- end footer -->
        </div>
    </div>

    <!-- JavaScript libraries -->
    <script src="{{ asset('material/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('material/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('material/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('material/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('material/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('material/assets/js/plugins.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('material/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Dashboard init -->
    <script src="{{ asset('material/assets/js/pages/dashboard-crm.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('material/assets/js/app.js') }}"></script>

    <!-- Script for handling logout and DataTable -->
    <script>
        $(document).ready(function() {
            // Logout button handler
            $('#logoutButton').click(function() {
                $.ajax({
                    url: "{{ route('logout') }}",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            window.location.href = "{{ url('/') }}"; // Redirect ke halaman utama atau login
                        }
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat logout');
                    }
                });
            });

            // Initialize DataTable for users table
           
        });
    </script>
</body>

</html>