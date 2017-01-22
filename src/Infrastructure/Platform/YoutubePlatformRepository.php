<?php

namespace VideoPublisherApp\Infrastructure\Platform;

use VideoPublisher\Domain\Data\Identity;
use VideoPublisher\Domain\Data\MediaContent\MediaContent;
use VideoPublisher\Domain\Data\PlatformContent\PlatformContent;
use VideoPublisher\Domain\Data\PlatformContent\PlatformRepository\Exceptions\UnableToUpload;
use VideoPublisher\Domain\Data\PlatformContent\PlatformRepository\PlatformRepository;
use VideoPublisherApp\Infrastructure\FileLocation\Factory\FileLocationRepositoryFactory;

class YoutubePlatformRepository implements PlatformRepository
{
    /** @var  \Google_Service_YouTube */
    private $google_service_youtube;
    /** @var string */
    private $platform_name = "youtube";
    /** @var  FileLocationRepositoryFactory */
    private $file_location_factory;
    
    /**
     * YoutubePlatformRepository constructor.
     *
     * @param \Google_Service_YouTube       $google_service_youtube
     * @param FileLocationRepositoryFactory $file_location_factory
     */
    public function __construct(
        \Google_Service_YouTube $google_service_youtube,
        FileLocationRepositoryFactory $file_location_factory
    ) {
        $this->google_service_youtube = $google_service_youtube;
        $this->file_location_factory  = $file_location_factory;
    }
    
    
    /**
     * Upload video to Youtube
     *
     * Ref: https://developers.google.com/youtube/v3/code_samples/php#upload_a_video
     *
     * @param MediaContent $content
     *
     * @return PlatformContent
     */
    public function uploadMediaContent(MediaContent $content): PlatformContent
    {
        //
        // Get Full URL of the video to upload
        //
        $repository = $this->file_location_factory->getRepositoryForFileLocation($content->getFileLocation());
        $video_path = $repository->getUrl($content->getFileLocation());
        $file_size  = $content->getFileSize();
        
        try {
            $snippet = new \Google_Service_YouTube_VideoSnippet();
            $snippet->setTitle($content->getTitle());
            $snippet->setDescription($content->getText());
            //$snippet->setTags(array("tag1", "tag2"));
            
            // Set the video's status to "public". Valid statuses are "public",
            // "private" and "unlisted".
            $status = new \Google_Service_YouTube_VideoStatus();
            $status->setPrivacyStatus("public");
            
            // Associate the snippet and status objects with a new video resource.
            $video = new \Google_Service_YouTube_Video();
            $video->setSnippet($snippet);
            $video->setStatus($status);
            
            // Specify the size of each chunk of data, in bytes. Set a higher value for
            // reliable connection as fewer chunks lead to faster uploads. Set a lower
            // value for better recovery on less reliable connections.
            $chunkSizeBytes = 1 * 1024 * 1024;
    
            // Setting the defer flag to true tells the client to return a request which can be called
            // with ->execute(); instead of making the API call immediately.
            $this->google_service_youtube->getClient()->setDefer(true);
            
            // Create a request for the API's videos.insert method to create and upload the video.
            $insertRequest = $this->google_service_youtube->videos->insert("status,snippet", $video);
            
            // Create a MediaFileUpload object for resumable uploads.
            $media = new \Google_Http_MediaFileUpload(
                $this->google_service_youtube->getClient(),
                $insertRequest,
                'video/*',
                null,
                true,
                $chunkSizeBytes
            );
            $media->setFileSize($file_size);
            
            // Read the media file and upload it chunk by chunk.
            $status = false;
            $handle = fopen($video_path, "rb");
            while(!$status && !feof($handle)) {
                $chunk  = fread($handle, $chunkSizeBytes);
                $status = $media->nextChunk($chunk);
            }
            
            fclose($handle);
            
            //
            // Now Return new Platform Content
            //
            return new PlatformContent(new Identity(), $status['id'], $this->platform_name);
            
        } catch(\Google_Service_Exception | \Google_Exception $e) {
            throw UnableToUpload::fromProblem(
                "PLATFORM_REPOSITORY_UNABLE_UPLOAD",
                "Unable to upload this file, because: :message",
                [
                    ':message' => $e->getMessage(),
                ],
                $e
            );
        }
    }
    
    public function modifyMetaForMediaContent(
        PlatformContent $platform_content,
        MediaContent $new_content
    ): PlatformContent {
        // TODO: Implement modifyMetaForMediaContent() method.
    }
    
    public function destroyMediaContent(PlatformContent $platform_content)
    {
        // TODO: Implement destroyMediaContent() method.
    }
    
}