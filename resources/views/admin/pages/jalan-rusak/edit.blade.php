@extends('admin.layouts.jalan-rusak')

@section('slot')
  <div class="mt-[63px] sm:mt-[68px] flex-auto w-full sm:w-[calc(100vw_-_16rem)] sm:absolute sm:right-0 sm:left-auto">
    <div class="p-4 sm:p-6">
      <h1 class="font-semibold text-2xl md:text-3xl">
        {{ $page_title }}
      </h1>

      <main class="mt-4">
        <form action="{{ route('admin.jalan-rusak.update', $jalan_rusak->id) }}" method="POST" enctype="multipart/form-data"
          class="space-y-3">
          @csrf
          @method('PUT')

          {{-- Deskripsi --}}
          <div>
            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" required
              class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2">{{ old('deskripsi', $jalan_rusak->deskripsi) }}</textarea>
            @error('deskripsi')
              <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
          </div>

          {{-- Foto --}}
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Foto</label>
            <div id="foto-input-list" class="flex flex-wrap gap-2 mb-2">
              @foreach ($jalan_rusak->foto as $foto)
                <div class="foto-input-item flex flex-col items-center" data-foto-id="{{ $foto->id }}">
                  <div class="relative group foto-viewer-wrapper">
                    <img src="{{ asset('storage/' . $foto->foto) }}" class="w-28 h-28 sm:w-32 sm:h-32 object-cover rounded-lg border" />
                    <button type="button"
                      class="w-6 h-6 bg-white rounded-full text-red-500 hover:text-red-700 shadow-lg border border-black flex items-center justify-center absolute top-1 right-1 z-10 remove-foto-btn"
                      title="Hapus foto">
                      <i class="fa-solid fa-xmark"></i>
                    </button>
                  </div>
                  <div class="w-28 sm:w-32 text-center text-sm mt-1 font-semibold text-gray-500">Foto lama</div>
                </div>
              @endforeach
            </div>
            @error('foto')
              <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
            @error('foto.*')
              <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="flex flex-col">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-3">
              {{-- Longitude --}}
              <div>
                <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-1">Longitude</label>
                <input type="number" step="any" id="longitude" name="longitude"
                  value="{{ old('longitude', $jalan_rusak->longitude) }}" required
                  class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2"
                  placeholder="-6.1234567">
                @error('longitude')
                  <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
              </div>

              {{-- Latitude --}}
              <div>
                <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-1">Latitude</label>
                <input type="number" step="any" id="latitude" name="latitude"
                  value="{{ old('latitude', $jalan_rusak->latitude) }}" required
                  class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2"
                  placeholder="106.1234567">
                @error('latitude')
                  <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="flex gap-2 mt-2">
              <button type="button" id="detect-location-btn"
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

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-3">
            {{-- Tingkat Keparahan --}}
            <div>
              <label for="tingkat_keparahan" class="block text-sm font-semibold text-gray-700 mb-1">Tingkat
                Keparahan</label>
              <select id="tingkat_keparahan" name="tingkat_keparahan" required
                class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2">
                <option value="ringan"
                  {{ old('tingkat_keparahan', $jalan_rusak->tingkat_keparahan) == 'ringan' ? 'selected' : '' }}>
                  Ringan</option>
                <option value="sedang"
                  {{ old('tingkat_keparahan', $jalan_rusak->tingkat_keparahan) == 'sedang' ? 'selected' : '' }}>
                  Sedang</option>
                <option value="berat"
                  {{ old('tingkat_keparahan', $jalan_rusak->tingkat_keparahan) == 'berat' ? 'selected' : '' }}>
                  Berat</option>
              </select>
              @error('tingkat_keparahan')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
              @enderror
            </div>

            {{-- Status Perbaikan --}}
            <div>
              <label for="sudah_diperbaiki" class="block text-sm font-semibold text-gray-700 mb-1">Status
                Perbaikan</label>
              <select id="sudah_diperbaiki" name="sudah_diperbaiki" required
                class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2">
                <option value="0"
                  {{ old('sudah_diperbaiki', $jalan_rusak->sudah_diperbaiki) == 0 ? 'selected' : '' }}>
                  Belum diperbaiki</option>
                <option value="1"
                  {{ old('sudah_diperbaiki', $jalan_rusak->sudah_diperbaiki) == 1 ? 'selected' : '' }}>
                  Sudah diperbaiki</option>
              </select>
              @error('sudah_diperbaiki')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="flex gap-2">
            <button type="submit"
              class="bg-biru text-kuning preline-btn preline-btn-primary w-auto block py-2 px-3 font-semibold rounded-lg shadow hover:shadow-md transition">
              <i class="fa-solid fa-save mr-1"></i> Ubah
            </button>
            <a href="{{ route('admin.jalan-rusak.index') }}"
              class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
              <i class="fa-solid fa-times mr-1"></i>Batal
            </a>
          </div>
        </form>
      </main>
    </div>
  </div>

  {{-- Modal Peta Pilih Lokasi (Preline Style) --}}
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
          <div class="absolute right-auto left-4 top-16 sm:left-auto sm:top-4 sm:right-4 z-20 gap-2">
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
    // Dynamic foto input
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
          <div class="w-28 sm:w-32 text-center text-sm mt-1 font-semibold text-gray-500">Foto baru</div>
        `;
        return div;
      }

      const fotoInputList = document.getElementById('foto-input-list');

      function updateRemoveButtons() {
        const items = fotoInputList.querySelectorAll('.foto-input-item');
        const totalFotoBaru = fotoInputList.querySelectorAll('.foto-input-item:not([data-foto-id])').length;
        items.forEach((item) => {
          const removeBtn = item.querySelector('.remove-foto-btn');
          if (!removeBtn) return;
          // Hapus semua event handler sebelumnya agar tidak dobel
          const newRemoveBtn = removeBtn.cloneNode(true);
          removeBtn.parentNode.replaceChild(newRemoveBtn, removeBtn);

          if (item.hasAttribute('data-foto-id')) {
            newRemoveBtn.style.display = 'flex';
            newRemoveBtn.classList.remove('hidden');
            newRemoveBtn.disabled = false;
            newRemoveBtn.classList.remove('opacity-50', 'pointer-events-none');
            newRemoveBtn.addEventListener('click', function handler(e) {
              e.preventDefault();
              let overlay = item.querySelector('.foto-will-be-deleted');
              if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'foto-will-be-deleted absolute inset-0 flex flex-col items-center justify-center bg-gray-100/85 z-20 rounded-lg';
                overlay.innerHTML = `
                  <span class="text-red-600 font-bold text-xs sm:text-sm text-center mb-2">Foto akan <br> dihapus</span>
                  <button type="button" class="restore-foto-btn mt-1 px-2 py-1 rounded bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold flex items-center gap-1 border border-black">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Batalkan
                  </button>
                `;
                const relativeGroup = item.querySelector('.relative.group');
                if (relativeGroup) {
                  relativeGroup.appendChild(overlay);
                } else {
                  item.appendChild(overlay);
                }
              } else {
                overlay.style.display = 'flex';
              }
              let hidden = item.querySelector('input[name="deleted_foto[]"]');
              if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'deleted_foto[]';
                hidden.value = item.getAttribute('data-foto-id');
                document.querySelector('form').appendChild(hidden);
              }
              newRemoveBtn.disabled = true;
              newRemoveBtn.classList.add('opacity-50', 'pointer-events-none');
              const restoreBtn = overlay.querySelector('.restore-foto-btn');
              if (restoreBtn) {
                restoreBtn.onclick = function(ev) {
                  ev.preventDefault();
                  overlay.style.display = 'none';
                  newRemoveBtn.disabled = false;
                  newRemoveBtn.classList.remove('opacity-50', 'pointer-events-none');
                  if (hidden && hidden.parentNode) hidden.parentNode.removeChild(hidden);
                  updateRemoveButtons();
                };
              }
            }, { once: true });
          } else {
            const label = item.querySelector('label');
            const input = label ? label.querySelector('.foto-input') : null;
            if (
              input &&
              input.files &&
              input.files.length > 0
            ) {
              newRemoveBtn.style.display = 'flex';
              newRemoveBtn.classList.remove('hidden');
              newRemoveBtn.disabled = false;
            } else {
              newRemoveBtn.style.display = 'none';
              newRemoveBtn.classList.add('hidden');
              newRemoveBtn.disabled = true;
            }
            newRemoveBtn.addEventListener('click', function(e) {
              e.preventDefault();
              if (items.length > 1 || item.hasAttribute('data-foto-id')) {
                item.remove();
                updateRemoveButtons();
              }
            });
          }
        });
      }

      function attachFotoInputEvents(item) {
        const label = item.querySelector('label');
        const input = label.querySelector('.foto-input');
        const preview = label.querySelector('.foto-preview');
        const removeBtn = item.querySelector('.remove-foto-btn');
        // Tambahkan event click pada preview agar bisa viewerjs (harus selalu ada)
        if (preview) {
          // Hapus event click sebelumnya agar tidak dobel
          preview.onclick = null;
          preview.addEventListener('click', function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
            // Cari .foto-viewer-wrapper terdekat
            let wrapper = item.querySelector('.foto-viewer-wrapper');
            if (!wrapper) {
              wrapper = item.querySelector('.relative.group.foto-viewer-wrapper') || label.closest('.relative.group.foto-viewer-wrapper');
            }
            if (wrapper && wrapper.viewerInstance) {
              // Pastikan preview sudah ada di wrapper dan tidak hidden
              if (!preview.classList.contains('hidden') && preview.src && preview.src !== '#') {
                // Hapus semua img lain di wrapper (kecuali preview) agar viewerjs hanya menampilkan preview
                wrapper.querySelectorAll('img').forEach(function(img) {
                  if (img !== preview) img.remove();
                });
                // Tambahkan preview ke wrapper jika belum ada
                if (preview.parentNode !== wrapper) {
                  wrapper.appendChild(preview);
                }
                // Refresh viewerjs agar gambar baru terdeteksi
                wrapper.viewerInstance.update();
                wrapper.viewerInstance.show();
              }
            }
            return false;
          });
          preview.style.cursor = 'pointer';
        }
        // Cegah file manager muncul jika klik pada gambar preview (bukan label)
        if (label) {
          // Hapus event click sebelumnya agar tidak dobel
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
              // Refresh viewerjs agar gambar baru terdeteksi
              if (wrapper && wrapper.viewerInstance) {
                wrapper.viewerInstance.update();
              }
              // Tampilkan tombol x jika sudah ada foto
              if (removeBtn) {
                removeBtn.style.display = 'flex';
                removeBtn.classList.remove('hidden');
              }
            };
            reader.readAsDataURL(input.files[0]);
            // Setelah upload, tambahkan kotak upload baru jika belum ada kotak kosong di akhir
            setTimeout(() => {
              const lastInput = fotoInputList.querySelector('.foto-input-item:last-child .foto-input');
              if (lastInput && lastInput.files.length > 0) {
                const newInput = createFotoInput();
                fotoInputList.appendChild(newInput);
                attachFotoInputEvents(newInput);
                updateRemoveButtons();
              }
            }, 100);
          } else {
            preview.src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            // Sembunyikan tombol x jika belum ada foto
            if (removeBtn) {
              removeBtn.style.display = 'none';
              removeBtn.classList.add('hidden');
            }
            // Enable klik pada label jika belum ada file
            label.style.pointerEvents = '';
            label.style.opacity = '';
            label.title = '';
            // (preview.onclick tetap ada agar viewerjs tetap aktif)
          }
          updateRemoveButtons();
        });
      }

      // Inisialisasi: pastikan selalu ada satu kotak upload di akhir
      function ensureUploadBox() {
        const last = fotoInputList.querySelector('.foto-input-item:last-child');
        if (!last || last.hasAttribute('data-foto-id')) {
          const newInput = createFotoInput();
          fotoInputList.appendChild(newInput);
          attachFotoInputEvents(newInput);
        } else {
          attachFotoInputEvents(last);
        }
      }

      // Inisialisasi untuk foto lama
      ensureUploadBox();
      updateRemoveButtons();

      // Jika user menghapus kotak upload terakhir, tambahkan lagi satu kotak upload
      const observer = new MutationObserver(() => {
        ensureUploadBox();
        updateRemoveButtons();
      });
      observer.observe(fotoInputList, { childList: true });

    });

    // Location detection
    const detectBtn = document.getElementById('detect-location-btn');
    const longitudeInput = document.getElementById('longitude');
    const latitudeInput = document.getElementById('latitude');

    detectBtn.addEventListener('click', function() {
      detectBtn.disabled = true;
      detectBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Memproses...';

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            longitudeInput.value = position.coords.longitude;
            latitudeInput.value = position.coords.latitude;
            detectBtn.innerHTML =
              '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi dari GPS';
            detectBtn.disabled = false;
          },
          function(error) {
            alert('Gagal mendapatkan lokasi: ' + error.message);
            detectBtn.innerHTML =
              '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi dari GPS';
            detectBtn.disabled = false;
          }
        );
      } else {
        alert('Browser tidak mendukung geolocation.');
        detectBtn.innerHTML =
          '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi dari GPS';
        detectBtn.disabled = false;
      }
    });

    // --- PILIH LOKASI DARI PETA (ambil dari create.blade.php) ---
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
                parseFloat(longitudeInput.value) ||
                117.1466,
                parseFloat(latitudeInput.value) || -0.5022
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
            if (longitudeInput.value && latitudeInput.value) {
              const lon = parseFloat(longitudeInput.value);
              const lat = parseFloat(latitudeInput.value);
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

              // Set selectedPoint dan enable tombol pilih
              selectedPoint = {
                longitude: lon.toFixed(7),
                latitude: lat.toFixed(7)
              };
              confirmPilihLokasiBtn.disabled = false;
              selectedCoordsInfo.innerHTML =
                `<b>Longitude</b>: ${selectedPoint.longitude} <br> <b>Latitude</b>: ${selectedPoint.latitude}`;
            } else {
              selectedCoordsInfo.textContent =
                'Pilih lokasi pada peta untuk mendapatkan longitude dan latitude.';
              confirmPilihLokasiBtn.disabled = true;
              selectedPoint = null;
            }

            mapView.on("click", function(event) {
              const lon = event.mapPoint.longitude.toFixed(7);
              const lat = event.mapPoint.latitude.toFixed(7);
              selectedPoint = {
                longitude: lon,
                latitude: lat
              };
              confirmPilihLokasiBtn.disabled = false;

              // Update koordinat info
              selectedCoordsInfo.innerHTML =
                `<b>Longitude</b>: ${lon} <br> <b>Latitude</b>: ${lat}`;

              // Remove previous marker
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
          // Set ulang marker dan koordinat info setiap buka modal
          if (longitudeInput.value && latitudeInput.value) {
            const lon = parseFloat(longitudeInput.value);
            const lat = parseFloat(latitudeInput.value);
            if (markerGraphic && graphicsLayer) graphicsLayer.remove(markerGraphic);
            // Perbaiki: jangan gunakan optional chaining pada new expression
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
            selectedPoint = {
              longitude: lon.toFixed(7),
              latitude: lat.toFixed(7)
            };
            confirmPilihLokasiBtn.disabled = false;
            selectedCoordsInfo.textContent =
              `Longitude: ${selectedPoint.longitude}, Latitude: ${selectedPoint.latitude}`;
          } else {
            selectedCoordsInfo.textContent =
              'Pilih lokasi pada peta untuk mendapatkan longitude dan latitude.';
            confirmPilihLokasiBtn.disabled = true;
            selectedPoint = null;
          }
        }
      }, 100);
    });

    confirmPilihLokasiBtn.addEventListener('click', function() {
      if (selectedPoint) {
        longitudeInput.value = selectedPoint.longitude;
        latitudeInput.value = selectedPoint.latitude;
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

    // Tambahkan inisialisasi Viewer.js untuk semua foto lama dan baru
    document.addEventListener('DOMContentLoaded', function() {
      function initFotoViewer() {
        document.querySelectorAll('.foto-viewer-wrapper').forEach(function(wrapper) {
          // Hindari duplikasi instance
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
          // Untuk semua img di dalam wrapper, klik = show viewer
          wrapper.querySelectorAll('img').forEach(function(img) {
            img.onclick = function(ev) {
              ev.stopPropagation();
              wrapper.viewerInstance.show();
            };
          });
        });
      }

      // Panggil saat halaman load dan setiap kali input baru ditambah
      initFotoViewer();

      // Hook agar setiap kali input baru ditambah, viewer tetap aktif
      const fotoInputList = document.getElementById('foto-input-list');
      const observer = new MutationObserver(() => {
        initFotoViewer();
      });
      observer.observe(fotoInputList, { childList: true, subtree: true });
    });
  </script>
@endsection
