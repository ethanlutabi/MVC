<?php
// tests/Feature/CategoryApiTest.php
namespace Tests\Feature\Tasks;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_get_all_categories()
    {
        Category::factory()->count(3)->create(['user_id' => $this->user->id]);
        
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/categories');
            
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id', 'name', 'description', 'color', 
                        'tasks_count', 'created_at', 'updated_at'
                    ]
                ]
            ]);
    }

    public function test_user_can_create_category()
    {
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'color' => '#FF0000',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/categories', $categoryData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Category created successfully'
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_cannot_create_duplicate_category_name()
    {
        Category::factory()->create([
            'name' => 'Work',
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/categories', [
                'name' => 'Work',
                'color' => '#FF0000',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_cannot_delete_category_with_tasks()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'Cannot delete category that contains tasks. Please move or delete tasks first.'
            ]);
    }

    public function test_user_can_get_category_tasks()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        Task::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/categories/{$category->