<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $organization_id
 * @property string $event_title
 * @property Carbon $event_start_date
 * @property Carbon $event_end_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'event_title',
        'event_start_date',
        'event_end_date',
    ];

    /**
     * @return BelongsTo<User, Event>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organization_id');
    }
}
