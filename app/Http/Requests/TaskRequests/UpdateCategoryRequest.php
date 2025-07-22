<?php
namespace App\Http\Requests\TaskRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($this->route('category')),
            ],
            'description' => 'sometimes|nullable|string|max:500',
            'color' => 'sometimes|nullable|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique' => 'You already have a category with this name.',
            'color.regex' => 'Color must be a valid hex color code (e.g., #FF0000).',
        ];
    }
}
