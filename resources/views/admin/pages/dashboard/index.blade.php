@extends('admin.layouts.dashboard')

@section('slot')

	<div 
		class="p-4 sm:ml-64">
		<div
			class="p-4 mt-14">
			<div>
				<h1
					class="font-semibold text-2xl md:text-3xl">
					{{ $page_title }}
				</h1>

				<main
					class="mt-5">
					{{-- Selamat datang --}}
					<div
						class="w-full p-4 rounded-lg shadow-xl sm:p-8">
						<h5 
							class="mb-2 text-3xl font-bold text-center md:text-start text-gray-900">
							Selamat Datang di Admin Panel
						</h5>

						<p
							class="mb-5 text-base text-center md:text-start text-gray-500 sm:text-lg">
							Kelola website {{ config('app.name') }} ({{ config('app.name_short') }}) {{ config('app.location') }} di sini
						</p>
					</div>
					
					{{-- Jalan Rusak --}}
					<div
						class="w-full p-4 rounded-lg shadow-xl mt-5 sm:p-8">
						<h5 
							class="mb-2 text-3xl font-bold text-center md:text-start text-gray-900">
							Statistik Jalan Rusak
						</h5>

						<div class="p-4 bg-white rounded-lg md:p-8" id="stats" role="tabpanel" aria-labelledby="stats-tab">
							<dl class="grid max-w-screen-xl grid-cols-1 gap-8 mx-auto text-gray-900 sm:grid-cols-2">
								{{-- Jumlah jalan rusak --}}
								<div class="text-center block max-w-sm p-3 bg-white rounded-lg shadow-lg border-s-4 border-white border-l-red-600  hover:bg-gray-100">
									<h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
										{{ $total_jalan_rusak ?? 0 }}
									</h5>
									<p class="font-normal text-gray-700 dark:text-gray-400">Jumlah jalan yang sekarang rusak</p>
								</div>

                                {{-- Jumlah jalan telah diperbaiki --}}
								<div class="text-center block max-w-sm p-3 bg-white rounded-lg shadow-lg border-s-4 border-white border-l-green-700  hover:bg-gray-100">
									<h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
										{{ $total_jalan_diperbaiki ?? 0 }}
									</h5>
									<p class="font-normal text-gray-700 dark:text-gray-400">Jumlah jalan yang telah diperbaiki</p>
								</div>
							</dl>
						</div>

						{{-- <a
						 	class="font-semibold text-base sm:text-lg"
							href="{{ route('admin.dashboard.index') }}">
							<i class="fa-solid fa-angles-right"></i> Jalan Rusak
						</a> --}}
					</div>
				</main>
			</div>
		</div>
	</div>

@endsection