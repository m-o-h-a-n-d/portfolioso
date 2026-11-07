<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class StorageUploadService
{
    /**
     * Generate a presigned direct upload URL with an expiration date.
     *
     * @param  array  $data
     * @return array
     */
    public function generatePresignedUploadUrl(array $data): array
    {
        $fileName = $data['file_name'];
        $fileType = $data['file_type'] ?? 'application/octet-stream';
        $folder = $data['folder'] ?? 'general';
        $expiresIn = (int) ($data['expires_in'] ?? 900); // 15 minutes default

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $uniqueName = Str::uuid()->toString() . '-' . time() . ($extension ? ".{$extension}" : '');
        $path = "uploads/{$folder}/{$uniqueName}";

        $expiration = now()->addSeconds($expiresIn);
        $diskName = config('filesystems.default', 's3');

        // Check if S3 disk is active or configured with credentials
        $hasS3Config = !empty(config('filesystems.disks.s3.key')) && !empty(config('filesystems.disks.s3.bucket'));

        if ($diskName === 's3' || $hasS3Config) {
            $disk = Storage::disk('s3');
            
            $options = [];
            if (!empty($fileType)) {
                $options['ContentType'] = $fileType;
            }

            $uploadData = $disk->temporaryUploadUrl($path, $expiration, $options);

            $uploadUrl = $uploadData['url'];
            $headers = $uploadData['headers'] ?? [];
            if (!empty($fileType)) {
                $headers['Content-Type'] = $fileType;
            }

            $fileUrl = $disk->url($path);
        } else {
            // Local fallback signed route for offline dev when S3 is not configured
            $uploadUrl = URL::temporarySignedRoute(
                'storage.local-upload',
                $expiration,
                ['path' => $path]
            );
            $headers = [
                'Content-Type' => $fileType,
            ];
            $fileUrl = Storage::disk('public')->url($path);
        }

        return [
            'upload_url' => $uploadUrl,
            'method' => 'PUT',
            'file_path' => $path,
            'file_url' => $fileUrl,
            'headers' => $headers,
            'expires_at' => $expiration->toIso8601String(),
            'expires_in' => $expiresIn,
            'folder' => $folder,
        ];
    }
}

