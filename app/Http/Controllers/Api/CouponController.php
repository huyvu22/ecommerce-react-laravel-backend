<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use \App\Traits\HttpResponses;
    public function applyCoupon($code)
    {
        $coupon = Coupon::where(['status'=>1, 'code'=>$code])->first();
        if($coupon == null){
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon not exist',
            ], 404);
        }
        if(Carbon::now() < $coupon->start_date || Carbon::now() > $coupon->end_date ){
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon expired',
            ], 400);
        }

        if($coupon->quantity <= $coupon->total_used) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon has been used up',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'coupon' => new CouponResource($coupon),
        ]);
    }
}
