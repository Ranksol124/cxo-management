<?php

namespace App\Filament\Traits;

trait HasResourcePermissions
{
    // ✅ Static checks
    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view ' . static::getPermissionName()) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create ' . static::getPermissionName()) ?? false;
    }
    public static function shouldRegisterNavigation(): bool
    {
        // Super admin always sees everything
        if (auth()->user()?->hasRole('super-admin')) {
            return true;
        }

        // Only show if user has "view" permission for this resource
        return auth()->user()?->can('view ' . static::getPermissionName()) ?? false;
    }
    // public static function canAccess(): bool
    // {
    //     return auth()->user()?->can('view ' . static::getPermissionName()) ?? false;
    // }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('edit ' . static::getPermissionName()) ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('delete ' . static::getPermissionName()) ?? false;
    }

    public static function canView($record): bool
    {
        return auth()->user()?->can('view ' . static::getPermissionName()) ?? false;
    }

    /**
     * Infer resource permission name from model or override it.
     */
    protected static function getPermissionName(): string
    {
        return str(class_basename(static::$model))
            ->lower()->plural(); // "Magazine" => "magazines"
    }
}