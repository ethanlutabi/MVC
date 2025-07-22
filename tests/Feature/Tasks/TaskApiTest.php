<?php
// tests/Feature/TaskApiTest.php
namespace Tests\Feature\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_get_all_tasks()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);
        
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/tasks');
            
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'priority',
                        'due_date',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'meta'
            ]);
    }

    public function test_user_can_create_task()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'category_id' => $category->id,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Task created successfully'
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_update_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        
        $updateData = [
            'title' => 'Updated Task',
            'status' => 'completed',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Task updated successfully'
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'status' => 'completed',
        ]);
    }

    public function test_user_can_delete_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_access_other_users_tasks()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(404);
    }

    public function test_task_validation_works()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/tasks', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }
}