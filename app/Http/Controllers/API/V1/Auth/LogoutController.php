<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    //
    public function logout(Request $request)
    {
        // // حذف التوكن الحالي فقط
        $request->user()->currentAccessToken()->delete();

        return apiResponse(
            200,
            'Logged out successfully'
        );
    }
}
