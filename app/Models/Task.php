<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Add this import

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'completed',
        'created_by', // Add this to fillable
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    } // Remove the semicolon after the closing brace
}