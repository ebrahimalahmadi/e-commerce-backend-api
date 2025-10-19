<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    //

    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Authenticated user retrieved successfully',
            'data' => $request->user()
        ], 200);
    }
}
