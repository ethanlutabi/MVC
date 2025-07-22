<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;




class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'description',
        'user_id'];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany{
        return $this->hasMany(Task::class);
    }
}
