<?php

namespace App\Http\Controllers\Backend;

use App\Models\EmailConfig;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class SettingController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $generalSetting = GeneralSetting::first();
        $emailSettings = EmailConfig::first();
        return view('admin.setting.index', compact('generalSetting','emailSettings'));
    }

    public function generalSetting(Request $request)
    {

        $request->validate([
            'name' =>'required',
            'email' =>'required|email',
            'phone' =>'required',
            'address' =>'required',
            'map' =>'required',
            'currency_name' =>'required',
            'currency_icon' =>'required',

        ]);

        GeneralSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'map' => $request->map,
                'currency_name' => $request->currency_name,
                'currency_icon' => $request->currency_icon,
            ]
        );

        toastr()->success('Updated successfully');
        return redirect()->back();
    }

    public function emailConfigSettingUpdate(Request $request)
    {
        $request->validate([
            'email' =>'required|email',
            'host' =>'required|max:200',
            'username' =>'required|max:200',
            'password' =>'required|max:200',
            'port' =>'required',
            'encryption' =>'required',

        ]);
        EmailConfig::updateOrCreate(
            ['id' => 1],
            [
                'email' => $request->email,
                'host' => $request->host,
                'username' => $request->username,
                'password' => $request->password,
                'port' => $request->port,
                'encryption' => $request->encryption,
            ]
        );

        toastr()->success('Updated successfully');
        return redirect()->back();
    }
}
