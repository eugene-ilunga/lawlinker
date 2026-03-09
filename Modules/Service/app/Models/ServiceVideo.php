<?php

namespace Modules\Service\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceVideo extends Model {
    use HasFactory;

    protected $fillable = [
        'service_id', 'link', 'code', 'status',
    ];
    public function service(): ?BelongsTo {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
