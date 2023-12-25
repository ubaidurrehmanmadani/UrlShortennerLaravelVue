<?php

namespace App\Http\Controllers;

use App\Models\ShortenerUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SafeBrowsingGoogleAPI
{
    public static function checkSafeBrowsing(array $urls)
    {
        $apiKey = env('GOOGLE_API_KEY');
        // Google Safe Browsing API endpoint
        $apiEndpoint = 'https://safebrowsing.googleapis.com/v4/threatMatches:find';

        // Prepare request data
        $requestData = array(
            'client' => array(
                'clientId' => 'Ubaid Ur Rehman',
                'clientVersion' => '1.0.0',
            ),
            'threatInfo' => array(
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                'platformTypes' => ['ANY_PLATFORM'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => array_map(function ($url) {
                    return ['url' => $url];
                }, $urls),
            ),
        );

        // Encode request data as JSON
        $jsonData = json_encode($requestData);

        // Prepare cURL options
        $options = array(
            CURLOPT_URL => $apiEndpoint . '?key=' . $apiKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_SSL_VERIFYPEER => false
        );

        // Initialize cURL session
        $ch = curl_init();
        curl_setopt_array($ch, $options);

        // Execute cURL session and get the response
        $response = curl_exec($ch);
        // Check for cURL errors
        if (curl_errno($ch)) {
            return ['status' => false, 'message' => 'Curl error: ' . curl_error($ch)];
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $responseData = json_decode($response, true);

        // Process the response
        if (isset($responseData['matches']) && !empty($responseData['matches'])) {
            // The URL is flagged as unsafe
            return ['status' => false, 'message' => 'Unsafe URL detected!'];
            // You may want to take appropriate action here, such as blocking access to the URL.
        } else {
            // The URL is safe
            return ['status' => true, 'message' => 'URL is safe, saved Successfully!'];
        }
    }
}



