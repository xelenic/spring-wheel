<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GiftItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpinnerController extends Controller
{
    /**
     * Decide the outcome of a single spin and tell the frontend which
     * wheel segment to land on.
     *
     * The wheel's segments are built by GiftItems::wheelSegments() — one
     * "TRY AGAIN" slice ahead of every gift, in stable id order. This
     * endpoint draws a weighted random winner from the same gift pool
     * (weighted by each gift's `per_day` value, with an equal-sized share
     * of the total weight reserved for "try again" so the odds don't
     * collapse to "always win" just because every entry in the pool is a
     * real prize), then reports back the index of the segment that result
     * lives at so the client can spin the wheel to exactly that slice.
     */
    public function shuffle(Request $request): JsonResponse
    {
        $segments = GiftItems::wheelSegments();
        $availableGifts = GiftItems::where('qty', '>', 0)->get();

        $result = $this->drawWinner($availableGifts);

        if ($result['type'] === 'win') {
            /** @var GiftItems $gift */
            $gift = $result['gift'];
            $gift->decrement('qty');
            $gift->increment('remaining_qty');

            $label = $gift->gift_items;
            $prizeIndex = collect($segments)->search(
                fn ($segment) => $segment['type'] === 'win' && $segment['gift_id'] === $gift->id
            );
        } else {
            $label = 'TRY AGAIN';
            $tryAgainIndexes = collect($segments)
                ->filter(fn ($segment) => $segment['type'] === 'try_again')
                ->keys();
            $prizeIndex = $tryAgainIndexes->isNotEmpty() ? $tryAgainIndexes->random() : 0;
        }

        Customer::create([
            'name'             => $request->input('name', 'Guest'),
            'phone_number'     => $request->input('phone_number'),
            'spined_gift_item' => $label,
        ]);

        return response()->json([
            'winner'      => $result['type'] === 'win',
            'type'        => $result['type'],
            'label'       => $label,
            'prize_index' => (int) $prizeIndex,
            'segments'    => count($segments),
        ]);
    }

    /**
     * Weighted random draw over the available gifts plus a "try again"
     * option, using each gift's `per_day` value as its weight.
     *
     * @param  \Illuminate\Support\Collection<int, GiftItems>  $availableGifts
     * @return array{type: string, gift: GiftItems|null}
     */
    protected function drawWinner($availableGifts): array
    {
        $pool = [];

        foreach ($availableGifts as $gift) {
            $pool[] = ['type' => 'win', 'gift' => $gift, 'weight' => max((int) $gift->per_day, 1)];
        }

        // Give "try again" a weight equal to the combined gift weight, so
        // the overall win chance stays roughly 50/50 no matter how many
        // gifts are currently in the pool.
        $giftWeight = array_sum(array_column($pool, 'weight'));
        $pool[] = ['type' => 'try_again', 'gift' => null, 'weight' => max($giftWeight, 1)];

        $totalWeight = array_sum(array_column($pool, 'weight'));
        $roll = random_int(1, $totalWeight);

        $cumulative = 0;
        foreach ($pool as $entry) {
            $cumulative += $entry['weight'];
            if ($roll <= $cumulative) {
                return $entry;
            }
        }

        // Unreachable in practice, but keeps the return type honest.
        return ['type' => 'try_again', 'gift' => null];
    }
}
