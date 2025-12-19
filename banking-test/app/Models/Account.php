<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'customer_id',
        'account_type',
        'currency',
        'balance',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'status' => 'string',
        'account_type' => 'string',
    ];

    /**
     * Get the customer that owns the account.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
