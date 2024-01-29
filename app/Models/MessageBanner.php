<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MessageBanner extends Model
{
    use HasFactory, SoftDeletes, HasPermissionsTrait, HasRoles;

    protected $guarded = ['id'];

    public function language():BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
