@extends('admin.layouts.jalan-rusak')

@section('slot')
  <div class="mt-[63px] sm:mt-[68px] flex-auto w-full sm:w-[calc(100vw_-_16rem)] sm:absolute sm:right-0 sm:left-auto">
    <div class="p-4 sm:p-6">
      <h1 class="font-semibold text-2xl md:text-3xl">
        {{ $page_title }}
      </h1>

      <main class="mt-4">
        <form action="{{ route('admin.jalan-rusak.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
          @csrf

          {{-- Deskripsi --}}
          <div>
            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" required
              class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
              <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
          </div>

          {{-- Foto --}}
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Foto</label>
            <div id="foto-input-list" class="flex flex-wrap gap-2 mb-2">
              <!-- Foto input akan di-generate oleh JS -->
            </div>
            @error('foto')
              <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
            @error('foto.*')
              <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
          </div>
          {{-- Script Preview --}}

          <div class="flex flex-col gap-y-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-3">
              {{-- Longitude --}}
              <div>
                <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-1">Longitude</label>
                <div class="flex gap-2">
                  <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude') }}"
                    required
                    class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2"
                    placeholder="-6.1234567">
                </div>
                @error('longitude')
                  <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
              </div>

              {{-- Latitude --}}
              <div>
                <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-1">Latitude</label>
                <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude') }}" required
                  class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2"
                  placeholder="106.1234567">
                @error('latitude')
                  <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="flex gap-2">
              <button type="button" id="detect-location-gps-btn"
                class="inline-flex items-center px-3 py-2 rounded-lg border border-primary-500 text-primary-600 bg-white hover:bg-primary-50 transition text-sm font-medium"
                title="Deteksi lokasi sekarang">
                <i class="fa-solid fa-location-crosshairs mr-1"></i>
                Deteksi dari GPS
              </button>
              {{-- Tombol Pilih dari Peta --}}
              <button type="button" id="select-location-map-btn"
                class="inline-flex items-center px-3 py-2 rounded-lg border border-primary-500 text-primary-600 bg-white hover:bg-primary-50 transition text-sm font-medium"
                aria-haspopup="dialog" aria-expanded="false" aria-controls="modal-pilih-lokasi-peta"
                data-hs-overlay="#modal-pilih-lokasi-peta" title="Pilih lokasi dari peta">
                <i class="fa-solid fa-map-location-dot mr-1"></i>
                Pilih dari peta
              </button>
            </div>
          </div>

          {{-- Tingkat Keparahan --}}
          <div>
            <label for="tingkat_keparahan" class="block text-sm font-semibold text-gray-700 mb-1">Tingkat
              Keparahan</label>
            <select id="tingkat_keparahan" name="tingkat_keparahan" required
              class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2">
              <option value="">Pilih tingkat keparahan</option>
              <option value="ringan" {{ old('tingkat_keparahan') == 'ringan' ? 'selected' : '' }}>Ringan
              </option>
              <option value="sedang" {{ old('tingkat_keparahan') == 'sedang' ? 'selected' : '' }}>Sedang
              </option>
              <option value="berat" {{ old('tingkat_keparahan') == 'berat' ? 'selected' : '' }}>Berat
              </option>
            </select>
            @error('tingkat_keparahan')
              <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div>
            <button type="submit"
              class="bg-biru text-kuning preline-btn preline-btn-primary w-auto block py-2 px-3 font-semibold rounded-lg shadow hover:shadow-md transition">
              <i class="fa-solid fa-plus me-1"></i>Tambah
            </button>
          </div>
        </form>
      </main>
    </div>
  </div>

  {{-- Modal Peta Pilih Lokasi --}}
  <div id="modal-pilih-lokasi-peta"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="modal-pilih-lokasi-peta-label">
    <div
      class="hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-2xl sm:w-full m-3 sm:mx-auto">
      <div class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl pointer-events-auto">
        <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
          <h3 id="modal-pilih-lokasi-peta-label" class="font-bold text-gray-800">
            Pilih Lokasi dari Peta
          </h3>
          <button type="button"
            class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none"
            aria-label="Close" data-hs-overlay="#modal-pilih-lokasi-peta">
            <span class="sr-only">Close</span>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M18 6 6 18"></path>
              <path d="m6 6 12 12"></path>
            </svg>
          </button>
        </div>
        <div class="m-4 overflow-y-auto relative">
          <div class="absolute top-4 left-4 z-20">
            <div class="flex items-center bg-white border border-gray-300 shadow">
              <div id="searchWidgetPilihLokasi"></div>
            </div>
          </div>
          <div class="absolute right-auto left-4 top-15 sm:left-auto sm:top-4 sm:right-4 z-20 gap-2">
            <div class="hs-dropdown relative inline-flex [--placement:bottom-right] [--auto-close:false]">
              <button type="button"
                class="hs-dropdown-toggle bg-white border border-gray-300 shadow px-3 py-1 text-sm font-medium hover:bg-gray-100 focus:outline-none"
                aria-expanded="false" title="Ganti Basemap">
                <i class="fa fa-map"></i> Basemap
              </button>
              <div
                class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[220px] max-h-[230px] sm:max-h-[270px] overflow-y-auto bg-white shadow border border-gray-200 p-2 mt-2">
                <div id="basemapGalleryPilihLokasi"></div>
              </div>
            </div>
          </div>
          <div style="position:relative;">
            <div id="arcgisMapPilihLokasi" style="width:100%;height:350px;border-radius:8px;"></div>
          </div>
          <div class="flex justify-between items-start gap-x-4 pt-3 border-t border-gray-200">
            <div class="text-sm text-gray-700" id="selected-coords-info">
              Pilih lokasi pada peta untuk mendapatkan longitude dan latitude.
            </div>
            <button id="confirm-pilih-lokasi-btn"
              class="bg-biru text-kuning hover:bg-kuning hover:text-biru font-semibold inline-flex items-center px-3 py-2 rounded-lg hover:bg-primary-700 transition text-nowrap"
              disabled>Pilih</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('document.end')
  <script>
    // Dynamic foto input + ViewerJS (mengikuti pola edit.blade.php)
    document.addEventListener('DOMContentLoaded', function() {
      function createFotoInput() {
        const div = document.createElement('div');
        div.className = 'foto-input-item flex flex-col items-center';
        div.innerHTML = `
          <div class="relative group foto-viewer-wrapper w-28 h-28 sm:w-32 sm:h-32">
            <label class="flex flex-col items-center justify-center w-full h-full border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden m-0">
              <div class="foto-placeholder flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-1h10v1a1 1 0 01-1 1h-4z" />
                </svg>
                <p class="mb-1 text-xs text-gray-500 font-semibold text-center">Klik untuk upload</p>
                <p class="text-xs text-gray-400 text-center">PNG, JPG, JPEG <br> (max 2MB)</p>
              </div>
              <img class="foto-preview hidden absolute inset-0 w-full h-full object-contain rounded-lg bg-white" />
              <input name="foto[]" type="file" accept="image/*" class="hidden foto-input" />
            </label>
            <button type="button"
              class="w-6 h-6 bg-white rounded-full text-red-500 hover:text-red-700 shadow-lg border border-black flex items-center justify-center absolute top-1 right-1 z-10 remove-foto-btn hidden"
              title="Hapus foto">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
        `;
        return div;
      }

      const fotoInputList = document.getElementById('foto-input-list');

      function updateRemoveButtons() {
        const items = fotoInputList.querySelectorAll('.foto-input-item');
        items.forEach((item) => {
          const removeBtn = item.querySelector('.remove-foto-btn');
          if (!removeBtn) return;
          // Untuk foto baru, tombol x hanya muncul jika sudah ada file (preview sudah tampil)
          const label = item.querySelector('label');
          const input = label?.querySelector('.foto-input');
          const preview = label?.querySelector('.foto-preview');
          // GUNAKAN .files.length > 0 SAJA, JANGAN CEK preview.classList.contains('hidden')
          if (
            input &&
            input.files &&
            input.files.length > 0
          ) {
            removeBtn.style.display = 'flex';
            removeBtn.classList.remove('hidden');
            removeBtn.disabled = false;
          } else {
            removeBtn.style.display = 'none';
            removeBtn.classList.add('hidden');
            removeBtn.disabled = true;
          }
          removeBtn.onclick = function() {
            // Jika lebih dari satu kotak upload, hapus item
            if (items.length > 1) {
              item.remove();
              setTimeout(updateRemoveButtons, 10);
            } else {
              // Jika hanya satu kotak upload, reset input dan preview
              if (input) input.value = '';
              if (preview) {
                preview.src = '#';
                preview.classList.add('hidden');
              }
              const placeholder = label?.querySelector('.foto-placeholder');
              if (placeholder) placeholder.classList.remove('hidden');
              removeBtn.style.display = 'none';
              removeBtn.classList.add('hidden');
              removeBtn.disabled = true;
              setTimeout(updateRemoveButtons, 10);
            }
          };
        });
      }

      function attachFotoInputEvents(item) {
        const label = item.querySelector('label');
        const input = label.querySelector('.foto-input');
        const preview = label.querySelector('.foto-preview');
        const removeBtn = item.querySelector('.remove-foto-btn');
        // Tambahkan event click pada preview agar bisa viewerjs (harus selalu ada)
        if (preview) {
          preview.onclick = null;
          preview.addEventListener('click', function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
            let wrapper = item.querySelector('.foto-viewer-wrapper');
            if (!wrapper) {
              wrapper = item.querySelector('.relative.group.foto-viewer-wrapper') || label.closest('.relative.group.foto-viewer-wrapper');
            }
            if (wrapper && wrapper.viewerInstance) {
              if (!preview.classList.contains('hidden') && preview.src && preview.src !== '#') {
                wrapper.querySelectorAll('img').forEach(function(img) {
                  if (img !== preview) img.remove();
                });
                if (preview.parentNode !== wrapper) {
                  wrapper.appendChild(preview);
                }
                // Update instance agar viewerjs aware terhadap img baru
                wrapper.viewerInstance.update();
                // TUNDA show() agar update() selesai, supaya viewerjs bisa menampilkan gambar baru
                setTimeout(() => {
                  wrapper.viewerInstance.show();
                }, 0);
              }
            }
            return false;
          });
          preview.style.cursor = 'pointer';
        }
        if (label) {
          label.onclick = null;
          label.addEventListener('mousedown', function(e) {
            if (
              e.target.classList.contains('foto-preview') &&
              !e.target.classList.contains('hidden')
            ) {
              e.preventDefault();
              e.stopPropagation();
              return false;
            }
          }, true);
        }
        input.addEventListener('change', function(e) {
          const placeholder = label.querySelector('.foto-placeholder');
          if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
              preview.src = ev.target.result;
              preview.classList.remove('hidden');
              placeholder.classList.add('hidden');
              // Pastikan preview sudah ada di wrapper agar viewerjs bisa menampilkan
              let wrapper = item.querySelector('.foto-viewer-wrapper');
              if (wrapper && preview.parentNode !== wrapper) {
                wrapper.appendChild(preview);
              }
              if (wrapper && wrapper.viewerInstance) {
                wrapper.viewerInstance.update();
              }
              // Tampilkan tombol x jika sudah ada foto
              if (removeBtn) {
                removeBtn.style.display = 'flex';
                removeBtn.classList.remove('hidden');
                removeBtn.disabled = false;
              }
              setTimeout(updateRemoveButtons, 10);
            };
            reader.readAsDataURL(input.files[0]);
            setTimeout(() => {
              const lastInput = fotoInputList.querySelector('.foto-input-item:last-child .foto-input');
              if (lastInput && lastInput.files.length > 0) {
                const newInput = createFotoInput();
                fotoInputList.appendChild(newInput);
                attachFotoInputEvents(newInput);
                updateRemoveButtons();
                // Inisialisasi Viewer.js pada input baru
                const wrapper = newInput.querySelector('.foto-viewer-wrapper');
                if (wrapper && !wrapper.viewerInstance && window.Viewer) {
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
              }
            }, 100);
          } else {
            preview.src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            if (removeBtn) {
              removeBtn.style.display = 'none';
              removeBtn.classList.add('hidden');
              removeBtn.disabled = true;
            }
            label.style.pointerEvents = '';
            label.style.opacity = '';
            label.title = '';
            setTimeout(updateRemoveButtons, 10);
          }
          updateRemoveButtons();
        });
      }

      // Inisialisasi: pastikan selalu ada satu kotak upload di akhir
      function ensureUploadBox() {
        const last = fotoInputList.querySelector('.foto-input-item:last-child');
        if (!last) {
          const newInput = createFotoInput();
          fotoInputList.appendChild(newInput);
          attachFotoInputEvents(newInput);
        } else {
          attachFotoInputEvents(last);
        }
      }

      ensureUploadBox();
      updateRemoveButtons();

      // Jika user menghapus kotak upload terakhir, tambahkan lagi satu kotak upload
      const observer = new MutationObserver(() => {
        ensureUploadBox();
        updateRemoveButtons();
      });
      observer.observe(fotoInputList, { childList: true });

      // Inisialisasi Viewer.js untuk semua foto baru
      function initFotoViewer() {
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
            img.onclick = function(ev) {
              ev.stopPropagation();
              wrapper.viewerInstance.show();
            };
          });
        });
      }
      initFotoViewer();

      observer.observe(fotoInputList, { childList: true, subtree: true });
    });

    // Preview gambar
    document.addEventListener('DOMContentLoaded', function() {
      // Hapus kode berikut karena input, preview, placeholder tidak ada di DOM:
      // const input = document.getElementById('foto');
      // const preview = document.getElementById('foto-preview');
      // const placeholder = document.getElementById('foto-placeholder');
      // input.addEventListener('change', function(e) {
      //   ...
      // });

      // Tombol deteksi lokasi
      const detectLocGPSBtn = document.getElementById('detect-location-gps-btn');
      const longitudeInput = document.getElementById('longitude');
      const latitudeInput = document.getElementById('latitude');
      detectLocGPSBtn.addEventListener('click', function() {
        detectLocGPSBtn.disabled = true;
        detectLocGPSBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Memproses...';
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            longitudeInput.value = position.coords.longitude;
            latitudeInput.value = position.coords.latitude;
            detectLocGPSBtn.innerHTML =
              '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi dari GPS';
            detectLocGPSBtn.disabled = false;
          }, function(error) {
            alert('Gagal mendapatkan lokasi: ' + error.message);
            detectLocGPSBtn.innerHTML =
              '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi dari GPS';
            detectLocGPSBtn.disabled = false;
          });
        } else {
          alert('Browser tidak mendukung geolocation.');
          detectLocGPSBtn.innerHTML =
            '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi dari GPS';
          detectLocGPSBtn.disabled = false;
        }
      });

      // --- PILIH LOKASI DARI PETA ---
      const selectLocBtn = document.getElementById('select-location-map-btn');
      const modalPilihLokasi = document.getElementById('modal-pilih-lokasi-peta');
      const confirmPilihLokasiBtn = document.getElementById('confirm-pilih-lokasi-btn');
      let selectedPoint = null;
      let mapView = null;
      let markerGraphic = null;
      let graphicsLayer = null;
      const selectedCoordsInfo = document.getElementById('selected-coords-info');

      selectLocBtn.addEventListener('click', function() {
        modalPilihLokasi.classList.remove('hidden');
        setTimeout(() => {
          if (!mapView) {
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
            ], function(Map, MapView, Locate, ScaleBar, Compass, Search,
              BasemapGallery, Graphic,
              GraphicsLayer) {
              const map = new Map({
                basemap: "hybrid"
              });
              graphicsLayer = new GraphicsLayer();
              map.add(graphicsLayer);

              mapView = new MapView({
                container: "arcgisMapPilihLokasi",
                map: map,
                center: [
                  parseFloat(document.getElementById(
                    'longitude').value) || 117.1466,
                  parseFloat(document.getElementById(
                    'latitude').value) || -0.5022
                ],
                zoom: 12
              });

              // Pindahkan kontrol zoom ke bottom-right
              mapView.ui.move("zoom", "bottom-right");

              // Locate widget
              const locateWidget = new Locate({
                view: mapView
              });
              mapView.ui.add(locateWidget, {
                position: "bottom-right"
              });

              // Compass
              const compass = new Compass({
                view: mapView
              });
              mapView.ui.add(compass, {
                position: "bottom-left"
              });

              // ScaleBar
              const scaleBar = new ScaleBar({
                view: mapView,
                unit: "metric"
              });
              mapView.ui.add(scaleBar, {
                position: "bottom-left"
              });

              // Search
              const searchWidget = new Search({
                view: mapView,
                container: "searchWidgetPilihLokasi",
                allPlaceholder: "Cari lokasi",
                includeDefaultSources: true
              });

              // BasemapGallery
              const basemapGallery = new BasemapGallery({
                view: mapView,
                container: "basemapGalleryPilihLokasi"
              });

              // Tampilkan marker awal jika sudah ada koordinat
              if (document.getElementById('longitude').value && document
                .getElementById('latitude')
                .value) {
                const lon = parseFloat(document.getElementById('longitude')
                  .value);
                const lat = parseFloat(document.getElementById('latitude')
                  .value);
                markerGraphic = new Graphic({
                  geometry: {
                    type: "point",
                    longitude: lon,
                    latitude: lat
                  },
                  symbol: {
                    type: "simple-marker",
                    color: [226, 119, 40],
                    outline: {
                      color: [255, 255, 255],
                      width: 2
                    }
                  }
                });
                graphicsLayer.add(markerGraphic);
              }

              mapView.on("click", function(event) {
                const lon = event.mapPoint.longitude.toFixed(7);
                const lat = event.mapPoint.latitude.toFixed(7);
                selectedPoint = {
                  longitude: lon,
                  latitude: lat
                };
                confirmPilihLokasiBtn.disabled = false;

                selectedCoordsInfo.innerHTML =
                  `<b>Longitude</b>: ${lon} <br> <b>Latitude</b>: ${lat}`;

                if (markerGraphic) graphicsLayer.remove(
                  markerGraphic);

                markerGraphic = new Graphic({
                  geometry: event.mapPoint,
                  symbol: {
                    type: "simple-marker",
                    color: [226, 119, 40],
                    outline: {
                      color: [255, 255, 255],
                      width: 2
                    }
                  }
                });
                graphicsLayer.add(markerGraphic);
              });
            });
          } else {
            mapView.container = "arcgisMapPilihLokasi";
            mapView.resize();
          }
        }, 100);
      });

      confirmPilihLokasiBtn.addEventListener('click', function() {
        if (selectedPoint) {
          document.getElementById('longitude').value = selectedPoint.longitude;
          document.getElementById('latitude').value = selectedPoint.latitude;
        }

        if (window.HSOverlay) {
          const modal = document.getElementById('modal-pilih-lokasi-peta');
          window.HSOverlay.close(modal);
        } else {
          modalPilihLokasi.classList.add('hidden');
        }
        confirmPilihLokasiBtn.disabled = true;
        selectedPoint = null;
        if (markerGraphic && graphicsLayer) graphicsLayer.remove(markerGraphic);
        selectedCoordsInfo.textContent = 'Pilih lokasi pada peta untuk mendapatkan koordinat.';
      });
    });
  </script>
@endsection
