<?php
// app/Http/Controllers/API/CategoryController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::where('user_id', auth()->id())
            ->withCount('tasks')
            ->when($request->has('with_tasks'), function ($query) {
                $query->with(['tasks' => function ($taskQuery) {
                    $taskQuery->latest()->take(5);
                }]);
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show(int $id): JsonResponse
    {
        $category = Category::where('user_id', auth()->id())
            ->withCount('tasks')
            ->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $category = Category::where('user_id', auth()->id())->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $category->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => new CategoryResource($category->fresh()),
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(int $id): JsonResponse
    {
        $category = Category::where('user_id', auth()->id())->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        // Check if category has tasks
        if ($category->tasks()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that contains tasks. Please move or delete tasks first.',
                'data' => [
                    'tasks_count' => $category->tasks()->count(),
                ]
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    /**
     * Get all tasks for a specific category.
     */
    public function tasks(Request $request, int $id): JsonResponse
    {
        $category = Category::where('user_id', auth()->id())->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $tasks = $category->tasks()
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('priority'), function ($query) use ($request) {
                $query->where('priority', $request->priority);
            })
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => TaskResource::collection($tasks),
            'category' => new CategoryResource($category),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
        ]);
    }

    /**
     * Get category statistics.
     */
    public function statistics(int $id): JsonResponse
    {
        $category = Category::where('user_id', auth()->id())->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $tasks = $category->tasks;
        
        $statistics = [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', 'completed')->count(),
            'pending_tasks' => $tasks->where('status', 'pending')->count(),
            'in_progress_tasks' => $tasks->where('status', 'in_progress')->count(),
            'overdue_tasks' => $tasks->filter(function ($task) {
                return $task->due_date && $task->due_date->isPast() && $task->status !== 'completed';
            })->count(),
            'completion_rate' => $tasks->count() > 0 
                ? round(($tasks->where('status', 'completed')->count() / $tasks->count()) * 100, 2) 
                : 0,
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
            'category' => new CategoryResource($category),
        ]);
    }
}

// ==========================================
// app/Http/Requests/StoreCategoryRequest.php
// ==========================================



// ============================================
// app/Http/Requests/UpdateCategoryRequest.php
// ============================================


// =======================================
// Update app/Http/Resources/CategoryResource.php
// =======================================



// =======================================
// Database Factory for Testing
// =======================================



// =======================================
// Feature Test for Category API
// =======================================

