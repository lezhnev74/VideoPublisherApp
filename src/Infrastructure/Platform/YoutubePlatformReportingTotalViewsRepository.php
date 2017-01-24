<?php

namespace VideoPublisherApp\Infrastructure\Platform;


use Carbon\Carbon;
use VideoPublisher\Domain\Data\PlatformContent\PlatformContent;
use VideoPublisher\Domain\Data\PlatformContent\Report\TotalViewsReport\Exception\UnableToGetReport;
use VideoPublisher\Domain\Data\PlatformContent\Report\TotalViewsReport\PlatformTotalViewsReportingRepository;
use VideoPublisher\Domain\Data\PlatformContent\Report\TotalViewsReport\TotalViewsReport;

class YoutubePlatformReportingTotalViewsRepository implements PlatformTotalViewsReportingRepository
{
    /** @var string */
    private $platform_name = "youtube";
    /** @var  string */
    private $youtube_api_key;
    
    /**
     * YoutubePlatformReportingTotalViewsRepository constructor.
     *
     * @param string $youtube_api_key
     */
    public function __construct($youtube_api_key)
    {
        $this->youtube_api_key = $youtube_api_key;
    }
    
    
    public function getTotalViews(PlatformContent $content): TotalViewsReport
    {
        $JSON = @file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $content->getPlatformId() . "&key=" . $this->youtube_api_key);
        
        if($JSON === false) {
            $error = error_get_last();
            throw UnableToGetReport::fromProblem(
                "PLATFORM_CONTENT_REPORT_FETCHING_FAILED",
                "Unable to fetch total views report for :platform_name",
                [
                    "platform_name" => $this->platform_name
                ]
            );
        }
        
        $json_data = json_decode($JSON, true);
        
        return new TotalViewsReport($content->getId(), Carbon::now(),
                                    $json_data['items'][0]['statistics']['viewCount']);
        
    }
    
    
}