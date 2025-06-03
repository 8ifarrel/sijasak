@extends('auth.layouts.login')

@section('slot')
<div class="h-screen-fix flex items-center justify-center bg-gradient-to-br p-5">
  <form method="POST" action="{{ route('auth.login.submit') }}" class="w-full max-w-sm space-y-8">
    @csrf
    <div class="text-center mb-6">
      <img src="{{ asset('logos/sijasak.png') }}" class="h-12 w-auto mx-auto mb-2" alt="{{ config('app.name_short') }}">
      <h1 class="text-2xl font-bold text-gray-800 mb-1 tracking-tight">Login Admin</h1>
      <p class="text-gray-500 text-sm">Masuk untuk mengelola website <span class="font-semibold text-biru">{{ config('app.name_short') }} {{ config('app.location') }}</span></p>
    </div>
    <!-- Username Floating Input -->
    <div class="relative">
      <input
        type="text"
        id="hs-floating-underline-input-username"
        name="username"
        value="{{ old('username') }}"
        class="peer py-4 px-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 sm:text-sm placeholder:text-transparent focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none
        focus:pt-6
        focus:pb-2
        not-placeholder-shown:pt-6
        not-placeholder-shown:pb-2
        autofill:pt-6
        autofill:pb-2 @error('username') border-red-400 focus:border-red-400 @enderror"
        placeholder="Username"
        required
        autofocus
        autocomplete="username"
      >
      <label for="hs-floating-underline-input-username"
        class="absolute top-0 start-0 py-4 px-0 h-full sm:text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent  origin-[0_0] peer-disabled:opacity-50 peer-disabled:pointer-events-none
        peer-focus:scale-90
        peer-focus:translate-x-0.5
        peer-focus:-translate-y-1.5
        peer-focus:text-gray-500 
        peer-not-placeholder-shown:scale-90
        peer-not-placeholder-shown:translate-x-0.5
        peer-not-placeholder-shown:-translate-y-1.5
        peer-not-placeholder-shown:text-gray-500 ">
        Username
      </label>
      @error('username')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <!-- Password Floating Input -->
    <div class="relative">
      <input
        type="password"
        id="hs-floating-underline-input-password"
        name="password"
        class="peer py-4 px-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 sm:text-sm placeholder:text-transparent focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none 
        focus:pt-6
        focus:pb-2
        not-placeholder-shown:pt-6
        not-placeholder-shown:pb-2
        autofill:pt-6
        autofill:pb-2 @error('password') border-red-400 focus:border-red-400 @enderror"
        placeholder="********"
        required
        autocomplete="current-password"
      >
      <label for="hs-floating-underline-input-password"
        class="absolute top-0 start-0 py-4 px-0 h-full sm:text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent  origin-[0_0] peer-disabled:opacity-50 peer-disabled:pointer-events-none
        peer-focus:scale-90
        peer-focus:translate-x-0.5
        peer-focus:-translate-y-1.5
        peer-focus:text-gray-500 
        peer-not-placeholder-shown:scale-90
        peer-not-placeholder-shown:translate-x-0.5
        peer-not-placeholder-shown:-translate-y-1.5
        peer-not-placeholder-shown:text-gray-500">
        Password
      </label>
      @error('password')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <!-- Remember Me & Submit -->
    <div class="flex items-center justify-between">
      <div class="flex items-center">
        <input id="remember-me" name="remember-me" type="checkbox"
          class="h-4 w-4 text-biru border-gray-300 rounded accent-biru"
          {{ old('remember-me') ? 'checked' : '' }}>
        <label for="remember-me" class="ml-2 text-sm text-gray-600">Ingati saya</label>
      </div>
    </div>
    <button type="submit"
      class="w-full py-3 rounded-lg bg-biru hover:bg-kuning text-kuning hover:text-biru font-semibold text-base shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 flex items-center justify-center gap-2">
      <i class="fa-solid fa-arrow-right-to-bracket"></i>
      Masuk
    </button>
    <div class="text-center text-xs text-gray-400 mt-8">
      &copy; {{ date('Y') }} Kelompok 4
    </div>
  </form>
</div>
@endsection
