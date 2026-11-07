<?php

if (! function_exists('apiResponce')) {
    function apiResponce($status, $message, $data = null)
    {
        $result = [
            'status' => $status,
            'message' => $message,
        ];

        if ($data !== null) {
            $result['data'] = $data;
        }

        return response()->json($result, $status);
    }
}

if (! function_exists('mediaUrl')) {
    /**
     * Resolve a stored media value to a usable URL.
     * Uses S3 Presigned GET URL with expiration when S3 is configured.
     */
    function mediaUrl($path)
    {
        if (! $path) {
            return null;
        }

        // If it's already an external URL (e.g. unsplash, youtube, etc.) and not a local/wasmer relative upload
        if (is_string($path) && str_starts_with($path, 'http')) {
            // If it contains '/uploads/', extract the relative path to sign it properly
            if (preg_match('#(uploads/[a-zA-Z0-9_\-\./]+)#', $path, $matches)) {
                $path = $matches[1];
            } else {
                return $path;
            }
        }

        $cleanPath = ltrim($path, './');
        $hasS3Config = ! empty(config('filesystems.disks.s3.key')) && ! empty(config('filesystems.disks.s3.bucket'));

        if ((config('filesystems.default') === 's3' || $hasS3Config) && str_starts_with($cleanPath, 'uploads/')) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('s3')->temporaryUrl(
                    $cleanPath,
                    now()->addHours(24)
                );
            } catch (\Throwable $e) {
                return \Illuminate\Support\Facades\Storage::disk('s3')->url($cleanPath);
            }
        }

        if (str_starts_with($cleanPath, 'uploads/')) {
            return secure_asset('storage/' . $cleanPath);
        }

        return secure_asset($cleanPath);
    }
}
