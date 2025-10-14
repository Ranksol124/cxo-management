<x-filament::page>
    <div class="space-y-4">
        <a href="{{ url()->previous() }}" class="text-primary-600 hover:underline mt-2">← Back</a>
        <div class="bg-white shadow rounded-lg p-[35px] w-full">
            <!-- Top Section -->
            
            <div class="p-0 md:p-6 flex items-start justify-between">
                <div class="flex flex-wrap gap-4">
                    @if($record->image != null)
                    <img src="{{ asset('storage/' . $record->event_image) }}" alt="Profile"
                        class="w-24 h-24 rounded-md object-cover">
                    @else
                    <img src="{{ asset('icons/no_icon.svg') }}" alt="Profile"
                        class="w-24 h-24 rounded-md object-cover">
                    @endif
                    <div class="flex flex-col gap-1">
                        <h2 class="text-xl record-title max-w-[500px] break-words pb-3 font-semibold">
                            {{ $record->title }}</h2>
                        <p class="text-gray-700">
                            {{ $record->link }}
                        </p>

                        <p class="text-gray-500 text-sm">
                            Start Date: {{ \Carbon\Carbon::parse($record->start_date)->format('d M Y') }}
                        </p>
                        <p class="text-gray-500 text-sm">
                            End Date: {{ \Carbon\Carbon::parse($record->end_date)->format('d M Y') }}
                        </p>
                        <p class="text-gray-500 text-sm">Event Type: {{ $record->event_type }}</p>
                    </div>
                </div>
                <span
                    class="bg-sky-600 text-white text-sm px-4 py-1 rounded-full h-fit">{{ ucfirst($record->event_status ? 'Approved' : 'Pending') }}</span>
            </div>
            @if($record->description != null)
            <!-- Info Section -->
            <div class="bg-gray-50 p-6 rounded-b-lg">
                <div class="grid grid-cols-1 gap-6 ">
                    
                    
                    <div class="py-4">
                        <p class="font-medium">Description:</p>
                        <p class="text-gray-600">{{ $record->description ?? '' }}</p>
                    </div>


                </div>
            </div>
            @endif
        </div>
        
    </div>
</x-filament::page>
