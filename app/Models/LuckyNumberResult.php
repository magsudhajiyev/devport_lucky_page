<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LuckyNumberResult extends Model
{
    use HasFactory;

    protected $table = 'lucky_number_results';

    protected $fillable = ['user_id', 'lucky_number', 'winning_amount'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
