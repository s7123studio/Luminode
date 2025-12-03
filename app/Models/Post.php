<?php
/*
 * @Author: 7123
 * @Date: 2025-10-18 19:10:11
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:26:26
 */

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
