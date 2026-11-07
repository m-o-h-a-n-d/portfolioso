<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageManager
{
    /**
     * Create a new class instance.
     */
    public function uploadSingleImage($file, $path, $disk = null, $oldPath = null)
    {
        $disk = $disk ?? config('filesystems.default', 'public');
        if ($file) {
            if ($oldPath) {
                $this->deleteImageFromLocal($oldPath, $disk);
            }
            $newImageName = $this->generateName($file, $path, $disk);

            return $newImageName;
        }

        return false;
    }

    private function generateName($image, $path, $disk)
    {
        $file = Str::uuid().time().'.'.$image->getClientOriginalExtension();

        return $image->storeAs("uploads/$path", $file, ['disk' => $disk]);
    }

    public function deleteImageFromLocal($imagePath, $disk = null)
    {
        $disk = $disk ?? config('filesystems.default', 'public');

        if (is_array($imagePath)) {
            foreach ($imagePath as $path) {
                if ($path) {
                    $clean = $this->cleanPath($path);
                    Storage::disk($disk)->delete($clean);
                }
            }
            return;
        }

        if ($imagePath) {
            $clean = $this->cleanPath($imagePath);
            Storage::disk($disk)->delete($clean);
        }
    }

    private function cleanPath($path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parsed = parse_url($path, PHP_URL_PATH);
            // remove leading slash and bucket name if in path
            $bucket = config('filesystems.disks.s3.bucket');
            $clean = ltrim($parsed, '/');
            if ($bucket && str_starts_with($clean, $bucket . '/')) {
                $clean = substr($clean, strlen($bucket) + 1);
            }
            if (str_starts_with($clean, 'storage/')) {
                $clean = substr($clean, 8);
            }
            return $clean;
        }

        return ltrim($path, '/');
    }

    public function uploadCv($file, $path, $disk = 'public', $oldPath = null)
    {
        if ($file) {

            if ($oldPath) {
                $this->deleteImageFromLocal($oldPath, $disk);
            }

            $fileName = 'cv.'.$file->getClientOriginalExtension();

            return $file->storeAs(
                "uploads/$path",
                $fileName,
                ['disk' => $disk]
            );
        }

        return false;
    }
}
