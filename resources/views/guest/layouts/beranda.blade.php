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

    {{-- ArcGIS --}}
    <link rel="stylesheet" href="https://js.arcgis.com/4.28/esri/themes/light/main.css" />

    {{-- Tailwind CSS & Preline UI --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>

<body class="h-screen flex flex-col">
    @include('guest.components.navbar')

    @yield('slot')

    @include('guest.components.footer')

    {{-- ArcGIS --}}
    <script src="https://js.arcgis.com/4.28/"></script>
    <script>
        require([
            "esri/Map",
            "esri/views/MapView",
            "esri/widgets/Locate",
            "esri/widgets/ScaleBar",
            "esri/widgets/Compass",
            "esri/widgets/Search",
            "esri/widgets/BasemapGallery",
            "esri/Graphic",
            "esri/layers/GraphicsLayer"
        ], function(Map, MapView, Locate, ScaleBar, Compass, Search, BasemapGallery, Graphic, GraphicsLayer) {
            var map = new Map({
                basemap: "hybrid"
            });

            var view = new MapView({
                container: "arcgisMap",
                map: map,
                center: [117.1466, -0.5022],
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
            view.ui.add(locateWidget, { position: "bottom-right" });

            var compass = new Compass({ view: view });
            view.ui.add(compass, { position: "bottom-left" });

            var scaleBar = new ScaleBar({ view: view, unit: "metric" });
            view.ui.add(scaleBar, { position: "bottom-left" });

            var searchWidget = new Search({
                view: view,
                container: "searchWidgetContainer",
                allPlaceholder: "Cari nama jalan atau lokasi",
                includeDefaultSources: true
            });

            // BasemapGallery untuk desktop (dropdown kanan atas)
            var basemapGallery = new BasemapGallery({
                view: view,
                container: "basemapGalleryWidget"
            });

            // BasemapGallery untuk sidebar (mobile)
            var basemapGallerySidebar = new BasemapGallery({
                view: view,
                container: "basemapGalleryWidgetSidebar"
            });

            // --- Tambahan untuk marker jalan rusak ---
            var graphicsLayer = new GraphicsLayer();
            map.add(graphicsLayer);

            // Icon marker berdasarkan tingkat keparahan
            var iconUrls = {
                ringan: "{{ asset('icons/rusak-ringan.svg') }}",
                sedang: "{{ asset('icons/rusak-sedang.svg') }}",
                berat: "{{ asset('icons/rusak-berat.svg') }}"
            };

            // Ganti dengan fungsi reverse geocoding menggunakan OpenStreetMap
            async function getNamaJalan(lat, lon) {
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18`);
                    const data = await response.json();
                    return data.display_name || "Lokasi tidak diketahui";
                } catch (error) {
                    console.error("Error fetching address:", error);
                    return "Nama jalan tidak ditemukan";
                }
            }

            // Ambil data jalan rusak dari backend
            fetch('/api/jalan-rusak')
                .then(res => res.json())
                .then(data => {
                    data.forEach(function(jalan) {
                        var point = {
                            type: "point",
                            longitude: jalan.longitude,
                            latitude: jalan.latitude
                        };

                        var markerSymbol = {
                            type: "picture-marker",
                            url: iconUrls[jalan.tingkat_keparahan] || iconUrls.ringan,
                            width: "32px",
                            height: "32px"
                        };

                        var graphic = new Graphic({
                            geometry: point,
                            symbol: markerSymbol,
                            attributes: jalan,
                            zIndex: 999
                        });

                        // Reverse geocoding untuk nama jalan
                        graphic.popupTemplate = {
                            title: "Memuat nama jalan...",
                            content: function() {
                                var container = document.createElement("div");
                                container.innerHTML = `
                                    <div><b>Deskripsi:</b> ${jalan.deskripsi}</div>
                                    <div><b>Longitude:</b> ${jalan.longitude}</div>
                                    <div><b>Latitude:</b> ${jalan.latitude}</div>
                                    <div><b>Waktu dibuat:</b> ${jalan.created_at}</div>
                                    <div><b>Foto:</b><br>
                                        <img src="/storage/${jalan.foto}" alt="Foto Jalan Rusak" style="max-width:200px;max-height:150px;border-radius:8px;margin-top:4px;">
                                    </div>
                                `;

                                // Dapatkan nama jalan
                                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${jalan.latitude}&lon=${jalan.longitude}&zoom=18`)
                                    .then(res => res.json())
                                    .then(data => {
                                        // Ambil nama jalan dari address
                                        const address = data.address || {};
                                        const namaJalan = address.road || 
                                                        address.pedestrian || 
                                                        address.footway || 
                                                        address.path || 
                                                        "Nama jalan tidak diketahui";
                                        view.popup.title = namaJalan;
                                    })
                                    .catch(() => {
                                        view.popup.title = "Nama jalan tidak ditemukan";
                                    });

                                return container;
                            }
                        };

                        graphicsLayer.add(graphic);
                    });
                });
        });
    </script>
</body>

</html>
