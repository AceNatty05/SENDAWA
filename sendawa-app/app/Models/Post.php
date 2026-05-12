<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'content',
        'image_path',
    ];

    /**
     * Cast otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
