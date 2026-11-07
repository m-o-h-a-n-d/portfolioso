<?php

namespace App\Http\Requests\Storage;

use Illuminate\Foundation\Http\FormRequest;

class PresignedUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                    $disallowed = ['php', 'phtml', 'phar', 'exe', 'sh', 'bat', 'cmd', 'js', 'py', 'pl', 'cgi', 'env', 'htaccess'];
                    if (empty($ext) || in_array($ext, $disallowed, true)) {
                        $fail("The file extension [{$ext}] is not allowed for security reasons.");
                    }
                },
            ],
            'file_type' => 'required|string|max:100',
            'folder' => 'nullable|string|in:achievements,blogs,courses,lectures,researches,settings,users,general',
            'expires_in' => 'nullable|integer|min:60|max:7200', // Expiration in seconds (1 min to 2 hours)
        ];
    }

    public function messages(): array
    {
        return [
            'file_name.required' => 'The file name is required.',
            'file_type.required' => 'The file MIME type is required.',
            'folder.in' => 'Invalid destination folder specified.',
            'expires_in.min' => 'Expiration date cannot be less than 60 seconds.',
            'expires_in.max' => 'Expiration date cannot exceed 2 hours.',
        ];
    }
}

