<?php
// app/Services/TaskService.php
namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function getAllTasks(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->taskRepository->getAllForUser($userId, $filters);
    }

    public function getTaskById(int $id, int $userId): ?Task
    {
        $task = $this->taskRepository->findById($id);
        
        if (!$task || $task->user_id !== $userId) {
            return null;
        }

        return $task;
    }

    public function createTask(array $data, int $userId): Task
    {
        $data['user_id'] = $userId;
        return $this->taskRepository->create($data);
    }

    public function updateTask(int $id, array $data, int $userId): ?Task
    {
        $task = $this->getTaskById($id, $userId);
        
        if (!$task) {
            return null;
        }

        return $this->taskRepository->update($id, $data);
    }

    public function deleteTask(int $id, int $userId): bool
    {
        $task = $this->getTaskById($id, $userId);
        
        if (!$task) {
            return false;
        }

        return $this->taskRepository->delete($id);
    }

    public function getTasksByStatus(int $userId, string $status): Collection
    {
        return $this->taskRepository->getByStatus($userId, $status);
    }

    public function getOverdueTasks(int $userId): Collection
    {
        return $this->taskRepository->getOverdueTasks($userId);
    }

    public function getTaskStatistics(int $userId): array
    {
        $tasks = Task::where('user_id', $userId)->get();
        
        return [
            'total' => $tasks->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'overdue' => $tasks->filter(function ($task) {
                return $task->due_date && $task->due_date->isPast() && $task->status !== 'completed';
            })->count(),
        ];
    }
}