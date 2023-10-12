<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Http\Resources\ProvinceResource;
use App\Models\UserAddress;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorAddressController extends Controller
{
    use \App\Traits\HttpResponses;
    use \App\Traits\ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendorAddress = Vendor::where('user_id',Auth::user()->id)->get();
        return $this->success($vendorAddress);
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
        $vendor_id = Auth::user()->id;
        $found = Vendor::where('user_id',$vendor_id)->first();

        $rules = [
            'shop_name' => 'required|max:200',
            'banner' => 'nullable',
            'email' => 'required',
            'phone' => 'nullable',
            'address' => 'required',
            'description' => 'nullable',
        ];

        if (!$found){
            $rules['image'] = 'required|image|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return $this->error( $validator->errors(), 'Validation failed', 422);
        }

        if ($found && $request->banner !== null){
            $imagePath= $this->updateImage( $request, 'banner','uploads', $found->banner);
        }else{
            $imagePath= $this->uploadImage( $request, 'banner','uploads');
        }

        $data = [
            'shop_name'=> $request->shop_name,
            'banner' => $imagePath ?? $found->banner,
            'email' => $request->email,
            'phone' => $request->phone ,
            'address' => $request->address,
            'description' => $request->description
        ];
        Vendor::updateOrCreate(
            [
                'user_id' => $vendor_id
            ],
            $data
        );

        return $this->success($data, 'Store user address successfully!', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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
        //
    }

    public function vendorInfo(string $id)
    {
        $vendorInfo = Vendor::find($id);
        return $this->success($vendorInfo);
    }
}
