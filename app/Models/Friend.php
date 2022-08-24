<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'friend_id',
    ];

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
