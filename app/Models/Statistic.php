<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Statistic
 *
 * @property-read \App\Models\Application $application
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereRespondentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $action
 * @property int $application_id
 * @method static \Illuminate\Database\Eloquent\Builder|Statistic whereApplicationId($value)
 * @method static \Database\Factories\StatisticFactory factory(...$parameters)
 */
class Statistic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'action',
        'template_id'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
