<?php
/**
 * THIS IS CONCEPT PROOF CODE, NOT FOR PRODUCTION
 */
require "../../vendor/autoload.php";
require "../../bootstrap/bootstrap.php";

$data       = [
    'client_id' => config('content_platforms.youtube.oauth.id'),
    'response_type' => 'code',
    'redirect_uri' => 'http://localhost:8001/youtube/redirect.php',
    'scope' => 'https://www.googleapis.com/auth/youtube.upload',
    'access_type' => 'offline',
];
$google_url = "https://accounts.google.com/o/oauth2/auth?" . http_build_query($data);

echo "
<script>
    window.location.href = '" . $google_url . "';
</script>
";