<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReciteLanguage extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function surahRecitationFiles():HasMany
    {
        return $this->hasMany(SurahReciteFile::class);
    }

    public function ayatRecitationFiles():HasMany
    {
        return $this->hasMany(AyatReciteFile::class);
    }

}
