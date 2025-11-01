<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItem\StoreOrderItemRequest;
use App\Http\Requests\OrderItem\UpdateOrderItemRequest;
use App\Http\Resources\OrderItemResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * إضافة عنصر لطلب
     */
    public function store(StoreOrderItemRequest $request, Order $order)
    {
        // التأكد من أن المستخدم يملك الطلب أو هو ادمن
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validated();
        $product = Product::findOrFail($validated['product_id']);

        $orderItem = $order->items()->create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $product->price,
            'total_price' => $validated['quantity'] * $product->price,
        ]);

        // تحديث إجماليات الطلب
        $order->updateTotals();

        return response()->json([
            'message' => 'Order item added successfully',
            'data' => new OrderItemResource($orderItem->load('product'))
        ], 201);
    }

    /**
     * تحديث عنصر طلب
     */
    public function update(UpdateOrderItemRequest $request, Order $order, OrderItem $item)
    {
        // التأكد من أن العنصر يتبع الطلب
        if ($item->order_id !== $order->id) {
            abort(404, 'Order item not found');
        }

        // التأكد من أن المستخدم يملك الطلب أو هو ادمن
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validated();

        $item->update([
            'quantity' => $validated['quantity'],
            'total_price' => $validated['quantity'] * $item->unit_price,
        ]);

        // تحديث إجماليات الطلب
        $order->updateTotals();

        return response()->json([
            'message' => 'Order item updated successfully',
            'data' => new OrderItemResource($item->load('product'))
        ]);
    }

    /**
     * حذف عنصر طلب
     */
    public function destroy(Order $order, OrderItem $item)
    {
        // التأكد من أن العنصر يتبع الطلب
        if ($item->order_id !== $order->id) {
            abort(404, 'Order item not found');
        }

        // التأكد من أن المستخدم يملك الطلب أو هو ادمن
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $item->delete();

        // تحديث إجماليات الطلب
        $order->updateTotals();

        return response()->json([
            'message' => 'Order item deleted successfully'
        ]);
    }
}
