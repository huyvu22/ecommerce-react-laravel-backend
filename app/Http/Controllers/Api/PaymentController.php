<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaypalSetting;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    use \App\Traits\HttpResponses;

    public function storeOrder($data,$paymentMethod, $paymentStatus,$payAmount)
    {
        $shoppingCart = json_decode($data['my_cart'], true);
        $subTotal = json_decode($data['sub-total'], true);
        $amount = json_decode($data['amount'], true);
        $orderAddress = $data['order_address'];
        $shippingMethod = $data['shipping_method'];
        $coupon = $data['coupon'];

        //Store order
        $order = new Order();
        $order->invoice_id = rand(1,10000);
        $order->user_id = Auth::user()->id;
        $order->sub_total = $subTotal;
        $order->amount = $amount;
        $order->currency_name = 'USD';
        $order->currency_icon = '$';
        $order->product_quantity = count($shoppingCart);
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentStatus;
        $order->order_address = $orderAddress;
        $order->shipping_method = $shippingMethod;
        $order->coupon = $coupon;
        $order->order_status = 'pending';
        $order->save();

        //Store order product
        foreach ($shoppingCart as $item) {
            $product = Product::find($item['id']);

            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->vendor_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->variants = null;
            $orderProduct->variant_total = null;
            $orderProduct->unit_price = $item['price'];
            $orderProduct->quantity = $item['quantity'];
            $orderProduct->save();

            //Update product quantity in database
            $productCurrentQuantity = $product->quantity;
            $productUpdateQuantity = $productCurrentQuantity - $item['quantity'];
            $product->quantity = $productUpdateQuantity;
            $product->save();
        }

        //Store transaction detail
        $transaction  = new Transaction();
        $transaction->order_id = $order->id;
        $transaction->transaction_id = $data['responseId'];
        $transaction->payment_method = $paymentMethod;
        $transaction->amount = $payAmount;
        $transaction->save();

        //Update Coupon quantity
//        if($coupon !== null){
            $coupon = Coupon::first();
            $coupon->total_used = (int)$coupon->total_used + 1;
            $coupon->save();
//        }
    }

    /* Pay with PayPal*/
    public function paypalConfig()
    {
        $paypalSetting = PaypalSetting::first();
        $config = [
            'mode'    => $paypalSetting->mode == 1 ? 'live' : 'sandbox',
            'sandbox' => [
                'client_id'         => $paypalSetting->client_id ,
                'client_secret'     => $paypalSetting->secret_key,
                'app_id'            => '',
            ],
            'live' => [
                'client_id'         => $paypalSetting->client_id ,
                'client_secret'     => $paypalSetting->secret_key,
                'app_id'            => '',
            ],

            'payment_action' =>  'Sale',
            'currency'       => $paypalSetting->currency_name,
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   =>  true,
        ];
        return $config;
    }

    public function connectPaypalPayment(Request $request)
    {
        $request->validate([
            'total'=> 'required',
        ]);
        $config = $this->paypalConfig();

        $provider = new PayPalClient($config);

        $provider->getAccessToken();

        $totalPay = $request->total;

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => ('http://localhost:3000/payment/success'),
                "cancel_url" => ('http://localhost:3000/payment/cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $config['currency'],
                        "value" => $totalPay,
                    ]
                ]
            ]
        ]);

        if (isset($response['id'] ) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return $this->success([
                        'paypal_checkout_url' => $link['href'] // Return the PayPal checkout URL
                    ]);
                }
            }
        } else {
            return $this->error('','Payment Cancel', 404);
        }


    }

    public function paypalPaymentSuccess(Request $request)
    {
        $config = $this->paypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        if(isset($response['status']) && $response['status'] == 'COMPLETED'){

            return $this->success($response['id'],'Payment Successfully');
        }else{

            return $this->error($response,'Payment Error',404);
        }
    }

    public function paypalPaymentCancel()
    {
        toastr()->error('Something went wrong try again');
        return redirect()->route('user.payment');
    }

    /* Pay with COD*/
    public function payWithCod(Request $request)
    {
//        $codSetting = CodSetting::first();
//        if ($codSetting->status == 0){
//            toastr('Vui lòng thử lại sau, xin cảm ơn!', 'error');
//            return redirect()->back();
//        }

        return $this->success('', 'Order Successfully!', );
    }

    public function getCartList(Request $request)
    {
        $data = json_decode( $request->getContent(), true);
        $paypalSetting = PaypalSetting::first();
        $shoppingCart = json_decode($data['my_cart'], true);

        $totalPay = round(json_decode($data['amount'], true) * $paypalSetting->currency_rate, 2);

        //storeOrder in Database
        $this->storeOrder($data,$data['order_method'],1, $totalPay);

        return $this->success(($shoppingCart), 'send cart to server successful', );
    }
}
