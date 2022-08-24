<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Battle extends Model
{
    use HasFactory;

    protected $fillable = [
        'attacker_id',
        'opponent_id',
        'attack',
        'is_original_attacker',
    ];

    protected $casts = [
        'is_original_attacker' => 'boolean',
    ];

    protected $attributes = [
        'is_original_attacker' => 0
    ];

    public function attacker(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function opponent(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
