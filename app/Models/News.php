<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Casts\Attribute;
class News extends Model
{
    use Encryptable;
    protected $fillable = [
        'title',
        'description',
        'image',
        'news_type',
        'website_preview',
        'status',
        'link'
    ];

    protected function title(): Attribute
    {
        return $this->makeEncryptableAttribute();
    }

    protected function description(): Attribute
    {
        return $this->makeEncryptableAttribute();
    }
}
