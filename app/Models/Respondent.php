<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * App\Models\Respondent
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Statistic[] $statistics
 * @property-read int|null $statistics_count
 * @property-read \App\Models\Template $templates
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\RespondentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property bool $active
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Respondent whereUserId($value)
 */
class Respondent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'name',
        'active',
        'template_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(Statistic::class);
    }

    public function templates(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
