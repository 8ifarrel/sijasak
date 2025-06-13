@extends('admin.layouts.dashboard')

@section('slot')
  <div class="p-4 sm:ml-64 min-h-screen">
    <div class="p-4 mt-14">
      <div>
        <h1 class="font-semibold text-2xl md:text-3xl text-gray-800">
          {{ $page_title }}
        </h1>

        <main class="mt-5">
          {{-- Selamat datang --}}
          <div class="w-full p-6 rounded-xl shadow-lg sm:p-8 mb-6">
            <h5 class="mb-2 text-3xl font-bold text-center md:text-start">
              Selamat Datang di Admin Panel
            </h5>
            <p class="mb-5 text-base text-center md:text-start sm:text-lg">
              Kelola website <span class="font-semibold">{{ config('app.name') }}</span>
              ({{ config('app.name_short') }}) {{ config('app.location') }} di sini
            </p>
          </div>

          {{-- Statistik Jalan Rusak --}}
          <div class="w-full p-6 rounded-xl shadow-lg bg-white sm:p-8">
            <h5 class="mb-6 text-3xl font-bold text-center md:text-start text-gray-900">
              Statistik Jalan Rusak
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              {{-- Chart 1: Tingkat Keparahan --}}
              <div class="rounded-xl p-6 shadow border border-gray-100 flex flex-col items-center">
                <h6 class="font-semibold mb-4 text-lg text-yellow-800 text-center">
                  Jalan Rusak Belum Diperbaiki<br><span class="text-sm text-gray-500">(Berdasarkan Tingkat Keparahan)</span>
                </h6>
                <div class="w-full flex justify-center">
                  <canvas id="chartKeparahan" style="max-width:320px;max-height:320px;"></canvas>
                </div>
              </div>
              {{-- Chart 2: Status Perbaikan --}}
              <div class="rounded-xl p-6 shadow border border-gray-100 flex flex-col items-center">
                <h6 class="font-semibold mb-4 text-lg text-green-800 text-center">
                  Jalan Rusak <br> Belum dan Telah Diperbaiki
                </h6>
                <div class="w-full flex justify-center">
                  <canvas id="chartStatus" style="max-width:320px;max-height:320px;"></canvas>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>

  {{-- Chart.js CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Data for Chart 1
    const keparahanData = [
      {{ $total_jalan_rusak_ringan ?? 0 }},
      {{ $total_jalan_rusak_sedang ?? 0 }},
      {{ $total_jalan_rusak_berat ?? 0 }}
    ];
    const keparahanLabels = ['Ringan', 'Sedang', 'Berat'];
    const keparahanColors = [
      'rgb(250,204,21)',   // kuning
      'rgb(251,146,60)',   // oranye
      'rgb(239,68,68)'     // merah
    ];

    // Chart 1: Tingkat Keparahan
    const ctxKeparahan = document.getElementById('chartKeparahan').getContext('2d');
    let chartKeparahan = new Chart(ctxKeparahan, {
      type: 'doughnut',
      data: {
        labels: keparahanLabels,
        datasets: [{
          data: keparahanData,
          backgroundColor: keparahanColors,
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          }
        },
        cutout: '70%'
      }
    });

    // Data for Chart 2
    const statusData = [
      {{ $total_jalan_rusak ?? 0 }},
      {{ $total_jalan_diperbaiki ?? 0 }}
    ];
    const statusLabels = ['Belum Diperbaiki', 'Sudah Diperbaiki'];
    const statusColors = [
      'rgba(239,68,68,0.85)',   // merah (berat)
      'rgba(34,197,94,0.85)'    // hijau
    ];

    // Chart 2: Status Perbaikan
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    let chartStatus = new Chart(ctxStatus, {
      type: 'pie',
      data: {
        labels: statusLabels,
        datasets: [{
          data: statusData,
          backgroundColor: statusColors,
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          }
        }
      }
    });
  </script>
@endsection
