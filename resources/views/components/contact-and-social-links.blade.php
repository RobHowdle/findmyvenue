@if ($item->contact_number)
  <a class="mr-2 hover:text-ynsYellow" href="tel:{{ $item->contact_number }}">
    <span class="fas fa-phone"></span>
  </a>
@endif
@if ($item->contact_email)
  <a class="mr-2 hover:text-ynsYellow" href="mailto:{{ $item->contact_email }}">
    <span class="fas fa-envelope"></span>
  </a>
@endif
@if ($item->platforms)
  @foreach ($item->platforms as $platform)
    @if ($platform['platform'] == 'facebook')
      <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target="_blank">
        <span class="fab fa-facebook"></span>
      </a>
    @elseif($platform['platform'] == 'twitter')
      <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target="_blank">
        <span class="fab fa-twitter"></span>
      </a>
    @elseif($platform['platform'] == 'instagram')
      <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target="_blank">
        <span class="fab fa-instagram"></span>
      </a>
    @elseif($platform['platform'] == 'snapchat')
      <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target="_blank">
        <span class="fab fa-snapchat-ghost"></span>
      </a>
    @elseif($platform['platform'] == 'tiktok')
      <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target="_blank">
        <span class="fab fa-tiktok"></span>
      </a>
    @elseif($platform['platform'] == 'youtube')
      <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target="_blank">
        <span class="fab fa-youtube"></span>
      </a>
    @else
      <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target="_blank">
        <span class="fas fa-globe"></span>
      </a>
    @endif
  @endforeach
@endif
