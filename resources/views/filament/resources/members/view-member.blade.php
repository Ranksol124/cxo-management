<x-filament::page>
    <div class="space-y-4">
        <a href="{{ url()->previous() }}" class="text-primary-600 hover:underline mt-2">← Back</a>
        <div class="bg-white shadow rounded-lg p-[35px] w-full">
            <!-- Top Section -->
            <div class="p-0 md:p-6 flex items-start justify-between">
                <div class="flex flex-wrap gap-4">
                    @if ($record->image != null)
                        <img src="{{ asset('storage/' . $record->dp) }}" alt="Profile"
                            class="w-24 h-24 rounded-md object-cover">
                    @else
                        <img src="{{ asset('icons/no_icon.svg') }}" alt="Profile"
                            class="w-24 h-24 rounded-md object-cover">
                    @endif

                    <div class="flex flex-col gap-1">
                        <h2 class="text-xl record-title max-w-[500px] break-words pb-3 font-semibold">
                            {{ $record->full_name }} {{ isset($record->designation) ? '- ' . $record->designation : '' }}
                        </h2>
                        <p class="text-gray-700">{{ $record->organization }}</p>
                        <p class="text-gray-500 text-sm">{{ $record->email }}</p>
                        <p class="text-gray-500 text-sm">{{ $record->contact }}</p>
                        <p class="text-gray-500 text-sm">
                            {{ $record->state?->name }},
                            {{ $record->city?->name }}
                        </p>
                        <p class="text-gray-500 text-sm">
                            {{ $record->country?->name }}
                        </p>
                    </div>
                </div>
                <span
                    class="bg-sky-600 text-white text-sm px-4 py-1 rounded-full h-fit">{{ ucfirst($record->status) }}</span>
            </div>

            <!-- Info Section -->
            <div class="bg-gray-50 p-6 rounded-b-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="pb-4 border-b border-gray-200">
                        <p class="font-medium">Plan:</p>
                        <p class="text-gray-600">{{ $record->plan->name ?? '' }}</p>
                    </div>
                    @if ($record->silverDetails != null or $record->goldDetails != null)
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Number of employees:</p>
                            <p class="text-gray-600">{{ $record->silverDetails->number_of_employees ?? '' }} Employed
                            </p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Organization status:</p>
                            <p class="text-gray-600">{{ $record->silverDetails->organization_status ?? '' }} Employed
                            </p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Gender:</p>
                            <p class="text-gray-600">{{ $record->silverDetails->gender ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Qualification:</p>
                            <p class="text-gray-600">{{ $record->silverDetails->qualification ?? '' }} Employed</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Payment timeline:</p>
                            <p class="text-gray-600">{{ $record->silverDetails->payment_timeline ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Organization business:</p>
                            <p class="text-gray-600">{{ $record->silverDetails->organization_business ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Expectation:</p>
                            <p class="text-gray-600">{{ $record->silverDetails->expectation ?? '' }}</p>
                        </div>
                    @else
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Organization status:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->organization_status ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Organization business:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->organization_business ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Organization contact:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->organization_contact ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">No of Employees:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->number_of_employees ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Second member name:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->second_member_name ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Second member contact:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->second_member_contact ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Second member designation:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->second_member_designation ?? '' }}
                            </p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Second member email:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->second_member_email ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Mailing address:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->mailing_address ?? '' }}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium">Expectations:</p>
                            <p class="text-gray-600">{{ $record->enterpriseDetails->expectation ?? '' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
