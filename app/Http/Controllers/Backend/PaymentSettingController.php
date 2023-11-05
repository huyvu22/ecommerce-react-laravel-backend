<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\PaypalSetting;
use App\Models\VnPaySetting;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $paypalSetting = PaypalSetting::first();
        $vnPaySetting = VnPaySetting::first();
        $codSetting = CodSetting::first();

        $client = new Client();
        $apiKey = 'EVql9dBo0Wj2bDny3JfNGGeBq8IlULmY';
        $response = $client->get('https://api.apilayer.com/exchangerates_data/latest?base=USD', [
            'query' => ['apikey' => $apiKey],
        ]);

        $exchangeRatesData = json_decode($response->getBody(), true);
        $baseCurrency = 'USD';
        $usdExchangeRates = [];

        if ($exchangeRatesData['base'] === $baseCurrency) {
            $usdExchangeRates = $exchangeRatesData['rates'];
        } else {
            // Convert all rates to be based on USD
            foreach ($exchangeRatesData['rates'] as $currency => $rate) {
                $usdRate = $rate / $exchangeRatesData['rates'][$baseCurrency];
                $usdExchangeRates[$currency] = $usdRate;
            }
        }

        return view('admin.payment.index', compact('paypalSetting', 'codSetting', 'vnPaySetting', 'usdExchangeRates'));
    }
}
