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
     * Absolute URLs are returned untouched; relative paths are resolved via default storage disk or asset URL.
     */
    function mediaUrl($path)
    {
        if (! $path) {
            return null;
        }

        if (is_string($path) && str_starts_with($path, 'http')) {
            return $path;
        }

        $disk = config('filesystems.default', 'public');
        if ($disk === 's3') {
            return \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
        }

        return secure_asset($path);
    }
}
