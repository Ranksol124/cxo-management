<?php 
namespace App\Settings;
use Spatie\LaravelSettings\Settings;

class UserEmailSetting extends Settings
{
    public ?string $subject = '';
    public ?string $greeting = '';
    public ?string $intro_line = '';
    public ?string $credentials_line = '';
    public ?string $password_line = '';
    public ?string $button_text = '';
    public ?string $closing_line = '';
    
    public static function group(): string
    {
        return 'user_register_email';
    }
    public static function getNavigationSort(): ?int
    {
        return 99; // Users sabse upar
    }
}