<x-guest-layout>
  <div class="mx-auto max-w-xl">
    <h1 class="mb-6 text-center font-heading text-6xl font-bold text-white">Register</h1>
    <div class="rounded bg-black p-8">
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Name -->
        <div>
          <x-input-label for="name" :value="__('Name')" />
          <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required
            autofocus autocomplete="name" />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
            required autocomplete="username" />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
          <x-input-label for="password" :value="__('Password')" />

          <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required
            autocomplete="new-password" />

          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
          <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

          <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password"
            name="password_confirmation" required autocomplete="new-password" />

          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
          <x-input-label for="role" :value="__('Select User Role')" />
          <select id="role" name="role"
            class="mt-1 block w-full rounded-md border-ynsRed shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
            required autofocus autocomplete="role">
            @foreach ($roles as $role)
              <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
          </select>
          <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
          <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            href="{{ route('login') }}">
            {{ __('Already registered?') }}
          </a>

          <x-primary-button class="ms-4 bg-gradient-to-t from-ynsDarkOrange to-ynsYellow text-white">
            {{ __('Register') }}
          </x-primary-button>
        </div>
      </form>
    </div>
  </div>
</x-guest-layout>
