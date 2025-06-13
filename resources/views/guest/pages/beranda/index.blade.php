@extends('guest.layouts.beranda')

@section('document.head')
  {{-- Menghilangkan tombol next dan previous pada Viever.js --}}
  <style>
    .viewer-arrow,
    .viewer-next,
    .viewer-prev {
      display: none !important;
    }
  </style>
  {{-- SplideJS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

  {{-- Manual dynamic vh (tidak semua browser mendukung unit dvh) --}}
  <script>
    function setViewportHeight() {
      const vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty("--vh", `${vh}px`);
    }
    window.addEventListener("resize", setViewportHeight);
    window.addEventListener("load", setViewportHeight);
    setViewportHeight();
  </script>

  <style>
    .h-screen-fix {
      height: calc(var(--vh, 1vh) * 100);
    }
  </style>
@endsection

@section('slot')
  <div id="arcgisMap" class="w-full h-full relative">
    <div class="absolute top-4 left-4 z-20">
      {{-- START Search jalan --}}
      <div class="flex items-center bg-white border border-gray-300 shadow">
        {{-- START Hamburger konfigurasi peta (md ke bawah) --}}
        <button type="button" class="block md:hidden px-2 border-e border-gray-300" aria-label="Open sidebar"
          data-hs-overlay="#hs-sidebar-basic-usage">
          <i class="fa-solid fa-bars" style="color: #808080;"></i>
        </button>
        {{-- END Hamburger konfigurasi peta (md ke bawah) --}}
        <div id="searchWidgetContainer"></div>
      </div>
      {{-- END Search jalan --}}
    </div>

    <div class="absolute top-4 right-4 z-20 gap-2 hidden md:flex">
      {{-- START Basemap --}}
      <div class="hs-dropdown relative inline-flex [--placement:bottom-right] [--auto-close:false]">
        <button id="toggleBasemapGallery" type="button"
          class="hs-dropdown-toggle bg-white border border-gray-300 shadow px-3 py-1 text-sm font-medium hover:bg-gray-100 focus:outline-none"
          aria-expanded="false" title="Ganti Basemap">
          <i class="fa fa-map"></i> Basemap
        </button>
        <div id="basemapGalleryContainer"
          class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[220px] max-h-[340px] overflow-y-auto bg-white shadow border border-gray-200 p-2 mt-2"
          aria-labelledby="toggleBasemapGallery">
          <div id="basemapGalleryWidget"></div>
        </div>
      </div>
      {{-- END Basemap --}}

      {{-- START Filter jalan rusak --}}
      <div class="hs-dropdown relative inline-flex [--placement:bottom-right] [--auto-close:false]">
        <button id="toggleFilterJalan" type="button"
          class="hs-dropdown-toggle bg-white border border-gray-300 shadow px-3 py-1 text-sm font-medium hover:bg-gray-100 focus:outline-none"
          aria-expanded="false" title="Filter Jalan Rusak">
          <i class="fa fa-filter"></i> Filter Jalan Rusak
        </button>
        <div id="filterJalanContainer"
          class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[220px] bg-white shadow border border-gray-200 p-4 mt-2"
          aria-labelledby="toggleFilterJalan">
          <div class="font-semibold mb-2">Filter Jalan Rusak</div>
          <div class="flex flex-col gap-2">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakRinganDropdown" checked>
              <span><i class="fa-solid fa-circle-exclamation text-yellow-400"></i> Rusak Ringan</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakSedangDropdown" checked>
              <span><i class="fa-solid fa-triangle-exclamation text-orange-400"></i> Rusak Sedang</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakBeratDropdown" checked>
              <span><i class="fa-solid fa-triangle-exclamation text-red-600"></i> Rusak Berat</span>
            </label>
          </div>
        </div>
      </div>
      {{-- END Filter jalan rusak --}}
    </div>
  </div>

  <div id="hs-sidebar-basic-usage"
    class="hs-overlay md:hidden [--auto-close:md] w-64 hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform h-full hidden fixed top-0 start-0 bottom-0 z-60 bg-white border-e border-gray-200"
    role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full ">
      {{-- START Sidebar (md ke bawah) --}}
      <div class="p-4 flex justify-between items-center gap-x-2">
        <a class="flex-none font-semibold text-xl text-black focus:outline-hidden focus:opacity-80" href="#"
          aria-label="Konfigurasi Peta">Konfigurasi Peta</a>
        <div class="-me-2">
          <button type="button"
            class="flex justify-center items-center gap-x-3 size-6 bg-white border border-gray-200 text-sm text-gray-600 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100"
            data-hs-overlay="#hs-sidebar-basic-usage">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
            <span class="sr-only">Close</span>
          </button>
        </div>
      </div>
      {{-- END Sidebar (md ke bawah) --}}

      <div
        class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300">
        <div class="pb-0 px-2 w-full flex flex-col flex-wrap">
          <ul class="space-y-1">
            {{-- START Filter jalan rusak (md ke bawah) --}}
            <li class="hs-accordion" id="sidebar-accordion-filter">
              <button type="button"
                class="hs-accordion-toggle w-full text-start flex items-center gap-x-1.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
                aria-expanded="false" aria-controls="sidebar-accordion-filter-content">
                <i class="fa fa-filter size-4"></i>
                Filter Jalan Rusak
                <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600"
                  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m18 15-6-6-6 6" />
                </svg>
                <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600"
                  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m6 9 6 6 6-6" />
                </svg>
              </button>
              <div id="sidebar-accordion-filter-content"
                class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden ps-8"
                role="region" aria-labelledby="sidebar-accordion-filter">
                <div class="pt-2 flex flex-col gap-2">
                  <label class="inline-flex items-center gap-2">
                    <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakRinganCollapse" checked>
                    <span><i class="fa-solid fa-circle-exclamation text-yellow-400"></i> Rusak Ringan</span>
                  </label>
                  <label class="inline-flex items-center gap-2">
                    <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakSedangCollapse" checked>
                    <span><i class="fa-solid fa-triangle-exclamation text-orange-400"></i> Rusak Sedang</span>
                  </label>
                  <label class="inline-flex items-center gap-2">
                    <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakBeratCollapse" checked>
                    <span><i class="fa-solid fa-triangle-exclamation text-red-600"></i> Rusak Berat</span>
                  </label>
                </div>
              </div>
            </li>
            {{-- END Filter jalan rusak (md ke bawah) --}}

            {{-- START Basemap (md ke bawah) --}}
            <li class="hs-accordion" id="sidebar-accordion-basemap">
              <button type="button"
                class="hs-accordion-toggle w-full text-start flex items-center gap-x-1.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
                aria-expanded="false" aria-controls="sidebar-accordion-basemap-content">
                <i class="fa fa-map size-4"></i>
                Basemap
                <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600"
                  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m18 15-6-6-6 6" />
                </svg>
                <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600"
                  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m6 9 6 6 6-6" />
                </svg>
              </button>
              <div id="sidebar-accordion-basemap-content"
                class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden ps-8"
                role="region" aria-labelledby="sidebar-accordion-basemap">
                <div class="pt-2">
                  <div id="basemapGalleryWidgetSidebar"></div>
                </div>
              </div>
            </li>
            {{-- END Basemap (md ke bawah) --}}
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('document.end')
  <script>
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
      // Map
      var map = new Map({
        basemap: "hybrid"
      });

      // Memunculkan map
      var view = new MapView({
        container: "arcgisMap",
        map: map,
        center: [117.1466, -0.5022],
        zoom: 12
      });
      view.ui.move("zoom", "bottom-right");

      // Locate GPS
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

      // Compass
      var compass = new Compass({
        view: view
      });
      view.ui.add(compass, {
        position: "bottom-left"
      });

      // Scale bar
      var scaleBar = new ScaleBar({
        view: view,
        unit: "metric"
      });
      view.ui.add(scaleBar, {
        position: "bottom-left"
      });

      // Search
      var searchWidget = new Search({
        view: view,
        container: "searchWidgetContainer",
        allPlaceholder: "Cari nama jalan atau lokasi",
        includeDefaultSources: true
      });

      // Basemap
      var basemapGallery = new BasemapGallery({
        view: view,
        container: "basemapGalleryWidget"
      });

      // Basemap (md ke bawah)
      var basemapGallerySidebar = new BasemapGallery({
        view: view,
        container: "basemapGalleryWidgetSidebar"
      });

      // Marker jalan rusak
      var graphicsLayer = new GraphicsLayer();
      map.add(graphicsLayer);

      // Icon marker jalan rusak
      var iconUrls = {
        ringan: "{{ asset('icons/belum-diperbaiki/rusak-ringan.svg') }}",
        sedang: "{{ asset('icons/belum-diperbaiki/rusak-sedang.svg') }}",
        berat: "{{ asset('icons/belum-diperbaiki/rusak-berat.svg') }}",
      };

      // Reverse geoccode
      async function getNamaJalan(lat, lon) {
        try {
          const response = await fetch(`/api/reverse-geocode?lat=${lat}&lon=${lon}`);
          const data = await response.json();
          return data.display_name || "Lokasi tidak diketahui";
        } catch (error) {
          console.error("Error fetching address:", error);
          return "Nama jalan tidak ditemukan";
        }
      }

      // Filter jalan rusak
      function filterMarkers() {
        const showRingan = document.querySelector('#jalanRusakRinganDropdown')?.checked ??
          document.querySelector('#jalanRusakRinganCollapse')?.checked ?? true;
        const showSedang = document.querySelector('#jalanRusakSedangDropdown')?.checked ??
          document.querySelector('#jalanRusakSedangCollapse')?.checked ?? true;
        const showBerat = document.querySelector('#jalanRusakBeratDropdown')?.checked ??
          document.querySelector('#jalanRusakBeratCollapse')?.checked ?? true;

        // Sinkronisasi checkbox yang ada di collapse dengan dropdown
        const syncCheckboxes = (type, isChecked) => {
          const dropdownCb = document.querySelector(`#jalanRusak${type}Dropdown`);
          const collapseCb = document.querySelector(`#jalanRusak${type}Collapse`);
          if (dropdownCb) dropdownCb.checked = isChecked;
          if (collapseCb) collapseCb.checked = isChecked;
        };
        syncCheckboxes('Ringan', showRingan);
        syncCheckboxes('Sedang', showSedang);
        syncCheckboxes('Berat', showBerat);

        // Memunculkan marker jaaln rusak
        graphicsLayer.graphics.forEach(function(graphic) {
          const keparahan = graphic.attributes.tingkat_keparahan;
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
        });
      }

      ['Ringan', 'Sedang', 'Berat'].forEach(type => {
        const dropdownCb = document.querySelector(`#jalanRusak${type}Dropdown`);
        const collapseCb = document.querySelector(`#jalanRusak${type}Collapse`);

        [dropdownCb, collapseCb].forEach(cb => {
          if (cb) {
            cb.addEventListener('change', (e) => {
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
          // Filter hanya yang belum diperbaiki
          data.filter(jalan => !jalan.sudah_diperbaiki).forEach(function(jalan) {
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
              visible: true
            });

            // Pop up informasi jalan rusak
            graphic.popupTemplate = {
              title: "Memuat nama jalan...",
              content: function() {
                var container = document.createElement("div");
                // SPLIDEJS CAROUSEL
                let fotoHtml = '';
                if (Array.isArray(jalan.foto) && jalan.foto.length > 0) {
                  if (jalan.foto.length > 1) {
                    const splideId = 'splide-popup-foto-' + jalan.id;
                    fotoHtml = `
                      <div class="splide" id="${splideId}">
                        <div class="splide__track">
                          <ul class="splide__list">
                            ${jalan.foto.map(f =>
                              `<li class="splide__slide flex items-center justify-center h-full">
                                <img src="${f}" alt="Foto Jalan Rusak" class="block mx-auto max-w-full max-h-full object-contain">
                              </li>`
                            ).join('')}
                          </ul>
                        </div>
                      </div>
                    `;
                  } else {
                    fotoHtml = `<img src="${jalan.foto[0]}" alt="Foto Jalan Rusak" class="h-[100px]" />`;
                  }
                } else {
                  fotoHtml = '<span class="text-xs text-gray-400">Tidak ada foto</span>';
                }
                container.innerHTML = `
                  <div><b>Deskripsi:</b> ${jalan.deskripsi}</div>
                  <div><b>Longitude:</b> ${jalan.longitude}</div>
                  <div><b>Latitude:</b> ${jalan.latitude}</div>
                  <div><b>Waktu dibuat:</b> ${formatTanggalIndonesia(jalan.created_at)}</div>
                  <div><b>Foto:</b><br>
                    <div class="foto-viewer-popup">
                      ${fotoHtml}
                    </div>
                  </div>
                `;

                fetch(`/api/reverse-geocode?lat=${jalan.latitude}&lon=${jalan.longitude}`)
                  .then(res => res.json())
                  .then(data => {
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

                setTimeout(function() {
                  // Inisialisasi SplideJS jika ada carousel
                  if (Array.isArray(jalan.foto) && jalan.foto.length > 1) {
                    const splideId = 'splide-popup-foto-' + jalan.id;
                    const splideEl = container.querySelector('#' + splideId);
                    if (splideEl && window.Splide) {
                      new Splide(splideEl, {
                        type: 'loop',
                        perPage: 1,
                        height: '100px',
                        width: '250px',
                        drag: true,
                      }).mount();
                    }
                  }
                  // Inisialisasi Viewer.js untuk semua gambar di popup
                  var popupFotoWrappers = container.querySelectorAll('.foto-viewer-popup');
                  popupFotoWrappers.forEach(function(wrapper) {
                    if (!wrapper.viewerInstance) {
                      wrapper.viewerInstance = new Viewer(wrapper, {
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
                    wrapper.querySelectorAll('img').forEach(function(img) {
                      img.addEventListener('click', function() {
                        wrapper.viewerInstance.show();
                      });
                    });
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
  </script>
@endsection
