<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Mail\ForgotPassword;
use App\Models\User;
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
        $request->validated($request->all());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

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

            // Send the email with the new password
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


    public function checkToken(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            return response()->json(['valid' => true], 200);
        } else {
            return response()->json(['valid' => false], 401);
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
