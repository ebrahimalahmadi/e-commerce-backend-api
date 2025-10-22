<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'Profile data fetched successfully.',
            'data' => $user
        ], 200);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $user->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user
        ], 200);
    }


    /**
     * Update the authenticated user's password.
     */

    public function updatePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $user = Auth::user();
        $validated = $request->validated();

        // تحقق من كلمة المرور الحالية
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        // تحقق أن كلمة المرور الجديدة ليست نفس القديمة
        if (Hash::check($validated['new_password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'New password cannot be the same as the current password'
            ], 400);
        }

        // تحديث كلمة المرور
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully'
        ], 200);
    }
}






// 

// /**
//  * Update the authenticated user's profile.
//  */
    // public function update(Request $request)
    // {
    //     $user = Auth::user();

     
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'sometimes|string|max:255',
    //         // // not allow the use for update the email 
    //         // 'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
    //         'phone' => 'nullable|string|unique:users,phone,' . $user->id,
    //         'address' => 'nullable|string',
    //         'country' => 'nullable|string',
    //         'city' => 'nullable|string',
    //         'state' => 'nullable|string',
    //         'zip_code' => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // $user->update($request->all());
    //     // $user->update($request->all());
    //     // $user->update($request->all());

    //     $user->update($validator->validated());

    //     // return response()->json(['user' => $user, 'message' => 'Profile updated successfully'], 200);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Profile updated successfully.',
    //         'data' => $user
    //     ], 200);
    // }



    // =======================


    
//     /**
//      * Update the authenticated user's password.
//      */

//     public function updatePassword(Request $request)
//     {
//         $user = Auth::user();

//         $validator = Validator::make($request->all(), [
//             'current_password' => 'required|string',
//             'new_password' => 'required|string|min:6|confirmed',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()], 422);
//         }

//         // Check if the current password matches the user's password
//         // تحقق من كلمة المرور الحالية
//         if (!Hash::check($request->current_password, $user->password)) {
//             return response()->json(['message' => 'Current password is incorrect'], 400);
//         }

//         // تحقق أن كلمة المرور الجديدة ليست نفس القديمة
//         if (Hash::check($request->new_password, $user->password)) {
//             return response()->json(['message' => 'New password cannot be the same as the current password'], 400);
//         }

//         // تحديث كلمة المرور
//         $user->password = Hash::make($request->new_password);
//         $user->save();

//         return response()->json(['message' => 'Password updated successfully'], 200);
//     }
// }
