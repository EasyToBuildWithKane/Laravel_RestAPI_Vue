<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $taskId = $this->route('task')->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tasks', 'name')->ignore($taskId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhiệm vụ.',
            'name.string' => 'Tên nhiệm vụ phải là một chuỗi ký tự.',
            'name.max' => 'Tên nhiệm vụ không được vượt quá :max ký tự.',
            'name.unique' => 'Tên nhiệm vụ đã tồn tại.',
        ];
    }
}
