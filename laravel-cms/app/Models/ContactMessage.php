<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'source_page',
        'name',
        'phone',
        'email',
        'service',
        'message',
        'is_read',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];
}
