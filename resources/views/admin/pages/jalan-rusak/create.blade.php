@extends('admin.layouts.jalan-rusak')

@section('slot')
<div class="">
	<div class="">
		<div class="p-4 sm:p-6">
			<h1 class="font-semibold text-2xl md:text-3xl">
				{{ $page_title }}
			</h1>

			<main class="mt-4">
				<form action="{{ route('admin.jalan-rusak.store') }}" method="POST" enctype="multipart/form-data"
					class="space-y-3">
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
						<label for="foto" class="block text-sm font-semibold text-gray-700 mb-1">Foto</label>
						<label id="foto-label" for="foto"
							class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden">
							<div id="foto-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
								<svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-1h10v1a1 1 0 01-1 1h-4z" />
								</svg>
								<p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Klik untuk
										upload</span> atau drag & drop</p>
								<p class="text-xs text-gray-400">PNG, JPG, JPEG (max 2MB)</p>
							</div>
							<img id="foto-preview" src="#" alt="Preview"
								class="hidden absolute inset-0 w-full h-full object-contain rounded-lg bg-white" />
						</label>
						<input id="foto" name="foto" type="file" accept="image/*" required class="hidden" />
						@error('foto')
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
						<div>
							<button type="button" id="detect-location-btn"
								class="inline-flex items-center px-3 py-2 rounded-lg border border-primary-500 text-primary-600 bg-white hover:bg-primary-50 transition text-sm font-medium"
								title="Deteksi lokasi sekarang">
								<i class="fa-solid fa-location-crosshairs mr-1"></i>
								Deteksi longitude & latitude
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
							<option value="ringan" {{ old('tingkat_keparahan')=='ringan' ? 'selected' : '' }}>Ringan
							</option>
							<option value="sedang" {{ old('tingkat_keparahan')=='sedang' ? 'selected' : '' }}>Sedang
							</option>
							<option value="berat" {{ old('tingkat_keparahan')=='berat' ? 'selected' : '' }}>Berat
							</option>
						</select>
						@error('tingkat_keparahan')
						<div class="text-red-500 text-xs mt-1">{{ $message }}</div>
						@enderror
					</div>

					{{-- Status Perbaikan --}}
					<div>
						<label for="sudah_diperbaiki" class="block text-sm font-semibold text-gray-700 mb-1">Status Perbaikan</label>
						<select id="sudah_diperbaiki" name="sudah_diperbaiki" required
							class="w-full rounded-lg border border-gray-300 bg-white focus:border-primary-500 focus:ring-primary-500 transition px-3 py-2">
							<option value="0" {{ old('sudah_diperbaiki') == '0' ? 'selected' : '' }}>Belum diperbaiki</option>
							<option value="1" {{ old('sudah_diperbaiki') == '1' ? 'selected' : '' }}>Sudah diperbaiki</option>
						</select>
						@error('sudah_diperbaiki')
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
</div>

<script>
	// Preview gambar
	document.addEventListener('DOMContentLoaded', function () {
		const input = document.getElementById('foto');
		const preview = document.getElementById('foto-preview');
		const placeholder = document.getElementById('foto-placeholder');
		input.addEventListener('change', function (e) {
			if (input.files && input.files[0]) {
				const reader = new FileReader();
				reader.onload = function (ev) {
					preview.src = ev.target.result;
					preview.classList.remove('hidden');
					placeholder.classList.add('hidden');
				};
				reader.readAsDataURL(input.files[0]);
			} else {
				preview.src = '#';
				preview.classList.add('hidden');
				placeholder.classList.remove('hidden');
			}
		});

		// Tombol deteksi lokasi
		const detectBtn = document.getElementById('detect-location-btn');
		const longitudeInput = document.getElementById('longitude');
		const latitudeInput = document.getElementById('latitude');
		detectBtn.addEventListener('click', function () {
			detectBtn.disabled = true;
			detectBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Memproses...';
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function (position) {
					longitudeInput.value = position.coords.longitude;
					latitudeInput.value = position.coords.latitude;
					detectBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi longitude & latitude';
					detectBtn.disabled = false;
				}, function (error) {
					alert('Gagal mendapatkan lokasi: ' + error.message);
					detectBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi longitude & latitude';
					detectBtn.disabled = false;
				});
			} else {
				alert('Browser tidak mendukung geolocation.');
				detectBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs mr-1"></i>Deteksi longitude & latitude';
				detectBtn.disabled = false;
			}
		});
	});
</script>
@endsection