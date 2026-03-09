<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use Laravel\Sanctum\HasApiTokens;
use Modules\Order\app\Models\Order;
use Illuminate\Notifications\Notifiable;
use Modules\Appointment\app\Models\Appointment;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'password',
        'ready_for_appointment',
        'email_verified_at',
        'verification_token',
        'forget_password_token',
        'status',
        'is_banned',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password','remember_token','email_verified_at','verification_token','forget_password_token','status','is_banned','updated_at','created_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function appointments(): ?HasMany {
        return $this->hasMany(Appointment::class, 'user_id');
    }
    public function meeting_history(): ?HasMany {
        return $this->hasMany(MeetingHistory::class, 'user_id');
    }

    public function scopeActive($query) {
        return $query->where('status', UserStatus::ACTIVE);
    }

    public function scopeInactive($query) {
        return $query->where('status', UserStatus::DEACTIVE);
    }

    public function scopeBanned($query) {
        return $query->where('is_banned', UserStatus::BANNED);
    }

    public function scopeUnbanned($query) {
        return $query->where('is_banned', UserStatus::UNBANNED);
    }

    public function socialite() {
        return $this->hasMany(SocialiteCredential::class, 'user_id');
    }

    public function details(): ?HasOne {
        return $this->hasOne(UserDetails::class);
    }
    public function messages(): ?HasMany {
        return $this->hasMany(Message::class, 'user_id');
    }
    function orders(): HasMany {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }
}
