<?php

namespace App\Services;

use MuxPhp\Api\DirectUploadsApi;
use MuxPhp\Api\AssetsApi;
use MuxPhp\Configuration;
use MuxPhp\Models\CreateAssetRequest;
use MuxPhp\Models\InputSettings;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class MuxService
{
    private $directUploadsApi;
    private $assetsApi;
    private $muxTokenId;
    private $muxTokenSecret;

    public function __construct()
    {
        $this->muxTokenId = config('services.mux.token_id', env('MUX_TOKEN_ID'));
        $this->muxTokenSecret = config('services.mux.token_secret', env('MUX_TOKEN_SECRET'));

        if (!$this->muxTokenId || !$this->muxTokenSecret) {
            throw new \Exception('MUX credentials are not configured. Please set MUX_TOKEN_ID and MUX_TOKEN_SECRET in your .env file.');
        }

        $config = Configuration::getDefaultConfiguration()
            ->setUsername($this->muxTokenId)
            ->setPassword($this->muxTokenSecret);

        // Create Guzzle client with SSL configuration
        $guzzleClient = $this->createGuzzleClient();

        $this->directUploadsApi = new DirectUploadsApi(
            $guzzleClient,
            $config
        );

        $this->assetsApi = new AssetsApi(
            $guzzleClient,
            $config
        );
    }

    /**
     * Create a Guzzle HTTP client with proper SSL configuration
     *
     * @return Client
     */
    private function createGuzzleClient(): Client
    {
        $options = [];

        // Handle SSL certificate verification
        // In local development, you may need to disable SSL verification
        // Set MUX_VERIFY_SSL=false in .env for local development
        $verifySslEnv = env('MUX_VERIFY_SSL');

        // Convert string to boolean if set, otherwise use default based on environment
        if ($verifySslEnv !== null) {
            $verifySsl = filter_var($verifySslEnv, FILTER_VALIDATE_BOOLEAN);
        } else {
            // Default: verify SSL in production, disable in local/dev
            $verifySsl = config('app.env') === 'production';
        }

        if (!$verifySsl) {
            // Disable SSL verification for local development
            $options['verify'] = false;
        } else {
            // Use system CA bundle or specify a custom path
            $caBundle = env('MUX_CA_BUNDLE');
            if ($caBundle && file_exists($caBundle)) {
                $options['verify'] = $caBundle;
            } else {
                // Try to use Laravel's CA bundle if available
                $laravelCaBundle = base_path('vendor/guzzlehttp/guzzle/src/cacert.pem');
                if (file_exists($laravelCaBundle)) {
                    $options['verify'] = $laravelCaBundle;
                } else {
                    // Use system default
                    $options['verify'] = true;
                }
            }
        }

        return new Client($options);
    }

    /**
     * Upload a video file to MUX and return the asset ID
     *
     * @param UploadedFile $file
     * @return string|null The MUX asset ID
     * @throws \Exception
     */
    public function uploadVideo(UploadedFile $file): ?string
    {
        try {
            // Validate file type
            $allowedMimeTypes = ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
            if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                throw new \Exception('Invalid video file type. Allowed types: MP4, MPEG, MOV, AVI, WEBM');
            }

            // Validate file size (max 500MB)
            $maxSize = 500 * 1024 * 1024; // 500MB in bytes
            if ($file->getSize() > $maxSize) {
                throw new \Exception('Video file size exceeds maximum allowed size of 500MB');
            }

            // Create a direct upload
            // Note: Setting 'test' to false allows full video playback (test mode limits to 10 seconds)
            $createUploadRequest = new \MuxPhp\Models\CreateUploadRequest([
                'new_asset_settings' => new CreateAssetRequest([
                    'playback_policy' => ['public'],
                    'input' => new InputSettings([
                        'generated_subtitles' => false,
                    ]),
                ]),
                'test' => false, // Set to false to allow full video playback
            ]);

            $upload = $this->directUploadsApi->createDirectUpload($createUploadRequest);

            if (!$upload || !$upload->getData()) {
                throw new \Exception('Failed to create MUX upload');
            }

            $uploadId = $upload->getData()->getId();
            $uploadUrl = $upload->getData()->getUrl();

            // Upload the file to MUX using a client with SSL configuration
            $uploadClient = $this->createGuzzleClient();
            $response = $uploadClient->put($uploadUrl, [
                'body' => fopen($file->getRealPath(), 'r'),
                'headers' => [
                    'Content-Type' => $file->getMimeType(),
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Failed to upload video to MUX');
            }

            // Wait for the asset to be ready (polling)
            $assetId = null;
            $maxAttempts = 60; // 5 minutes max (5 seconds * 60)
            $attempt = 0;

            while ($attempt < $maxAttempts) {
                sleep(5); // Wait 5 seconds between checks
                $attempt++;

                try {
                    $uploadStatus = $this->directUploadsApi->getDirectUpload($uploadId);
                    if ($uploadStatus && $uploadStatus->getData()) {
                        $status = $uploadStatus->getData()->getStatus();

                        if ($status === 'asset_created') {
                            $assetId = $uploadStatus->getData()->getAssetId();
                            break;
                        } elseif ($status === 'errored') {
                            throw new \Exception('MUX upload failed: ' . ($uploadStatus->getData()->getError()?->getMessage() ?? 'Unknown error'));
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Error checking MUX upload status: ' . $e->getMessage());
                }
            }

            if (!$assetId) {
                throw new \Exception('Video upload timed out. Please try again.');
            }

            return $assetId;
        } catch (\Exception $e) {
            Log::error('MUX upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get playback ID for an asset
     *
     * @param string $assetId
     * @return string|null
     */
    public function getPlaybackId(string $assetId): ?string
    {
        try {
            $asset = $this->assetsApi->getAsset($assetId);
            if ($asset && $asset->getData() && $asset->getData()->getPlaybackIds()) {
                $playbackIds = $asset->getData()->getPlaybackIds();
                if (count($playbackIds) > 0) {
                    return $playbackIds[0]->getId();
                }
            }
        } catch (\Exception $e) {
            Log::error('Error getting MUX playback ID: ' . $e->getMessage());
        }

        return null;
    }
}

