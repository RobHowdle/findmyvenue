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
       <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
         <div class="notice mb-4 bg-amber-400 p-2 text-gray-900 sm:rounded-lg">
           There are 4 new notifications for you to view.
           <span class="ml-2 underline">Click to view</span>
         </div>
         <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
           <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
             <div id="greeting">
               {{ $greeting }}, {{ Auth::user()->name }}
             </div>
           </div>

           <div class="grid grid-cols-3 grid-rows-1 gap-4 p-6">
             <div>Test 1</div>
             <div>Test 2</div>
             <div>Test 3</div>
           </div>
         </div>
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
   </script>
