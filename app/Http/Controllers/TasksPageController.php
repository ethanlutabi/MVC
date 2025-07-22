<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Resources\Tasks\TaskResource;
use Inertia\Inertia;

class TasksPageController extends Controller
{
    public function index()
    {
       // Fetch all tasks (as resources)…
        $tasks = Task::select('id', 'title', 'description', 'is_completed', 'created_at', 'updated_at')
             ->get();

        // …and render the Inertia page, passing them as props
        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks
        ]);
    }
}
