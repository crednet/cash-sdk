<?php

namespace CredPal\CPCash\Models;

use Illuminate\Database\Eloquent\Model;

class CashWallet extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cpcash_wallets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'wallet_id'
    ];
}
