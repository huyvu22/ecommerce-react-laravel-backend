<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use \App\Traits\HttpResponses;

    public function products()
    {
        return ProductResource::collection(
           Product::where('status', 1)->get()
        );
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

        $products = $products->paginate(8);

        return ProductResource::collection($products);
    }

    public function productsByCategory(Request $request, $slug)
    {
        $category = Category::with('products')->where('slug', $slug)->first();

        $products = $category->products();

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

        $products = $products->paginate(8);
        return ProductResource::collection($products);
    }

    public function productsBySubCategory(Request $request, $slug)
    {
        $subCategory = SubCategory::with('products')->where('slug', $slug)->first();

        $products = $subCategory->products();

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

        $products = $products->paginate(8);
        return ProductResource::collection($products);

//        $subCategory = SubCategory::with('products')->where('slug', $slug)->first();
//
//        $productsQuery = $subCategory->products();
//
//        if ($keyword !== null) {
//            $productsQuery->where('name', 'like', '%' . $keyword . '%');
//        }
//
//        // Get max and min values
//        $maxPrice = $productsQuery->max('price');
//        $minPrice = $productsQuery->min('price');
//
//        // Paginate products
//        $products = $productsQuery->paginate(8);
//
//        // Create a data array that includes max and min values
//        $data = [
//            'maxPrice' => $maxPrice,
//            'minPrice' => $minPrice,
//            'products' => $products,
//        ];
//
//        return ProductResource::collection($data);
    }

    public function searchProduct($keyword)
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
                })
                ;
            })
            ->paginate(8);
        return ProductResource::collection($products);
    }

    public function filterPrice($min, $max)
    {
        $products = Product::where('status',1)
            ->whereBetween('price', [$min, $max])
            ->paginate(8);
        return ProductResource::collection($products);
    }
}
