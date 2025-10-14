<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Gate;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\MemberFeedsPolicy;
use App\Policies\NotificationPolicy;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Filament\Support\Assets\Js;
use Filament\Facades\Filament;
use Filament\Panel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Panel $panel): void
    {
        // Register custom CSS and JS
        FilamentAsset::register([
            Css::make('custom-stylesheet', asset('css/custom-style.css?v=' . time())),
            Js::make('custom-script', asset('custom-script.js?v=' . time())),
        ]);

        // Define a before Gate to allow super-admin full access
        Gate::before(function (User $user, string $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        // Register policies
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(\App\Models\MemberFeed::class, MemberFeedsPolicy::class);
        Gate::policy(\App\Models\Notifications::class, NotificationPolicy::class);



        // Hide navigation for users without a plan_id
        // Filament::serving(function () {
        //     $user = auth()->user();

        //     if ($user && is_null($user->plan_id)) {
        //         // Hide all navigation (or customize as needed)
        //         Filament::registerNavigationItems([]);
        //     }
        // });
    }
}
