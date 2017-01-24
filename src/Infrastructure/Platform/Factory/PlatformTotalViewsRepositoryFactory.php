<?php
namespace VideoPublisherApp\Infrastructure\Platform\Factory;

use VideoPublisher\Domain\Data\PlatformContent\Report\TotalViewsReport\Factory\PlatformTotalViewsRepositoryFactory as FactoryInterface;
use VideoPublisher\Domain\Data\PlatformContent\Report\TotalViewsReport\Factory\UnsupportedPlatform;
use VideoPublisher\Domain\Data\PlatformContent\Report\TotalViewsReport\PlatformTotalViewsReportingRepository;

class PlatformTotalViewsRepositoryFactory implements FactoryInterface
{
    public function getTotalViewsRepositoryRepositoryForPlatform(string $platform_name
    ): PlatformTotalViewsReportingRepository {
        
        $di_key = 'platform.reporting.total_views.repository.' . $platform_name;
        if(!container()->has($di_key)) {
            throw UnsupportedPlatform::fromProblem(
                'PLATFORM_REPORTING_REPOSITORY_NOT_SUPPORTED',
                "Platform :platform is unsupported",
                [":platform" => $platform_name]
            );
        };
        
        return container()->get($di_key);
    }
    
}