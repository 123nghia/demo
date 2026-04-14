<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetailPage extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'slug',
        'summary',
        'content',
        'thumbnail_image',
        'thumbnail_click_action',
        'gallery_images',
        'sort_order',
        'is_published',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'gallery_images' => 'array',
        'is_published' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
