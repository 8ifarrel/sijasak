@extends('guest.layouts.beranda')

@section('slot')
<div id="arcgisMap" class="w-full h-full relative">
  <div class="absolute top-4 left-4 z-20">
    {{-- Search --}}
    <div class="flex items-center bg-white border border-gray-300 shadow">
      {{-- Hamburger: hanya md ke bawah --}}
      <button type="button"
        class="block md:hidden px-2 border-e border-gray-300"
        aria-label="Open sidebar"
        data-hs-overlay="#hs-sidebar-basic-usage">
        <i class="fa-solid fa-bars" style="color: #808080;"></i>
      </button>
      <div id="searchWidgetContainer"></div>
    </div>
  </div>

  {{-- Basemap & Filter: hanya md ke atas --}}
  <div class="absolute top-4 right-4 z-20 gap-2 hidden md:flex">
    {{-- Basemap --}}
    <div class="hs-dropdown relative inline-flex [--placement:bottom-right] [--auto-close:false]">
      <button id="toggleBasemapGallery"
        type="button"
        class="hs-dropdown-toggle bg-white border border-gray-300 shadow px-3 py-1 text-sm font-medium hover:bg-gray-100 focus:outline-none"
        aria-expanded="false"
        title="Ganti Basemap">
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
      <button id="toggleFilterJalan"
        type="button"
        class="hs-dropdown-toggle bg-white border border-gray-300 shadow px-3 py-1 text-sm font-medium hover:bg-gray-100 focus:outline-none"
        aria-expanded="false"
        title="Filter Jalan Rusak">
        <i class="fa fa-filter"></i> Filter Jalan Rusak
      </button>
      <div id="filterJalanContainer"
        class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[220px] bg-white shadow border border-gray-200 p-4 mt-2"
        aria-labelledby="toggleFilterJalan">
        <div class="font-semibold mb-2">Filter Jalan Rusak</div>
        <div class="flex flex-col gap-2">
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakRingan" checked>
            <span><i class="fa-solid fa-circle-exclamation text-yellow-400"></i> Rusak Ringan</span>
          </label>
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakSedang" checked>
            <span><i class="fa-solid fa-triangle-exclamation text-orange-400"></i> Rusak Sedang</span>
          </label>
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" class="form-checkbox accent-kuning" id="jalanRusakBerat" checked>
            <span><i class="fa-solid fa-triangle-exclamation text-red-600"></i> Rusak Berat</span>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Sidebar: hanya md ke bawah --}}
<div id="hs-sidebar-basic-usage" class="hs-overlay md:hidden [--auto-close:md] w-64 hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform h-full hidden fixed top-0 start-0 bottom-0 z-60 bg-white border-e border-gray-200" role="dialog" tabindex="-1" aria-label="Sidebar" >
  <div class="relative flex flex-col h-full max-h-full ">
    <!-- Header -->
    <header class="p-4 flex justify-between items-center gap-x-2">
      <a class="flex-none font-semibold text-xl text-black focus:outline-hidden focus:opacity-80" href="#" aria-label="Konfigurasi Peta">Konfigurasi Peta</a>
      <div class="-me-2">
        <!-- Close Button -->
        <button type="button" class="flex justify-center items-center gap-x-3 size-6 bg-white border border-gray-200 text-sm text-gray-600 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100" data-hs-overlay="#hs-sidebar-basic-usage">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          <span class="sr-only">Close</span>
        </button>
      </div>
    </header>
    <!-- End Header -->

    <!-- Body -->
    <nav class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300">
      <div class="pb-0 px-2 w-full flex flex-col flex-wrap">
        <ul class="space-y-1">
          <li class="hs-accordion" id="sidebar-accordion-filter">
            <button type="button"
              class="hs-accordion-toggle w-full text-start flex items-center gap-x-1.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
              aria-expanded="false"
              aria-controls="sidebar-accordion-filter-content">
              <i class="fa fa-filter size-4"></i>
              Filter Jalan Rusak
              <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
              <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="sidebar-accordion-filter-content" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden ps-8" role="region" aria-labelledby="sidebar-accordion-filter">
              <div class="pt-2 flex flex-col gap-2">
                <label class="inline-flex items-center gap-2">
                  <input type="checkbox" class="form-checkbox accent-yellow-500" id="sidebarJalanRusakRingan" checked>
                  <span><i class="fa-solid fa-circle-exclamation text-yellow-400"></i> Rusak Ringan</span>
                </label>
                <label class="inline-flex items-center gap-2">
                  <input type="checkbox" class="form-checkbox accent-yellow-500" id="sidebarJalanRusakSedang" checked>
                  <span><i class="fa-solid fa-triangle-exclamation text-orange-400"></i> Rusak Sedang</span>
                </label>
                <label class="inline-flex items-center gap-2">
                  <input type="checkbox" class="form-checkbox accent-yellow-500" id="sidebarJalanRusakBerat" checked>
                  <span><i class="fa-solid fa-triangle-exclamation text-red-600"></i> Rusak Berat</span>
                </label>
              </div>
            </div>
          </li>
          <li class="hs-accordion" id="sidebar-accordion-basemap">
            <button type="button"
              class="hs-accordion-toggle w-full text-start flex items-center gap-x-1.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
              aria-expanded="false"
              aria-controls="sidebar-accordion-basemap-content">
              <i class="fa fa-map size-4"></i>
              Basemap
              <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
              <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="sidebar-accordion-basemap-content" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden ps-8" role="region" aria-labelledby="sidebar-accordion-basemap">
              <div class="pt-2">
                <div id="basemapGalleryWidgetSidebar"></div>
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
@endsection