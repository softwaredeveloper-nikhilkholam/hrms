<!doctype html>
<html lang="en">
    <head>
        @include('storePartials.head')
        <style>
            .form-control {
                color: black;
                font-size: 16px;
            }
            label {
                color: #01041b;
                font-size: 18px;
            }
            .btn-success {
                font-size: 20px;
            }
            .btn-danger {
                font-size: 20px;
            }
            .btn-warning {
                font-size: 20px;
            }
            .btn-primary {
                font-size: 20px;
            }
        </style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </head>
    <body class="  ">
        <!-- Wrapper Start -->
        <div class="wrapper">
            @include('storePartials.sidebar')
            @include('storePartials.header')
            <div class="content-page">
                <div class="container-fluid">
                    @include('storePartials.messages')
                    @yield('content')
                </div>
            </div>
        </div>
        <footer class="iq-footer">
            @include('storePartials.footer')
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @include('storePartials.script')
  </body>
</html> 