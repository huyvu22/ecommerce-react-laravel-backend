<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductReviewGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    use \App\Traits\HttpResponses;
    use \App\Traits\ImageUploadTrait;
    public function showReview($productId)
    {
        $reviews = ProductReview::where(['product_id'=> $productId, 'status' => 1])->paginate(5);
        return ProductReviewResource::collection($reviews);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required',
            'review' => 'required',
        ]);

        $vendor = Product::with('vendor')->find($request->product_id)->vendor->first();
        $imagePathArr= $this->uploadMultiImage( $request, 'images','uploads');


        $review = new ProductReview();
        $review->product_id = $request->product_id;
        $review->user_id = Auth::user()->id;
        $review->vendor_id = $vendor->id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->status = 1;
        $review->save();

        if($imagePathArr){
            foreach ( $imagePathArr as $imagePath) {
                $review_gallery = new ProductReviewGallery();
                $review_gallery->product_review_id = $review->id;
                $review_gallery->image = $imagePath;
                $review_gallery->save();
            }
        }

        return $this->success('', 'Thank you for reviewing !');

    }
}
