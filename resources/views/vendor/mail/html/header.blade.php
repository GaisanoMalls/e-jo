@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ $message->embed(asset('images/e-jo.png')) }}" class="logo" alt="E-JO">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
