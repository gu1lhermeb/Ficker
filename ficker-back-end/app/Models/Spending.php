<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spending extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'planned_spending',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
