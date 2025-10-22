<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //
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

        //    =-=-=-==-==-=

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Authenticated user retrieved successfully',
        //     'data' => $user->only([
        //         'id',
        //         'name',
        //         'email',
        //         'phone',
        //         'address',
        //         'country',
        //         'city',
        //         'state',
        //         'zip_code',
        //         'status',
        //         'created_at',

        //     ])
        // ], 200);
    }



    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // $validator = Validator::make($request->all(), [
        //     'name' => 'sometimes|string|max:255',
        //     'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
        //     'phone' => 'nullable|string|unique:users,phone,' . $user->id,
        //     'address' => 'nullable|string',
        //     'country' => 'nullable|string',
        //     'city' => 'nullable|string',
        //     'state' => 'nullable|string',
        //     'zip_code' => 'nullable|string',
        // ]);

        // $validator = Validator::make($request->all(), [
        //     'name' => 'sometimes|string|max:255',
        //     'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
        //     'phone' => 'nullable|string|unique:users,phone,' . $user->id,
        //     'address' => 'nullable|string',
        //     'country' => 'nullable|string',
        //     'city' => 'nullable|string',
        //     'state' => 'nullable|string',
        //     'zip_code' => 'nullable|string',
        // ]);


        //   ===========================================
        // Validator::make(...): ينشئ كائن تحقق يدوي من البيانات.

        // $request->all(): يأخذ كل البيانات القادمة من الطلب.

        // 'sometimes': يعني "تحقق من الحقل فقط إذا كان موجودًا".
        // sometimes: يعني "تحقق من الحقل فقط إذا أُرسل".

        // 'nullable': يعني "اسمح بأن يكون الحقل فارغًا".

        // 'unique:users,email,' . $user->id: تحقق أن الإيميل غير مستخدم من قبل مستخدم آخر ما عدا المستخدم الحالي.

        // if ($validator->fails()): إذا فشلت عملية التحقق.

        // response()->json(...): يرجع الأخطاء بصيغة JSON مع كود HTTP 422.
        //   ===========================================
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            // // not allow the use for update the email 
            // 'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // $user->update($request->all());
        // $user->update($request->all());
        // $user->update($request->all());

        $user->update($validator->validated());

        // return response()->json(['user' => $user, 'message' => 'Profile updated successfully'], 200);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user
        ], 200);
    }


    /**
     * Update the authenticated user's password.
     */
    // public function updatePassword(Request $request)
    // {
    //     $user = Auth::user();

    //     $validator = Validator::make($request->all(), [
    //         'current_password' => 'required|string',
    //         'new_password' => 'required|string|min:6|confirmed',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // تحقق من كلمة المرور الحالية
    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return response()->json(['message' => 'Current password is incorrect'], 400);
    //     }

    //     // $user->password = Hash::make($request->new_password);
    //     // $user->save();
    //     // 

    //     $user->update([
    //         'password' => Hash::make($request->new_password),
    //     ]);

    //     return response()->json(['message' => 'Password updated successfully'], 200);


    //     // ===========================================
    //     //         confirmed: تعني أن هناك حقل إضافي اسمه new_password_confirmation يجب أن يطابق new_password.

    //     // Hash::check(...): يتحقق من أن كلمة المرور المدخلة تطابق المخزنة.

    //     // Hash::make(...): لتشفير كلمة المرور الجديدة قبل تخزينها.

    //     // الدالة تفترض أن المستخدم مسجل الدخول (auth()->user()).
    //     // ===========================================
    // }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the current password matches the user's password
        // تحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // تحقق أن كلمة المرور الجديدة ليست نفس القديمة
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json(['message' => 'New password cannot be the same as the current password'], 400);
        }

        // تحديث كلمة المرور
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }
}
