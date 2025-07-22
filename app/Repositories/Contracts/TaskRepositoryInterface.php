<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;


interface TaskRepositoryInterface{
	public function getAllForUser(int $userID, array $filters =[]): LengthAwarePaginator;
	public function findById(int $id): ?Task;
	public function create(array $data): Task;
	public function update(int $id, array $data): Task;
	public function delete(int $id): bool;
	public function getByStatus(int $userId, string $status): Collection;
	public function getOverdueTasks(int $userId): Collection;
}