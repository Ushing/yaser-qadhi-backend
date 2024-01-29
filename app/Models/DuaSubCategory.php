<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class DuaSubCategory extends Model
{
    use HasFactory, SoftDeletes, HasPermissionsTrait, HasRoles;

    protected $guarded = [];


    public function duaCategory(): BelongsTo
    {
        return $this->belongsTo(DuaCategory::class);
    }

    public function duas(): HasMany
    {
        return $this->hasMany(Dua::class);
    }

    public function language():BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
