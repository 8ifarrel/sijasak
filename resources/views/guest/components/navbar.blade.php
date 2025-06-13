<header class="flex flex-wrap lg:justify-start lg:flex-nowrap z-50 w-full py-3">
  <nav class="relative w-full md:flex md:items-center md:justify-between md:gap-3 mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between">
      <a class="flex items-center" href="{{ route('guest.beranda.index') }}">
        <img src="{{ asset('logos/sijasak.png') }}" class="h-8 sm:h-11 w-auto" alt="{{ config('app.name_short') }}">
        <div class="ms-2 sm:ms-3 flex flex-col justify-center my-auto">
          <span class="text-sm sm:text-lg font-semibold whitespace-nowrap leading-none">
            {{ config('app.name') }}
          </span>

          <span class="text-lg sm:text-2xl font-semibold whitespace-nowrap leading-none">
            {{ config('app.location') }}
          </span>
        </div>
      </a>
      <div class="sm:hidden">
        <button type="button"
          class="hs-collapse-toggle relative size-9 flex justify-center items-center gap-x-2 rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-transparent dark:border-neutral-700 dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10"
          id="hs-base-header-collapse" aria-expanded="false" aria-controls="hs-base-header"
          aria-label="Toggle navigation" data-hs-collapse="#hs-base-header">
          <svg class="hs-collapse-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" x2="21" y1="6" y2="6" />
            <line x1="3" x2="21" y1="12" y2="12" />
            <line x1="3" x2="21" y1="18" y2="18" />
          </svg>
          <svg class="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"    
            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
          </svg>
          <span class="sr-only">Toggle navigation</span>
        </button>
      </div>
    </div>
    <div id="hs-base-header"
      class="pt-3 hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block"
      aria-labelledby="hs-base-header-collapse">
      <div
        class="overflow-hidden overflow-y-auto max-h-[75vh] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
        <div class="py-2 md:py-0 flex flex-col md:flex-row md:items-center md:justify-end gap-0.5 md:gap-1">
          <a class="text-biru font-semibold p-2 flex items-center hover:text-white focus:outline-hidden focus:text-white"
            aria-current="page"
            href="{{ route('guest.beranda.index') }}">
            <svg class="shrink-0 size-4 me-3 md:me-2 block md:hidden" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
              <path
                d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            </svg>
            Beranda
          </a>

        </div>
      </div>
    </div>
  </nav>
</header>
