<?php
namespace VideoPublisherApp\Infrastructure\Platform\Factory;

use VideoPublisher\Domain\Data\PlatformContent\PlatformRepository\Factory\PlatformRepositoryFactory as FactoryInterface;
use VideoPublisher\Domain\Data\PlatformContent\PlatformRepository\Factory\UnsupportedPlatform;
use VideoPublisher\Domain\Data\PlatformContent\PlatformRepository\PlatformRepository;

class PlatformRepositoryFactory implements FactoryInterface
{
    
    public function getRepositoryForPlatform(string $platform_name): PlatformRepository
    {
        $di_key = 'platform.repository.' . $platform_name;
        if(!container()->has($di_key)) {
            throw UnsupportedPlatform::fromProblem(
                'PLATFORM_REPOSITORY_NOT_SUPPORTED',
                "Platform :platform is unsupported",
                [":platform" => $platform_name]
            );
        };
        
        return container()->get($di_key);
    }
}