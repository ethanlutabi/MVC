<?php
// database/factories/CategoryFactory.php
namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8'];
        
        return [
            'name' => $this->faker->unique()->randomElement([
                'Work', 'Personal', 'Shopping', 'Health', 'Learning', 
                'Travel', 'Finance', 'Home', 'Projects', 'Ideas'
            ]),
            'description' => $this->faker->optional()->sentence(),
            'color' => $this->faker->randomElement($colors),
            'user_id' => User::factory(),
        ];
    }
}