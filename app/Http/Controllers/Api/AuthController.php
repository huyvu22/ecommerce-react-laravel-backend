<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Mail\ForgotPassword;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mail;

class AuthController extends Controller
{
    use \App\Traits\HttpResponses;

    public function register(StoreUserRequest $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->error('', $validator->messages(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        UserAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of '.$user->name)->plainTextToken,
        ]);

    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
           'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);


        if (!Auth::attempt($validatedData)) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();
        if($request->remember_token) {
            $user->remember_token = $request->remember_token;
            $user->save();
        }


        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api Token of '.$user->name)->plainTextToken,
        ]);

    }

    public function forgot(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if($user) {
            $password = Str::random(8);
            $user->password = Hash::make($password);
            $user->save();

            Mail::to($request->email)->send(new ForgotPassword($user->name,$request->email, $password));
            return $this->success('', 'Password reset successful. Check your email for the new password.');
        } else {
            return $this->error('', 'Email not found.', 422);
        }
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success('','Logged out successfully');
    }

    public function checkTokenLogin(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            return response()->json(['valid' => true], 200);
        } else {
            return response()->json(['valid' => false], 401);
        }
    }

    public function checkRememberLogin(Request $request)
    {
       $rememberToken = $request->remember_token;
       if($rememberToken){
           $found = User::where('remember_token',$rememberToken)->first();
           if ($found) {
               return response()->json(['valid' => true], 200);
           } else {
               return response()->json(['valid' => false], 401);
           }
       }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->error('', $validator->messages(), 422);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->success('Password changed successfully',200 );
    }
}
