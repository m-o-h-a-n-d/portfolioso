<?php

namespace App\Services;

use Aws\S3\PostObjectV4;
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
        $hasS3Config = ! empty(config('filesystems.disks.s3.key')) && ! empty(config('filesystems.disks.s3.bucket'));

        if ($diskName === 's3' || $hasS3Config) {
            /** @var \Illuminate\Filesystem\AwsS3V3Adapter $disk */
            $disk = Storage::disk('s3');
            $client = $disk->getClient();
            $bucket = config('filesystems.disks.s3.bucket');

            $formInputs = [
                'key' => $path,
                'Content-Type' => $fileType,
            ];

            $options = [
                ['key' => $path],
                ['Content-Type' => $fileType],
            ];

            $postObject = new PostObjectV4(
                $client,
                $bucket,
                $formInputs,
                $options,
                $expiration
            );

            $formAttributes = $postObject->getFormAttributes();
            $fields = $postObject->getFormInputs();

            // Also provide presigned PUT URL for non-browser CLI/API clients
            $putData = $disk->temporaryUploadUrl($path, $expiration, [
                'ContentType' => $fileType,
            ]);

            $uploadUrl = $formAttributes['action'];
            $method = 'POST';
            $headers = [];
            $putUrl = $putData['url'];
            $fileUrl = $disk->temporaryUrl($path, now()->addHours(24));
        } else {
            // Local fallback signed route for offline dev when S3 is not configured
            $uploadUrl = URL::temporarySignedRoute(
                'storage.local-upload',
                $expiration,
                ['path' => $path]
            );
            $method = 'PUT';
            $fields = null;
            $putUrl = $uploadUrl;
            $headers = [
                'Content-Type' => $fileType,
            ];
            $fileUrl = Storage::disk('public')->url($path);
        }

        return [
            'upload_url' => $uploadUrl,
            'put_url' => $putUrl,
            'method' => $method,
            'fields' => $fields,
            'file_path' => $path,
            'file_url' => $fileUrl,
            'headers' => $headers,
            'expires_at' => $expiration->toIso8601String(),
            'expires_in' => $expiresIn,
            'folder' => $folder,
        ];
    }
}
