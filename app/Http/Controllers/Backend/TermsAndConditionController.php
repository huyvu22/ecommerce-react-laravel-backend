<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;

class TermsAndConditionController extends Controller
{
    public function index()
    {
        $content = TermsAndCondition::first();
        return view('admin.terms-and-condition.index',compact('content') );
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        TermsAndCondition::updateOrCreate(
            ['id' => 1],
            [
                'content' => $request->content
            ]
        );
        toastr()->success('Updated successfully');
        return redirect()->back();
    }
}
