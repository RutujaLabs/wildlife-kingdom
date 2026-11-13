<?php

namespace Tests\Unit;

use App\Services\CloudinaryImageService;
use Cloudinary\Cloudinary;
use Tests\TestCase;

class CloudinaryImageServiceConfigTest extends TestCase
{
    public function test_separate_credentials_take_precedence_over_cloudinary_url(): void
    {
        config([
            'services.cloudinary.cloud_name' => 'direct-cloud',
            'services.cloudinary.api_key' => 'direct-key',
            'services.cloudinary.api_secret' => 'direct-secret',
            'services.cloudinary.url' => 'not-used-cloudinary-url',
        ]);

        $client = $this->clientFromService(new CloudinaryImageService);

        $this->assertInstanceOf(Cloudinary::class, $client);
        $this->assertSame('direct-cloud', $client->configuration->cloud->cloudName);
        $this->assertSame('direct-key', $client->configuration->cloud->apiKey);
        $this->assertSame('direct-secret', $client->configuration->cloud->apiSecret);
    }

    public function test_cloudinary_url_remains_a_fallback_when_separate_credentials_are_missing(): void
    {
        config([
            'services.cloudinary.cloud_name' => 'configured-cloud',
            'services.cloudinary.api_key' => null,
            'services.cloudinary.api_secret' => null,
            'services.cloudinary.url' => 'cloudinary://fallback-key:fallback-secret@fallback-cloud',
        ]);

        $client = $this->clientFromService(new CloudinaryImageService);

        $this->assertInstanceOf(Cloudinary::class, $client);
        $this->assertSame('fallback-cloud', $client->configuration->cloud->cloudName);
        $this->assertSame('fallback-key', $client->configuration->cloud->apiKey);
        $this->assertSame('fallback-secret', $client->configuration->cloud->apiSecret);
    }

    private function clientFromService(CloudinaryImageService $service): ?Cloudinary
    {
        $method = new \ReflectionMethod($service, 'client');

        return $method->invoke($service);
    }
}
