<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // بحث بالاسم أو الايميل أو الهاتف
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // تصفية حسب الحالة
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // ترتيب و فرز
        $sortBy = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sortBy, $direction);

        // ترقيم النتائج
        // $users = $query->paginate(10);
        $users = $query->get();


        return response()->json([
            'status'  => true,
            'message' => 'Users retrieved successfully.',
            'data'    => $users,
        ]);
    }



    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone'    => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]*$/',
                'unique:users,phone',
            ],
            'address'  => 'nullable|string',
            'country'  => 'nullable|string',
            'city'     => 'nullable|string',
            'zip_code' => 'nullable|string',
            'status'   => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        // تنظيف رقم الهاتف
        $cleanPhone = $request->input('phone');
        if ($cleanPhone) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $cleanPhone);
            if (!str_starts_with($cleanPhone, '967')) {
                $cleanPhone = '967' . ltrim($cleanPhone, '0');
            }
        }

        $user = new User();
        $user->name     = $validatedData['name'];
        $user->email    = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->phone    = $cleanPhone;
        $user->address  = $validatedData['address'] ?? null;
        $user->country  = $validatedData['country'] ?? null;
        $user->city     = $validatedData['city'] ?? null;
        $user->zip_code = $validatedData['zip_code'] ?? null;
        $user->status   = $validatedData['status'] ?? 'active';
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'User created successfully.',
            'data'    => $user,
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User retrieved successfully.',
            'data'    => $user,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        }

        $validatedData = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => [
                'sometimes',
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'sometimes|required|string|min:6',
            'phone'    => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]*$/',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'address'  => 'nullable|string',
            'country'  => 'nullable|string',
            'city'     => 'nullable|string',
            'zip_code' => 'nullable|string',
            'status'   => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        $cleanPhone = $request->input('phone');
        if ($cleanPhone) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $cleanPhone);
            if (!str_starts_with($cleanPhone, '967')) {
                $cleanPhone = '967' . ltrim($cleanPhone, '0');
            }
        }

        $user->name     = $validatedData['name'] ?? $user->name;
        $user->email    = $validatedData['email'] ?? $user->email;
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->phone    = $cleanPhone ?? $user->phone;
        $user->address  = $validatedData['address'] ?? $user->address;
        $user->country  = $validatedData['country'] ?? $user->country;
        $user->city     = $validatedData['city'] ?? $user->city;
        $user->zip_code = $validatedData['zip_code'] ?? $user->zip_code;
        $user->status   = $validatedData['status'] ?? $user->status;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'User updated successfully.',
            'data'    => $user,
        ]);
    }



    // public function update(Request $request, User $user)
    // {
    //     // الآن $user هو الـ Model الصحيح مباشرة
    //     $validatedData = $request->validate([
    //         'name'     => 'sometimes|required|string|max:255',
    //         'email'    => [
    //             'sometimes',
    //             'required',
    //             'string',
    //             'email',
    //             Rule::unique('users', 'email')->ignore($user->id),
    //         ],
    //         'password' => 'sometimes|required|string|min:6',
    //         'phone'    => [
    //             'nullable',
    //             'string',
    //             'max:20',
    //             'regex:/^[0-9+\-\s()]*$/',
    //             Rule::unique('users', 'phone')->ignore($user->id),
    //         ],
    //         'address'  => 'nullable|string',
    //         'country'  => 'nullable|string',
    //         'city'     => 'nullable|string',
    //         'zip_code' => 'nullable|string',
    //         'status'   => ['nullable', Rule::in(['active', 'inactive'])],
    //     ]);

    //     // تحديث الحقول
    //     $user->name     = $request->input('name', $user->name);
    //     $user->email    = $request->input('email', $user->email);
    //     $user->address  = $request->input('address', $user->address);
    //     $user->country  = $request->input('country', $user->country);
    //     $user->city     = $request->input('city', $user->city);
    //     $user->zip_code = $request->input('zip_code', $user->zip_code);
    //     $user->status   = $request->input('status', $user->status);

    //     // الهاتف
    //     if ($request->filled('phone')) {
    //         $cleanPhone = preg_replace('/[^0-9]/', '', $request->input('phone'));
    //         if (!str_starts_with($cleanPhone, '967')) {
    //             $cleanPhone = '967' . ltrim($cleanPhone, '0');
    //         }
    //         $user->phone = $cleanPhone;
    //     }

    //     // كلمة المرور
    //     if ($request->filled('password')) {
    //         $user->password = Hash::make($request->input('password'));
    //     }

    //     $user->save();

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'User updated successfully.',
    //         'data'    => $user,
    //     ]);
    // }




    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'User not found.',
    //         ], 404);
    //     }

    //     // ✅ التحقق من صحة البيانات
    //     $validatedData = $request->validate([
    //         'name'     => 'sometimes|required|string|max:255',
    //         'email'    => [
    //             'sometimes',
    //             'required',
    //             'string',
    //             'email',
    //             Rule::unique('users', 'email')->ignore($user->id),
    //         ],
    //         'password' => 'sometimes|required|string|min:6',

    //         // 📞 التحقق من صحة الرقم
    //         'phone' => [
    //             'nullable',
    //             'string',
    //             'max:20',
    //             'regex:/^[0-9+\-\s()]*$/',
    //             Rule::unique('users', 'phone')->ignore($user->id),
    //         ],

    //         'address'  => 'nullable|string',
    //         'country'  => 'nullable|string',
    //         'city'     => 'nullable|string',
    //         'zip_code' => 'nullable|string',
    //         'status'   => ['nullable', Rule::in(['active', 'inactive'])],
    //     ]);

    //     // 🧹 تنظيف رقم الهاتف قبل الحفظ
    //     $cleanPhone = $request->input('phone');
    //     if ($cleanPhone) {
    //         $cleanPhone = preg_replace('/[^0-9]/', '', $cleanPhone);

    //         if (!str_starts_with($cleanPhone, '967')) {
    //             $cleanPhone = '967' . ltrim($cleanPhone, '0');
    //         }
    //     }

    //     // 🔄 تحديث البيانات
    //     $user->name     = $validatedData['name'] ?? $user->name;
    //     $user->email    = $validatedData['email'] ?? $user->email;
    //     if (!empty($validatedData['password'])) {
    //         $user->password = Hash::make($validatedData['password']);
    //     }
    //     $user->phone    = $cleanPhone ?? $user->phone;
    //     $user->address  = $validatedData['address'] ?? $user->address;
    //     $user->country  = $validatedData['country'] ?? $user->country;
    //     $user->city     = $validatedData['city'] ?? $user->city;
    //     $user->zip_code = $validatedData['zip_code'] ?? $user->zip_code;
    //     $user->status   = $validatedData['status'] ?? $user->status;
    //     $user->save();

    //     // ✅ النتيجة
    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'User updated successfully.',
    //         'data'    => $user,
    //     ]);
    // }



    /**
     * Remove the specified user.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User deleted successfully.',
        ]);
    }
}
