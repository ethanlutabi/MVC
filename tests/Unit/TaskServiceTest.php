<?php
// tests/Unit/TaskServiceTest.php
namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Tests\TestCase;
use Mockery;

class TaskServiceTest extends TestCase
{
    public function test_get_task_statistics_returns_correct_data()
    {
        $user = User::factory()->create();
        $mockRepo = Mockery::mock(TaskRepositoryInterface::class);
        $service = new TaskService($mockRepo);

        // Mock Task model static methods
        Task::shouldReceive('where')
            ->with('user_id', $user->id)
            ->andReturnSelf()
            ->once();
            
        Task::shouldReceive('get')
            ->andReturn(collect([
                (object)['status' => 'completed'],
                (object)['status' => 'pending'],
                (object)['status' => 'in_progress'],
            ]))
            ->once();

        $stats = $service->getTaskStatistics($user->id);

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('completed', $stats);
        $this->assertArrayHasKey('pending', $stats);
        $this->assertArrayHasKey('in_progress', $stats);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}