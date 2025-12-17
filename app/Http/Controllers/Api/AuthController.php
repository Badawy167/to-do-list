<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
public function register(Request $request){
    // تسجيل مستخدم جديد
    $validated = $request->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required','email','max:255','unique:users'],
        'password' => ['required','confirmed','string','min:8'],
    ]);

    $user = User::create([
        'name'=>$validated['name'],
        'email'=>$validated['email'],
        'password' => Hash::make($validated['password'])
    ]);
    
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status'=>true,
            'message'=>[
                'ar'=> 'تم التسجيل بنجاح',
                'en' => 'Registered successfully'] ,
    
            'data'=>[
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'token'=>$token,
            ]
        ]);
}
}
