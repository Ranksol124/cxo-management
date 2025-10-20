<x-filament::page>
    <div class="space-y-4 p-3">
        <a href="{{ url()->previous() }}" class="text-primary-600 hover:underline mt-2">← Back</a>

        <div class="bg-white shadow rounded-lg p-6 w-full space-y-6">
            <h2 class="text-xl font-semibold">Job Application Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="font-medium">Full Name:</p>
                    <p class="text-gray-700">{{ $record->member->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-medium">Email:</p>
                    <p class="text-gray-700">{{ $record->member->email ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-medium">Job Title:</p>
                    <p class="text-gray-700">{{ $record->job->title ?? '-' }}</p>
                </div>

                <div>
                    <p class="font-medium">CV File:</p>
                    @if ($record->cv_upload)
                        <a href="{{ asset('storage/' . $record->cv_upload) }}"
                           target="_blank"
                           class="text-blue-600 underline">Download CV</a>
                    @else
                        <p class="text-gray-700">No CV uploaded</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
