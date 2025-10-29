<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


/**
 * ✅ دالة للردود الناجحة (Success Responses)
 */

if (!function_exists('apiResponse')) {

    /**
     * تقوم بإرجاع رد موحّد لنجاح العملية
     *
     * تدعم:
     * - Resource Collections (مع Pagination)
     * - أي بيانات عادية (Array / Object)
     *
     * @param int $statusCode كود HTTP (مثل 200 أو 201)
     * @param string $message الرسالة النصية للرد
     * @param mixed|null $data البيانات (Resource أو Array أو Object)
     * @return JsonResponse
     */
    function apiResponse(int $statusCode, string $message, $data = null): JsonResponse
    {
        // حالة النجاح: أي كود < 400 يعتبر نجاح
        $status = $statusCode < 400;

        // هيكل الرد الأساسي
        $response = [
            'code' => $statusCode,
            'status' => $status,
            'message' => $message,
        ];

        // 🔹 في حال كانت البيانات تحتوي على pagination
        // ⚡ إذا كانت البيانات Resource Collection (مثلاً: CategoryResource::collection($categories))
        if ($data instanceof AnonymousResourceCollection) {
            // نحول Resource إلى Array
            $resourceArray = $data->response()->getData(true);

            // نضع العناصر ضمن 'data'
            $response['data'] = $resourceArray['data'];

            // إذا كان هناك Pagination meta، نحتفظ فقط بالحقول المهمة
            if (isset($resourceArray['meta'])) {
                $response['meta'] = [
                    'current_page' => $resourceArray['meta']['current_page'] ?? 1,
                    'per_page'     => $resourceArray['meta']['per_page'] ?? count($resourceArray['data']),
                    'total'        => $resourceArray['meta']['total'] ?? count($resourceArray['data']),
                    'last_page'    => $resourceArray['meta']['last_page'] ?? 1,
                ];
            }
        }
        // ⚡ إذا كانت بيانات عادية (Array / Object)
        elseif (!is_null($data)) {
            $response['data'] = $data;
        }

        // إرجاع الرد النهائي بصيغة JSON
        return response()->json($response, $statusCode);
    }
}


/**
 * ❌ دالة للردود الفاشلة (Error Responses)
 */

if (!function_exists('apiErrorResponse')) {
    /**
     *      *
     * @param int $statusCode كود HTTP
     * @param string $message رسالة الخطأ
     * @param mixed|null $errors تفاصيل الأخطاء
     * @return JsonResponse
     */
    function apiErrorResponse(int $statusCode, string $message, $errors = null): JsonResponse
    {
        $response = [
            'code' => $statusCode,
            'status' => false,
            'message' => $message,
        ];

        // إضافة تفاصيل الأخطاء إذا كانت موجودة
        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}



/**
 * ⚠️ دالة مخصصة لأخطاء التحقق من البيانات (Validation Errors)
 */

if (!function_exists('apiValidationError')) {
    /**
     * تستخدم apiErrorResponse() مع كود 422
     *
     * @param array|string $errors تفاصيل الأخطاء
     * @param string $message الرسالة الافتراضية: Validation failed
     * @return JsonResponse
     */
    function apiValidationError($errors, string $message = 'Validation failed'): JsonResponse
    {
        return apiErrorResponse(422, $message, $errors);
    }
}




// ==================================
// this function can be used like this anywhere in the app:
// return apiResponse(200, 'Categories retrieved successfully', $categories);
// return apiErrorResponse(404, 'Category not found');
// ==================================
// After adding this file, run the following command to update the autoloader:
// this will make the helper function available throughout the application
// composer dump-autoload
// ==================================