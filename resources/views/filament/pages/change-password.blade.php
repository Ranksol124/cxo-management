<x-filament::page>
    <div class="profile-container">
        {{-- Left: Profile Info --}}
        <div class="profile-card">
            <div
                class="profile-avatar w-32 h-32 rounded-full overflow-hidden flex items-center justify-center bg-gray-200">
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture"
                    class="w-full h-full object-cover">
            </div>

            <h2 class="profile-name">{{ Auth::user()->name }}</h2>
            <p class="profile-email">{{ Auth::user()->email }}</p>
        </div>

        {{-- Right: Update Form --}}
        <div class="profile-form">
            <form wire:submit.prevent="save">
                {{ $this->form }}

                <div class="form-actions">
                    <button type="submit" class="profile-btn">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-filament::page>
