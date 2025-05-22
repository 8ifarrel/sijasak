<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="{{ $meta_description }}" />

    <title>
        {{ $page_title }} | {{ config('app.name') }} ({{ config('app.name_short') }}) {{ config('app.location') }}
    </title>

    <link rel="icon" type="image/x-icon" href="" />

    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- AOS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

    {{-- ArcGIS --}}
    <link rel="stylesheet" href="https://js.arcgis.com/4.28/esri/themes/light/main.css" />

    {{-- Tailwind CSS & Preline UI --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>

<body class="h-screen flex flex-col">
    @include('guest.components.navbar')

    <div class="flex-1 min-h-0">
        @yield('slot')
    </div>

    {{-- Jika ingin footer tetap, bisa gunakan absolute atau hapus baris berikut --}}
    {{-- @include('guest.components.footer') --}}

    {{-- AOS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js">
        AOS.init({
            duration: 800,
        });
    </script>

    {{-- ArcGIS --}}
    <script src="https://js.arcgis.com/4.28/"></script>
    <script>
        require([
            "esri/Map",
            "esri/views/MapView",
            "esri/widgets/Locate",
            "esri/widgets/ScaleBar",
            "esri/widgets/Compass",
            "esri/widgets/Search" // Tambahkan modul Search
        ], function(Map, MapView, Locate, ScaleBar, Compass, Search) {
            var map = new Map({
                basemap: "hybrid"
            });

            var view = new MapView({
                container: "arcgisMap",
                map: map,
                center: [117.1466, -0.5022], // Koordinat Kota Samarinda
                zoom: 12
            });

            view.ui.move("zoom", "bottom-right");

            var locateWidget = new Locate({
                view: view,
                geolocationOptions: {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 15000
                }
            });
            view.ui.add(locateWidget, {
                position: "bottom-right"
            });

            var compass = new Compass({
                view: view
            });
            view.ui.add(compass, {
                position: "bottom-left"
            });

            var scaleBar = new ScaleBar({
                view: view,
                unit: "metric"
            });
            view.ui.add(scaleBar, {
                position: "bottom-left"
            });

            // Tambahkan widget Search
            var searchWidget = new Search({
                view: view,
                allPlaceholder: "Cari nama jalan atau lokasi",
                includeDefaultSources: true
            });
            view.ui.add(searchWidget, {
                position: "top-left",
                index: 0
            });
        });
    </script>
</body>

</html>
