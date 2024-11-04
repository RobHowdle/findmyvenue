<div x-show="selectedTab === 2" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.promoter.basic-information-form', [
        'name' => $name,
        'promoterName' => $promoterData['promoterName'],
    ]) </div>
</div>
<div x-show="selectedTab === 3" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.promoter.about', [
        'about' => $promoterData['about'],
    ]) </div>
</div>
<div x-show="selectedTab === 4" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    @include('profile.promoter.my-venues', [
        'myVenues' => $promoterData['myVenues'],
    ]) </div>
</div>
<div x-show="selectedTab === 5" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    5
    @include('profile.promoter.my-events', [
        'userRole' => $userRole,
        'name' => $name,
    ]) </div>
</div>
<div x-show="selectedTab === 6" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    6
    @include('profile.promoter.my-bands', [
        'userRole' => $userRole,
        'name' => $name,
    ]) </div>
</div>
<div x-show="selectedTab === 7" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
  <div class="w-full">
    7
    @include('profile.promoter.my-genres', [
        'dashboardType' => $dashboardType,
        'userRole' => $userRole,
        'name' => $name,
    ]) </div>
</div>
