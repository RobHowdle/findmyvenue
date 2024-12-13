<div x-show="selectedTab === 2" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.standard.my-genres', [
        'userId' => $userId,
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'genres' => $standardUserData['genres'],
        'standardUserGenres' => $standardUserData['standardUserGenres'],
        'standardUser' => $standardUserData['standardUser'],
        'isAllGenres' => $standardUserData['isAllGenres'],
        'bandTypes' => $standardUserData['bandTypes'],
    ]) </div>
</div>
