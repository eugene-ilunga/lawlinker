<?php

namespace Modules\Lawyer\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\File;

class Department extends Model {
    use HasFactory;

    protected $fillable = [
        'slug',
        'thumbnail_image',
        'show_homepage',
        'status',
    ];
    protected $hidden = ['updated_at','created_at'];

    public function getNameAttribute(): ?string {
        return $this?->translation?->name;
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
        return $this->hasOne(DepartmentTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?DepartmentTranslation {
        return $this->hasOne(DepartmentTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany {
        return $this->hasMany(DepartmentTranslation::class, 'department_id');
    }
    public function scopeActive($query) {
        return $query->where('status', 1);
    }
    public function scopeHomepage($query) {
        return $query->where('show_homepage', 1);
    }

    public function images() {
        return $this->hasMany(DepartmentImage::class);
    }
    public function videos() {
        return $this->hasMany(DepartmentVideo::class);
    }
    public function department_faq() {
        return $this->hasMany(DepartmentFaq::class);
    }
    public function lawyers() {
        return $this->hasMany(Lawyer::class, 'department_id');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function ($department) {
            try {
                if ($department->images) {
                    $department->images()->each(function ($image) {
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
                        $image->department()->dissociate();
                        $image->delete();
                    });
                }
                if ($department->translations) {
                    $department->translations()->each(function ($translation) {
                        $translation->department()->dissociate();
                        $translation->delete();
                    });
                }
                if ($department->videos) {
                    $department->videos()->each(function ($video) {
                        if ($video->thumbnail) {
                            if (File::exists(public_path($video->thumbnail))) {
                                unlink(public_path($video->thumbnail));
                            }
                        }
                        $video->department()->dissociate();
                        $video->delete();
                    });
                }
                if ($department->department_faq) {
                    $department->department_faq()->each(function ($department_faq) {
                        $department_faq->translations()->each(function ($translation) {
                            $translation->department_faq()->dissociate();
                            $translation->delete();
                        });
                        $department_faq->delete();
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
