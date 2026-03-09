<?php

namespace Modules\Lawyer\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentTranslation extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'department_id',
        'lang_code',
        'name',
        'description',
        'seo_title',
        'seo_description',
    ];
    protected $hidden = ['updated_at','created_at'];

    public function department(): ?BelongsTo {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
