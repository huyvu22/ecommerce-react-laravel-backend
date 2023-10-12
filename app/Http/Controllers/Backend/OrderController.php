<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CanceledOrderDataTable;
use App\DataTables\DeliveredOrderDataTable;
use App\DataTables\DropOffOrderDataTable;
use App\DataTables\OrderDataTable;
use App\DataTables\OutOfDeliveryOrderDataTable;
use App\DataTables\PendingOrderDataTable;
use App\DataTables\ProcessedOrderDataTable;
use App\DataTables\ShippedOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.index');
    }

    public function show(Order $order)
    {
        if($order->coupon){
            $coupon = Coupon::where('code', $order->coupon)->first();
            $discountValue = $coupon->discount_value;
            return view('admin.order.show', compact('order', 'discountValue'));
        }

        return view('admin.order.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        //delete order product
        $order->orderProducts()->delete();

        //delete order transaction
        $order->transaction()->delete();

        $order->delete();
        toastr('Deleted Successfully');
        return redirect()->back();
    }

    public function pendingOrders(PendingOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.pending-order');
    }

    public function processedOrders(ProcessedOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.processed-order');
    }

    public function dropOffOrders(DropOffOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.drop-off-order');
    }

    public function shippedOrders(ShippedOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.shipped-order');
    }

    public function outOfDeliveryOrders(OutOfDeliveryOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.out-of-delivery-order');
    }

    public function deliveredOrders(DeliveredOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.delivered-order');
    }

    public function canceledOrders(CanceledOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.canceled-order');
    }


    public function getOrderStatus( $id, $status)
    {
        $order = Order::find($id);
        $order->order_status = $status;
        $order->save();
        return response([
            'status' => $status,
            'message' => 'Updated Order status successfully'
        ]);
    }

    public function changePaymentStatus( $id, $status)
    {
        $order = Order::find($id);
        $order->payment_status = $status;
        $order->save();
        return response([
            'status' => $status,
            'message' => 'Updated Payment status successfully'
        ]);
    }
}
