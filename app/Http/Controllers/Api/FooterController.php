<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FooterColumnThree;
use App\Models\FooterColumnTwo;
use App\Models\FooterInfo;
use App\Models\FooterTitle;

class FooterController extends Controller
{
    use \App\Traits\HttpResponses;
    public function title()
    {
        $titles = FooterTitle::first();

        $data = [
            'contact' => FooterInfo::first(),
            'columns'=>[
                [
                    'title' => $titles->footer_column_2_title,
                    'links' => FooterColumnTwo::all()
                ],
                [
                    'title' => $titles->footer_column_3_title,
                    'links' => FooterColumnThree::all()
                ]
            ]
        ];
        return $this->success($data);
    }
}
