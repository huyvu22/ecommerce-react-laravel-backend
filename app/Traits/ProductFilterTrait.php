<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait ProductFilterTrait
{
    protected function filterProducts($query, $request)
    {
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('price-range')) {
            $priceRange = explode(',', $request->input('price-range'));

            if (count($priceRange) === 2) {
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];

                $query->whereBetween('offer_price', [$minPrice, $maxPrice]);
            }
        }

        if ($request->has('rating')) {
            $rating = intval($request->rating);
            if ($rating === 0) {
                $query->whereDoesntHave('reviews');
            }else{
                $subquery = DB::table('product_reviews')
                    ->select('product_id', DB::raw('avg(rating) as avg_rating'))
                    ->groupBy('product_id')
                    ->havingRaw('ROUND(avg_rating) = ?', [$rating]);

                $query->joinSub($subquery, 'avg_reviews', function ($join) {
                    $join->on('products.id', '=', 'avg_reviews.product_id');
                });
            }
        }

        // Add more filters as needed

        return $query;
    }
}
