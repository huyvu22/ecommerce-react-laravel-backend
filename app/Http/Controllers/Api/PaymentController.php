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
            $orderProduct->unit_price = $item['offer_price'];
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
        if($coupon !== null){
            $coupon = Coupon::first();
            $coupon->total_used = (int)$coupon->total_used + 1;
            $coupon->save();
        }
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
                "return_url" => ('http://localhost:3000/payment/paypal/success'),
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
        return $this->success('', 'Order Successfully!', );
    }

    /* Pay with VnPay*/
    public function connectVnPayPayment(Request $request)
    {

        $vnPaySetting = \App\Models\VnPaySetting::first();
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $totalPay = $request->total;

        $vnp_TmnCode = $vnPaySetting->client_id;
        $vnp_HashSecret = $vnPaySetting->secret_key;
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = 'http://localhost:3000/payment/vnpay/success';
        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
        $vnp_TxnRef = rand(1,10000); // order_id
        $vnp_Amount = $totalPay; // Số tiền thanh toán
        $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD tren Ecommerce",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire,
            'vnp_BankCode' => 'ncb'
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );

        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }

    public function vnPayCheck(Request $request)
    {
        $vnp_ResponseCode = $request->vnp_ResponseCode;

        if(!empty($vnp_ResponseCode)){
            if($vnp_ResponseCode == '00'){
                return $request->all();
            }elseif ($payment_id === '2'){
                $data_url = $this->connectVnPayPayment();
                return redirect()->to($data_url);
            }
        }
    }

    public function getCartList(Request $request)
    {
        $data = json_decode( $request->getContent(), true);
        $paypalSetting = PaypalSetting::first();
        $shoppingCart = json_decode($data['my_cart'], true);

        $totalPay = round(json_decode($data['amount'], true) * $paypalSetting->currency_rate, 2);

        //storeOrder in Database
        $this->storeOrder($data,$data['order_method'],$data['payment_status'], $totalPay);

        return $this->success(($shoppingCart), 'send cart to server successful', );
    }
}
