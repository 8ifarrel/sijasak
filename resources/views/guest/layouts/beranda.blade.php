<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <meta name="description" content="{{ $meta_description }}" />

  <title>
    {{ $page_title }} | {{ config('app.name') }} ({{ config('app.name_short') }}) {{ config('app.location') }}
  </title>

  <link rel="icon" type="image/x-icon" href="" />

  {{-- Fontawesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- AOS --}}
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

  {{-- Tailwind CSS & Preline UI --}}
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>

<body>
  @include('guest.components.navbar')

  @yield('slot')

  @include('guest.components.footer')

  {{-- AOS --}}
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js">
    AOS.init({
      duration: 800,
    });
  </script>
</body>

</html>