<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'images',
        'tags',
        'links',
        'user_id',
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
        'links'  => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id');
    // }
}
