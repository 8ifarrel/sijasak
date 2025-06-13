<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <meta name="description" content="{{ $meta_description }}" />

  <title>
    {{ $page_title }} | Admin Panel {{ config('app.name') }} ({{ config('app.name_short') }})
    {{ config('app.location') }}
  </title>

  <link rel="icon" type="image/x-icon" href="" />

  {{-- Disallow bot crawler --}}
  <meta name="robots" content="noindex, nofollow">

  {{-- Fontawesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- ArcGIS --}}
  <link rel="stylesheet" href="https://js.arcgis.com/4.28/esri/themes/light/main.css" />

  {{-- JQuery --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  {{-- Viewer.js --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.css" />

  <style>
    .viewer-arrow,
    .viewer-next,
    .viewer-prev {
      display: none !important;
    }
  </style>

  {{-- Tailwind CSS & Preline UI --}}
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')

  @yield('document.head')
</head>

<body class="h-screen-fix flex flex-col">
  @include('admin.components.navbar')
  @include('admin.components.aside')

  @yield('slot')

  {{-- ArcGIS --}}
  <script src="https://js.arcgis.com/4.28/"></script>

  @yield('document.end')
</body>

</html>
