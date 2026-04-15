<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    public const ZONE_MAIN = 'main';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'url',
        'menu_zone',
        'parent_id',
        'page_key',
        'sort_order',
        'is_active',
        'open_in_new_tab',
        'is_home_icon',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'parent_id' => 'integer',
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'is_home_icon' => 'boolean',
    ];

    public static function zones(): array
    {
        return [
            static::ZONE_MAIN => 'Menu chính (Header)',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->ordered();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInZone($query, string $zone)
    {
        return $query->where('menu_zone', $zone);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function scopeOrderedHierarchy($query)
    {
        return $query
            ->orderBy('menu_zone')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
