<?php

namespace Modules\Service\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\File;

class Service extends Model {
    use HasFactory;

    protected $fillable = [
        'slug',
        'icon',
        'show_homepage',
        'status',
    ];

    public function getTitleAttribute(): ?string {
        return $this?->translation?->title;
    }

    public function getSortDescriptionAttribute(): ?string {
        return $this?->translation?->sort_description;
    }
    public function getDescriptionAttribute(): ?string {
        return $this?->translation?->description;
    }

    public function getSeoTitleAttribute(): ?string {
        return $this?->translation?->seo_title;
    }

    public function getSeoDescriptionAttribute(): ?string {
        return $this?->translation?->seo_description;
    }

    public function translation(): ?HasOne {
        return $this->hasOne(ServiceTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?ServiceTranslation {
        return $this->hasOne(ServiceTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(ServiceTranslation::class, 'service_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    public function scopeHomepage($query) {
        return $query->where('show_homepage', 1);
    }
    public function images() {
        return $this->hasMany(ServiceImage::class);
    }
    public function videos() {
        return $this->hasMany(ServiceVideo::class);
    }
    public function service_faq() {
        return $this->hasMany(ServiceFaq::class);
    }
    protected static function boot() {
        parent::boot();

        static::deleting(function ($service) {
            try {
                if ($service->images) {
                    $service->images()->each(function ($image) {
                        if ($image->large_image && !str($image->large_image)->contains('website/images')) {
                            if (@File::exists(public_path($image->large_image))) {
                                @unlink(public_path($image->large_image));
                            }
                        }
                        if ($image->small_image && !str($image->small_image)->contains('website/images')) {
                            if (@File::exists(public_path($image->small_image))) {
                                @unlink(public_path($image->small_image));
                            }
                        }
                        $image->service()->dissociate();
                        $image->delete();
                    });
                }
                if ($service->translations) {
                    $service->translations()->each(function ($translation) {
                        $translation->service()->dissociate();
                        $translation->delete();
                    });
                }
                if ($service->videos) {
                    $service->videos()->each(function ($video) {
                        if ($video->thumbnail) {
                            if (File::exists(public_path($video->thumbnail))) {
                                unlink(public_path($video->thumbnail));
                            }
                        }
                        $video->service()->dissociate();
                        $video->delete();
                    });
                }
                if ($service->service_faq) {
                    $service->service_faq()->each(function ($service_faq) {
                        $service_faq->translations()->each(function ($translation) {
                            $translation->service_faq()->dissociate();
                            $translation->delete();
                        });
                        $service_faq->delete();
                    });
                }
            } catch (\Exception $e) {
                info($e);

                $notification = __('Unable to delete as relational data exists!');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()->back()->with($notification);
            }
        });
    }

}
