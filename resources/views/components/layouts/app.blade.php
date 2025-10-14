
<!DOCTYPE html>
<html lang="fa" dir="rtl">
  <head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <link href="{{ asset('panel/assets/images/logo/favicon.png') }}" rel="icon" type="image/x-icon" />
    <link href="{{ asset('panel/assets/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon" />
        <title>{{ $title ?? 'Page Title' }}</title>

    <!-- Animation css -->
    <link href="{{ asset('panel/assets/vendor/animation/animate.min.css') }}" rel="stylesheet" />

    <!-- Fonts -->
    <link href="{{ asset('panel/assets/fonts/estedad/fontface.css') }}" rel="stylesheet" />

    <!-- iconoir icon css  -->
    <link href="{{ asset('panel/assets/vendor/ionio-icon/css/iconoir.css') }}" rel="stylesheet" />

    <!-- wheather icon css-->
    <link href="{{ asset('panel/assets/vendor/weather/weather-icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('panel/assets/vendor/weather/weather-icons-wind.css') }}" rel="stylesheet" type="text/css" />

    <!--flag Icon css-->
    <link
      href="{{ asset('panel/assets/vendor/flag-icons-master/flag-icon.css') }}"
      rel="stylesheet"
      type="text/css" />

    <!-- tabler icons-->
    <link href="{{ asset('panel/assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css" />

    <!-- prism css-->
    <link href="{{ asset('panel/assets/vendor/prism/prism.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- apexcharts css-->
    <link href="{{ asset('panel/assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet" type="text/css" />

    <!-- slick css -->
    <link href="{{ asset('panel/assets/vendor/slick/slick.css') }}" rel="stylesheet" />
    <link href="{{ asset('panel/assets/vendor/slick/slick-theme.css') }}" rel="stylesheet" />

    <!-- Data Table css-->
    <link
      href="{{ asset('panel/assets/vendor/datatable/jquery.dataTables.min.css') }}"
      rel="stylesheet"
      type="text/css" />

    <!-- Bootstrap css-->
    <link href="{{ asset('panel/assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- apexcharts css-->
    <link href="{{ asset('panel/assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet" type="text/css" />

    <!-- simplebar css-->
    <link href="{{ asset('panel/assets/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css-->
    <link href="{{ asset('panel/assets/css/style.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive css-->
    <link href="{{ asset('panel/assets/css/responsive.css') }}" rel="stylesheet" type="text/css" />

    {{-- Page-level styles --}}
    @stack('styles')

    <script>
      function isRTL() {
        return document.documentElement.dir === "rtl";
      }
    </script>
  </head>

  <body class="rtl">
    <div class="app-wrapper">


      <!-- Menu Navigation starts -->
      @include('components.layouts.partials.nav')
      <!-- Menu Navigation ends -->

      <div class="app-content">
        <div class="">
          <!-- Header Section starts -->
           @include('components.layouts.partials.header')
          <!-- Header Section ends -->

          <!-- Body main section starts -->


          {{ $slot }}

        </div>
      </div>
      <!-- Body main section ends -->

      <!-- tap on top -->
      <div class="go-top">
        <span class="progress-value">
          <i class="ti ti-chevron-up"></i>
        </span>
      </div>

      <!-- Footer Section starts-->
      <footer>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-9 col-12">
              <div class="footer-text">
                <p class="f-w-500 mb-0">
                  کپی‌رایت ۲۰۲۵ © خانه نواوری اصفهان. تمامی حقوق محفوظ است 💖
                  <a class="f-w-600 text-primary" href="https://fadaee.dev">ورژن 1.1.0 طراحی و توسعه با آرش فدایی</a>
                  ✨
                </p>
              </div>
            </div>

          </div>
        </div>
      </footer>
      <!-- Footer Section ends-->
    </div>

    <!--customizer-->
    <div id="customizer"></div>

    <!-- latest jquery-->
    <script src="{{ asset('panel/assets/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('panel/assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- Simple bar js-->
    <script src="{{ asset('panel/assets/vendor/simplebar/simplebar.js') }}"></script>

    <!-- phosphor js -->
    <script src="{{ asset('panel/assets/vendor/phosphor/phosphor.js') }}"></script>

    <!-- slick-file -->
    <script src="{{ asset('panel/assets/vendor/slick/slick.min.js') }}"></script>

    <!-- apexcharts-->
    <script src="{{ asset('panel/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Customizer js-->
    <script src="{{ asset('panel/assets/js/customizer.js') }}"></script>

    <!-- prism js-->
    <script src="{{ asset('panel/assets/vendor/prism/prism.min.js') }}"></script>

    <!-- Ecommerce js-->
    <script src="{{ asset('panel/assets/js/ecommerce_dashboard.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('panel/assets/js/script.js') }}"></script>

    {{-- Page-level scripts --}}
    @stack('scripts')
  </body>
</html>
