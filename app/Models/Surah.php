<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surah extends Model
{
    use HasFactory;

    protected $table = ' surahs';

    public function ayats():HasMany
    {
        return $this->hasMany(Ayat::class);
    }
}
