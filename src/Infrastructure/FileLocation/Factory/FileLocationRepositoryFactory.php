<?php
namespace VideoPublisherApp\Infrastructure\FileLocation\Factory;

use VideoPublisher\Domain\Data\MediaContent\FileLocation\FileLocation;
use VideoPublisher\Domain\Data\MediaContent\FileLocation\Repository\Factory\UnsupportedFileLocationRepository;
use VideoPublisher\Domain\Data\MediaContent\FileLocation\Repository\FileLocationRepository;

class FileLocationRepositoryFactory implements \VideoPublisher\Domain\Data\MediaContent\FileLocation\Repository\Factory\FileLocationRepositoryFactory
{
    public function getRepositoryForFileLocation(FileLocation $location): FileLocationRepository
    {
        $di_key = 'file_location.repository.' . $location->getStorageType();
        if(!container()->has($di_key)) {
            throw UnsupportedFileLocationRepository::fromProblem(
                'FILE_LOCATION_REPOSITORY_NOT_SUPPORTED',
                "File location Repository :repo is unsupported",
                [":repo" => $location->getStorageType()]
            );
        };
        
        return container()->get($di_key);
    }
    
}