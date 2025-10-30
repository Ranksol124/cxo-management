<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Resources\DashboardStatesResource\Widgets\StatsOverview;
use App\Http\Middleware\RoleRedirect;
use Filament\Facades\Filament;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\ChangePassword;
use App\Filament\Pages\ProfileSettings;
use Rupadana\ApiService\ApiServicePlugin;
use App\Http\Middleware\ApiPublicRestriction;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\FileUpload;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {

        return $panel
            ->default()
            ->id('admin')
            ->path('portal')
            ->login()->passwordReset()
            ->darkMode(false) // user ko toggle hide, tum default fix kar do
            ->colors([
                'primary' => Color::Cyan,
            ])->brandLogo(asset('images/logo.png'))->brandLogoHeight('2rem')
            ->navigationGroups([
                'Membership',
                'Media Center',
            ])
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('5s') // optional: auto-refresh every 5 seconds
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            // ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                // ProfileSettings::class,
                // ChangePassword::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                StatsOverview::class,
            ])

            // ->navigationItems([
            //                     \Filament\Navigation\NavigationItem::make('Plans')
            //                         ->url(fn() => route('filament.admin.resources.plans.index'))
            //                         // ->icon('heroicon-o-clipboard-list')
            //                         ->group('Membership')
            //                         ->visible(fn() => true), // Always visible

            //                     \Filament\Navigation\NavigationItem::make('Members')
            //                         ->url(fn() => route('filament.admin.resources.members.index'))
            //                         ->icon('heroicon-o-users')
            //                         ->group('Membership')
            //                         ->visible(fn() => auth()->user()?->plan_id), // Only visible if user has a plan
            //                 ])



            ->viteTheme('resources/css/filament/admin/theme.css')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                // FilamentSpatieRolesPermissionsPlugin::make(),
                ApiServicePlugin::make()->middleware([
                    ApiPublicRestriction::class
                ]),
                BreezyCore::make()->myProfile(
                    shouldRegisterUserMenu: true,
                    shouldRegisterNavigation: true,
                    navigationGroup: 'Settings',
                    userMenuLabel: 'My Profile',
                    hasAvatars: true,
                    slug: 'my-profile'
                )
                    ->passwordUpdateRules(
                        rules: [Password::default()->mixedCase()->uncompromised(3)],
                        requiresCurrentPassword: false
                    )

                    ->enableBrowserSessions(condition: true)
                    ->avatarUploadComponent(
                        fn() =>
                        FileUpload::make('profile_picture')->disk('public')->directory('avatar')
                    )



            ])
            ->authMiddleware([
                Authenticate::class,
                // RoleRedirect::class
            ]);
    }
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::auth()->user()?->can('super-admin');
        });
    }
}
