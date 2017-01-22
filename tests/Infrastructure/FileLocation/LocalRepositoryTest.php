<?php
namespace VideoPublisherApp\Tests\Infrastructure\FileLocation;

use VideoPublisher\Domain\Data\MediaContent\FileLocation\Repository\LocalFileLocationRepository;

class LocalRepositoryTest extends \PHPUnit_Framework_TestCase
{
    function test_can_upload_local_file_and_then_remove_it()
    {
        
        $local_file = __DIR__ . "/../../resources/video/h264_low.mp4";
        
        //
        // Upload file
        //
        $repository    = new LocalFileLocationRepository(storage_path());
        $file_location = $repository->copyFileToLocation($local_file, "/" . basename($local_file));
        
        $this->assertTrue(file_exists($repository->getUrl($file_location)));
        
        //
        // Remove it
        //
        $repository->removeFile($file_location);
        
        $this->assertFalse(file_exists($repository->getUrl($file_location)));
        
    }
}