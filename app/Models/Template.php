<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

/**
 * App\Models\Template
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $type
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Template newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Template newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Template query()
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Respondent[] $respondents
 * @property-read int|null $respondents_count
 * @property-read string $key
 * @method static \Database\Factories\TemplateFactory factory(...$parameters)
 */
class Template extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'name'
    ];

    protected $appends = [
        'key'
    ];

    public function getKeyAttribute(): string
    {
        return encrypt(['template_id' => $this->id, 'user_id' => $this->user_id]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function respondents(): HasMany
    {
        return $this->hasMany(Respondent::class);
    }

    public function delete()
    {
        // remove template storage
        $this->deleteStorage();
        return parent::delete();
    }

    public function deleteStorage()
    {
        File::deleteDirectory($this->path());
    }

    public function path()
    {
        return storage_path('app/templates/' . $this->user_id . '/' . $this->id . '/');
    }

    public function export(): string
    {
        $path = storage_path('app/tmp/' . time() . $this->user_id . $this->name . '.pdf');
        $url = \URL::to('/?key=') . $this->key;

        Browsershot::url($url)
            ->noSandbox()
            ->save($path);

        return $path;
    }
}
