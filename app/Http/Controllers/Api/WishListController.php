<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishListResource;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    use \App\Traits\HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wishList = WishList::with('product')->where('user_id', Auth::user()->id)->orderBy('id','DESC')->get();
        return WishListResource::collection($wishList);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $found = WishList::where(['user_id'=> Auth::user()->id, 'product_id'=> $request->product_id])->first();

        if($found){
           return $this->error('','Product has already been added to wishlist', 422);
        }

        $wishList = new WishList();
        $wishList->product_id = $request->product_id;
        $wishList->user_id = Auth::user()->id;
        $wishList->save();
        return $this->success($wishList, 'Add product to wishlist successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wishList = WishList::where('product_id', $id)->first();
        $wishList->delete();
        return $this->success($wishList, 'Remove product from wishlist successfully');
    }
}
