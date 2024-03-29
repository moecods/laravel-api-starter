<?php

namespace App\Models;

use App\Filters\UserFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Qirolab\Laravel\Reactions\Contracts\ReactsInterface;
use Qirolab\Laravel\Reactions\Traits\Reacts;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int|string $id
 * @property string $name
 * @property string $email
 */
class User extends Authenticatable implements ReactsInterface
{
    use Filterable, HasApiTokens, HasFactory, HasRoles, Notifiable, Reacts;

    protected string $default_filters = UserFilters::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
