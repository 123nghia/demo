<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'legacy_file',
        'page_key',
        'seo_title',
        'seo_description',
        'sort_order',
        'is_published',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
