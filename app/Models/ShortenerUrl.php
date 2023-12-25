<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortenerUrl extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'shortener_url';

    public static function getUrl($url){
        return self::where('url', '=', $url)->get()->toArray();
    }

    public static function getUrlByShortCode($shortCode){
        return self::where('short_code', '=', $shortCode)->get()->toArray();
    }

    public static function getOriginalUrlByShortCode($shortCode){
        return head(self::getUrlByShortCode($shortCode))['url'] ?? null;
    }

}
