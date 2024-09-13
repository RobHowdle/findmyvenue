   <div
     class="suggestion-wrapper min-w-screen-xl relative mx-auto my-6 mt-8 w-full max-w-screen-xl border border-white p-8 text-white">
     <p class="mb-4 text-xl text-white">Suggestions</p>
     <div class="suggestion-block group grid grid-cols-5 gap-4">
       @if ($existingPromoters->isNotEmpty())
         @foreach ($existingPromoters as $promoter)
           <a href="{{ route('promoter', $promoter->id) }}" class="w-auto">
             @if ($promoter->logo_url)
               <img class="max-w-yns132" src="{{ asset($promoter->logo_url) }}" alt="{{ $promoter->name }} Logo">
             @endif
             <p>{{ $promoter->name }}</p>
           </a>
         @endforeach
       @else
         @if ($promoterWithHighestRating)
           <a class="flex flex-col items-center justify-center p-4 text-center transition-colors duration-100 ease-in-out hover:bg-ynsYellow"
             href="{{ route('promoter', $promoterWithHighestRating->id) }}">
             @if ($promoterWithHighestRating->logo_url)
               <img class="asr1 mb-4 max-w-yns132 flex-shrink object-contain"
                 src="{{ asset($promoterWithHighestRating->logo_url) }}"
                 alt="{{ $promoterWithHighestRating->name }} Logo">
             @endif
             <p class="flex-grow">{{ $promoterWithHighestRating->name }}</p>
           </a>
         @endif
         @if ($photographerWithHighestRating)
           <a class="flex flex-col items-center justify-center p-4 text-center transition-colors duration-100 ease-in-out hover:bg-ynsYellow"
             href="{{ route('singleService', ['serviceName' => $photographerWithHighestRating->otherServiceList->service_name, 'serviceId' => $photographerWithHighestRating->id]) }}">
             @if ($photographerWithHighestRating->logo_url)
               <img class="asr1 mb-4 max-w-yns132 flex-shrink object-contain"
                 src="{{ asset($photographerWithHighestRating->logo_url) }}"
                 alt="{{ $photographerWithHighestRating->name }} Logo">
             @endif
             <p class="flex-grow">{{ $photographerWithHighestRating->name }}</p>
           </a>
         @endif
         @if ($videographerWithHighestRating)
           <a class="flex flex-col items-center justify-center p-4 text-center transition-colors duration-100 ease-in-out hover:bg-ynsYellow"
             href="{{ route('singleService', ['serviceName' => $videographerWithHighestRating->otherServiceList->service_name, 'serviceId' => $videographerWithHighestRating->id]) }}">
             @if ($videographerWithHighestRating->logo_url)
               <img class="asr1 mb-4 max-w-yns132 flex-shrink object-contain"
                 src="{{ asset($videographerWithHighestRating->logo_url) }}"
                 alt="{{ $videographerWithHighestRating->name }} Logo">
             @endif
             <p class="flex-grow">{{ $videographerWithHighestRating->name }}</p>
           </a>
         @endif
         @if ($bandWithHighestRating)
           <a class="flex flex-col items-center justify-center p-4 text-center transition-colors duration-100 ease-in-out hover:bg-ynsYellow"
             href="{{ route('singleService', ['serviceName' => $bandWithHighestRating->otherServiceList->service_name, 'serviceId' => $bandWithHighestRating->id]) }}">
             @if ($bandWithHighestRating->logo_url)
               <img class="asr1 mb-4 max-w-yns132 flex-shrink object-contain"
                 src="{{ asset($bandWithHighestRating->logo_url) }}" alt="{{ $bandWithHighestRating->name }} Logo">
             @endif
             <p class="flex-grow">{{ $bandWithHighestRating->name }}</p>
           </a>
         @endif
         @if ($designerWithHighestRating)
           <a class="flex flex-col items-center justify-center p-4 text-center transition-colors duration-100 ease-in-out hover:bg-ynsYellow"
             href="{{ route('singleService', ['serviceName' => $designerWithHighestRating->otherServiceList->service_name, 'serviceId' => $designerWithHighestRating->id]) }}">
             @if ($designerWithHighestRating->logo_url)
               <img class="asr1 mb-4 max-w-yns132 flex-shrink object-contain"
                 src="{{ asset($designerWithHighestRating->logo_url) }}"
                 alt="{{ $designerWithHighestRating->name }} Logo">
             @endif
             <p class="flex-grow">{{ $designerWithHighestRating->name }}</p>
           </a>
         @endif
       @endif
     </div>
   </div>
