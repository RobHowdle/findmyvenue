<div x-show="selectedTab === 2" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Your Details</p>
    @include('profile.promoter.basic-information-form', [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'location' => $location,
        'name' => $promoterData['name'],
        'contact_name' => $promoterData['contact_name'],
        'email' => $promoterData['contact_email'],
        'contact_number' => $promoterData['contact_number'],
        'platformsToCheck' => $promoterData['platformsToCheck'],
        'platforms' => $promoterData['platforms'],
        'logo' => $promoterData['logo'],
    ])
  </div>
</div>
<div x-show="selectedTab === 3" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">About You</p>
    @include('profile.promoter.about', [
        'about' => $promoterData['about'],
    ])
  </div>
</div>
<div x-show="selectedTab === 4" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Your Venues</p>
    @include('profile.promoter.my-venues', [
        'myVenues' => $promoterData['myVenues'],
    ])
  </div>
</div>
{{-- <div x-show="selectedTab === 5" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">My Events</p>
    @include('profile.promoter.my-events', [
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'location' => $location,
        'myEvents' => $promoterData['myEvents'],
        'dashboardType' => $dashboardType,
    ])
  </div>
</div> --}}
{{-- <div x-show="selectedTab === 6" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">My Bands</p>
    @include('profile.promoter.my-bands', [
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'location' => $location,
        'uniqueBands' => $promoterData['uniqueBands'],
    ])
  </div>
</div> --}}
<div x-show="selectedTab === 7" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    <p class="text-xl font-bold">Genres & Band Types</p>
    @include('profile.promoter.my-genres', [
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'location' => $location,
        'genres' => $promoterData['genres'],
        'promoterGenres' => $promoterData['promoterGenres'],
        'userId' => $userId,
        'promoter' => $promoterData['promoter'],
        'isAllGenres' => $promoterData['isAllGenres'],
        'bandTypes' => $promoterData['bandTypes'],
    ])
  </div>
</div>
