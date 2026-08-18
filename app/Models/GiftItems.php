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

    /**
     * Build the wheel's segment order: one "TRY AGAIN" slice ahead of every
     * gift, in stable id order. Both the spin page (rendering the wheel)
     * and SpinnerController (deciding + landing on a winner) call this same
     * method so the visual wheel and the server's chosen index always agree
     * on what segment N means.
     *
     * @return array<int, array{label: string, type: string, gift_id: int|null}>
     */
    public static function wheelSegments(): array
    {
        $gifts = static::orderBy('id')->get();

        if ($gifts->isEmpty()) {
            // Nothing configured yet — fall back to a plain try-again wheel
            // instead of rendering an empty one.
            return array_fill(0, 6, ['label' => 'TRY AGAIN', 'type' => 'try_again', 'gift_id' => null]);
        }

        $segments = [];

        foreach ($gifts as $gift) {
            $segments[] = ['label' => 'TRY AGAIN', 'type' => 'try_again', 'gift_id' => null];
            $segments[] = ['label' => $gift->gift_items, 'type' => 'win', 'gift_id' => $gift->id];
        }

        return $segments;
    }
}
