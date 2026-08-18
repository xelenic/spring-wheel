<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_items',
        'qty',
        'per_day',
        'remaining_qty',
    ];

    protected static function booted()
    {
        static::creating(function (GiftItems $gift) {
            // A freshly added gift starts fully stocked unless a remaining
            // qty was explicitly provided.
            if (is_null($gift->remaining_qty)) {
                $gift->remaining_qty = $gift->qty;
            }
        });
    }
}
