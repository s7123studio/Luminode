<?php

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
