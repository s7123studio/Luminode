<?php

namespace App\Models;

use Luminode\Core\ORM\BaseModel;

class Comment extends BaseModel
{
    protected $fillable = [
        'post_id',
        'author',
        'content',
    ];

    /**
     * Defines the relationship that a comment belongs to a post.
     */
    public function post(): \Luminode\Core\ORM\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
