<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <meta name="description" content="{{ $meta_description }}" />

  <title>
    {{ $page_title }} | Admin Panel {{ config('app.name') }} ({{ config('app.name_short') }})
    {{ config('app.location') }}
  </title>

  <link rel="icon" type="image/x-icon" href="" />

  {{-- Disallow bot crawler --}}
  <meta name="robots" content="noindex, nofollow">

  {{-- Fontawesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- ArcGIS --}}
  <link rel="stylesheet" href="https://js.arcgis.com/4.28/esri/themes/light/main.css" />

  {{-- JQuery --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  {{-- Viewer.js JS (tanpa integrity/crossorigin) --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.js"></script>

  {{-- DataTables --}}
  <link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css" rel="stylesheet" />

  {{-- Tailwind CSS & Preline UI --}}
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')

  {{-- Viewer.js CSS (tanpa integrity/crossorigin) --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.css" />

  <style>
    /* Sembunyikan tombol arrow kiri-kanan pada Viewer.js */
    .viewer-arrow,
    .viewer-next,
    .viewer-prev {
      display: none !important;
    }
  </style>

  <script>
    function setViewportHeight() {
      const vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty('--vh', `${vh}px`);
    }

    window.addEventListener('resize', setViewportHeight);
    window.addEventListener('load', setViewportHeight);
    setViewportHeight();
  </script>

  <style>
    .h-screen-fix {
      height: calc(var(--vh, 1vh) * 100);
    }
  </style>
</head>

<body class="h-screen-fix flex flex-col">
  @include('admin.components.navbar')
  @include('admin.components.aside')

  @yield('slot')

  {{-- DataTables --}}
  <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

  {{-- ArcGIS --}}
  <script src="https://js.arcgis.com/4.28/"></script>
  <script>
    // Fungsi untuk format tanggal: huruf pertama hari dan bulan kapital, sisanya kecil
    function formatTanggalIndonesia(datetimeStr) {
      const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
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

    // --- Tambahan: deklarasi view di global scope agar bisa diakses di luar require ---
    var view;

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

      // --- Ubah let/var view menjadi assignment ke variabel global ---
      view = new MapView({
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
        ringan: "{{ asset('icons/belum-diperbaiki/rusak-ringan.svg') }}",
        sedang: "{{ asset('icons/belum-diperbaiki/rusak-sedang.svg') }}",
        berat: "{{ asset('icons/belum-diperbaiki/rusak-berat.svg') }}"
      };

      // Ganti dengan fungsi reverse geocoding menggunakan OpenStreetMap
      async function getNamaJalan(lat, lon) {
        try {
          const response = await fetch(
            `/api/reverse-geocode?lat=${lat}&lon=${lon}`);
          const data = await response.json();
          return data.display_name || "Lokasi tidak diketahui";
        } catch (error) {
          console.error("Error fetching address:", error);
          return "Nama jalan tidak ditemukan";
        }
      }

      // Function to filter markers based on checkbox state
      function filterMarkers() {
        const showRingan = document.querySelector('#jalanRusakRinganDropdown')?.checked ??
          document.querySelector('#jalanRusakRinganCollapse')?.checked ?? true;
        const showSedang = document.querySelector('#jalanRusakSedangDropdown')?.checked ??
          document.querySelector('#jalanRusakSedangCollapse')?.checked ?? true;
        const showBerat = document.querySelector('#jalanRusakBeratDropdown')?.checked ??
          document.querySelector('#jalanRusakBeratCollapse')?.checked ?? true;
        const showSudahDiperbaiki = document.querySelector('#jalanRusakSudahDiperbaikiDropdown')?.checked ??
          document.querySelector('#jalanRusakSudahDiperbaikiCollapse')?.checked ?? true;

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
        syncCheckboxes('SudahDiperbaiki', showSudahDiperbaiki);

        // Update marker visibility
        graphicsLayer.graphics.forEach(function(graphic) {
          const keparahan = graphic.attributes.tingkat_keparahan;
          const sudahDiperbaiki = !!graphic.attributes.sudah_diperbaiki;
          if (sudahDiperbaiki) {
            graphic.visible = showSudahDiperbaiki;
          } else {
            switch (keparahan) {
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
          }
        });
      }

      // Add event listeners to all checkboxes
      function addFilterCheckboxListeners() {
        ['Ringan', 'Sedang', 'Berat', 'SudahDiperbaiki'].forEach(type => {
          const dropdownCb = document.querySelector(`#jalanRusak${type}Dropdown`);
          const collapseCb = document.querySelector(`#jalanRusak${type}Collapse`);

          [dropdownCb, collapseCb].forEach(cb => {
            if (cb) {
              cb.addEventListener('change', (e) => {
                const targetType = e.target.id.includes('Dropdown') ?
                  'Collapse' : 'Dropdown';
                const otherCb = document.querySelector(
                  `#jalanRusak${type}${targetType}`);
                if (otherCb) otherCb.checked = e.target.checked;
                filterMarkers();
              });
            }
          });
        });
      }

      // Jalankan listener setelah DOM siap dan filter sudah ditambahkan
      setTimeout(addFilterCheckboxListeners, 200);

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

            // --- Perubahan: marker hijau bulat jika sudah_diperbaiki ---
            var markerSymbol;
            if (jalan.sudah_diperbaiki) {
              markerSymbol = {
                type: "simple-marker",
                style: "circle",
                color: [21, 128, 61, 1],
                size: "26px",
                outline: {
                  color: [255, 255, 255, 1],
                  width: 1
                }
              };
            } else {
              markerSymbol = {
                type: "picture-marker",
                url: iconUrls[jalan.tingkat_keparahan] || iconUrls.ringan,
                width: "28px",
                height: "28px"
              };
            }

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
											<div class="foto-viewer-popup" style="display:inline-block;">
													<img src="/storage/${jalan.foto}" alt="Foto Jalan Rusak" style="max-width:200px;max-height:150px;border-radius:8px;margin-top:4px;cursor:pointer;">
											</div>
									</div>
								`;

                // Dapatkan nama jalan
                fetch(
                    `/api/reverse-geocode?lat=${jalan.latitude}&lon=${jalan.longitude}`
                  )
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

                // Inisialisasi Viewer.js pada gambar popup setelah popup dibuka
                setTimeout(function() {
                  var popupFotoWrappers = container.querySelectorAll(
                    '.foto-viewer-popup');
                  popupFotoWrappers.forEach(function(wrapper) {
                    if (!wrapper.viewerInstance) {
                      wrapper.viewerInstance = new Viewer(
                        wrapper, {
                          navbar: false,
                          toolbar: true,
                          title: false,
                          tooltip: false,
                          movable: false,
                          zoomable: true,
                          scalable: false,
                          transition: true,
                          fullscreen: false
                        });
                    }
                    var img = wrapper.querySelector('img');
                    if (img) {
                      img.addEventListener('click',
                        function() {
                          wrapper.viewerInstance
                            .show();
                        });
                    }
                  });
                }, 100);

                return container;
              }
            };

            graphicsLayer.add(graphic);
          });
          filterMarkers();
        });
    });

    document.querySelectorAll(
        'input[name="visualisasiDropdown"], input[name="visualisasiCollapse"], input[name="visualisasiPage"]')
      .forEach(
        radio => {
          radio.addEventListener('change', function() {
            const value = this.value;
            const groups = ['visualisasiDropdown', 'visualisasiCollapse', 'visualisasiPage'];
            groups.forEach(group => {
              const radioButton = document.querySelector(
                `input[name="${group}"][value="${value}"]`);
              if (radioButton) radioButton.checked = true;
            });

            const mapView = document.getElementById('arcgisMap');
            const tableView = document.getElementById('dataTable');

            if (value === 'peta') {
              window.location.reload();
              return;
            } else {
              if (mapView) {
                mapView.style.visibility = 'hidden';
                mapView.style.height = '0px';
                mapView.style.display = 'none';
                mapView.classList.add('hidden');
              }
              if (tableView) {
                tableView.style.visibility = 'visible';
                tableView.style.height = '';
                tableView.style.display = 'block';
                tableView.classList.remove('hidden');
              }
            }
          });
        });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Dropdown (desktop)
      let filterDropdown = document.getElementById('filterJalanContainer');
      if (filterDropdown) {
        let filterList = filterDropdown.querySelector('.flex.flex-col.gap-2');
        if (filterList && !document.getElementById('jalanRusakSudahDiperbaikiDropdown')) {
          let label = document.createElement('label');
          label.className = "inline-flex items-center gap-2";
          label.innerHTML = `
            <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakSudahDiperbaikiDropdown" checked>
            <span><i class="fa-solid fa-circle text-green-700" style="color: #008236"></i> Sudah Diperbaiki</span>
          `;
          filterList.appendChild(label);
        }
      }

      // Sidebar (mobile)
      let sidebarFilter = document.getElementById('sidebar-accordion-filter-content');
      if (sidebarFilter) {
        let filterList = sidebarFilter.querySelector('.flex.flex-col.gap-2');
        if (filterList && !document.getElementById('jalanRusakSudahDiperbaikiCollapse')) {
          let label = document.createElement('label');
          label.className = "inline-flex items-center gap-2";
          label.innerHTML = `
            <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakSudahDiperbaikiCollapse" checked>
            <span><i class="fa-solid fa-circle text-green-700" style="color: #008236"></i> Sudah Diperbaiki</span>
          `;
          filterList.appendChild(label);
        }
      }
    });
  </script>

  {{-- ===== END Tambahan checkbox Sudah Diperbaiki ===== --}}
</body>

</html>
