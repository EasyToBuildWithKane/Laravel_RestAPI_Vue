<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'is_completed' => ['required', 'boolean'],
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'is_completed.required' => 'Trường trạng thái là bắt buộc.',
            'is_completed.boolean' => 'Trường trạng thái phải là hoàn thành hoặc không.',
        ];
    }
}
