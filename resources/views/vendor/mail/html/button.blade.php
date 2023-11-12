@props([
'url',
'color' => 'primary',
'align' => 'center',
])
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
  <tr>
    <td align="{{ $align }}">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
          <td align="{{ $align }}">
            <table border="0" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td>
                  <a href="{{ $url }}" class="button" style="background-color: blue; padding:8px 15px" target="_blank" rel="noopener">{{ $slot }}</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>