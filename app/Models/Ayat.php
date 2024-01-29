<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ayat extends Model
{
    use HasFactory;

    protected $table = ' ayahs';


    public function surah():BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }
}
