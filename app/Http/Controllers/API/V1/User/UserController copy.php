<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserControllerUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    //     //
    //     $user = User::all();
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User data fetched successfully.',
    //         'data' => $user
    //     ], 200);
    // }
    public function index(Request $request)
    {
        // 🔍 فلترة وبحث بسيط
        $query = User::query();

        // البحث بالاسم أو الإيميل أو رقم الهاتف
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // تصفية حسب الحالة (active / inactive)
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // الترتيب (من الأحدث إلى الأقدم)
        $query->orderBy('created_at', 'desc');

        // ترقيم النتائج (10 عناصر في الصفحة)
        $users = $query->paginate(10);

        // ✅ إرجاع البيانات كـ JSON
        return response()->json([
            'status'  => true,
            'message' => 'Users retrieved successfully.',
            'data'    => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    //     $validatordata = $request->validate([

    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|unique:users,email',
    //         'password' => 'required|string|min:6',
    //         // 'phone' => 'nullable|string|max:255|unique:users,phone|',
    //         // 'phone' => 'nullable|string|max:255|unique:users,phone|',
    //         'phone' => 'nullable|string|max:255|unique:users,phone|',


    //         // 'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],

    //         'address' => 'nullable|string',
    //         'country' => 'nullable|string',
    //         'city' => 'nullable|string',
    //         'zip_code' => 'nullable|string',
    //         'status' => ['nullable', Rule::in(['active', 'inactive'])],
    //     ]);


    //     // $user = User::create($validatordata);

    //     // $user = new User();
    //     // $user->name = $request->input('name');
    //     // $user->email = $request->input('email');
    //     // $user->password = Hash::make($request->input('password'));
    //     // $user->phone = $request->input('phone');
    //     // $user->address = $request->input('address');
    //     // $user->country = $request->input('country');
    //     // $user->city = $request->input('city');
    //     // $user->zip_code = $request->input('zip_code');
    //     // $user->status = $request->input('status') ?? 'active';
    //     // $user->save();


    //     $user = new User();
    //     $user->name = $request->input('name');
    //     $user->email = $request->input('email');
    //     $user->password = Hash::make($request->input('password'));
    //     $user->phone = $request->input('phone');
    //     $user->address = $request->input('address');
    //     $user->country = $request->input('country');
    //     $user->city = $request->input('city');
    //     $user->zip_code = $request->input('zip_code');
    //     $user->status = $request->input('status') ?? 'active';
    //     $user->save();


    //     // $user->name = $request->name;
    //     // $user->email = $request->email;
    //     // $user->password = Hash::make($request->password);
    //     // $user->phone = $request->phone;
    //     // $user->address = $request->address;
    //     // $user->country = $request->country;
    //     // $user->city = $request->city;
    //     // $user->zip_code = $request->zip_code;
    //     // $user->status = $request->status;
    //     // $user->save();



    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User created successfully.',
    //         'data' => $user
    //     ], 201);
    // }



    // public function store(Request $request)
    // {
    //     $validatordata = $request->validate([
    //         'name'     => 'required|string|max:255',
    //         'email'    => 'required|string|email|unique:users,email',
    //         'password' => 'required|string|min:6',
    //         // 'phone'    => 'nullable|string|max:20|unique:users,phone',
    //         // 'phone' => 'nullable|string|max:20|unique:users,phone',
    //         // 'phone' => 'nullable|string|max:20|unique:users,phone',
    //         // 'phone' => 'nullable|string|max:20|unique:users,phone' . $userId->id,
    //         // 'phone' => 'nullable|string|max:20|unique:users',
    //         'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|max:20',

    //         // 'phone' => [
    //         //     'nullable',
    //         //     'string',
    //         //     'max:20',
    //         // Rule::unique('users', 'phone')->where(function ($query) use ($request) {
    //         //     return $query->whereNotNull('phone');
    //         // }),
    //         // Rule::unique('users', 'phone')->where(function ($query) use ($request) {
    //         //     return $query->whereNotNull('phone');
    //         // }),
    //         // ],


    //         'address'  => 'nullable|string',
    //         'country'  => 'nullable|string',
    //         'city'     => 'nullable|string',
    //         'zip_code' => 'nullable|string',
    //         'status'   => ['nullable', Rule::in(['active', 'inactive'])],
    //     ]);

    //     $user = new User();
    //     $user->name     = $request->input('name');
    //     $user->email    = $request->input('email');
    //     $user->password = Hash::make($request->input('password'));
    //     $user->phone    = $request->input('phone');
    //     $user->address  = $request->input('address');
    //     $user->country  = $request->input('country');
    //     $user->city     = $request->input('city');
    //     $user->zip_code = $request->input('zip_code');
    //     $user->status   = $request->input('status') ?? 'active';
    //     $user->save();

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'User created successfully.',
    //         'data'    => $user,
    //     ], 201);
    // }




    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name'     => 'required|string|max:255',
    //         'email'    => 'required|string|email|unique:users,email',
    //         'password' => 'required|string|min:6',

    //         //  التحقق من صحة الرقم
    //         'phone' => [
    //             'nullable',
    //             'string',
    //             'max:9',
    //             'regex:/^[0-9+\-\s()]*$/', // أرقام ورموز الهاتف فقط
    //             'unique:users,phone',
    //         ],

    //         'address'  => 'nullable|string',
    //         'country'  => 'nullable|string',
    //         'city'     => 'nullable|string',
    //         'zip_code' => 'nullable|string',
    //         'status'   => ['nullable', Rule::in(['active', 'inactive'])],
    //     ]);

    //     //  تنظيف الرقم قبل الحفظ
    //     $cleanPhone = null;
    //     if ($request->filled('phone')) {
    //         // إزالة أي رموز غير رقمية (مثل +، -، فراغات، إلخ)
    //         $cleanPhone = preg_replace('/\D/', '', $request->input('phone'));

    //         // مثال: لو تريد فرض أن يبدأ برمز الدولة السعودية 967 إن لم يكن موجودًا
    //         if (!str_starts_with($cleanPhone, '967')) {
    //             $cleanPhone = '967' . ltrim($cleanPhone, '0');
    //         }
    //     }

    //     //  إنشاء المستخدم
    //     $user = new User();
    //     $user->name     = $validatedData['name'];
    //     $user->email    = $validatedData['email'];
    //     $user->password = Hash::make($validatedData['password']);
    //     $user->phone    = $cleanPhone; // الرقم المنظف
    //     $user->address  = $validatedData['address'] ?? null;
    //     $user->country  = $validatedData['country'] ?? null;
    //     $user->city     = $validatedData['city'] ?? null;
    //     $user->zip_code = $validatedData['zip_code'] ?? null;
    //     $user->status   = $validatedData['status'] ?? 'active';
    //     $user->save();

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'User created successfully.',
    //         'data'    => $user,
    //     ], 201);
    // }



    public function store(Request $request)
    {
        // ✅ التحقق من صحة البيانات
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',

            // 📞 التحقق من صحة الرقم
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]*$/', // فقط أرقام ورموز الهاتف المقبولة
                'unique:users,phone',
            ],

            'address'  => 'nullable|string',
            'country'  => 'nullable|string',
            'city'     => 'nullable|string',
            'zip_code' => 'nullable|string',
            'status'   => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        // 🧹 تنظيف رقم الهاتف قبل الحفظ
        $cleanPhone = $request->input('phone');

        if ($cleanPhone) {
            // إزالة أي رموز غير رقمية (مثل +، -، فراغات، إلخ)
            $cleanPhone = preg_replace('/[^0-9]/', '', $cleanPhone);

            // لو الرقم لا يبدأ بـ 967 (رمز اليمن مثلًا)، أضفه
            if (!str_starts_with($cleanPhone, '967')) {
                $cleanPhone = '967' . ltrim($cleanPhone, '0');
            }
        }

        // 🧠 إنشاء المستخدم
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

        // 🔁 النتيجة
        return response()->json([
            'status'  => true,
            'message' => 'User created successfully.',
            'data'    => $user,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 🔍 جلب المستخدم
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        }

        // ✅ إرجاع المستخدم
        return response()->json([
            'status'  => true,
            'message' => 'User retrieved successfully.',
            'data'    => $user,
        ]);
    }


    /**
     * Update the specified resource in storage.
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

        // ✅ التحقق من صحة البيانات
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

            // 📞 التحقق من صحة الرقم
            'phone' => [
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

        // 🧹 تنظيف رقم الهاتف قبل الحفظ
        $cleanPhone = $request->input('phone');
        if ($cleanPhone) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $cleanPhone);

            if (!str_starts_with($cleanPhone, '967')) {
                $cleanPhone = '967' . ltrim($cleanPhone, '0');
            }
        }

        // 🔄 تحديث البيانات
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

        // ✅ النتيجة
        return response()->json([
            'status'  => true,
            'message' => 'User updated successfully.',
            'data'    => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
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
