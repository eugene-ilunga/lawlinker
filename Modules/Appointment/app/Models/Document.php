<?php

namespace Modules\Appointment\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

class Document extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['appointment_id','path'];

    public function appointments(): ?BelongsTo {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
    public function deleteDocuments() {
        try {
            if ($this->path && File::exists(storage_path("app/public/uploads/{$this->path}"))) {
                File::delete(storage_path("app/public/uploads/{$this->path}"));
            }
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }
}
