@php
    $image = $getRecord()->profile_picture;
    $name = $getRecord()->name;
    $letter = strtoupper(substr($name, 0, 1));
   
@endphp
<div class="mb-4 text-center">
    @if ($image)
        <img src="{{ asset('storage/' . $image) }}" class="rounded-t-md h-40 w-40 object-cover" />
    @else
        <div
            class="flex items-center justify-center h-40 w-40 bg-gray-200 rounded-t-md text-6xl font-semi-bold text-gray-700"  style="font-size:80px;">
            {{ $letter }}
        </div>
    @endif
</div>