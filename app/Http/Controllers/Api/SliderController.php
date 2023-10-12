<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Traits\HttpResponses;

class SliderController extends Controller
{
    use HttpResponses;
    public function sliders()
    {
        $sliders = Slider::where('status', 1)
            ->orderBy('serial', 'asc')
            ->select('banner','title','id', 'title', 'btn_url')
            ->get();

        return $this->success($sliders);
    }
}
