<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'member_id',
        'healt',
        'armor',
        'potion',
        'step',
        'start_sleep',
        'is_dead'
    ];

    protected $casts = [
        'is_dead' => 'boolean',
        'start_sleep' => 'datetime'
    ];

    protected $attributes = [
        'healt' => 100000,
        'armor' => 0,
        'potion' => 3,
        'step' => 0,
        'is_dead' => 0
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function friends(): HasMany
    {
        return $this->hasMany(Friend::class);
    }

    public function battles(): HasMany
    {
        return $this->hasMany(Battle::class, 'attacker_id');
    }

    public function thefts(): HasMany
    {
        return $this->hasMany(Theft::class, 'theft_id');
    }
}
