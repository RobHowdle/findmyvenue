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
         <div class="collapsible-container mb-2 overflow-x-auto bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
           <div class="collapsible-header toggle-header p-6">
             <h2 class="text-xl text-gray-900 dark:text-gray-100">Users</h2>
             <svg class="toggle-icon h-6 w-6 fill-current text-gray-600 transition-transform"
               xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
               <path class="chevron-down" fill-rule="evenodd" clip-rule="evenodd"
                 d="M10 13.59l-5.3-5.3a1 1 0 011.4-1.42L10 11.76l4.9-4.89a1 1 0 111.4 1.42l-5.3 5.3a1 1 0 01-1.4 0z" />
             </svg>
           </div>
           <div class="collapsible-content">
             <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
               @role('administrator')
                 <table class="w-full table-auto border border-gray-100 text-left dark:bg-gray-800">
                   <thead>
                     <tr class="whitespace-nowrap border-b px-6 py-4">
                       <th class="p-2">User</th>
                       <th class="p-2">Role</th>
                       <th class="p-2">Email</th>
                       <th class="p-2">Last Logged In</th>
                       <th class="p-2">Actions</th>
                     </tr>
                   </thead>
                   <tbody>
                     @foreach ($users as $user)
                       <tr>
                         <td class="p-2">{{ $user->name }}</td>
                         <td class="p-2">
                           @foreach ($user->roles as $role)
                             {{ $role->name }}
                           @endforeach
                         </td>
                         <td class="p-2">{{ $user->email }}</td>
                         <td class="p-2">
                           @if ($user->last_logged_in)
                             {{ $user->last_logged_in->format('d/m/Y') }} ({{ $user->last_logged_in->diffForHumans() }})
                           @else
                             Never logged in
                           @endif
                         </td>
                         <td class="collapsible-actions flex p-2">
                           <form action="{{ route('profile.edit', $user->id) }}" method="POST">
                             @csrf
                             <button type="submit" class="bg-yellow p-2">Edit</button>
                           </form>
                           <button class="bg-red p-2">Delete</button>
                         </td>
                       </tr>
                     @endforeach
                   </tbody>
                 </table>
               @endrole
             </div>
           </div>
         </div>

         {{-- Reviews Pending --}}
         <div class="collapsible-container mb-2 overflow-x-auto bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
           <div class="collapsible-header toggle-header p-6">
             <h2 class="text-xl text-gray-900 dark:text-gray-100">Pending Reviews</h2>
             <svg class="toggle-icon h-6 w-6 fill-current text-gray-600 transition-transform"
               xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
               <path class="chevron-down" fill-rule="evenodd" clip-rule="evenodd"
                 d="M10 13.59l-5.3-5.3a1 1 0 011.4-1.42L10 11.76l4.9-4.89a1 1 0 111.4 1.42l-5.3 5.3a1 1 0 01-1.4 0z" />
             </svg>
           </div>
           <div class="collapsible-content">
             <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
               @role('administrator')
                 <table class="w-full table-auto border border-gray-100 text-left dark:bg-gray-800">
                   <thead>
                     <tr class="whitespace-nowrap border-b px-6 py-4">
                       <th class="p-2">Promoter</th>
                       <th class="p-2">Review</th>
                       <th class="p-2">Author</th>
                       <th class="p-2">IP</th>
                       <th class="p-2">Actions</th>
                     </tr>
                   </thead>
                   <tbody>
                     @foreach ($pendingReviews as $review)
                       <tr>
                         <td class="p-2">{{ $review->promoter->name }}</td>
                         <td class="p-2">{{ $review->review }}</td>
                         <td class="p-2">{{ $review->author }}</td>
                         <td class="p-2">IP ADDRESS GOES HERE</td>
                         <td class="collapsible-actions flex items-center p-2">
                           <form action="{{ route('pending-review.approve-display', $review->id) }}" method="POST">
                             @csrf
                             <button type="submit" class="bg-yellow p-2">Approve & Display</button>
                           </form>
                           <form action="{{ route('pending-review.approve', $review->id) }}" method="POST">
                             @csrf
                             <button type="submit" class="bg-yellow p-2">Approve</button>
                           </form>
                           <button class="bg-red p-2">Delete</button>
                         </td>
                       </tr>
                     @endforeach
                   </tbody>
                 </table>
               @endrole
             </div>
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
   </script>
