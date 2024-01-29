<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HajjSublist extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hajjCheckList(): BelongsTo
    {
        return $this->belongsTo(HajjChecklist::class);
    }

}
