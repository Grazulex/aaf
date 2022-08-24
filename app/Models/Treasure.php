<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treasure extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'value',
        'is_finded',
        'member_id'
    ];

    protected $casts = [
        'is_finded' => 'boolean',
    ];


    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
