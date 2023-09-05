<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVendorRequest;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthVendorController extends Controller
{
    use \App\Traits\HttpResponses;
    use \App\Traits\ImageUploadTrait;

    public function register(StoreVendorRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'vendor',
        ]);
        $imagePath= $this->uploadImage( $request, 'image','uploads');
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'shop_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'banner' => $imagePath,
            'description' => $request->description
        ]);

        return $this->success([
            'user' => $vendor,
            'token' => $user->createToken('API Token of '.$user->name)->plainTextToken,
        ]);

    }
}
