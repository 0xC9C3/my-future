<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Application[] $applications
 * @property-read int|null $applications_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Respondent[] $respondents
 * @property-read int|null $respondents_count
 * @property-read \App\Models\Setting|null $setting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Template[] $templates
 * @property-read int|null $templates_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property bool|null $two_factor_verified
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function (User $user) {
            $user->setting()->create([
                'smtp_host' => '',
                'smtp_port' => 25,
                'smtp_username' => '',
                'smtp_password' => '',
                'smtp_encryption' => '',
                'smtp_from_address' => '',
                'smtp_from_name' => '',
                'track_web_access' => false,
                'track_email_access' => false,
            ]);
        });
    }

    // cred @ Nicolas Bailly
    // https://dev.to/nicolus/laravel-fortify-implement-2fa-in-a-way-that-won-t-let-users-lock-themselves-out-2ejk
    public function verifyTOTP($code): bool
    {
        if ($this->two_factor_verified) {
            return true;
        }

        $codeIsValid = app(TwoFactorAuthenticationProvider::class)
            ->verify(decrypt($this->two_factor_secret), $code);

        if (!$codeIsValid) {
            return false;
        }

        $this->two_factor_verified = true;
        return $this->save();
    }

    public function hasTOTPEnabled(): bool
    {
        return $this->two_factor_secret && $this->two_factor_verified;
    }

    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class);
    }

    public function respondents(): HasMany
    {
        return $this->hasMany(Respondent::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }

    public function applications(): HasMany {
        return $this->hasMany(Application::class);
    }
}
