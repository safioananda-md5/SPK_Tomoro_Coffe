<!--
=========================================================
* Material Dashboard 3 - v3.2.0
=========================================================

* Product Page:  https://www.creative-tim.com/product/material-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Coded by www.creative-tim.com

 =========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/material/img/apple-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-mini.webp') }}" />
    <title>
        @stack('title') || SPK Tomoro Coffe
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/material/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/material/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/material/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
    @yield('style')
</head>

<body class="landing-page bg-gray-200">
    <!-- Navbar -->
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <nav
                    class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-fixed shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                    <div class="container-fluid px-0">
                        <a class="navbar-brand font-weight-bolder ms-sm-3 d-none d-md-block" href="/"
                            rel="tooltip" title="Designed and Coded by Creative Tim" data-placement="bottom"
                            target="_blank">
                            <img src="{{ asset('assets/images/logo-mini.webp') }}" alt="logo" width="50px" />
                            Tomoro Coffe
                        </a>
                        <a class="navbar-brand font-weight-bolder ms-sm-3 d-block d-md-none" href="/"
                            rel="tooltip" title="Designed and Coded by Creative Tim" data-placement="bottom"
                            target="_blank">
                            <img src="{{ asset('assets/images/logo-mini.webp') }}" alt="logo" width="50px" />
                            Tomoro Coffe
                        </a>
                        <button class="navbar-toggler shadow-none ms-2 ms-md-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon mt-2">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </span>
                        </button>
                        <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0" id="navigation">
                            <ul class="navbar-nav navbar-nav-hover ms-auto">
                                <li class="nav-item d-flex align-items-center">
                                    <a href="{{ route('login') }}" role="button"
                                        class="nav-link ps-2 d-flex cursor-pointer align-items-center">
                                        <i class="fa fa-sign-in text-lg opacity-8 me-3"></i> Login
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
        </div>
    </div>
    @yield('content')
    <footer class="footer pt-5 mt-5">
        <div class="container">
            <div class=" row">
                <div class="col-md-3 mb-4">
                    <div>
                        <a href="https://www.creative-tim.com/product/material-dashboard">
                            <img src="{{ asset('assets/images/logo.webp') }}" class="mb-3 ms-n3" width="200px"
                                alt="main_logo">
                        </a>
                        <h6 class="font-weight-bolder mb-4">Tomorro Coffe</h6>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-6 mb-4">
                    <div>
                        <h6 class="text-sm">Company</h6>
                        <ul class="flex-column ms-n3 nav">
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.tomoro-coffee.id/home" target="_blank">
                                    About Us
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.tomoro-coffee.id/app/download" target="_blank">
                                    Download Apps
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-center">
                        <p class="text-dark my-4 text-sm font-weight-normal">
                            All rights reserved. Copyright ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            code by <strong>Owl Job</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/material/js/core/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/material/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ asset('assets/material/js/plugins/perfect-scrollbar.min.js') }}"></script> --}}
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!--  Plugin for TypedJS, full documentation here: https://github.com/inorganik/CountUp.js -->
    <script src="{{ asset('assets/material/js/plugins/countup.min.js') }}"></script>
    <script type="text/javascript">
        if (document.getElementById('stats1')) {
            const countUp = new CountUp('stats1', document.getElementById("stats1").getAttribute("countTo"));
            if (!countUp.error) {
                countUp.start();
            } else {
                console.error(countUp.error);
            }
        }
        if (document.getElementById('stats2')) {
            const countUp1 = new CountUp('stats2', document.getElementById("stats2").getAttribute("countTo"));
            if (!countUp1.error) {
                countUp1.start();
            } else {
                console.error(countUp1.error);
            }
        }
        if (document.getElementById('stats3')) {
            const countUp2 = new CountUp('stats3', document.getElementById("stats3").getAttribute("countTo"));
            if (!countUp2.error) {
                countUp2.start();
            } else {
                console.error(countUp2.error);
            };
        }
        if (document.getElementById('stats4')) {
            const countUp3 = new CountUp('stats4', document.getElementById("stats4").getAttribute("countTo"));
            if (!countUp3.error) {
                countUp3.start();
            } else {
                console.error(countUp3.error);
            };
        }
    </script>
    <!-- Control Center for Material UI Kit: parallax effects, scripts for the example pages etc -->
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
    <script src="{{ asset('assets/material/js/material-dashboard.min.js?v=3.2.0') }}" type="text/javascript"></script>
    @yield('scripts')
</body>

</html>
