<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the accounts for the customer.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(\App\Models\Account::class);
    }
}
