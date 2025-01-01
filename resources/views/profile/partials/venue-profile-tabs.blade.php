<div x-show="selectedTab === 2" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.venue.basic-information-form', [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'location' => $location,
        'name' => $venueData['name'],
        'contact_name' => $venueData['contact_name'],
        'email' => $venueData['contact_email'],
        'contact_number' => $venueData['contact_number'],
        'platformsToCheck' => $venueData['platformsToCheck'],
        'platforms' => $venueData['platforms'],
        'logo' => $venueData['logo'],
    ]) </div>
</div>
<div x-show="selectedTab === 3" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.venue.about', [
        'about' => $venueData['about'],
    ]) </div>
</div>
<div x-show="selectedTab === 4" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.venue.capacity', [
        'capacity' => $venueData['capacity'],
    ]) </div>
</div>
<div x-show="selectedTab === 5" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.venue.in-house-gear', [
        'inHouseGear' => $venueData['inHouseGear'],
    ]) </div>
</div>
<div x-show="selectedTab === 6" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.venue.my-events', [
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'location' => $location,
        'myEvents' => $venueData['myEvents'],
        'dashboardType' => $dashboardType,
    ]) </div>
</div>
<div x-show="selectedTab === 7" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.venue.my-bands', [
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'location' => $location,
        'uniqueBands' => $venueData['uniqueBands'],
    ]) </div>
</div>
<div x-show="selectedTab === 8" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.venue.my-genres', [
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'location' => $location,
        'genres' => $venueData['genres'],
        'promoterGenres' => $venueData['venueGenres'],
        'userId' => $userId,
        'venue' => $venueData['venue'],
    ]) </div>
</div>
<div x-show="selectedTab === 9" x-init="if (selectedTab === 9) { initializeMaps() }" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.venue.additional-info', [
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'userId' => $userId,
        'additionalInfo' => $venueData['additionalInfo'],
    ]) </div>
</div>
