<?php
/**
 * THIS IS CONCEPT PROOF CODE, NOT FOR PRODUCTION
 */

use VideoPublisher\Domain\Data\Identity;
use VideoPublisher\Domain\Data\MediaContent\FileLocation\FileLocation;
use VideoPublisher\Domain\Data\MediaContent\MediaContent;
use VideoPublisher\Domain\Data\MediaContent\Meta\Meta;
use VideoPublisher\Domain\Data\MediaContent\Stream\StreamCollection;
use VideoPublisher\Domain\Data\PlatformContent\PlatformContentCollection\PlatformContentCollection;
use VideoPublisherApp\Infrastructure\Platform\Factory\PlatformRepositoryFactory;

require "../../vendor/autoload.php";
require "../../bootstrap/bootstrap.php";


$relative_path = '/h264_low.mp4';
$video_path    = storage_path($relative_path);


$media_content = new MediaContent(
    new Identity(),
    new StreamCollection([]),
    new Meta("Test video title", "Some long description about this video goes here"),
    new FileLocation('local', $relative_path, filesize($video_path)),
    new PlatformContentCollection([])
);

$factory          = new PlatformRepositoryFactory();
$repository       = $factory->getRepositoryForPlatform('youtube');
$platform_content = $repository->uploadMediaContent($media_content);

var_dump($platform_content);
