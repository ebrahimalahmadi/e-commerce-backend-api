<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    //

    public function register(Request $request)
    {
        //  Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:255|unique:users,phone',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'zip_code' => 'nullable|string',
            // 'status' => 'nullable|string',
            'status' => ['nullable', Rule::in(['active', 'inactive'])],

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        //  Create user
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'phone' => $request->phone,
        //     'address' => $request->address,
        //     'country' => $request->country,
        //     'city' => $request->city,
        //     'state' => $request->state,
        //     'zip_code' => $request->zip_code,
        //     'status' => 'active',

        // ]);

        //   ==========

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make(value: $request->password),
        //     'phone' => $request->phone,
        //     'address' => $request->address,
        //     'country' => $request->country,
        //     'city' => $request->city,
        //     'state' => $request->state,
        //     'zip_code' => $request->zip_code,
        // ]);


        //   ==========

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'phone' => $request->phone ?? null,
        //     'address' => $request->address ?? null,
        //     'country' => $request->country ?? null,
        //     'city' => $request->city ?? null,
        //     'state' => $request->state ?? null,
        //     'zip_code' => $request->zip_code ?? null,
        // ]);

        // ================

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone ?? null,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'status' => $request->status ?? 'active',
        ]);




        //  Create token
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
}
