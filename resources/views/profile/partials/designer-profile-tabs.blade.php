<div x-show="selectedTab === 2" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Your Details</p>
    @include('profile.designer.basic-information-form', [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'location' => $location,
        'name' => $designerUserData['name'],
        'contact_name' => $designerUserData['contact_name'],
        'contact_email' => $designerUserData['contact_email'],
        'contact_number' => $designerUserData['contact_number'],
        'platformsToCheck' => $designerUserData['platformsToCheck'],
        'platforms' => $designerUserData['platforms'],
        'logo' => $designerUserData['logo'],
    ])
  </div>
</div>
<div x-show="selectedTab === 3" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">About You</p>

    @include('profile.designer.about', [
        'about' => $designerUserData['about'],
    ])
  </div>
</div>
@php
  $dashboardData = $designerUserData ?? ($photographerUserData ?? ($videographerUserData ?? []));
@endphp
<div x-show="selectedTab === 4" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Portfolio</p>
    @include('profile.designer.portfolio', [
        'waterMarkedPortfolioImages' => $dashboardData['waterMarkedPortfolioImages'],
    ])
  </div>
</div>
<div x-show="selectedTab === 5" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Genres & Band Types</p>
    @include('profile.designer.my-genres', [
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'location' => $location,
        'genres' => $designerUserData['genres'],
        'designerGenres' => $designerUserData['designerGenres'],
        'userId' => $userId,
        'designer' => $designerUserData['designer'],
        'isAllGenres' => $designerUserData['isAllGenres'],
        'bandTypes' => $designerUserData['bandTypes'],
    ])
  </div>
</div>
<div x-show="selectedTab === 6" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Environments & Working Times</p>
    @include('profile.designer.environments-and-times', [
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'userId' => $userId,
        'designer' => $designerUserData['designer'],
        'environmentTypes' => $designerUserData['environmentTypes'],
        'groups' => $designerUserData['groups'],
        'workingTimes' => $designerUserData['workingTimes'],
    ])
  </div>
</div>
