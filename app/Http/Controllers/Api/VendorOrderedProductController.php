<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\OrderResource;
use App\Http\Resources\VendorOrderedProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;


class VendorOrderedProductController extends \App\Http\Controllers\Controller
{
    use \App\Traits\HttpResponses;
    public function index()
    {
       $orders = OrderProduct::where('vendor_id', Auth::user()->vendor->id)->get();
       return VendorOrderedProduct::collection($orders);
    }

    public function orderDetail($orderId)
    {
        $order = Order::where('invoice_id',$orderId)->get();
        if (!$order) {
            return $this->error('', 'Order not found', 404);
        }
        return OrderResource::collection($order);
    }

    public function orderShortBy($status)
    {
        if($status === 'all_orders'){
           return $this->index();
        }
        $order = OrderProduct::where('vendor_id', Auth::user()->vendor->id)
            ->whereHas('order', function ($query) use ($status) {
                $query->where('order_status', $status);
            })
            ->get();
        return VendorOrderedProduct::collection($order);
    }

    public function setOrderStatus($orderId,$status)
    {
        $order = Order::with('orderProducts')->where('invoice_id',$orderId)->first();
        if (!$order) {
            return $this->error('', 'Order not found', 404);
        }
        $order->order_status = $status;
        $order->save();
        return $this->success($order, 'Update Order Status Successfully');
    }

}
