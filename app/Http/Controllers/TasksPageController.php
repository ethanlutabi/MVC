<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\Tasks\TaskResource;
use Inertia\Inertia;

class TasksPageController extends Controller
{
    public function index()
    {
    $tasks = TaskResource::collection(
        Task::where('created_by', auth()->id())
            ->select('id', 'title', 'description', 'completed', 'created_at', 'updated_at')
            ->get()
    );
    
    return Inertia::render('Tasks/Index', [
        'tasks' => $tasks
    ]);
    }

    public function create()
    {

        return Inertia::render('Tasks/Create');
    }
    public function store(Request $request)
    {
    $data = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);
    
    // Add the current user's ID
    $data['created_by'] = auth()->id();
    
    Task::create($data);
    return redirect()->route('tasks.index')
                     ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => [
                'data' => $task
            ]
        ]);
    }

    // In TaskPageController
    public function update(Request $request, Task $task)
    {
    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',        // Added 'sometimes'
        'description' => 'sometimes|nullable|string',          // Added 'sometimes'
        'completed' => 'sometimes|boolean',                    // Added 'sometimes'
    ]);
    
    $task->update($validated);
    return redirect()->route('tasks.index')->with('success', 'Task updated successfully');  
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted.');
    }
}


