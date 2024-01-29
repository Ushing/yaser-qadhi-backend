<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurahRecitation extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function surahReciteFiles():HasMany
    {
        return $this->hasMany(SurahReciteFile::class);
    }

    public function surah():BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }

}
