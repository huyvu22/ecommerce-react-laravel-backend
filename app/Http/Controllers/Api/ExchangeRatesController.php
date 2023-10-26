<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExchangeRatesController extends Controller
{
    use \App\Traits\HttpResponses;
    public function index()
    {
        $vnPaySetting = \App\Models\VnPaySetting::first();
        return $this->success($vnPaySetting->currency_rate);
    }
}
