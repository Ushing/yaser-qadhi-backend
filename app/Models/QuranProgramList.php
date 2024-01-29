<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuranProgramList extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function quranProgramCategory():BelongsTo
    {
        return $this->belongsTo(QuranProgramCategory::class);
    }

    public function quranProgramFiles():HasMany
    {
        return $this->hasMany(QuranProgramFiles::class);
    }
}
