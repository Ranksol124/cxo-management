<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('cxo_user_register_email.subject', null);
        $this->migrator->add('cxo_user_register_email.greeting', null);
        $this->migrator->add('cxo_user_register_email.intro_line', null);
        $this->migrator->add('cxo_user_register_email.credentials_line', null);
        $this->migrator->add('cxo_user_register_email.password_line', null);
        $this->migrator->add('cxo_user_register_email.button_text', null);
        $this->migrator->add('cxo_user_register_email.closing_line', null);
    }
};
