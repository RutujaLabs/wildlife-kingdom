<?php

namespace App\Console\Commands;

use App\Services\CloudinaryImageService;
use Illuminate\Console\Command;
use Throwable;

class CheckCloudinaryAuthentication extends Command
{
    protected $signature = 'cloudinary:check-auth';

    protected $description = 'Check Cloudinary credentials with the Admin API ping endpoint without uploading files';

    public function handle(CloudinaryImageService $cloudinary): int
    {
        $diagnostics = $cloudinary->credentialDiagnostics();
        $this->line('Cloud name: '.$diagnostics['cloud_name']);
        $this->line('Cloud name present: '.($diagnostics['cloud_name_present'] ? 'yes' : 'no'));
        $this->line('API Key present: '.($diagnostics['api_key_present'] ? 'yes' : 'no'));
        $this->line('API Key character count: '.$diagnostics['api_key_length']);
        $this->line('API Key contains whitespace: '.($diagnostics['api_key_unexpected_whitespace'] ? 'yes' : 'no'));
        $this->line('API Secret present: '.($diagnostics['api_secret_present'] ? 'yes' : 'no'));
        $this->line('CLOUDINARY_URL configured: '.($diagnostics['cloudinary_url_present'] ? 'yes' : 'no'));
        $this->line('Laravel config cached: '.(app()->configurationIsCached() ? 'yes' : 'no'));

        if (! $cloudinary->isConfigured()) {
            $this->error('Cloudinary credentials are not configured; no request was made.');

            return self::FAILURE;
        }

        try {
            $cloudinary->ping();
            $this->info('Cloudinary Admin API authentication succeeded.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Cloudinary Admin API authentication failed: '.$this->safeDiagnostic($exception));

            return self::FAILURE;
        }
    }

    private function safeDiagnostic(Throwable $exception): string
    {
        $status = null;
        if (method_exists($exception, 'getResponse')) {
            try {
                $response = $exception->getResponse();
                if (is_object($response) && method_exists($response, 'getStatusCode')) {
                    $candidate = $response->getStatusCode();
                    if (is_int($candidate) || (is_string($candidate) && ctype_digit($candidate))) {
                        $status = (string) $candidate;
                    }
                }
            } catch (Throwable) {
                // Response metadata is optional across SDK exception types.
            }
        }

        $code = $exception->getCode();
        $safeCode = (is_int($code) || (is_string($code) && ctype_digit($code))) && (string) $code !== '0'
            ? (string) $code
            : null;
        $details = $status !== null ? ' HTTP '.$status : ($safeCode !== null ? ' code '.$safeCode : '');

        // Do not expose SDK/server exception text; it may contain credential fragments.
        return get_class($exception).$details.' (error text suppressed).';
    }
}
