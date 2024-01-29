<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Lecture extends Model
{
    use HasFactory, SoftDeletes, HasPermissionsTrait, HasRoles;

    protected $guarded = [];

    public function lectureSubCategory(): BelongsTo
    {
        return $this->belongsTo(LectureSubCategory::class);
    }

    public function language():BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
