<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AchievementRequest extends FormRequest
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
            'description' => 'required|string|max:500',
            'full_description' => 'required|string|min:3',
            'cover' => 'required|image|max:3500',
            'date' => 'nullable|date',
            'category' => 'nullable|string|max:100',
            'live_link' => 'nullable|string|max:255',
            'gallery' => 'nullable|array',
            'order' => 'nullable|integer',
        ];

        if ($this->isMethod('PUT')) {
            $data['title'] = 'sometimes|string|min:3|max:255';
            $data['description'] = 'sometimes|string|max:500';
            $data['full_description'] = 'sometimes|string|min:3';
            $data['cover'] = 'sometimes|image|max:3500';
        }

        return $data;
    }
}
