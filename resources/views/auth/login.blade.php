<x-guest-layout>
  {{-- <div class="flex w-full items-center justify-center "> --}}
  <div class="mx-auto min-h-screen w-full max-w-xl pt-44">
    <p class="px-8 py-8 text-center font-heading text-4xl font-bold text-white">Login</p>
    <div class="rounded bg-black p-8 font-sans">
      <x-auth-session-status class="mb-4" :status="session('status')" />
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required
            autofocus autocomplete="username" />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
          <x-input-label for="password" :value="__('Password')" />

          <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required
            autocomplete="current-password" />

          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 block">
          <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox"
              class="rounded border-yns_red shadow-sm focus:ring-indigo-500 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
              name="remember">
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
          </label>
        </div>

        <div class="mt-4 flex items-center justify-end">
          @if (Route::has('password.request'))
            <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
              href="{{ route('password.request') }}">
              {{ __('Forgot your password?') }}
            </a>
          @endif

          <x-primary-button class="ms-3 bg-gradient-to-t from-yns_dark_orange to-yns_yellow text-white">
            {{ __('Log in') }}
          </x-primary-button>
        </div>
      </form>
    </div>
  </div>
  {{-- </div> --}}
</x-guest-layout>
