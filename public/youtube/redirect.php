<?php
/**
 * THIS IS CONCEPT PROOF CODE, NOT FOR PRODUCTION
 */

require "../../vendor/autoload.php";
require "../../bootstrap/bootstrap.php";


$data = [
    'code' => $_GET['code'],
    'client_id' => config('content_platforms.youtube.oauth.id'),
    'client_secret' => config('content_platforms.youtube.oauth.secret'),
    'redirect_uri' => 'http://localhost:8001/youtube/redirect.php',
    'grant_type' => 'authorization_code',
];
$url  = 'https://accounts.google.com/o/oauth2/token';

//
// Send POST request
//
// use key 'http' even if you send the request to https://...
$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data),
    ],
];
$context = stream_context_create($options);
$result  = file_get_contents($url, false, $context);
if($result === false) {
    echo "Problem";
}

var_dump($result);
    
    
    

