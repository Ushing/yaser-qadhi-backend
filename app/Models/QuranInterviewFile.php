<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuranInterviewFile extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function quranInterview():BelongsTo
    {
        return $this->belongsTo(QuranInterview::class);
    }
}
