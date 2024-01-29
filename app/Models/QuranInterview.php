<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuranInterview extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function quranInterviewFiles():HasMany
    {
        return $this->hasMany(QuranInterviewFile::class);
    }
}
