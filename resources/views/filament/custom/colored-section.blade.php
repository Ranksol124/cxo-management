<div {{ $attributes->class([
    'filament-forms-section-component rounded-xl border border-gray-300 bg-white shadow-sm',
    'dark:border-gray-700 dark:bg-gray-800' => config('filament.dark_mode'),
]) }}>
    <div class="bg-emerald-600 rounded-t-xl px-4 py-2 border-b border-gray-300 dark:border-gray-700">
        @if ($heading)
            <h3 class="text-xl font-semibold leading-6 text-white">
                {{ $heading ?? 'Custom heading' }}
            </h3>
        @endif

        @if ($description)
            <p class="mt-1 text-sm text-gray-100">
                {{ $description }}
            </p>
        @endif
    </div>

    <div class="p-4">
        {{ $this->getChildComponentContainer() }}
    </div>
</div>