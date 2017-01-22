<?php

/**
 * Here I use DI PHP-DI to set services I need with it's initialization logic
 */
use VideoPublisher\Domain\Data\MediaContent\FileLocation\Repository\LocalFileLocationRepository;

return [
    'di' => [
        
        //
        // Platform Repositories
        //
        'platform.repository.youtube' => \DI\factory(function() {
            
            // Ref: https://github.com/google/google-api-php-client/blob/master/examples/simple-file-upload.php
            $client = new Google_Client();
            $client->setApplicationName('VideoPublisherApp');
            $client->setAccessToken(env('YOUTUBE_ACCESS_TOKEN')); // TODO connect to Access token dynamic storage
            $service = new Google_Service_YouTube($client);
            
            return new YoutubePlatformRepository($service);
            
        }),
        
        
        //
        // FileLocation Repositories
        //
        'file_location.repository.local' => \DI\factory(function() {
            return new LocalFileLocationRepository(storage_path());
        }),
    
    ],
];