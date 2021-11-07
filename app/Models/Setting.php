<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Setting
 *
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @mixin \Eloquent
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $smtp_host
 * @property int $smtp_port
 * @property string $smtp_username
 * @property string $smtp_password
 * @property string $smtp_encryption
 * @property string $smtp_from_address
 * @property string $smtp_from_name
 * @property bool $track_web_access
 * @property bool $track_email_access
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSmtpEncryption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSmtpFromAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSmtpFromName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSmtpHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSmtpPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSmtpPort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSmtpUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereTrackEmailAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereTrackWebAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUserId($value)
 */
class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
        'track_web_access',
        'track_email_access',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'smtp_username',
        'smtp_password',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
