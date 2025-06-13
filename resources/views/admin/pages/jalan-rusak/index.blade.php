@extends('admin.layouts.jalan-rusak')

@section('document.head')
  <style>
    .splide__pagination {
      counter-reset: pagination-num;
      display: flex !important;
      justify-content: center;
      gap: 1px;
      margin-top: 0.5rem;
      position: static !important;
      margin-top: 5px !important;
    }

    .splide__pagination__page:before {
      counter-increment: pagination-num;
      content: counter(pagination-num);
      font-size: 0.95em;
      font-weight: 600;
    }

    .splide__pagination__page {
      background: #e5e7eb;
      color: #222;
      border-radius: 0 !important;
      width: 1.5rem;
      height: 1.5rem;
      min-width: 1.5rem;
      min-height: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      font-weight: 500;
      opacity: 1 !important;
      border: 2px solid #d1d5db;
      /* border abu tailwind: gray-300 */
      margin: 0 2px;
      transition: background 0.2s, color 0.2s, border 0.2s;
      transform: none !important;
    }

    .splide__pagination__page.is-active,
    .splide__pagination__page:focus {
      background: #fde047 !important;
      /* kuning tailwind: yellow-300 */
      color: #222 !important;
      outline: none;
      border: 2px solid #facc15 !important;
      /* border kuning tailwind: yellow-400 */
      box-shadow: 0 0 0 2px #fde04755;
      transform: none !important;
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

  {{-- DataTables --}}
  <link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css" rel="stylesheet" />
  <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

  {{-- SplideJS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
@endsection

@section('slot')
  <div
    class="flex-auto w-full sm:w-[calc(100vw_-_16rem)] h-[calc(var(--vh,_1vh)*100_-_69px)] sm:h-[calc(100vh_-_69px)] absolute bottom-0 right-0">
    <div id="arcgisMap" class="w-full h-full" style="min-height: calc(var(--vh, 1vh) * 100 - 69px);">
      <div class="absolute top-4 left-4 z-20">
        {{-- Search --}}
        <div class="flex items-center bg-white border border-gray-300 shadow">
          {{-- Hamburger: hanya lg ke bawah --}}
          <button type="button" class="block lg:hidden px-2 border-e border-gray-300" aria-label="Open sidebar"
            data-hs-overlay="#hs-sidebar-basic-usage">
            <i class="fa-solid fa-bars" style="color: #808080;"></i>
          </button>
          <div id="searchWidgetContainer"></div>
        </div>
      </div>

      {{-- Basemap & Filter: hanya lg ke atas --}}
      <div class="absolute top-4 right-4 z-20 gap-2 hidden lg:flex">
        {{-- Basemap --}}
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

        {{-- Filter Jalan Rusak --}}
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

        {{-- Visualisasi Data --}}
        <div class="hs-dropdown relative inline-flex [--placement:bottom-right] [--auto-close:false]">
          <button id="toggleVisualisasi" type="button"
            class="hs-dropdown-toggle bg-white border border-gray-300 shadow px-3 py-1 text-sm font-medium hover:bg-gray-100 focus:outline-none"
            aria-expanded="false" title="Visualisasi Data">
            <i class="fa-solid fa-eye"></i> Visualisasi Data
          </button>
          <div id="visualisasiContainer"
            class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[220px] bg-white shadow border border-gray-200 p-4 mt-2"
            aria-labelledby="toggleVisualisasi">
            <div class="font-semibold mb-2">Visualisasi Data</div>
            <div class="flex flex-col gap-2">
              <label class="inline-flex items-center gap-2">
                <input type="radio" name="visualisasiDropdown" value="peta" class="form-radio accent-kuning" checked>
                <span><i class="fa-solid fa-map-location-dot"></i> Peta</span>
              </label>
              <label class="inline-flex items-center gap-2">
                <input type="radio" name="visualisasiDropdown" value="tabel" class="form-radio accent-kuning">
                <span><i class="fa-solid fa-table-list"></i> Tabel</span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Data Table View --}}
    <div id="dataTable" class="w-full h-full hidden p-4 sm:p-6">
      <div class="flex justify-between items-center">
        <h5 class="text-2xl md:text-3xl font-bold text-gray-900">
          Daftar Jalan Rusak
        </h5>

        <div class="flex gap-2 md:gap-4 items-center">
          <label class="flex items-center gap-1.5 flex-nowrap">
            <input type="radio" name="visualisasiPage" value="peta" class="form-radio accent-kuning" checked>
            <div class="text-sm flex items-center gap-x-0.5"><i class="fa-solid fa-map-location-dot"></i> Peta
            </div>
          </label>
          <label class="inline-flex items-center gap-1.5 flex-nowrap">
            <input type="radio" name="visualisasiPage" value="tabel" class="form-radio accent-kuning">
            <div class="text-sm flex items-center gap-x-0.5"><i class="fa-solid fa-table-list"></i> Tabel</div>
          </label>
        </div>
      </div>

      <div class="w-full p-4 rounded-lg shadow-xl sm:p-8 my-4">
        <div class="relative overflow-x-auto text-sm md:text-base">
          <table id="jalan-rusak-table" class="stripe hover row-border table-auto w-full"
            style="width:100% !important">
            <thead>
              <tr>
                <th>#</th>
                <th>Deskripsi</th>
                <th>Tingkat Keparahan</th>
                <th class="min-w-[129px]">Status Perbaikan</th>
                <th>Foto</th>
                <th>Koordinat</th>
                <th>Diajukan Pada</th>
                <th>Kelola</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($jalan_rusak as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->deskripsi }}</td>
                  <td>
                    @if ($item->tingkat_keparahan == 'ringan')
                      <span
                        class="bg-yellow-100 text-yellow-800 text-xs font-bold px-1 py-0.5 rounded border border-yellow-400">
                        Ringan
                      </span>
                    @elseif ($item->tingkat_keparahan == 'sedang')
                      <span
                        class="bg-orange-100 text-orange-800 text-xs font-bold px-1 py-0.5 rounded border border-orange-400">
                        Sedang
                      </span>
                    @else
                      <span class="bg-red-100 text-red-800 text-xs font-bold px-1 py-0.5 rounded border border-red-400">
                        Berat
                      </span>
                    @endif
                  </td>
                  <td>
                    @if ($item->sudah_diperbaiki)
                      <span
                        class="bg-green-100 text-green-800 text-xs font-bold px-1 py-0.5 rounded border border-green-400">
                        Sudah diperbaiki
                      </span>
                    @else
                      <span
                        class="bg-gray-100 text-gray-800 text-xs font-bold px-1 py-0.5 rounded border border-gray-500">
                        Belum diperbaiki
                      </span>
                    @endif
                  </td>
                  <td>
                    <div class="foto-viewer-wrapper">
                      @if ($item->foto->count() > 0)
                        <div class="splide splide-foto-{{ $item->id }} w-full">
                          <div class="splide__track">
                            <ul class="splide__list">
                              @foreach ($item->foto as $foto)
                                <li class="splide__slide flex items-center justify-center h-full"> {{-- Tambah Tailwind center --}}
                                  <img src="{{ asset('storage/' . $foto->foto) }}" alt="Foto Jalan Rusak"
                                    class="foto-viewer-img block mx-auto max-w-full max-h-full object-contain" />
                                  {{-- Tambah Tailwind --}}
                                </li>
                              @endforeach
                            </ul>
                          </div>
                        </div>
                      @else
                        <span class="text-xs text-gray-400">Tidak ada foto</span>
                      @endif
                    </div>
                  </td>
                  <td>{{ $item->longitude }}, {{ $item->latitude }}</td>
                  <td>{{ $item->created_at->translatedFormat('d/m/Y H:i') }}</td>
                  <td>
                    <a href="{{ route('admin.jalan-rusak.edit', $item->id) }}"
                      class="rounded-lg bg-biru px-3 py-2 text-xs font-medium text-white">
                      <i class="fa-solid fa-edit"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Sidebar: hanya lg ke bawah --}}
    <div id="hs-sidebar-basic-usage"
      class="hs-overlay lg:hidden [--auto-close:lg] w-64 hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform h-full hidden fixed top-0 start-0 bottom-0 z-60 bg-white border-e border-gray-200"
      role="dialog" tabindex="-1" aria-label="Sidebar">
      <div class="relative flex flex-col h-full max-h-full ">
        <!-- Header -->
        <header class="p-4 flex justify-between items-center gap-x-2">
          <a class="flex-none font-semibold text-xl text-black focus:outline-hidden focus:opacity-80" href="#"
            aria-label="Konfigurasi Peta">Konfigurasi Peta</a>
          <div class="-me-2">
            <!-- Close Button -->
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
        </header>
        <!-- End Header -->

        <!-- Body -->
        <nav
          class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300">
          <div class="pb-0 px-2 w-full flex flex-col flex-wrap">
            <ul class="space-y-1">
              <li class="hs-accordion" id="sidebar-accordion-filter">
                <button type="button"
                  class="hs-accordion-toggle w-full text-start flex items-center gap-x-1.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
                  aria-expanded="false" aria-controls="sidebar-accordion-filter-content">
                  <i class="fa fa-filter size-4"></i>
                  Filter Jalan Rusak
                  <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m18 15-6-6-6 6" />
                  </svg>
                  <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6" />
                  </svg>
                </button>
                <div id="sidebar-accordion-filter-content"
                  class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden ps-8"
                  role="region" aria-labelledby="sidebar-accordion-filter">
                  <div class="pt-2 flex flex-col gap-2">
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakRinganCollapse" checked>
                      <span><i class="fa-solid fa-circle-exclamation text-yellow-400"></i> Rusak
                        Ringan</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakSedangCollapse" checked>
                      <span><i class="fa-solid fa-triangle-exclamation text-orange-400"></i> Rusak
                        Sedang</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakBeratCollapse" checked>
                      <span><i class="fa-solid fa-triangle-exclamation text-red-600"></i> Rusak
                        Berat</span>
                    </label>
                  </div>
                </div>
              </li>
              <li class="hs-accordion" id="sidebar-accordion-basemap">
                <button type="button"
                  class="hs-accordion-toggle w-full text-start flex items-center gap-x-1.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
                  aria-expanded="false" aria-controls="sidebar-accordion-basemap-content">
                  <i class="fa fa-map size-4"></i>
                  Basemap
                  <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m18 15-6-6-6 6" />
                  </svg>
                  <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
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
              <li class="hs-accordion" id="sidebar-accordion-visualisasi">
                <button type="button"
                  class="hs-accordion-toggle w-full text-start flex items-center gap-x-1.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
                  aria-expanded="false" aria-controls="sidebar-accordion-visualisasi-content">
                  <i class="fa-solid fa-eye size-4"></i>
                  Visualisasi Data
                  <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m18 15-6-6-6 6" />
                  </svg>
                  <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6" />
                  </svg>
                </button>
                <div id="sidebar-accordion-visualisasi-content"
                  class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden ps-8"
                  role="region" aria-labelledby="sidebar-accordion-visualisasi">
                  <div class="pt-2 flex flex-col gap-2">
                    <label class="inline-flex items-center gap-2">
                      <input type="radio" name="visualisasiCollapse" value="peta" class="form-radio accent-kuning"
                        checked>
                      <span><i class="fa-solid fa-map-location-dot"></i> Peta</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                      <input type="radio" name="visualisasiCollapse" value="tabel"
                        class="form-radio accent-kuning">
                      <span><i class="fa-solid fa-table-list"></i> Tabel</span>
                    </label>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection

