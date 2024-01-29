<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class LectureSubCategory extends Model
{
    use HasFactory, SoftDeletes, HasPermissionsTrait, HasRoles;

    protected $guarded = [];

    public function lectureCategory(): BelongsTo
    {
        return $this->belongsTo(LectureCategory::class);
    }

    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class);
    }

    public function language():BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
