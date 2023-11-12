@props(['url'])
<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === 'Laravel')
      <div style="display: flex;">
        <!-- <img src="{{ url('assets/images/logo.png') }}" class="logo" alt="esc logo"> -->
        <img src="http://0.0.0.0/assets/images/logo.png" class="logo" alt="esc logo">
        <h1 style="font-size:40px; margin-top: 8px;">ESC</h1>
      </div>
      @else
      {{ $slot }}
      @endif
    </a>
  </td>
</tr>