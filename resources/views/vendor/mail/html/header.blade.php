@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('favicon.png') }}" class="logo" alt="{{ config('app.name') }}" style="max-height: 50px; display: inline-block;">
<br>
<span style="font-size: 18px; font-weight: bold; color: #333; margin-top: 10px; display: block;">{{ config('app.name') }}</span>
</a>
</td>
</tr>
