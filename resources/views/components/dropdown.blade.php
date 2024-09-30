@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-opac8Black'])

@php
  switch ($align) {
      case 'left':
          $alignmentClasses = 'origin-top-left left-0';
          $triggerAlignmentClasses = 'text-left'; // Optional for text alignment
          break;
      case 'top':
          $alignmentClasses = 'origin-top';
          $triggerAlignmentClasses = ''; // Default alignment
          break;
      case 'right':
      default:
          $alignmentClasses = 'origin-top-right right-0';
          $triggerAlignmentClasses = 'text-right'; // Optional for text alignment
          break;
  }

  switch ($width) {
      case '48':
          $width = 'w-48';
          break;
  }
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
  <div @click="open = ! open" class="{{ $triggerAlignmentClasses }}">
    {{ $trigger }}
  </div>

  <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
    class="{{ $width }} {{ $alignmentClasses }} absolute z-50 mt-2 rounded-md bg-opac8Black text-white shadow-lg"
    style="display: none;" @click="open = false">
    <div class="{{ $contentClasses }} rounded-md bg-opac8Black ring-1 ring-black ring-opacity-5">
      {{ $content }}
    </div>
  </div>
</div>
