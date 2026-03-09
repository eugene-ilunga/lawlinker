<?php

namespace Modules\HomeSection\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkSectionTranslation extends Model {
    use HasFactory;
    protected $fillable = [
        'work_section_id',
        'lang_code',
        'title',
    ];
    public function work_section(): ?BelongsTo {
        return $this->belongsTo(WorkSection::class, 'work_section_id');
    }
}
