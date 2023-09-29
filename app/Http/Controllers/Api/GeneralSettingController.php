<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class GeneralSettingController extends Controller
{
    use \App\Traits\HttpResponses;
    public function index()
    {
        $setting = GeneralSettingController::first();
        return  $this->success($setting);
    }
}
