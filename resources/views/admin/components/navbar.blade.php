<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
  <div class="px-3 py-3 lg:px-5">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button
          class="inline-flex items-center sm:p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
          aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-sidebar-offcanvas"
          aria-label="Toggle navigation" data-hs-overlay="#hs-sidebar-offcanvas" type="button">

          <span class="sr-only">
            Open sidebar
          </span>

          <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
              clip-rule="evenodd" fill-rule="evenodd">
            </path>
          </svg>
        </button>

        <a class="flex ms-2 md:me-24" href="https://flowbite.com">
          <img class="h-9 sm:h-11 me-3 my-auto" src="{{ asset('logos/sijasak.png') }}" alt="{{ config('app.name_short') }}" />

          <div class="flex flex-col justify-center">
            <span class="text-lg sm:text-xl font-semibold whitespace-nowrap leading-none">
              Admin Panel
            </span>

            <span class="text-xl sm:text-2xl font-semibold whitespace-nowrap leading-none">
              {{ config('app.name_short') }}
            </span>
          </div>
        </a>
      </div>

      <div class="flex items-center ms-3">
        <div class="hs-dropdown relative inline-flex">
          <button id="hs-dropdown-user" type="button"
            class="hs-dropdown-toggle flex items-center sm:gap-x-2 text-sm font-medium border-gray-200 text-gray-800 disabled:opacity-50 disabled:pointer-events-none sm:px-3 sm:py-2"
            aria-haspopup="menu" aria-expanded="false" aria-label="User menu">
            <i class="fa-solid fa-circle-user fa-xl"></i>
            <svg class="hidden sm:block hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <path d="m6 9 6 6 6-6" />
            </svg>
          </button>
          <div
            class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full z-50"
            role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-user">
            <div class="px-4 py-3" role="none">
              <p class="text-sm text-gray-900" role="none">
                {{ Auth::user()->name }}
              </p>
              <p class="text-sm font-medium text-gray-900 truncate" role="none">
                {{ Auth::user()->username }}
              </p>
            </div>
            <div class="p-1 space-y-0.5">
              <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button
                  class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 w-full text-left"
                  type="submit">
                  Log out
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>
