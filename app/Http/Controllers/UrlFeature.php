<?php

namespace App\Http\Controllers;

use App\Models\ShortenerUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UrlFeature
{
    const UN_TEST_SAFE_URLS = [
        'http://testsafebrowsing.appspot.com/s/malware.html',
        'http://testsafebrowsing.appspot.com/s/phishing.html',
        'http://testsafebrowsing.appspot.com/s/unwanted.html'
    ];
    public static function prepareAndSaveShortUrl(Request $request){
        $url = $request['url'] ?? null;
        $decodedUrl = urldecode($url);
        if (filter_var($decodedUrl, FILTER_VALIDATE_URL))
        {
            $shortCode = self::getShortUrl($decodedUrl);
            $result = SafeBrowsingGoogleAPI::checkSafeBrowsing(self::UN_TEST_SAFE_URLS);
            if($result['status']){
                return response()->json(
                    ShortenerUrl::create([
                    'url' => $url,
                    'short_code' => $shortCode,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ])
                );
            }else{
                return response()->json($result);
            }
        }
    }

    public static function getShortUrl($url){
        $url = ShortenerUrl::getUrl($url);
        if(!$url){
            return self::generateUniqueID();
        }
    }

    public static function generateUniqueID(){
        $shortCode = substr(md5(uniqid(rand(), true)),0,6);
        $shortUrl = ShortenerUrl::getUrlByShortCode($shortCode);
        if($shortUrl){
            $shortCode = self::generateUniqueID();
        }else{
            return $shortCode;
        }
        return $shortCode;
    }

    public static function generateRedirectUrl(Request $request, $shortCode){
        $url = ShortenerUrl::getOriginalUrlByShortCode($shortCode);
        if($url){
            return Redirect::to($url);
        }
        return false;
    }

}
