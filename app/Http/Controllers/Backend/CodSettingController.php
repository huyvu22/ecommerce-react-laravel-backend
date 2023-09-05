<?php

namespace App\Http\Controllers\Backend;

use App\Models\CodSetting;
use Illuminate\Http\Request;

class CodSettingController extends \App\Http\Controllers\Controller
{
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required',
        ]);
        CodSetting::updateOrCreate(
            ['id' => $id],
            [
                'status' => $request->status,
            ]
        );
        toastr()->success('Updated Successfully');
        return redirect()->back();
    }
}
