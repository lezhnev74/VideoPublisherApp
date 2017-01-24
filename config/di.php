<?php

/**
 * Here I use DI PHP-DI to set services I need with it's initialization logic
 */
use VideoPublisher\Domain\Data\MediaContent\FileLocation\Repository\LocalFileLocationRepository;
use VideoPublisherApp\Infrastructure\FileLocation\Factory\FileLocationRepositoryFactory;
use VideoPublisherApp\Infrastructure\Platform\YoutubePlatformReportingTotalViewsRepository;
use VideoPublisherApp\Infrastructure\Platform\YoutubePlatformRepository;

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
            $client->setClientId(config('content_platforms.youtube.oauth.id'));
            $client->setClientSecret(config('content_platforms.youtube.oauth.secret'));
            $service = new Google_Service_YouTube($client);
            
            $file_location_factory = new FileLocationRepositoryFactory();
            
            return new YoutubePlatformRepository($service, $file_location_factory);
            
        }),
        
        //
        // Platform reporting repository
        //
        'platform.reporting.total_views.repository.youtube' => \DI\factory(function() {
            return new YoutubePlatformReportingTotalViewsRepository(config('content_platforms.youtube.api.key'));
        }),
        
        //
        // FileLocation Repositories
        //
        'file_location.repository.local' => \DI\factory(function() {
            return new LocalFileLocationRepository(storage_path());
        }),
    
    ],
];