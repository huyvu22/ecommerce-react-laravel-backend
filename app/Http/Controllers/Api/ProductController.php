<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use \App\Traits\HttpResponses;
    use \App\Traits\ProductFilterTrait;

    public function products()
    {
        return ProductResource::collection(
           Product::where('status', 1)->get()
        );
    }

    public function vendorProducts($id)
    {
        $vendor = Vendor::find($id);
        $products = Product::where([
            'status' => 1,
            'vendor_id' => $vendor->id
        ])->paginate(9);
        return ProductResource::collection($products);
    }

    public function showProduct(string $id, string $slug)
    {
        return ProductResource::collection(
            Product::where('slug', $slug)->orWhere('id',$id)->where('status', 1)->get()
        );
    }
    public function productType(Request $request, string $type)
    {

        $products = Product::where('product_type', 'like', '%' . $type . '%')
            ->where('status', 1);


//        if ($request->has('search')) {
//            $products->where('name', 'like', '%' . $request->search . '%');
//        }
//
//        if ($request->has('price-range')) {
//            $priceRange = explode(',', $request->input('price-range'));
//
//            if (count($priceRange) === 2) {
//                $minPrice = $priceRange[0];
//                $maxPrice = $priceRange[1];
//
//                $products->whereBetween('offer_price', [$minPrice, $maxPrice]);
//            }
//        }
//
//        if ($request->has('rating')) {
//            $rating = intval($request->rating);
//            if ($rating === 0) {
//                $products->whereDoesntHave('reviews');
//            }else{
//                $subquery = DB::table('product_reviews')
//                    ->select('product_id', DB::raw('avg(rating) as avg_rating'))
//                    ->groupBy('product_id')
//                    ->havingRaw('ROUND(avg_rating) = ?', [$rating]);
//
//                $products->joinSub($subquery, 'avg_reviews', function ($join) {
//                    $join->on('products.id', '=', 'avg_reviews.product_id');
//                });
//            }
//        }
//
//        if($request->has('price-range') && $request->search){
//
//            $priceRange = explode(',', $request->input('price-range'));
//            $minPrice = $priceRange[0];
//            $maxPrice = $priceRange[1];
//
//            $products->where(function ($query) use ($minPrice, $maxPrice, $request) {
//                $query->whereBetween('price', [$minPrice, $maxPrice])
//                    ->where('name', 'like', '%' . $request->search . '%');
//            });
//        }
        $products = $this->filterProducts($products, $request);

        $products = $products->paginate(9);

        return ProductResource::collection($products);
    }

    public function productsByCategory(Request $request, $slug)
    {
        $category = Category::with('products')->where('slug', $slug)->first();

        $products = $category->products();

        if($request->currentItem){
            $products->where('slug', '!=',$request->currentItem);
        }

        if ($request->has('search')) {
            $products->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('price-range')) {
            $priceRange = explode(',', $request->input('price-range'));

            if (count($priceRange) === 2) {
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];

                $products->whereBetween('offer_price', [$minPrice, $maxPrice]);
            }
        }
        if ($request->has('rating')) {
            $rating = intval($request->rating);
            if ($rating === 0) {
                $products->whereDoesntHave('reviews');
            }else{
                $subquery = DB::table('product_reviews')
                    ->select('product_id', DB::raw('avg(rating) as avg_rating'))
                    ->groupBy('product_id')
                    ->havingRaw('ROUND(avg_rating) = ?', [$rating]);

                $products->joinSub($subquery, 'avg_reviews', function ($join) {
                    $join->on('products.id', '=', 'avg_reviews.product_id');
                });
            }
        }

        if($request->has('price-range') && $request->search){

            $priceRange = explode(',', $request->input('price-range'));
            $minPrice = $priceRange[0];
            $maxPrice = $priceRange[1];

            $products->where(function ($query) use ($minPrice, $maxPrice, $request) {
                $query->whereBetween('price', [$minPrice, $maxPrice])
                    ->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $products = $products->paginate(9);
        return ProductResource::collection($products);
    }

    public function productsBySubCategory(Request $request, $slug)
    {
        $subCategory = SubCategory::with('products')->where('slug', $slug)->first();

        $products = $subCategory->products();

        if($request->currentItem){
            $products->where('slug', '!=',$request->currentItem);
        }

        if ($request->has('search')) {
            $products->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('price-range')) {
            $priceRange = explode(',', $request->input('price-range'));

            if (count($priceRange) === 2) {
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];

                $products->whereBetween('offer_price', [$minPrice, $maxPrice]);
            }
        }
        if($request->has('price-range') && $request->search){

            $priceRange = explode(',', $request->input('price-range'));
            $minPrice = $priceRange[0];
            $maxPrice = $priceRange[1];

            $products->where(function ($query) use ($minPrice, $maxPrice, $request) {
                $query->whereBetween('price', [$minPrice, $maxPrice])
                    ->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('rating')) {
            $rating = intval($request->rating);
            if ($rating === 0) {
                $products->whereDoesntHave('reviews');
            }else{
                $subquery = DB::table('product_reviews')
                    ->select('product_id', DB::raw('avg(rating) as avg_rating'))
                    ->groupBy('product_id')
                    ->havingRaw('ROUND(avg_rating) = ?', [$rating]);

                $products->joinSub($subquery, 'avg_reviews', function ($join) {
                    $join->on('products.id', '=', 'avg_reviews.product_id');
                });
            }
        }

        $products = $products->paginate(9);
        return ProductResource::collection($products);

    }


    public function searchProduct(Request $request, $keyword)
    {
        $products = Product::where('status', 1)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('full_description', 'like', '%' . $keyword . '%')
                    ->orWhereHas('category', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('subCategory', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
            });

        if ($request->has('price-range')) {
            $priceRange = explode(',', $request->input('price-range'));

            if (count($priceRange) === 2) {
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];

                $products->whereBetween('offer_price', [$minPrice, $maxPrice]);
            }
        }
        if ($request->has('rating')) {
            $rating = intval($request->rating);
            if ($rating === 0) {
                $products->whereDoesntHave('reviews');
            }else{
                $subquery = DB::table('product_reviews')
                    ->select('product_id', DB::raw('avg(rating) as avg_rating'))
                    ->groupBy('product_id')
                    ->havingRaw('ROUND(avg_rating) = ?', [$rating]);

                $products->joinSub($subquery, 'avg_reviews', function ($join) {
                    $join->on('products.id', '=', 'avg_reviews.product_id');
                });
            }
        }

        $products = $products->paginate(9);

        return ProductResource::collection($products);
    }


    public function filterPrice($min, $max)
    {
        $products = Product::where('status',1)
            ->whereBetween('price', [$min, $max])
            ->paginate(9);
        return ProductResource::collection($products);
    }
}
