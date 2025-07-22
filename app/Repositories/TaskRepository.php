<?php
// app/Repositories/TaskRepository.php
namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllForUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Task::with(['category'])->where('user_id', $userId);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function findById(int $id): ?Task
    {
        return Task::with(['category'])->find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task->fresh(['category']);
    }

    public function delete(int $id): bool
    {
        return Task::destroy($id);
    }

    public function getByStatus(int $userId, string $status): Collection
    {
        return Task::with(['category'])
            ->where('user_id', $userId)
            ->where('status', $status)
            ->get();
    }

    public function getOverdueTasks(int $userId): Collection
    {
        return Task::with(['category'])
            ->where('user_id', $userId)
            ->overdue()
            ->get();
    }
}