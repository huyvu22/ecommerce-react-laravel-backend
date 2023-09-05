<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use \App\Traits\HttpResponses;

    public function category()
    {
        return CategoryResource::collection(
            Category::where('status', 1)->get()
        );
    }
}
