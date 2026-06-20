<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string|min:3',
            'cover' => 'required|image|max:2025',
            'category'=>'required|string|max:255',
            'date' => 'nullable|date',
        ];

        if ($this->isMethod('PUT')) {
            $data['title'] = 'sometimes|string|min:3|max:255';
            $data['excerpt'] = 'sometimes|string|max:500';
            $data['content'] = 'sometimes|string|min:3';
            $data['cover'] = 'sometimes|image|max:1025';
        }

        return $data;
    }
}
