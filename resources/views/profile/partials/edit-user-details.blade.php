<section>
  <header>
    <h2 class="text-md font-heading font-medium text-white">
      {{ __('Change your user details') }}
    </h2>
  </header>
  <form method="POST" action="{{ route('profile.update', ['user' => $user->id]) }}" class="mt-6 space-y-6">
    @csrf
    @method('PUT')
    <div>
      <x-input-label for="name" :value="__('Name')" />
      <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name', $name ?? '')" required
        autofocus autocomplete="name" />
      <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $email ?? '')" required
        autocomplete="username" />
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
      <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation"
        required autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="mt-4">
      <x-input-label for="role" :value="__('Role')" />
      <select id="role" name="role"
        class="mt-1 block w-full rounded-md border-ynsRed capitalize shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
        autofocus autocomplete="role">
        @foreach ($roles as $role)
          <option value="{{ $role->id }}" {{ $userRole->first()->id == $role->id ? 'selected' : '' }}>
            {{ $role->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="flex items-center gap-4">
      <button type="submit"
        class="mt-8 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Save</button>
      @if (session('status') === 'profile-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
          class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
      @endif
    </div>
  </form>
</section>
