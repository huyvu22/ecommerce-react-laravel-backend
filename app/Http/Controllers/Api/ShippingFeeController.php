<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingRule;
use Illuminate\Http\Request;

class ShippingFeeController extends Controller
{
    use \App\Traits\HttpResponses;
    public function shippingFee(Request $request)
    {
        $request->validate([
            'sub_total' => 'required|integer',
        ]);
        $expressShip = ShippingRule::where(['status' => 1, 'name'=>'Express Ship'])->first();
        $freeShip = ShippingRule::where(['status' => 1, 'name'=>'Free Ship'])->first();
        if($request->sub_total >= $freeShip->min_cost){
            return $this->success($freeShip->cost, $freeShip->name);
        }else{
            return $this->success($expressShip->cost, $expressShip->name);
        }

    }
}
