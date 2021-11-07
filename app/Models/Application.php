<?php

namespace App\Models;

use App\Mail\JobApplication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


/**
 * App\Models\Application
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $respondent_id
 * @property int $template_id
 * @property string $email_content
 * @property-read \App\Models\Respondent $respondent
 * @property-read \App\Models\Template $template
 * @method static \Illuminate\Database\Eloquent\Builder|Application newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application query()
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereEmailContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereRespondentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @property-read string $key
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereUserId($value)
 * @property string $email_subject
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Statistic[] $statistics
 * @property-read int|null $statistics_count
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereEmailSubject($value)
 * @property bool $email_attach_portfolio
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereEmailAttachPortfolio($value)
 * @method static \Database\Factories\ApplicationFactory factory(...$parameters)
 */
class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'template_id',
        'respondent_id',
        'email_subject',
        'email_content',
        'email_attach_portfolio',
    ];

    protected $appends = [
        'key'
    ];

    public function getKeyAttribute(): string
    {
        return encrypt(['template_id' => $this->template_id, 'application_id' => $this->id, 'user_id' => $this->user_id]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(Respondent::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(Statistic::class);
    }

    public function sendMail()
    {
        $setting = $this->user->setting;

        if ($setting === null) {
            throw ValidationException::withMessages([
                __('missing_settings')
            ]);
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp' => [
                'transport' => 'smtp',
                'host' => $setting->smtp_host,
                'port' => $setting->smtp_port,
                'username' => $setting->smtp_username,
                'password' => $setting->smtp_password,
                'encryption' => $setting->smtp_encryption
            ]
        ]);

        $content = $this->email_content;
        $link = route('portfolio.view') . '/?key=' . $this->key;
        $trackingLink = route('portfolio.view', ['key' => $this->key]);
        $content = str_replace('PORTFOLIO_LINK', $link, $content);
        $content = str_replace('PORTFOLIO_EMAIL_LINK', $trackingLink, $content);

        try {
            // send email
            Mail::to($this->respondent->email)
                ->send(
                    new JobApplication(
                        $setting->smtp_from_address,
                        $setting->smtp_from_name,
                        $this->email_subject,
                        $content,
                        $this->email_attach_portfolio ? $this->template->export() : null
                    )
                );
        } catch (\Exception $ex) {
            throw ValidationException::withMessages([
                __('mail_send_failed')
            ]);
        }

        // add log entry
        $this->statistics()->create([
            'action' => 'sent',
        ]);
    }
}
