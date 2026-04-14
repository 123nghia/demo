<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'intro',
        'cover_image',
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

    public function detailPages()
    {
        return $this->hasMany(ProjectDetailPage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function blogs()
    {
        return $this->hasMany(ProjectBlog::class)->orderBy('sort_order')->orderBy('id');
    }

    public function videos()
    {
        return $this->hasMany(ProjectVideo::class)->orderBy('sort_order')->orderBy('id');
    }

    public function publicBlogs()
    {
        return $this->hasMany(Blog::class)->orderBy('sort_order')->orderBy('id');
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
