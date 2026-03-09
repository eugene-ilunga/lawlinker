<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetails extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id', 'phone', 'address', 'city', 'country', 'guardian_name', 'guardian_phone', 'occupation', 'age', 'date_of_birth', 'gender', 'ready_for_appointment',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['user_id','wallet_balance','updated_at','created_at'];

    public function user(): ?BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
