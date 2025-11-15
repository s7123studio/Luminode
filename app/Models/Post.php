<?php

namespace App\Models;

use Luminode\Core\ORM\BaseModel;

class Post extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'title',
        'content',
    ];

    /**
     * Defines the relationship that a post has many comments.
     *
     * @return \Luminode\Core\ORM\Relations\HasMany
     */
    public function comments(): \Luminode\Core\ORM\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
