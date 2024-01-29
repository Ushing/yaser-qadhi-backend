<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class CustomerDetail extends Model
{
    use HasFactory, SoftDeletes, HasApiTokens, HasPermissionsTrait, HasRoles;

    protected $guarded = ['id'];

    public function customerSubscriptions():HasMany
    {
        return $this->hasMany(CustomerSubscription::class);
    }
}
