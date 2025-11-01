<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * عرض جميع الطلبات (للادمن)
     */
    public function index()
    {
        // $orders = Order::with(['user', 'items.product'])
        //     ->latest()
        //     ->paginate(10);
        $orders = Order::with(['user', 'items.product'])->latest()->get();

        // return OrderResource::collection($orders);
        return apiResponse(
            200,
            'Orders fetched successfully',
            OrderResource::collection($orders)
        );
    }

    /**
     * إنشاء طلب جديد
     */
    public function store(StoreOrderRequest $request)
    {
        $orderData = $request->validated();

        // حساب الإجماليات
        $subtotal = 0;
        $orderItems = [];

        foreach ($orderData['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $unitPrice = $product->price;
            $totalPrice = $item['quantity'] * $unitPrice;

            $subtotal += $totalPrice;

            $orderItems[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ];
        }

        $total = $subtotal + ($orderData['shipping_cost'] ?? 0) + ($orderData['tax_amount'] ?? 0);

        // إنشاء الطلب
        $order = Order::create([
            'user_id' => auth()->id(),
            'subtotal' => $subtotal,
            'shipping_cost' => $orderData['shipping_cost'] ?? 0,
            'tax_amount' => $orderData['tax_amount'] ?? 0,
            'total' => $total,
            'notes' => $orderData['notes'] ?? null,
        ]);

        // إضافة عناصر الطلب
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        // تحميل العلاقات للإرجاع
        $order->load(['user', 'items.product']);

        return response()->json([
            'message' => 'Order created successfully',
            'data' => new OrderResource($order)
        ], 201);
    }

    /**
     * عرض طلب محدد
     */
    public function show(Order $order): OrderResource
    {
        $order->load(['user', 'items.product']);

        // التأكد من أن المستخدم يملك الطلب أو هو ادمن
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return new OrderResource($order);
    }

    /**
     * تحديث الطلب
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        // فقط الادمن يمكنه تحديث الطلب
        // if (auth()->user()->role !== 'admin') {
        //     abort(403, 'Only admin can update orders');
        // }

        $order->update($request->validated());

        return response()->json([
            'message' => 'Order updated successfully',
            'data' => new OrderResource($order->load(['user', 'items.product']))
        ]);
    }

    /**
     * حذف الطلب
     */
    // public function destroy(Order $order)
    // {
    //     // فقط الادمن يمكنه حذف الطلب
    //     // if (auth()->user()->role !== 'admin') {
    //     //     abort(403, 'Only admin can delete orders');
    //     // }

    //     $order->delete();

    //     return response()->json([
    //         'message' => 'Order deleted successfully'
    //     ]);
    // }
    // ---------------------------------
    public function destroy(Order $order)
    {
        // فقط الادمن يمكنه حذف الطلب
        // if (auth()->user()->role !== 'admin') {
        //     abort(403, 'Only admin can delete orders');
        // }

        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        // فقط الادمن يمكنه تحديث الحالة
        // if (auth()->user()->role !== 'admin') {
        //     abort(403, 'Only admin can update order status');
        // }

        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'data' => new OrderResource($order->load(['user', 'items.product']))
        ]);
    }

    /**
     * طلبات المستخدم الحالي
     */
    public function userOrders()
    {
        $orders = Order::with(['user', 'items.product'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }
}
