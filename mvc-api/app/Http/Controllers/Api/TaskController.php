<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Repositories\TaskRepository;


class TaskController extends Controller
{
    protected $taskRepo;
    
    public function __contrust(TaskRepository $taskRepo)
    {
        $this->taskRepo = $taskRepo;
    }

    public function index()
    {
        return reponse()->json($this->taskRepo->getAll(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,done',
        ]);

        $task = $this->taskRepo->create($data);
        return response()->json($task,201);
    }

    public function show($id)
    {
        $task = $this->taskRepo->findById($id);
        if(!$task){
            return response()->json(['message' => 'Task not found'], 400);  
        }
        return response()->json($task,200);
    }
    public function update(Request $request, $id)
    {
        $task = $this->taskRepo->findById($id);
        if(!$task){
            return response()->json(['message' => 'Task not found'], 404);
        }
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,done'
        ]);

        $updated = $this->taskRepo->update($task, $data);
        return response()->json($updated,200);
    }
    public function destroy($id)
    {
        $task = $this->taskRepo->findById($id);
        if(!$task){
            return response()->json(['message' => 'Task not found'], 400);

        }
        $this->taskRepo->delete($task);
        return response()->json(['message'=>'Task deleted'],204);
        
    }
}