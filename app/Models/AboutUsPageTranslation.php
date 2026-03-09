<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsPageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_us_page_id',
        'about_description',
        'mission_description',
        'vision_description',
    ];
}
