<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Collection $events
 */
class Authorization extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authorization';

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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'organization_id', 'id');
    }
}
