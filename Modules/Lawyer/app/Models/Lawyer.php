<?php

namespace Modules\Lawyer\app\Models;

use App\Models\LawyerSocialMedia;
use App\Models\MeetingHistory;
use App\Models\Message;
use App\Models\ZoomCredential;
use App\Models\ZoomMeeting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Appointment\app\Models\Appointment;
use Modules\Lawyer\app\Enums\LawyerStatus;
use Modules\Leave\app\Models\Leave;
use Modules\Schedule\app\Models\Schedule;

class Lawyer extends Authenticatable {
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'department_id',
        'location_id',
        'name',
        'slug',
        'fee',
        'years_of_experience',
        'show_homepage',
        'status',
        'email',
        'password',
        'phone',
        'forget_password_token',
        'email_verified_at',
        'email_verified_token',
        'wallet_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password',
        'remember_token', 'forget_password_token',
        'email_verified_at',
        'email_verified_token', 'updated_at', 'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getDesignationsAttribute(): ?string {
        return $this?->translation?->designations;
    }
    public function getAboutAttribute(): ?string {
        return $this?->translation?->about;
    }
    public function getAddressAttribute(): ?string {
        return $this?->translation?->address;
    }
    public function getEducationsAttribute(): ?string {
        return $this?->translation?->educations;
    }
    public function getExperienceAttribute(): ?string {
        return $this?->translation?->experience;
    }
    public function getQualificationsAttribute(): ?string {
        return $this?->translation?->qualifications;
    }

    public function getSeoTitleAttribute(): ?string {
        return $this?->translation?->seo_title;
    }

    public function getSeoDescriptionAttribute(): ?string {
        return $this?->translation?->seo_description;
    }

    public function department(): ?BelongsTo {
        return $this->belongsTo(Department::class);
    }
    public function location(): ?BelongsTo {
        return $this->belongsTo(Location::class);
    }
    public function schedules(): ?HasMany {
        return $this->hasMany(Schedule::class, 'lawyer_id');
    }
    public function leaves(): ?HasMany {
        return $this->hasMany(Leave::class, 'lawyer_id');
    }
    public function appointments(): ?HasMany {
        return $this->hasMany(Appointment::class, 'lawyer_id');
    }

    public function translation(): ?HasOne {
        return $this->hasOne(LawyerTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?LawyerTranslation {
        return $this->hasOne(LawyerTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(LawyerTranslation::class, 'lawyer_id');
    }
    public function zoom_credentials(): ?HasOne {
        return $this->hasOne(ZoomCredential::class, 'lawyer_id')->select('lawyer_id', 'zoom_account_id', 'zoom_api_key', 'zoom_api_secret');
    }
    public function accountID() {
        return $this->zoom_credentials->zoom_account_id;
    }
    public function clientID(): ?string {
        return $this->zoom_credentials->zoom_api_key;
    }

    public function clientSecret() {
        return $this->zoom_credentials->zoom_api_secret;
    }

    public function meeting(): ?HasMany {
        return $this->hasMany(ZoomMeeting::class, 'lawyer_id');
    }
    public function meeting_history(): ?HasMany {
        return $this->hasMany(MeetingHistory::class, 'lawyer_id');
    }
    public function scopeActive($query) {
        return $query->where('status', LawyerStatus::ACTIVE->value);
    }
    public function scopeInactive($query) {
        return $query->where('status', LawyerStatus::INACTIVE->value);
    }
    public function scopeHomepage($query) {
        return $query->where('show_homepage', 1);
    }
    public function scopeVerify($query) {
        return $query->where('email_verified_at', '!=', null);
    }
    public function scopePaid($query) {
        return $query->where('fee', '!=', 0);
    }
    public function messages(): ?HasMany {
        return $this->hasMany(Message::class, 'lawyer_id');
    }
    public function socialMedia(): ?HasMany {
        return $this->hasMany(LawyerSocialMedia::class, 'lawyer_id');
    }
}
