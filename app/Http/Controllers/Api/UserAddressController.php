<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Http\Resources\ProvinceResource;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserAddressController extends Controller
{
    use \App\Traits\HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userAddress = UserAddress::where('user_id',Auth::user()->id)->get();
        if($userAddress->count() <= 0) {
            $userAddress = User::where('id', Auth::user()->id)->get();
        }
        return response()->json($userAddress);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user_id = Auth::user()->id;

        $data = [
            'user_id' => $user_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ,
            'province' => $request->province ,
            'district' => $request->district ,
            'ward' => $request->ward,
            'address' => $request->address,
            'note' => $request->note
        ];

        $result = UserAddress::updateOrCreate(['user_id' => $user_id], $data);

        return $this->success($result, 'Store user address successfully!', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    public function getProvince()
    {
        $provinces = DB::table('province')->get();
        return ProvinceResource::collection($provinces);
    }

    public function getDistrict($provinceId)
    {
        $districts = DB::table('district')->where('_province_id', $provinceId)->get();
        return DistrictResource::collection($districts);
    }

    public function getWard($districtId)
    {
        $wards = DB::table('ward')->where('_district_id', $districtId)->get();
        return DistrictResource::collection($wards);
    }
}
