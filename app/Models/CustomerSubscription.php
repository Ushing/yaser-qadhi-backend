<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class CustomerSubscription extends Model
{
    use HasFactory, SoftDeletes, HasPermissionsTrait, HasRoles;

    protected $guarded = ['id'];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function customerDetail(): BelongsTo
    {
        return $this->belongsTo(CustomerDetail::class);

    }


}
