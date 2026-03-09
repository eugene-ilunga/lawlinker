<?php

namespace Modules\Lawyer\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentVideo extends Model {
    use HasFactory;

    protected $fillable = [
        'department_id', 'link', 'code', 'status',
    ];
    public function department(): ?BelongsTo {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
