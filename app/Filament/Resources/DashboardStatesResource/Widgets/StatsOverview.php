<?php

namespace App\Filament\Resources\DashboardStatesResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Member;
use App\Models\Magazine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {
        $isSuperAdmin = Auth::user()?->hasRole('super-admin');

        return [

            Stat::make('Members', $isSuperAdmin ? Member::count() : '—')
                ->icon('heroicon-o-users')
                ->description('Total Members')
                ->extraAttributes(['class' => 'members-stats !bg-[#0495ac] !text-white [&_svg]:text-white']),

            Stat::make('Active Members', $isSuperAdmin ? Member::where('status', 'Active')->count() : '—')
                ->icon('heroicon-o-user-group')
                ->description('Active Members')
                ->extraAttributes(['class' => 'members-stats !bg-[#843fbc] !text-white [&_svg]:text-white']),

            Stat::make('Pending Members', $isSuperAdmin ? Member::where('status', 'Pending')->count() : '—')
                ->icon('heroicon-o-clock')
                ->description('Pending for Approval')
                ->extraAttributes(['class' => '!bg-[#fd62ab] [&_svg]:text-white']),

            Stat::make('Blocked Members', $isSuperAdmin ? Member::where('status', 'Blocked')->count() : '—')
                ->icon('heroicon-o-shield-exclamation')
                ->description('Blocked Members')
                ->extraAttributes(['class' => '!bg-[#5d5167] [&_svg]:text-white']),
        ];
    }
}
