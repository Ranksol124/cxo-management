<?php

namespace App\Filament\Pages;

use App\Settings\UserEmailSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
Use Filament\Forms\Components\Toggle;
Use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
// use App\Filament\Traits\HasResourcePermissions;

class ManageUserEmail extends SettingsPage
{
    // use HasResourcePermissions;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static string $settings = UserEmailSetting::class;
    protected static ?string $navigationLabel = 'User Email Setting';
    
    public static function shouldRegisterNavigation(): bool
    {
        // Super admin sees everything
        if (auth()->user()?->hasRole('super-admin')) {
            return true;
        }

        // Use a fixed permission name for this page
        return auth()->user()?->can('view settings');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Email Settings')
                ->extraAttributes([
                    'class' => 'filament-custom-section',
                ])->schema([
                    Grid::make(2)->schema([
                        TextInput::make('subject')
                        ->label('Email Subject (use @subject for dynamic value)')
                        ->required(),
    
                        TextInput::make('greeting')
                            ->label('Greeting (use @name)')
                            ->required(),
    
                        Textarea::make('intro_line')
                            ->label('Intro Line (use @appName)'),
    
                        Textarea::make('credentials_line')
                            ->label('Credentials Line (use @email)'),
    
                        Textarea::make('password_line')
                            ->label('Password Line (use @password)'),
    
                        TextInput::make('button_text')
                            ->label('Button Text')
                            ->default('Login Now'),
    
                        Textarea::make('closing_line')
                            ->label('Closing Line (use @appUrl)'),
                        ])
                    ])
                
            ]);
    }
}
