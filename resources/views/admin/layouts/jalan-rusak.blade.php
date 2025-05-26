<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="{{ $meta_description }}" />

    <title>
        {{ $page_title }} | Admin Panel {{ config('app.name') }} ({{ config('app.name_short') }}) {{
        config('app.location') }}
    </title>

    <link rel="icon" type="image/x-icon" href="" />

    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- ArcGIS --}}
    <link rel="stylesheet" href="https://js.arcgis.com/4.28/esri/themes/light/main.css" />

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- DataTables --}}
    <link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css" rel="stylesheet" />

    {{-- Tailwind CSS & Preline UI --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>

<body class="h-screen flex flex-col">
    <div>
        @include('admin.components.navbar')
    </div>


    <div class="flex ">
        {{-- Sidebar: hanya md ke atas --}}
        <div class="flex-auto">
            @include('admin.components.aside')
        </div>

        <div class="flex-initial w-full sm:w-[calc(100vw_-_16rem)] h-[calc(100vh_-_68px)] absolute bottom-0 right-0">
            @yield('slot')
        </div>
    </div>

    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

    {{-- ArcGIS --}}
    <script src="https://js.arcgis.com/4.28/"></script>
    <script>
        // Fungsi untuk format tanggal: huruf pertama hari dan bulan kapital, sisanya kecil
        function formatTanggalIndonesia(datetimeStr) {
            const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const bulan = [
                'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'
            ];
            const d = new Date(datetimeStr);
            if (isNaN(d)) return datetimeStr;
            const h = hari[d.getDay()];
            const tgl = d.getDate();
            const bln = bulan[d.getMonth()];
            const thn = d.getFullYear();
            const jam = d.getHours().toString().padStart(2, '0');
            const menit = d.getMinutes().toString().padStart(2, '0');
            return `${h} ${tgl} ${bln} ${thn} (${jam}.${menit})`;
        }

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

            // Function to filter markers based on checkbox state
            function filterMarkers() {
                // Get checkbox states directly from the clicked checkbox
                const showRingan = document.querySelector('#jalanRusakRinganDropdown')?.checked ?? 
                                 document.querySelector('#jalanRusakRinganCollapse')?.checked ?? true;
                const showSedang = document.querySelector('#jalanRusakSedangDropdown')?.checked ?? 
                                 document.querySelector('#jalanRusakSedangCollapse')?.checked ?? true;
                const showBerat = document.querySelector('#jalanRusakBeratDropdown')?.checked ?? 
                                document.querySelector('#jalanRusakBeratCollapse')?.checked ?? true;

                // Sync the checkboxes between dropdown and collapse
                const syncCheckboxes = (type, isChecked) => {
                    const dropdownCb = document.querySelector(`#jalanRusak${type}Dropdown`);
                    const collapseCb = document.querySelector(`#jalanRusak${type}Collapse`);
                    if (dropdownCb) dropdownCb.checked = isChecked;
                    if (collapseCb) collapseCb.checked = isChecked;
                };

                syncCheckboxes('Ringan', showRingan);
                syncCheckboxes('Sedang', showSedang);
                syncCheckboxes('Berat', showBerat);

                // Update marker visibility
                graphicsLayer.graphics.forEach(function(graphic) {
                    const keparahan = graphic.attributes.tingkat_keparahan;
                    switch(keparahan) {
                        case 'ringan':
                            graphic.visible = showRingan;
                            break;
                        case 'sedang':
                            graphic.visible = showSedang;
                            break;
                        case 'berat':
                            graphic.visible = showBerat;
                            break;
                    }
                });
            }

            // Add event listeners to all checkboxes
            ['Ringan', 'Sedang', 'Berat'].forEach(type => {
                const dropdownCb = document.querySelector(`#jalanRusak${type}Dropdown`);
                const collapseCb = document.querySelector(`#jalanRusak${type}Collapse`);
                
                [dropdownCb, collapseCb].forEach(cb => {
                    if (cb) {
                        cb.addEventListener('change', (e) => {
                            // Update the other checkbox first
                            const targetType = e.target.id.includes('Dropdown') ? 'Collapse' : 'Dropdown';
                            const otherCb = document.querySelector(`#jalanRusak${type}${targetType}`);
                            if (otherCb) otherCb.checked = e.target.checked;
                            
                            filterMarkers();
                        });
                    }
                });
            });

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
                            zIndex: 999,
                            visible: true // Add initial visible state
                        });

                        // Reverse geocoding untuk nama jalan
                        graphic.popupTemplate = {
                            title: "Memuat nama jalan...",
                            content: function() {
                                var container = document.createElement("div");
                                container.innerHTML = `
                                    <div class="mb-2">
                                        <a href="/admin/jalan-rusak/${jalan.id}/edit" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                            <i class="fa-solid fa-edit mr-2"></i>
                                            Edit
                                        </a>
                                    </div>
                                    <div><b>Deskripsi:</b> ${jalan.deskripsi}</div>
                                    <div><b>Longitude:</b> ${jalan.longitude}</div>
                                    <div><b>Latitude:</b> ${jalan.latitude}</div>
                                    <div><b>Waktu dibuat:</b> ${formatTanggalIndonesia(jalan.created_at)}</div>
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
                    // Apply initial filter after all markers are added
                    filterMarkers();
                });
        });

        // Sync visualization radio buttons between dropdown, collapse, and page
        document.querySelectorAll('input[name="visualisasiDropdown"], input[name="visualisasiCollapse"], input[name="visualisasiPage"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const value = this.value;
                // Sync all radio groups
                const groups = ['visualisasiDropdown', 'visualisasiCollapse', 'visualisasiPage'];
                groups.forEach(group => {
                    const radioButton = document.querySelector(`input[name="${group}"][value="${value}"]`);
                    if (radioButton) radioButton.checked = true;
                });
                
                // Toggle visibility of map/table views
                const mapView = document.getElementById('arcgisMap');
                const tableView = document.getElementById('dataTable');
                
                if (value === 'peta') {
                    if (mapView) {
                        mapView.style.display = 'block';
                        setTimeout(() => {
                            if (typeof view !== 'undefined' && view && mapView) {
                                view.container = mapView;
                                view.resize();
                                // Trigger window resize event for ArcGIS
                                window.dispatchEvent(new Event('resize'));
                            }
                        }, 200);
                    }
                    if (tableView) tableView.style.display = 'none';
                } else {
                    if (mapView) mapView.style.display = 'none';
                    if (tableView) tableView.style.display = 'block';
                }
            });
        });
        
    </script>
</body>

</html>