@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'HushHushCloset')
                <img src="{{ asset('front/images/HUSH HUSH CLOSET.svg') }}" class="logo" alt="Hush Hush Closet">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
