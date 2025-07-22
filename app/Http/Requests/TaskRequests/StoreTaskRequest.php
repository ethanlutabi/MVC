<?php

namespace app\Http\Requests\TaskRequests;

use Illuminate\Foundation\Http\FormRequest;


class StoreTaskRequest extends FormRequest
{
	public function authorize(): bool {
		return true;
	}

	public function rules(): array
	{
		return [
			'title'=>'required|string|max:255',
			'description'=>'nullable|string',
			'status'=>'nullable|in:pending,in_progress,completed,cancelled',
			'priority'=> 'nullable|in:low,medium,high,urgent',
			'due_date'=>'nullable|date|after:now'm
			'category_id'=>'nullable|exists:categories,id'];
	}

	public function messageS():array
	{
		return [
			'title.required'=>'Task title is required.',
			'due_date.after'=> 'Due date must be in the future.',
			'category_id.exists'=>'Selected category does not exist.',
		];
	}
}