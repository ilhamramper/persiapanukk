<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- JS Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        #toast-container {
            opacity: 1 !important;
        }

        #toast-container>div {
            opacity: 1 !important;
        }
    </style>
</head>

<body>
    <div id="app">

        @include('layouts.navbar')

        <main class="pt-5 mt-5">
            @yield('content')
        </main>
    </div>

    <div style="margin-top: 10%">
        @include('layouts.footer')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://kit.fontawesome.com/2e55eb2e88.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @yield('scripts')

    @if (Session::has('success'))
        <script>
            toastr.success("{{ Session::get('success') }}", "Success", {
                "progressBar": true,
                "timeOut": 1500,
                "hideDuration": 300,
                "extendedTimeOut": 1000,
                "opacity": 1,
            });
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            toastr.error("{{ Session::get('error') }}", "Error", {
                "progressBar": true,
                "timeOut": 1000,
                "hideDuration": 300,
                "extendedTimeOut": 1000,
                "opacity": 1,
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var dropdownMenus = document.querySelectorAll('.navbar .dropdown-menu');

            dropdownMenus.forEach(function(dropdownMenu) {
                var dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');

                // Fungsi untuk mendapatkan lebar maksimum dari array elemen
                function getMaxWidth(elements) {
                    var maxWidth = 0;
                    elements.forEach(function(element) {
                        var width = element.offsetWidth;
                        if (width > maxWidth) {
                            maxWidth = width;
                        }
                    });
                    return maxWidth;
                }

                // Set lebar dropdown menu sesuai dengan lebar maksimum dari dropdown items
                dropdownMenu.style.minWidth = getMaxWidth(dropdownItems) + 'px';
            });
        });
    </script>
</body>

</html>
