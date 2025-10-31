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

        return apiResponse(
            200,
            'Profile data fetched successfully.',
            $user
        );
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        $user->update($validated);

        return apiResponse(
            200,
            'Profile updated successfully.',
            $user
        );
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

        return apiResponse(
            200,
            'Password updated successfully.',
            $user
        );
    }
}
