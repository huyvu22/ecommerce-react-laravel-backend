<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    use \App\Traits\HttpResponses;
    public function showReview($productId)
    {
        $reviews = ProductReview::where('product_id', $productId)->get();
        return $this->success($reviews);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required',
            'review' => 'required',
        ]);

        $vendor = Product::with('vendor')->find($request->product_id)->vendor->first();

        $review = new ProductReview();
        $review->product_id = $request->product_id;
        $review->user_id = Auth::user()->id;
        $review->vendor_id = $vendor->id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->status = 0;
        $review->save();

        return $this->success('', 'Thank you for reviewing !');
    }
}
