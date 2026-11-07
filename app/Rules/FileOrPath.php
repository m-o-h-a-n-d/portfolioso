<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class FileOrPath implements ValidationRule
{
    public function __construct(
        protected int $maxKilobytes = 10240,
        protected array $allowedExtensions = ['pdf', 'doc', 'docx']
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value instanceof UploadedFile) {
            if (! $value->isValid()) {
                $fail("The {$attribute} upload failed.");
                return;
            }

            if ($value->getSize() > ($this->maxKilobytes * 1024)) {
                $fail("The {$attribute} may not be greater than {$this->maxKilobytes} kilobytes.");
                return;
            }

            $ext = strtolower($value->getClientOriginalExtension());
            if (! in_array($ext, $this->allowedExtensions, true)) {
                $fail("The {$attribute} must be a file of type: " . implode(', ', $this->allowedExtensions) . ".");
                return;
            }
        } elseif (is_string($value)) {
            $trimmed = trim($value);
            if (empty($trimmed)) {
                $fail("The {$attribute} cannot be empty.");
                return;
            }

            $ext = strtolower(pathinfo(parse_url($trimmed, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
            if ($ext && ! in_array($ext, $this->allowedExtensions, true)) {
                $fail("The {$attribute} path must have a valid extension: " . implode(', ', $this->allowedExtensions) . ".");
            }
        } else {
            $fail("The {$attribute} must be a file or a valid storage path.");
        }
    }
}

