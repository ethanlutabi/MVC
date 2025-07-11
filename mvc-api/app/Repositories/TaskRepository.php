<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function getAll()
    {
        return Task::all();

    }
    public function findById($id)
    {
        return Task::find($id);

    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);
        return task;
    }
    
    public function delete(Task $task)
    {
        return $task->delete;
    }

}