@section('document.end')
  <script>
    $(document).ready(function() {
      $('#jalan-rusak-table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [10, 20, 50, 100],
        columnDefs: [{
            targets: 0,
            type: 'num'
          },
          {
            targets: '_all',
            type: 'string'
          }
        ],
        language: {
          search: "Cari:",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
          infoFiltered: "(disaring dari _MAX_ total data)",
          zeroRecords: "Tidak ada data yang cocok",
          paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "Selanjutnya",
            previous: "Sebelumnya"
          }
        }
      });

      // Inisialisasi SplideJS untuk setiap row
      @foreach ($jalan_rusak as $item)
        @if ($item->foto->count() > 1)
          new Splide('.splide-foto-{{ $item->id }}', {
            type: 'loop',
            perPage: 1,
            height: '100px',
            width: '250px',
            drag: true,
          }).mount();
        @endif
      @endforeach

      // Inisialisasi Viewer.js untuk semua gambar di carousel
      document.querySelectorAll('.foto-viewer-wrapper').forEach(function(wrapper) {
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
            fullscreen: false,
          });
        }
        wrapper.querySelectorAll('img').forEach(function(img) {
          img.addEventListener('click', function() {
            wrapper.viewerInstance.show();
          });
        });
      });

      // --- Visualisasi Data: Toggle Map/Table ---
      function showVisualisasi(mode) {
        if (mode === 'tabel') {
          $('#arcgisMap').hide();
          $('#dataTable').show();
        } else {
          $('#arcgisMap').show();
          $('#dataTable').hide();
        }
      }

      // Radio di sidebar dan di atas tabel
      $('input[name="visualisasiDropdown"], input[name="visualisasiPage"], input[name="visualisasiCollapse"]').on('change', function(e) {
        const val =
          $('input[name="visualisasiDropdown"]:checked').val() ||
          $('input[name="visualisasiPage"]:checked').val() ||
          $('input[name="visualisasiCollapse"]:checked').val();

        // Setiap menekan radio button peta, halaman refresh
        if ($(e.target).val() === 'peta') {
          window.location.reload();
          return;
        }

        showVisualisasi(val);

        // Sinkronkan semua radio
        $('input[name="visualisasiDropdown"]').prop('checked', val === 'tabel' ? false : true);
        $('input[name="visualisasiPage"]').prop('checked', val === 'tabel' ? false : true);
        $('input[name="visualisasiCollapse"]').prop('checked', val === 'tabel' ? false : true);
        if (val === 'tabel') {
          $('input[name="visualisasiDropdown"][value="tabel"]').prop('checked', true);
          $('input[name="visualisasiPage"][value="tabel"]').prop('checked', true);
          $('input[name="visualisasiCollapse"][value="tabel"]').prop('checked', true);
        } else {
          $('input[name="visualisasiDropdown"][value="peta"]').prop('checked', true);
          $('input[name="visualisasiPage"][value="peta"]').prop('checked', true);
          $('input[name="visualisasiCollapse"][value="peta"]').prop('checked', true);
        }
      });

      // Default: tampilkan peta
      showVisualisasi('peta');
    });

    function openModal(id) {
      document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
      document.getElementById(id).classList.add('hidden');
    }
  </script>

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

      var basemapGallery = new BasemapGallery({
        view: view,
        container: "basemapGalleryWidget"
      });

      var basemapGallerySidebar = new BasemapGallery({
        view: view,
        container: "basemapGalleryWidgetSidebar"
      });

      var graphicsLayer = new GraphicsLayer();
      map.add(graphicsLayer);

      var iconUrls = {
        ringan: "{{ asset('icons/belum-diperbaiki/rusak-ringan.svg') }}",
        sedang: "{{ asset('icons/belum-diperbaiki/rusak-sedang.svg') }}",
        berat: "{{ asset('icons/belum-diperbaiki/rusak-berat.svg') }}"
      };

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

      function filterMarkers() {
        const showRingan = document.querySelector('#jalanRusakRinganDropdown')?.checked ??
          document.querySelector('#jalanRusakRinganCollapse')?.checked ?? true;
        const showSedang = document.querySelector('#jalanRusakSedangDropdown')?.checked ??
          document.querySelector('#jalanRusakSedangCollapse')?.checked ?? true;
        const showBerat = document.querySelector('#jalanRusakBeratDropdown')?.checked ??
          document.querySelector('#jalanRusakBeratCollapse')?.checked ?? true;
        const showSudahDiperbaiki = document.querySelector('#jalanRusakSudahDiperbaikiDropdown')?.checked ??
          document.querySelector('#jalanRusakSudahDiperbaikiCollapse')?.checked ?? true;

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

      setTimeout(addFilterCheckboxListeners, 200);

      fetch('/api/jalan-rusak')
        .then(res => res.json())
        .then(data => {
          data.forEach(function(jalan) {
            var point = {
              type: "point",
              longitude: jalan.longitude,
              latitude: jalan.latitude
            };

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

            graphic.popupTemplate = {
              title: "Memuat nama jalan...",
              content: function() {
                var container = document.createElement("div");
                // SplideJS carousel untuk foto
                let fotoHtml = '';
                if (Array.isArray(jalan.foto) && jalan.foto.length > 0) {
                  if (jalan.foto.length > 1) {
                    // SplideJS carousel
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
                    fotoHtml =
                      `<img src="${jalan.foto[0]}" alt="Foto Jalan Rusak" class="h-[100px]"`;
                  }
                } else {
                  fotoHtml = '<span class="text-xs text-gray-400">Tidak ada foto</span>';
                }
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
											<div class="foto-viewer-popup">
													${fotoHtml}
											</div>
									</div>
								`;

                fetch(
                    `/api/reverse-geocode?lat=${jalan.latitude}&lon=${jalan.longitude}`
                  )
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
        });
    });
  </script>
@endsection
