@extends('admin.layouts.jalan-rusak')

@section('slot')
<div id="arcgisMap" class="w-full h-full">
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
        <div class="text-sm flex items-center gap-x-0.5"><i class="fa-solid fa-map-location-dot"></i> Peta</div>
      </label>
      <label class="inline-flex items-center gap-1.5 flex-nowrap">
        <input type="radio" name="visualisasiPage" value="tabel" class="form-radio accent-kuning">
        <div class="text-sm flex items-center gap-x-0.5"><i class="fa-solid fa-table-list"></i> Tabel</div>
      </label>
    </div>
  </div>

  <div class="w-full p-4 rounded-lg shadow-xl sm:p-8 mt-4">
    <div class="relative overflow-x-auto text-sm md:text-base">
      <table id="jalan-rusak-table" class="stripe hover row-border table-auto w-full" style="width:100% !important">
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
              <span class="bg-green-100 text-green-800 text-xs font-bold px-1 py-0.5 rounded border border-green-400">
                Sudah diperbaiki
              </span>
              @else
              <span class="bg-gray-100 text-gray-800 text-xs font-bold px-1 py-0.5 rounded border border-gray-500">
                Belum diperbaiki
              </span>
              @endif
            </td>
            <td>
              <button class="rounded-lg bg-biru px-3 py-2 text-xs font-medium text-white"
                onclick="openModal('foto_{{ $item->id }}')">
                <i class="fa-solid fa-eye"></i>
              </button>

              {{-- Modal foto --}}
              <div id="foto_{{ $item->id }}" class="hidden fixed inset-0 z-50">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-4 max-w-2xl w-full">
                  <div class="bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between p-4 border-b">
                      <h3 class="text-xl font-semibold">Foto Jalan Rusak</h3>
                      <button onclick="closeModal('foto_{{ $item->id }}')" class="text-gray-400 hover:text-gray-900">
                        <i class="fa-solid fa-times"></i>
                      </button>
                    </div>
                    <div class="p-4">
                      <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Jalan Rusak" class="w-full rounded-lg">
                    </div>
                  </div>
                </div>
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
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
          <li class="hs-accordion" id="sidebar-accordion-visualisasi">
            <button type="button"
              class="hs-accordion-toggle w-full text-start flex items-center gap-x-1.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
              aria-expanded="false" aria-controls="sidebar-accordion-visualisasi-content">
              <i class="fa-solid fa-eye size-4"></i>
              Visualisasi Data
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
            <div id="sidebar-accordion-visualisasi-content"
              class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden ps-8"
              role="region" aria-labelledby="sidebar-accordion-visualisasi">
              <div class="pt-2 flex flex-col gap-2">
                <label class="inline-flex items-center gap-2">
                  <input type="radio" name="visualisasiCollapse" value="peta" class="form-radio accent-kuning" checked>
                  <span><i class="fa-solid fa-map-location-dot"></i> Peta</span>
                </label>
                <label class="inline-flex items-center gap-2">
                  <input type="radio" name="visualisasiCollapse" value="tabel" class="form-radio accent-kuning">
                  <span><i class="fa-solid fa-table-list"></i> Tabel</span>
                </label>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Body -->
  </div>
</div>
{{-- End Sidebar --}}

<script>
  $(document).ready(function() {
    $('#jalan-rusak-table').DataTable({
      responsive: true,
      pageLength: 10,
      lengthMenu: [10, 20, 50, 100],
      columnDefs: [
        { targets: 0, type: 'num' }, 
        { targets: '_all', type: 'string' } 
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
  });

  function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
  }

  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }
</script>
@endsection