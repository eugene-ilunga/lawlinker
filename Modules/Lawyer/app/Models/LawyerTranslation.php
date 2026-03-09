<?php

namespace Modules\Lawyer\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LawyerTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'lawyer_id',
        'lang_code',
        'designations',
        'about',
        'address',
        'educations',
        'experience',
        'qualifications',
        'seo_title',
        'seo_description',
    ];
    protected $hidden = ['updated_at','created_at'];

    public function lawyer(): ?BelongsTo {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }
}
