<?php

namespace App\Console\Commands;

use App\Services\CloudinaryImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;
use Throwable;

class TransferUploadsToCloudinary extends Command
{
    protected $signature = 'cloudinary:transfer-uploads {--dry-run : Count and list local images without uploading them} {--single-test= : Upload exactly one image at this relative path for connectivity diagnostics}';

    protected $description = 'Transfer existing local uploads to Cloudinary without changing local files or database records';

    public function handle(CloudinaryImageService $cloudinary): int
    {
        $uploadsRoot = realpath(base_path('../uploads'));
        if ($uploadsRoot === false || ! is_dir($uploadsRoot)) {
            $this->error('The local uploads directory was not found.');

            return self::FAILURE;
        }

        $files = $this->imageFiles($uploadsRoot);
        $counts = ['animals' => 0, 'animals/gallery' => 0, 'habitats' => 0, 'events' => 0, 'gallery' => 0];

        foreach ($files as $file) {
            $relative = $this->relativePath($uploadsRoot, $file);
            $folder = str_contains($relative, '/') ? dirname($relative) : $relative;
            if (array_key_exists($folder, $counts)) {
                $counts[$folder]++;
            }
        }

        $dryRun = (bool) $this->option('dry-run');
        $singleTest = $this->option('single-test');
        $this->info(($dryRun ? 'Dry run: found ' : 'Found ').count($files).' local image(s).');
        foreach ($counts as $category => $count) {
            $this->line($category.': '.$count);
        }

        if ($dryRun) {
            foreach ($files as $file) {
                $this->line('Would upload: '.$this->relativePath($uploadsRoot, $file));
            }

            return self::SUCCESS;
        }

        if (is_string($singleTest) && trim($singleTest) !== '') {
            $singleTest = str_replace('\\', '/', trim($singleTest));
            $file = null;
            foreach ($files as $candidate) {
                if (hash_equals($this->relativePath($uploadsRoot, $candidate), $singleTest)) {
                    $file = $candidate;
                    break;
                }
            }
            if ($file === null) {
                $this->error('Single-file test path was not found among supported images. No upload was attempted.');

                return self::FAILURE;
            }

            $environment = $cloudinary->environmentStatus();
            $this->line('Laravel CLOUDINARY_URL config: '.($environment['laravel_configured'] ? 'present' : 'missing'));
            $this->line('Process CLOUDINARY_URL: '.($environment['process_environment_configured'] ? 'present' : 'missing'));
            if (! $environment['laravel_configured']) {
                $this->error('Cloudinary credentials are not configured; no file was uploaded.');

                return self::FAILURE;
            }

            try {
                $result = $cloudinary->transferExisting($file->getPathname(), $singleTest);
                $this->info($result === 'exists' ? 'Single-file test: already present (overwrite=false).' : 'Single-file test: upload succeeded (overwrite=false).');

                return self::SUCCESS;
            } catch (Throwable $exception) {
                $this->error('Single-file test failed: '.$this->diagnostic($exception, (string) config('services.cloudinary.url')));

                return self::FAILURE;
            }
        }

        if (! $cloudinary->isConfigured()) {
            $this->error('Cloudinary is not configured; no files were uploaded.');

            return self::FAILURE;
        }

        $uploaded = 0;
        $existing = 0;
        $failed = 0;

        foreach ($files as $file) {
            $relative = $this->relativePath($uploadsRoot, $file);
            try {
                $result = $cloudinary->transferExisting($file->getPathname(), $relative);
                $result === 'exists' ? $existing++ : $uploaded++;
            } catch (Throwable $exception) {
                $failed++;
                $this->error('Upload failed: '.$relative.' — '.$this->diagnostic($exception, (string) config('services.cloudinary.url')));
            }
        }

        $this->info('Uploaded: '.$uploaded.'; already present: '.$existing.'; failed: '.$failed.'.');

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function diagnostic(Throwable $exception, string $cloudinaryUrl): string
    {
        $class = get_class($exception);
        $code = $exception->getCode();
        $status = null;
        foreach (['getStatusCode', 'getHttpStatusCode'] as $method) {
            if (method_exists($exception, $method)) {
                try {
                    $candidate = $exception->{$method}();
                    if (is_int($candidate) || (is_string($candidate) && ctype_digit($candidate))) {
                        $status = (string) $candidate;
                        break;
                    }
                } catch (Throwable) {
                    // A status accessor is optional across SDK exception types.
                }
            }
        }
        if ($status === null && method_exists($exception, 'getResponse')) {
            try {
                $response = $exception->getResponse();
                if (is_object($response) && method_exists($response, 'getStatusCode')) {
                    $candidate = $response->getStatusCode();
                    if (is_int($candidate) || (is_string($candidate) && ctype_digit($candidate))) {
                        $status = (string) $candidate;
                    }
                }
            } catch (Throwable) {
                // HTTP response metadata is optional on SDK exceptions.
            }
        }

        $safeCode = (is_int($code) || (is_string($code) && ctype_digit($code))) && (string) $code !== '0' ? (string) $code : null;
        $detail = $status !== null ? 'HTTP '.$status : ($safeCode !== null ? 'code '.$safeCode : 'no HTTP status/code');
        $message = $exception->getMessage();
        if ($cloudinaryUrl !== '') {
            $message = str_replace($cloudinaryUrl, '[redacted credentials]', $message);
            $parts = parse_url($cloudinaryUrl);
            foreach ([$parts['user'] ?? null, $parts['pass'] ?? null] as $credential) {
                if (is_string($credential) && $credential !== '') {
                    $message = str_replace($credential, '[redacted credential]', $message);
                }
            }
        }
        $message = preg_replace('#cloudinary://[^\s"\']+#i', '[redacted credentials]', $message) ?? $message;
        $message = preg_replace('#https?://[^\s"\']+#i', '[redacted URL]', $message) ?? $message;
        $message = preg_replace('/(?:api[_ -]?key|api[_ -]?secret|authorization|bearer)\s*[:=]?\s*[^\s,;]+/i', '[redacted credential]', $message) ?? $message;
        $message = trim(preg_replace('/[\r\n\t]+/', ' ', $message) ?? $message);
        if ($message === '') {
            $message = 'No safe message available.';
        }

        return $class.' ('.$detail.'): '.$message;
    }

    /** @return list<SplFileInfo> */
    private function imageFiles(string $uploadsRoot): array
    {
        $files = [];
        foreach (['animals', 'habitats', 'events', 'gallery'] as $directory) {
            $path = $uploadsRoot.DIRECTORY_SEPARATOR.$directory;
            if (! is_dir($path)) {
                continue;
            }

            foreach (File::allFiles($path) as $file) {
                if (in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                    $files[] = $file;
                }
            }
        }

        usort($files, fn (SplFileInfo $a, SplFileInfo $b) => strcmp($a->getPathname(), $b->getPathname()));

        return $files;
    }

    private function relativePath(string $uploadsRoot, SplFileInfo $file): string
    {
        return str_replace('\\', '/', substr($file->getPathname(), strlen($uploadsRoot) + 1));
    }
}
