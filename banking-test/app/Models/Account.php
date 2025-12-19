<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Get transactions where this account is the source.
     */
    public function sourceTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'source_account_id');
    }

    /**
     * Get transactions where this account is the target.
     */
    public function targetTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'target_account_id');
    }
}
