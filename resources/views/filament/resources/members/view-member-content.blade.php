<x-filament::page>
    <div class="space-y-4">
        <a href="{{ url()->previous() }}" class="text-primary-600 hover:underline mt-2">← Back</a>

        <div class="bg-white shadow rounded-lg p-[35px] w-full">
            <h1 class="text-xl record-title max-w-[500px] break-words pb-3 font-semibold">
                {{ $record->member->full_name }}</h1>
            <div class="py-0 md:py-6 flex items-start justify-between">
                <div class="flex flex-wrap gap-4">
                    @php
                        function getFileIcon($path)
                        {
                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                            return match ($ext) {
                                'jpg', 'jpeg', 'png', 'gif', 'webp' => 'image', // show image preview
                                'mp4', 'mov', 'avi', 'webm' => 'video',
                                'pdf' => 'pdf',
                                'doc', 'docx' => 'word',
                                'txt' => 'txt',
                                'xls', 'xlsx' => 'excel',
                                'csv' => 'csv',
                                default => 'file',
                            };
                        }
                    @endphp

                    @if ($record->attachments->isNotEmpty())
                        @foreach ($record->attachments as $attachment)
                            @php
                                $filePath = 'storage/' . $attachment->file_path;
                                $type = getFileIcon($attachment->file_path);
                            @endphp

                            @if ($type === 'image')
                                <img src="{{ asset($filePath) }}" alt="Attachment"
                                    class="w-24 h-24 rounded-md object-cover">
                            @elseif ($type === 'video')
                                <video src="{{ asset($filePath) }}" controls
                                    class="w-24 h-24 rounded-md object-cover"></video>
                            @else
                                @php
                                    $iconPath = match ($type) {
                                        'pdf' => asset('icons/pdf_icon.svg'),
                                        'word' => asset('icons/word.svg'),
                                        'txt' => asset('icons/text_icon.svg'),
                                        'excel' => asset('icons/excel.svg'),
                                        'csv' => asset('icons/excel.svg'),
                                        default => asset('icons/file_icon.svg'),
                                    };
                                @endphp
                                <a href="{{ asset($filePath) }}" target="_blank">
                                    <img src="{{ $iconPath }}" alt="{{ $type }} file"
                                        class="w-24 h-24 rounded-md object-cover border border-gray-200 p-2">
                                </a>
                            @endif
                        @endforeach
                    @else
                        <img src="{{ asset('icons/no_icon.svg') }}" alt="No Image"
                            class="w-24 h-24 rounded-md object-cover">
                    @endif

                </div>

                <span
                    class="bg-sky-600 text-white text-sm px-4 py-1 rounded-full h-fit">{{ ucfirst($record->status) }}</span>
            </div>
            @if($record->description != null)
            <!-- Info Section -->
            <div class="bg-gray-50 p-6 rounded-b-lg">
                <div class="grid grid-cols-1 gap-6 ">
                    <div class="py-2">
                        <p class="font-medium">Title:</p>
                        <p class="text-gray-600">{{ $record->title ?? '' }}</p>
                    </div>

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
