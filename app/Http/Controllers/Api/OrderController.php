<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orders()
    {
        $orders = Order::with('orderProducts')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return OrderResource::collection($orders);
    }

    public function orderDetail($orderId)
    {
        $order = Order::with('orderProducts')->where('user_id', Auth::user()->id)->where('invoice_id',$orderId)->get();
        return OrderResource::collection($order);
    }
}
