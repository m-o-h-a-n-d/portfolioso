<?php

namespace App\Http\Requests;

use App\Rules\FileOrPath;
use App\Rules\ImageOrPath;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResearchRequest extends FormRequest
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
            'year' => 'required|integer|min:1900|max:2100',
            'abstract' => 'required|string|min:3',
            'authors' => 'required|array',
            'authors.*' => 'string',
            'keywords' => 'required|array',
            'keywords.*' => 'string',
            'journal' => 'nullable|string|max:255',
            'conference' => 'nullable|string|max:255',
            'doi' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'pdf' => ['nullable', new FileOrPath(10240)],
            'cover' => ['required', new ImageOrPath(2048)],
        ];

        if ($this->isMethod('PUT')) {
            $data['title'] = 'sometimes|string|min:3|max:255';
            $data['year'] = 'sometimes|integer|min:1900|max:2100';
            $data['abstract'] = 'sometimes|string|min:3';
            $data['authors'] = 'sometimes|array';
            $data['pdf'] = ['sometimes', 'nullable', new FileOrPath(10240)];
            $data['cover'] = ['sometimes', new ImageOrPath(2048)];
        }

        return $data;
    }
}
