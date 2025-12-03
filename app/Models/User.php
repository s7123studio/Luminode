<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:46:29
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:26:20
 */

namespace App\Models;

use Luminode\Core\ORM\BaseModel;

class User extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected string $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];
}
