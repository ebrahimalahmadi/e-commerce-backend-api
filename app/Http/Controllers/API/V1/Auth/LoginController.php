<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
// 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    //

    public function login(LoginRequest $request)
    {
        // تم التحقق من البيانات تلقائيًا بواسطة LoginRequest
        $validated = $request->validated();

        // البحث عن المستخدم
        $user = User::where('email', $validated['email'])->first();

        // التحقق من صحة كلمة المرور
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        // إنشاء التوكن
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 200);
    }
}
