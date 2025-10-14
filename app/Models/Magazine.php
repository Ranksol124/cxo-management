<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\Encryptable;
class Magazine extends Model
{
    use Encryptable;
    protected $fillable = [
        'title',
        'description',
        'file',
        'status',
    ];

    protected static function booted()
    {
        static::saving(function ($magazine) {
            if ($magazine->file) {
                // Extract extension from file path
                $magazine->file_type = strtolower(pathinfo($magazine->file, PATHINFO_EXTENSION));
            }
        });
    }
    protected function title(): Attribute
    {
        return $this->makeEncryptableAttribute();
    }

    protected function description(): Attribute
    {
        return $this->makeEncryptableAttribute();
    }

    // App\Models\Magazine.php
public function getFilePreviewAttribute(): string
{
    if (!$this->file) {
        return '<img src="' . asset('icons/no_icon.svg') . '" class="w-24 h-24 rounded-md object-cover" />';
    }

    $ext = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));

    $icons = [
        'pdf'  => asset('icons/pdf_icon.svg'),
        'doc'  => asset('icons/word.svg'),
        'docx' => asset('icons/word.svg'),
        'xls'  => asset('icons/excel.svg'),
        'xlsx' => asset('icons/excel.svg'),
        'txt'  => asset('icons/text_icon.svg'),
        'zip'  => asset('icons/zip_icon.svg'),
        'svg'  => asset('icons/image.svg'),
        'jpg'  => asset('icons/image.svg'),
        'jpeg' => asset('icons/image.svg'),
        'png'  => asset('icons/image.svg'),
        'webp' => asset('icons/image.svg'),
    ];

    // Agar image hai to actual preview karo
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
        return '<img src="' . asset('storage/' . $this->file) . '" 
            class="w-24 h-24 rounded-md object-cover" alt="' . e($ext) . ' file" />';
    }

    // Otherwise file icon show karo with link
    $icon = $icons[$ext] ?? asset('icons/file.png');

    return '<a href="' . asset('storage/' . $this->file) . '" download>
                <img src="' . $icon . '" 
                     class="w-24 h-24 object-cover rounded-md" 
                     alt="' . e($ext) . ' file" />
            </a>';
}

}
