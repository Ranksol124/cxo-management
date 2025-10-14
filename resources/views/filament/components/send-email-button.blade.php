@php
    $record = $getRecord();
@endphp

<x-filament::button
    color="primary"
    size="sm"
    wire:click.stop="$emit('sendEmailToAll', {{ $record->id }})"
>
    Send Email to All
</x-filament::button>
