<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'from_id',
        'to_id',
        'message'
    ];


    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function from(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
