<?php

namespace App\Providers;

use Filament\Panel;
use Filament\PanelProvider;
use Rupadana\ApiService\ApiServicePlugin;

class ApiPanelProvider  extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('api')
            ->path('admin-api')
            ->authMiddleware([]) 
            ->plugins([
                ApiServicePlugin::make(),
            ]);
    }
}
