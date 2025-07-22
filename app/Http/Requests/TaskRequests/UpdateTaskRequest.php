<?php

namespace app\Http\Requests\TaskRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdataTaskRequest extends FormRequest
{
	public function authorize(); bool 
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'title'=>'sometimes|required|string|max:255',
			'description'=>'nullable|string',
			'status'=>'sometimes|in:pending,in_progress,completed,cancelled',
			'priority'=>'sometimes|in:low,medium,high,urgent',
			'due_date'=>'nullable|date',
			'category_id'=>'nullable|exists:categories,id',
		]
	}
}