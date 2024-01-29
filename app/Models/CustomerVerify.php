<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerVerify extends Model
{
    use HasFactory;

    protected $table = "customer_verifies";

    protected $fillable = [
        'customer_id',
        'token',
    ];


    public function customer():BelongsTo
    {
        return $this->belongsTo(CustomerDetail::class);
    }
}
