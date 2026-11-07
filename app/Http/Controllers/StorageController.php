<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storage\PresignedUploadRequest;
use App\Services\StorageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function __construct(protected StorageUploadService $storageService) {}

    /**
     * Request permission to upload a file directly to storage via a presigned URL.
     *
     * @param  PresignedUploadRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestUploadUrl(PresignedUploadRequest $request)
    {
        $data = $this->storageService->generatePresignedUploadUrl($request->validated());

        return apiResponce(200, 'Upload permission granted with presigned URL', $data);
    }

    /**
     * Fallback endpoint for local development environments when S3 is not configured.
     * Accessible only via valid temporary signed URLs.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function localDirectUpload(Request $request)
    {
        if (! $request->hasValidSignature()) {
            return apiResponce(403, 'Invalid or expired upload signature');
        }

        $path = $request->query('path');
        if (! $path) {
            return apiResponce(400, 'Missing destination path');
        }

        $content = $request->getContent();
        Storage::disk('public')->put($path, $content);

        return apiResponce(200, 'File uploaded successfully', [
            'file_path' => $path,
            'file_url' => Storage::disk('public')->url($path),
        ]);
    }
}

