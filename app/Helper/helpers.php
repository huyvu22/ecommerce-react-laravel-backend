<?php


//active sidebar item
use App\Models\Product;

function isRole($dataArr,$moduleName,$role='view')
{
    if(!empty($dataArr[$moduleName])){
        $roleArr = $dataArr[$moduleName];
        if(in_array($moduleName.'.'.$role,$roleArr)){
            return true;
        }
    }
    return false;
}

function setActive($routes){
    if(is_array($routes)){
        foreach($routes as $route){
            if(request()->routeIs($route)){
                return 'active';
            }
        }
    }
}

function checkDiscount($product){
    $currentDate = date("Y-m-d");

    if($product->offer_price > 0 && $currentDate >= $product->offer_start_date && $currentDate <= $product->offer_end_date){
        return true;
    }
    return false;
}

function format($price){
    return  '$'.number_format($price, 2, ',', '.');
}

function discountPercent($originalPrice, $currentPrice){
    $discountPrice = $originalPrice - $currentPrice;
    return round($discountPrice/$originalPrice,2) * 100;
}

function getCartTotalRaw()
{
    $total = 0;
    foreach (\Cart::content() as $product){
        $total += ($product->price + $product->options->variants_total) * $product->qty;
    }
    return ($total);
}
function getCartTotal()
{
    $total = 0;
    foreach (\Cart::content() as $product){
        $total += ($product->price + $product->options->variants_total) * $product->qty;
    }
    return format($total);
}

function getStockQuantityProduct($rowId){
    $productId = \Cart::get($rowId)->id;
    return Product::find($productId)->quantity;
}

function getMainCartTotal(){
    if(\Session::has('coupon')){
        $coupon = \Session::get('coupon');
        if($coupon['discount_type'] == 'amount'){
            $total = getCartTotalRaw() - $coupon['discount'];
            return $total;
        }else if($coupon['discount_type'] == 'percent'){
            $discount_value = getCartTotalRaw() - ($coupon['discount']/100 *getCartTotalRaw());
            $total = getCartTotalRaw() - $discount_value;
            return $total;
        }
    }else{
         $total = getCartTotalRaw();
        return number_format($total, 0, ',', '.').'₫';
    }
}

function getMainCartTotalRaw(){
    if(\Session::has('coupon')){
        $coupon = \Session::get('coupon');
        if($coupon['discount_type'] == 'amount'){
            $total = getCartTotalRaw() - $coupon['discount'];
            return $total;
        }else if($coupon['discount_type'] == 'percent'){
            $discount_value = getCartTotalRaw() - ($coupon['discount']/100 *getCartTotalRaw());
            $total = getCartTotalRaw() - $discount_value;
            return $total;
        }
    }else{
        return getCartTotalRaw();
    }
}


function getShippingFeeRaw(){
    if(\Session::has('shipping_method')){
        return (Session::get('shipping_method')['cost']);
    }else{
        return 0;
    }
}

function getPayAmountRaw(){
    $finalPay = getMainCartTotalRaw() + getShippingFeeRaw();
    return $finalPay;
}
function getPayAmount(){
     $finalPay = getMainCartTotalRaw() + getShippingFeeRaw();
     return format($finalPay);
}

function limitText($text, $limit = 20){
    return \Str::limit($text, $limit);
}





