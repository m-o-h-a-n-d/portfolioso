<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'title' => 'required|string|min:3|max:255',
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp,svg,gif|max:2048',
            'department' => 'nullable|string|max:255',
            'university' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user()?->id),
            ],
            'contact_email' => [
                'required',
                'email',
                Rule::unique('users', 'contact_email')->ignore($this->user()?->id),
            ],
            'phone' => 'required|string|min:6|max:30',
            'office' => 'nullable|string|max:255',
            'office_hours' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'cv' => 'nullable|file|mimes:pdf|max:5120',
            'social_links' => 'required|array',
            'password' => 'required|string|min:8',
        ];

        if ($this->isMethod('PUT')) {
            $data['name'] = 'sometimes|string|min:3|max:255';
            $data['title'] = 'sometimes|string|min:3|max:255';
            $data['avatar'] = 'sometimes|image|mimes:jpg,jpeg,png,webp,svg,gif|max:2048';
            $data['avatar'] = 'sometimes|file|max:2048';
            $data['email'] = [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($this->user()?->id),
            ];
            $data['contact_email'] = [
                'sometimes',
                'email',
                Rule::unique('users', 'contact_email')->ignore($this->user()?->id),
            ];
            $data['phone'] = 'sometimes|string|min:6|max:30';
            $data['social_links'] = 'sometimes|array';
            $data['password'] = 'sometimes|string|min:8';
        }

        return $data;
    }
}
