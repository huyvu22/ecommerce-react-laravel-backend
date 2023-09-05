<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use \App\Traits\HttpResponses;
    public function index()
    {
        $questionAndAnswer = FAQ::where('status',1)->get();
        return $this->success($questionAndAnswer);
    }
}
