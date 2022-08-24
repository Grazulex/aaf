<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Theft extends Model
{
    use HasFactory;

    protected $fillable = [
        'sleeper_id',
        'theft_id',
    ];

    public function sleeper(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function theft(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
