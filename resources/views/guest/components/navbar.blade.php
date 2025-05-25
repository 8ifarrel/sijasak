<header class="flex flex-wrap lg:justify-start lg:flex-nowrap z-50 w-full py-2             ">
  <nav class="relative max-w-7xl w-full flex flex-wrap lg:grid lg:grid-cols-12 basis-full items-center px-4 md:px-6 lg:px-8 mx-auto bg-white">
    <div class="lg:col-span-3 flex items-center">
        <img src="{{ asset('logos/sijasak.png') }}" class="w-28 h-auto" alt="Sijasak">
      </a>
    </div>

    <div class="flex items-center gap-x-1 lg:gap-x-2 ms-auto py-1 lg:ps-6 lg:order-3 lg:col-span-3">
      <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold text-nowrap rounded-xl border border-transparent bg-kuning text-biru transition disabled:opacity-50 disabled:pointer-events-none">
        Lapor Jalan Rusak
      </button>

      <div class="lg:hidden">
        <button type="button" class="hs-collapse-toggle size-9.5 flex justify-center items-center text-sm font-semibold rounded-xl border border-gray-200 text-black hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:border-neutral-700 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" id="hs-navbar-hcail-collapse" aria-expanded="false" aria-controls="hs-navbar-hcail" aria-label="Toggle navigation" data-hs-collapse="#hs-navbar-hcail">
          <svg class="hs-collapse-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
          <svg class="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
    </div>

    <div id="hs-navbar-hcail" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow lg:block lg:w-auto lg:basis-auto lg:order-2 lg:col-span-6" aria-labelledby="hs-navbar-hcail-collapse">
      <div class="flex flex-col gap-y-4 gap-x-0 mt-5 lg:flex-row lg:justify-center lg:items-center lg:gap-y-0 lg:gap-x-7 lg:mt-0">
        <div>
          <a class="relative inline-block text-black focus:outline-hidden before:absolute before:bottom-0.5 before:start-0 before:-z-1 before:w-full before:h-1 before:bg-lime-400 dark:text-white" href="#" aria-current="page">Beranda</a>
        </div>
        <div>
          <a class="inline-block text-black hover:text-gray-600 focus:outline-hidden focus:text-gray-600 dark:text-white dark:hover:text-neutral-300 dark:focus:text-neutral-300" href="#">Soon</a>
        </div>
        <div>
          <a class="inline-block text-black hover:text-gray-600 focus:outline-hidden focus:text-gray-600 dark:text-white dark:hover:text-neutral-300 dark:focus:text-neutral-300" href="#">Soon</a>
        </div>
      </div>
    </div>
  </nav>
</header>
