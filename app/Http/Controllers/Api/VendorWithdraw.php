<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\WithdrawMethod;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorWithdraw extends Controller
{
    use \App\Traits\HttpResponses;

    public function totalBalance()
    {
        $vendorId = Auth::user()->vendor->id;

        $totalEarnings = OrderProduct::whereHas('order', function ($query) {
            $query->where('payment_status', 1)->where('order_status', 'delivered');
        })
            ->where('vendor_id', $vendorId)
            ->sum(DB::raw('unit_price * quantity'));

        $totalAmountWithdraw = WithdrawRequest::where('vendor_id', $vendorId)
            ->where('status', 'paid')
            ->sum('total_amount');

        return $totalEarnings - $totalAmountWithdraw;
    }

    public function method()
    {
        $methods = WithdrawMethod::all();
        return $this->success($methods);
    }


    public function index()
    {
        $vendorId = Auth::user()->vendor->id;

        $withdrawRequests = WithdrawRequest::where('vendor_id', $vendorId)->get();
        return $this->success(['total_amount' => $this->totalBalance(), 'withdraw_requests'=>$withdrawRequests]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'method' => 'required',
            'withdraw_amount' => 'required|numeric',
            'account_info' => 'required',
        ]);


        if ($validator->fails()) {
            return $this->error('', $validator->messages(), 422);
        }

        $method = WithdrawMethod::where('name', $request->method)->first();

        $vendorId = Auth::user()->vendor->id;



        if($request->withdraw_amount < $method->minimum_amount || $request->withdraw_amount > $method->maximum_amount){
            return $this->error('','Minimum amount is ' .format($method->minimum_amount). ' and less than '. format($method->maximum_amount), 200);
        }

        if($request->withdraw_amount > $this->totalBalance()){
            return $this->error('','You do not have sufficient balance.', 200);

        }

        if(WithdrawRequest::where(['vendor_id'=>$vendorId, 'status'=>'pending'])->exists()){
            return $this->error('','You have already submitted 1 request.', 200);
        }

        $withdraw = new WithdrawRequest();
        $withdraw->vendor_id = Auth::user()->vendor->id;
        $withdraw->method = $method->name;
        $withdraw->total_amount = $request->withdraw_amount;
        $withdraw->withdraw_amount = $request->withdraw_amount - ($request->withdraw_amount / 100)*$method->withdraw_charge;
        $withdraw->withdraw_charge = ($request->withdraw_amount / 100)*$method->withdraw_charge;
        $withdraw->account_info = $request->account_info;
        $withdraw->save();

        return $this->success('','Send request successfully');
    }
}
