<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VendorProductController extends Controller
{
    use \App\Traits\ImageUploadTrait;
    use \App\Traits\HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('vendor')->where('vendor_id',Auth::user()->vendor->id)->paginate(10);
        return $this->success($products);
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
        $foundProduct = Product::find($request->product_id);

        $rules = [
            'name' => 'required|max:200',
            'category' => 'required',
            'sub_category' => 'required',
            'sku' => 'nullable',
            'price' => 'required',
            'offer_price' => 'nullable',
            'stock_quantity' => 'required',
            'short_description' => 'required|max:600',
            'full_description' => 'required',
            'product_type' => 'nullable',
            'seo_title' => 'nullable|max:200',
            'seo_description' => 'nullable',
            'status' => 'required',
        ];

        if (!$foundProduct){
            $rules['image'] = 'required|image|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return $this->error( $validator->errors(), 'Validation failed', 422);
        }

        if ($foundProduct && $request->image !== null){
            $imagePath= $this->updateImage( $request, 'image','uploads', $foundProduct->thumb_image);
        }else{
            $imagePath= $this->uploadImage( $request, 'image','uploads');
        }

        $productData = [
            'thumb_image' => $imagePath ?? $foundProduct->thumb_image,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'vendor_id' => Auth::user()->vendor->id,
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category,
            'sku' => $request->sku,
            'price' => $request->price,
            'offer_price' => $request->offer_price,
            'quantity' => $request->stock_quantity,
            'short_description' => $request->short_description,
            'full_description' => $request->full_description,
            'product_type' => $request->product_type,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'is_approved' => 1,
            'status' => $request->status,
        ];

        $product = Product::updateOrCreate(['id' => $request->product_id], $productData);
        return $this->success($product, 'Product Saved Successfully');
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
        $product = Product::find($id);
        if($product){
            return new ProductResource($product);
        }else{
            return  $this->error('', "Product Not Found", 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $this->deleteImage($product->thumb_image);
        if($product){
            $product->delete();
            return $this->success('', 'Delete Successful!');
        }else{
            return $this->error('', 'Delete Fail!', 422);
        }
    }
}
