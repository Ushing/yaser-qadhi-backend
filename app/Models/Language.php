<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Language extends Model
{
    use HasFactory, SoftDeletes, HasPermissionsTrait, HasRoles;
    protected $guarded = [];

    public function duaCategories():HasMany
    {
        return $this->hasMany(DuaCategory::class);
    }

    public function duaSubCategories():HasMany
    {
        return $this->hasMany(DuaSubCategory::class);
    }

    public function duas(): HasMany
    {
        return $this->hasMany(Dua::class);
    }

    public function lectureCategories():HasMany
    {
        return $this->hasMany(LectureCategory::class);
    }

    public function lectureSubCategories():HasMany
    {
        return $this->hasMany(LectureSubCategory::class);
    }

    public function lectures():HasMany
    {
        return $this->hasMany(Lecture::class);
    }

    public function events():HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function messageBanners():HasMany
    {
        return $this->hasMany(MessageBanner::class);
    }
}
