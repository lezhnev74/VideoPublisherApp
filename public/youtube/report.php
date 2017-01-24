<?php
/**
 * THIS IS CONCEPT PROOF CODE, NOT FOR PRODUCTION
 */

use VideoPublisher\Domain\Data\Identity;
use VideoPublisher\Domain\Data\MediaContent\FileLocation\FileLocation;
use VideoPublisher\Domain\Data\MediaContent\MediaContent;
use VideoPublisher\Domain\Data\MediaContent\Meta\Meta;
use VideoPublisher\Domain\Data\MediaContent\Stream\StreamCollection;
use VideoPublisher\Domain\Data\PlatformContent\PlatformContent;
use VideoPublisher\Domain\Data\PlatformContent\PlatformContentCollection\PlatformContentCollection;
use VideoPublisherApp\Infrastructure\Platform\Factory\PlatformRepositoryFactory;
use VideoPublisherApp\Infrastructure\Platform\Factory\PlatformTotalViewsRepositoryFactory;

require "../../vendor/autoload.php";
require "../../bootstrap/bootstrap.php";


$platform_content = new PlatformContent(new Identity(), "vhXpNQHauwc", "youtube");

$factory    = new PlatformTotalViewsRepositoryFactory();
$repository = $factory->getTotalViewsRepositoryRepositoryForPlatform($platform_content->getPlatformName());
$report     = $repository->getTotalViews($platform_content);

var_dump($report);



