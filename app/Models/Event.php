<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $organization_id
 */
class Event extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_title',
        'event_start_date',
        'event_end_date',
        'organization_id',
    ];

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Authorization::class, 'organization_id', 'id');
    }
}
