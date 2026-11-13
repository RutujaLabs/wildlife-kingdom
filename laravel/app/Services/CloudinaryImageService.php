<?php

namespace App\Services;

use Cloudinary\Api\Exception\AlreadyExists;
use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class CloudinaryImageService
{
    private ?Cloudinary $client = null;

    public function isConfigured(): bool
    {
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');
        if (is_string($apiKey) && trim($apiKey) !== '' && is_string($apiSecret) && trim($apiSecret) !== '') {
            return true;
        }

        $url = config('services.cloudinary.url');

        return is_string($url) && trim($url) !== '';
    }

    public function ping(): void
    {
        $client = $this->client();
        if ($client === null) {
            throw new RuntimeException('Cloudinary is not configured.');
        }

        $client->adminApi()->ping();
    }

    public function environmentStatus(): array
    {
        return [
            'laravel_configured' => $this->isConfigured(),
            'process_environment_configured' => is_string(getenv('CLOUDINARY_URL')) && trim((string) getenv('CLOUDINARY_URL')) !== '',
        ];
    }

    public function credentialDiagnostics(): array
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');
        $url = config('services.cloudinary.url');

        return [
            'cloud_name' => is_string($cloudName) ? $cloudName : '[non-string]',
            'cloud_name_present' => is_string($cloudName) && trim($cloudName) !== '',
            'api_key_present' => is_string($apiKey) && trim($apiKey) !== '',
            'api_key_length' => is_string($apiKey) ? strlen($apiKey) : 0,
            'api_key_unexpected_whitespace' => is_string($apiKey) && preg_match('/\\s/', $apiKey) === 1,
            'api_secret_present' => is_string($apiSecret) && trim($apiSecret) !== '',
            'cloudinary_url_present' => is_string($url) && trim($url) !== '',
        ];
    }

    public function url($reference, string $directory): string
    {
        $reference = trim((string) $reference);
        if ($reference === '') {
            return '';
        }

        if (preg_match('#^(?:[a-z][a-z0-9+.-]*:|//)#i', $reference)) {
            return $reference;
        }

        $path = str_replace('\\', '/', $reference);
        $path = preg_replace('#^(?:\.\./)+#', '', $path) ?? $path;
        $path = ltrim($path, '/');
        if (stripos($path, 'uploads/') === 0) {
            $path = substr($path, strlen('uploads/'));
        }
        if (! str_contains($path, '/')) {
            $path = trim($directory, '/').'/'.$path;
        }

        try {
            $relativePath = $this->normalizeRelativePath($path);
        } catch (RuntimeException) {
            return '';
        }

        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $relativePath)));
        try {
            if (Storage::disk('legacy_uploads')->exists($relativePath)) {
                return asset('uploads/'.$encodedPath);
            }

            $basename = basename($relativePath);
            if ($relativePath !== $basename && Storage::disk('legacy_uploads')->exists($basename)) {
                return asset('uploads/'.rawurlencode($basename));
            }
        } catch (Throwable) {
            // Continue to the Cloudinary URL if local storage cannot be checked.
        }

        $publicId = $this->publicId($relativePath);
        $encodedPublicId = implode('/', array_map('rawurlencode', explode('/', $publicId)));

        return 'https://res.cloudinary.com/'.config('services.cloudinary.cloud_name', 'pg6m0frc').'/image/upload/f_auto,q_auto/'.$encodedPublicId;
    }

    /**
     * Upload an admin image, falling back to the existing local disk if needed.
     * The returned value remains the basename stored by the current database schema.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $directory = $this->normalizeRelativePath($directory);
        $filename = $file->hashName();

        try {
            $client = $this->client();
            if ($client !== null) {
                $client->uploadApi()->upload($file->getRealPath(), [
                    'public_id' => $this->publicId($directory.'/'.$filename),
                    'overwrite' => false,
                    'resource_type' => 'image',
                ]);

                return $filename;
            }
        } catch (Throwable) {
            // Keep the existing upload flow working if Cloudinary is unavailable.
        }

        $storedPath = $file->storeAs($directory, $filename, 'legacy_uploads');

        if (! is_string($storedPath) || $storedPath === '') {
            throw new RuntimeException('The image could not be stored.');
        }

        return basename($storedPath);
    }

    /**
     * Transfer one existing local image without changing its name or source file.
     * Returns "uploaded" or "exists" so the transfer command can be rerun safely.
     */
    public function transferExisting(string $absolutePath, string $relativePath): string
    {
        $client = $this->client();

        if ($client === null) {
            throw new RuntimeException('Cloudinary is not configured.');
        }

        $relativePath = $this->normalizeRelativePath($relativePath);

        try {
            $client->uploadApi()->upload($absolutePath, [
                'public_id' => $this->publicId($relativePath),
                'overwrite' => false,
                'resource_type' => 'image',
            ]);

            return 'uploaded';
        } catch (AlreadyExists) {
            return 'exists';
        }
    }

    private function client(): ?Cloudinary
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');
        if (is_string($cloudName) && trim($cloudName) !== ''
            && is_string($apiKey) && trim($apiKey) !== ''
            && is_string($apiSecret) && trim($apiSecret) !== '') {
            return $this->client = new Cloudinary([
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret,
                ],
            ]);
        }

        $url = config('services.cloudinary.url');
        if (is_string($url) && trim($url) !== '') {
            return $this->client = new Cloudinary($url);
        }

        return null;
    }

    private function publicId(string $relativePath): string
    {
        return (string) preg_replace('#\.[^./]+$#', '', $this->normalizeRelativePath($relativePath));
    }

    private function normalizeRelativePath(string $path): string
    {
        $path = str_replace('\\', '/', trim($path));
        $path = trim($path, '/');
        $segments = array_values(array_filter(explode('/', $path), static fn ($segment) => $segment !== ''));

        if ($segments === [] || in_array('.', $segments, true) || in_array('..', $segments, true)) {
            throw new RuntimeException('Invalid image path.');
        }

        return implode('/', $segments);
    }
}
