<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:14:57
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:26:32
 */

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
