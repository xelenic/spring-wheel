<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GiftItems;
use Illuminate\Http\Request;

class SpinnerController extends Controller
{


    public function spinWheel($prizes = null)
    {
        $totalQuantity = array_sum($prizes);
        $randomNum = rand(0, $totalQuantity - 1);
        $cumulative = 0;
        foreach ($prizes as $prize => $quantity) {
            $cumulative += $quantity;
            if ($randomNum < $cumulative) {
                $prizes[$prize]--;
                return response()->json(['prize' => $prize]);
            }
        }
        return response()->json(['error' => 'No prizes available'], 404);
    }

    public function switchData($sting)
    {

        if($sting == 'Anchor Lunch Box')
        {
            return 1;
        }elseif ($sting == 'Anchor Container') {
            return 2;
        }elseif ($sting == 'Anchor Water Bottles') {
            return 3;
        }elseif ($sting  == 'Pens'){
            return  6;
        }
    }


    public function shuffle(Request $request)
    {
        $getGiftItems = GiftItems::where('qty','>',0)->get();

        $getGiftItems = GiftItems::where('qty','>',0)
            ->pluck('per_day','gift_items')->toArray();
        $days = $request->input('days', 1);
        $results = [];
        for ($day = 0; $day < $days; $day++) {
            $result = $this->spinWheel($getGiftItems);
            if ($result->getStatusCode() === 200) {
                $results[] = $result->getData()->prize;
            } else {
                break; // Stop if no prizes are left
            }
        }

        $details = $results[0];
        $detailsUpdate = self::switchData($details);

        $registerCustomer  = Customer::create([
            'name' => $request->email,
            'phone_number' => $request->code,
            'spined_gift_item' => $details
        ]);

        $rotation = 0;
        if($details == 'Anchor Container')
        {
            $rotation = 1871;
        }elseif($details == 'Anchor Water Bottles'){
            $rotation = 2000;
        }elseif ($details == 'Pens')
        {
            $rotation = 4000;
        }elseif ($details == 'Anchor Lunch Box'){
            $rotation = 5000;
        }

        $getDetails = GiftItems::where('gift_items',$details)->first();

        $getItems = GiftItems::where('gift_items', $details)->update([
           'remaining_qty' => $getDetails->remaining_qty + 1,
           'qty' => $getDetails->qty - 1
        ]);

        $outDetails = [
          'spin' => [
              'code' => 'ABCDEFGH',
              'email' => $registerCustomer->name,
              'prize' => $detailsUpdate,
              'retry_used' => false,
              'rotation' => $rotation,
              'try_again' => 1871,
              'winner' => true
          ],
          'used' => false,
          'itemName' => $details
        ];

        return response()->json($outDetails,200);
    }
}
