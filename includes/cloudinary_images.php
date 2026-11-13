<?php

if (! function_exists('wk_cloudinary_image_url')) {
    /** Resolve an existing relative upload reference to local storage or Cloudinary. */
    function wk_cloudinary_image_url($reference, string $folder = 'animals'): string
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

        $folder = trim(str_replace('\\', '/', $folder), '/');
        if (! str_contains($path, '/') && $folder !== '') {
            $path = $folder.'/'.$path;
        }

        $segments = array_values(array_filter(explode('/', $path), static fn ($segment) => $segment !== ''));
        if ($segments === [] || in_array('.', $segments, true) || in_array('..', $segments, true)) {
            return '';
        }

        $relativePath = implode('/', $segments);
        $localPath = dirname(__DIR__).'/uploads/'.str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $encodedPath = implode('/', array_map('rawurlencode', $segments));

        if (is_file($localPath)) {
            return '../uploads/'.$encodedPath;
        }

        $rootFile = dirname(__DIR__).'/uploads/'.basename($relativePath);
        if (is_file($rootFile)) {
            return '../uploads/'.rawurlencode(basename($relativePath));
        }

        $publicId = preg_replace('#\.[^./]+$#', '', $relativePath) ?? $relativePath;
        $encodedPublicId = implode('/', array_map('rawurlencode', explode('/', $publicId)));

        return 'https://res.cloudinary.com/pg6m0frc/image/upload/f_auto,q_auto/'.$encodedPublicId;
    }
}
