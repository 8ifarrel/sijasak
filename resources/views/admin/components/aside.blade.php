<aside
  id="hs-sidebar-offcanvas"
  class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full fixed bg-white top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform duration-300 border-r border-gray-200 sm:translate-x-0"
  tabindex="-1">
  <div 
    class="h-full px-3 pb-4 overflow-y-auto ">
    <ul 
      class="space-y-2 font-medium">
      {{-- Dashboard --}}
      <li>
        <a  
          class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100"
          href="{{ route('admin.dashboard.index') }}">
          <i 
            class="fa-solid fa-chart-pie text-lg w-5">
          </i>

          <span
            class="ms-3">
            Dashboard
          </span>
        </a>
      </li>

      {{-- Jalan Rusak --}}
      <li class="hs-accordion" id="jalanrusak-accordion">
        <button type="button" class="hs-accordion-toggle w-full text-start flex items-center py-2 px-2.5 text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200" aria-expanded="false" aria-controls="jalanrusak-accordion-collapse">
          <i class="fa-solid fa-road-circle-exclamation text-lg w-5"></i>
          <span class="ms-3">Jalan Rusak</span>
          <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div id="jalanrusak-accordion-collapse" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden" role="region" aria-labelledby="jalanrusak-accordion">
          <ul class="pt-1 ps-7 space-y-1">
            <li>
              <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200" href="{{ route('admin.jalan-rusak.index') }}">
                Data Jalan Rusak
              </a>
            </li>
            <li>
              <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200" href="{{ route('admin.jalan-rusak.create') }}">
                Tambah Jalan Rusak
              </a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</aside>