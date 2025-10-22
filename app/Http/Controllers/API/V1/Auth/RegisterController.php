<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
// 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    //

    public function register(RegisterRequest $request)
    {
        //  Validate input

        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'zip_code' => $validated['zip_code'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }






















    // public function register(Request $request)
    // {
    //     //  Validate input
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|unique:users,email',
    //         'password' => 'required|string|min:6|confirmed',
    //         'phone' => 'nullable|string|max:255|unique:users,phone',
    //         'address' => 'nullable|string',
    //         'country' => 'nullable|string',
    //         'city' => 'nullable|string',
    //         'zip_code' => 'nullable|string',
    //         'status' => ['nullable', Rule::in(['active', 'inactive'])],

    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation errors',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }



    //     // ================

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'phone' => $request->phone ?? null,
    //         'address' => $request->address,
    //         'country' => $request->country,
    //         'city' => $request->city,
    //         'state' => $request->state,
    //         'zip_code' => $request->zip_code,
    //         'status' => $request->status ?? 'active',
    //     ]);




    //     //  Create token
    //     $token = $user->createToken('API Token')->plainTextToken;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User registered successfully',
    //         'data' => [
    //             'user' => $user,
    //             'token' => $token
    //         ]
    //     ], 201);
    // }
}
