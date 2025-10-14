@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
            @else
                <img src="http://localhost:8000/images/logo.png" class="logo" alt="CXO Management">
            @endif
        </a>
    </td>
</tr>
