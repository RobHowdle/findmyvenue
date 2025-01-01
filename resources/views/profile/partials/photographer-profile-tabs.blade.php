<div x-show="selectedTab === 2" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Your Details</p>
    @include('profile.photographer.basic-information-form', [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'location' => $location,
        'name' => $photographerData['name'],
        'contact_name' => $photographerData['contact_name'],
        'contact_email' => $photographerData['contact_email'],
        'contact_number' => $photographerData['contact_number'],
        'platformsToCheck' => $photographerData['platformsToCheck'],
        'platforms' => $photographerData['platforms'],
        'logo' => $photographerData['logo'],
    ])
  </div>
</div>
<div x-show="selectedTab === 3" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">About You</p>
    @include('profile.photographer.about', [
        'about' => $photographerData['about'],
    ])
  </div>
</div>
<div x-show="selectedTab === 4" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Portfolio</p>
    @include('profile.photographer.portfolio', [
        'portfolioImages' => $photographerData['portfolioImages'],
    ])
  </div>
</div>
<div x-show="selectedTab === 5" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Genres & Band Types</p>
    @include('profile.photographer.my-genres', [
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'location' => $location,
        'genres' => $photographerData['genres'],
        'photographerGenres' => $photographerData['photographerGenres'],
        'userId' => $userId,
        'photographer' => $photographerData['photographer'],
        'isAllGenres' => $photographerData['isAllGenres'],
        'bandTypes' => $photographerData['bandTypes'],
    ])
  </div>
</div>
<div x-show="selectedTab === 6" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Environments & Working Times</p>
    @include('profile.photographer.environments-and-times', [
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'userId' => $userId,
        'photographer' => $photographerData['photographer'],
        'environmentTypes' => $photographerData['environmentTypes'],
        'groups' => $photographerData['groups'],
        'workingTimes' => $photographerData['workingTimes'],
    ])
  </div>
</div>
