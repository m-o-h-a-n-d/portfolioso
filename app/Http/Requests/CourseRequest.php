<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $data = [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3',
            'objectives' => 'nullable|array',
            'objectives.*' => 'string',
            'cover' => 'required|image|max:2025',
        ];

        if ($this->isMethod('PUT')) {
            $data['title'] = 'sometimes|string|min:3|max:255';
            $data['description'] = 'sometimes|string|min:3';
            $data['cover'] = 'sometimes|image|max:2025';

        }

        return $data;
    }
}
