<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:tasks,name'
            ],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhiệm vụ.',
            'name.string' => 'Tên nhiệm vụ phải là một chuỗi ký tự.',
            'name.max' => 'Tên nhiệm vụ không được vượt quá :max ký tự.',
            'name.unique' => 'Nhiệm vụ đã trùng. Vui lòng nhập khác.'
        ];

    }
}