@extends('guest.layouts.beranda')

@section('slot')
<div id="arcgisMap" class="w-full h-full relative">
  <div class="absolute top-4 right-4 z-20 flex space-x-2.5">
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
        class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow border border-gray-200 p-4 mt-2"
        aria-labelledby="toggleFilterJalan">
        <div class="font-semibold mb-2">Filter Jalan Rusak</div>
        <div class="flex flex-col gap-2">
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" class="form-checkbox accent-kuning" id="rusakKecil" checked>
            <span>Rusak Kecil</span>
          </label>
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" class="form-checkbox accent-kuning" id="rusakSedang" checked>
            <span>Rusak Sedang</span>
          </label>
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" class="form-checkbox accent-kuning" id="rusakBesar" checked>
            <span>Rusak Besar</span>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection