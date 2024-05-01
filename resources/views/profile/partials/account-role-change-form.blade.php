<section>
  <header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      {{ __('Account Type Change') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
      {{ __('Account type to be changed. If you are Venue Owner, Promoter, Support Band etc.') }}
    </p>
  </header>
  <form method="POST" action="{{ route('profile.update', ['user' => $user->id]) }}" class="mt-6 space-y-6">
    @csrf
    @method('PUT')

    <select id="role" name="role" class="mt-1 block w-full" required autofocus autocomplete="role">
      @foreach ($roles as $role)
        <option value="{{ $role->id }}" {{ $userRole->first()->id == $role->id ? 'selected' : '' }}>
          {{ $role->name }}
        </option>
      @endforeach
    </select>

    <div class="flex items-center gap-4">
      <x-primary-button>{{ __('Save') }}</x-primary-button>

      @if (session('status') === 'profile-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
          class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
      @endif
    </div>
  </form>
</section>
