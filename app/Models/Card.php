<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'holder_name',
        'card_number',
        'exp_date',
        'cvv',
        'type',
    ];
}
