<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'product_id', 'quantity_purchased', 'owner', 'card_number', 'date_expiration', 'flag', 'cvv',
    ];
}
