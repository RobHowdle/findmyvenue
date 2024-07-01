   @php
     $now = now();
     $hour = $now->hour;

     // Define greeting messages
     if ($hour >= 5 && $hour < 12) {
         $greeting = 'Good Morning';
     } elseif ($hour >= 12 && $hour < 18) {
         $greeting = 'Good Afternoon';
     } else {
         $greeting = 'Good Evening';
     }
   @endphp

   <x-app-layout>
     <x-slot name="header">
       <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
         {{ __('Dashboard') }}
       </h2>
     </x-slot>

     <div class="py-12">
       <div class="mx-auto mb-2 max-w-7xl sm:px-6 lg:px-8">
         @if (session('success'))
           <div class="alert alert-success">
             {{ session('success') }}
           </div>
         @endif
         <div class="notice mb-4 bg-amber-400 p-2 text-gray-900 sm:rounded-lg">
           There are 4 new notifications for you to view.
           <span class="ml-2 underline">Click to view</span>
         </div>
         <div class="mb-2 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
           <div class="grid grid-cols-2 items-center p-6 text-xl text-gray-900 dark:text-gray-100">
             <div id="greeting" class="col-span-1">
               {{ $greeting }}, {{ Auth::user()->name }}
             </div>
             <div class="event-count col-span-1">
               <p>There are 6 events happening near you this week. <span class="underline">Click here</span> to view
                 them
               </p>
             </div>
           </div>
         </div>
         @include('admin.dashboards.admin-dash')
         @include('admin.dashboards.promoter-dash')
       </div>
     </div>
   </x-app-layout>

   <script>
     // Greeting
     function updateGreeting() {
       var now = new Date();
       var hour = now.getHours();
       var greeting = '';

       if (hour >= 5 && hour < 12) {
         greeting = 'Good Morning';
       } else if (hour >= 12 && hour < 18) {
         greeting = 'Good Afternoon';
       } else {
         greeting = 'Good Evening';
       }

       document.getElementById('greeting').innerHTML = greeting + ', {{ Auth::user()->name }}';
     }

     // Update the greeting every minute
     setInterval(updateGreeting, 60000);

     // Collapse/Expand Blocks
     document.addEventListener('DOMContentLoaded', function() {
       const headers = document.querySelectorAll('.toggle-header');
       headers.forEach(header => {
         header.addEventListener('click', function() {
           const content = this.nextElementSibling;
           const icon = this.querySelector('.toggle-icon');
           content.classList.toggle('show');
           icon.classList.toggle('rotate-180');
         });
       });
     });

     document.addEventListener('DOMContentLoaded', function() {
       const typeSelect = document.getElementById('type-select');
       const dynamicSelectContainer = document.getElementById('dynamic-select-container');

       const venues = @json($venues);
       const promoters = @json($promoters);
       const otherServices = @json($otherServices); // Assuming you have this data

       typeSelect.addEventListener('change', function() {
         const selectedType = typeSelect.value;
         let options = '';

         if (selectedType === 'venues') {
           options = venues.map(venue => `<option value="${venue.id}">${venue.name}</option>`).join('');
         } else if (selectedType === 'promoters') {
           options = promoters.map(promoter => `<option value="${promoter.id}">${promoter.name}</option>`).join(
             '');
         } else if (selectedType === 'other_service') {
           options = otherServices.map(service => `<option value="${service.id}">${service.name}</option>`).join(
             '');
         }

         dynamicSelectContainer.innerHTML = `
        <select name="service_select_id" class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
          ${options}
        </select>
      `;
       });
     });
   </script>
