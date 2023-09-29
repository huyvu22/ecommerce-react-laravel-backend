<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        $content = PrivacyPolicy::first();
        return view('admin.policy.index',compact('content') );
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        PrivacyPolicy::updateOrCreate(
            ['id' => 1],
            [
                'content' => $request->content
            ]
        );
        toastr()->success('Updated successfully');
        return redirect()->back();
    }
}
