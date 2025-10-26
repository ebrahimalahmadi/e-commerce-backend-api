<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('product_id')->constrained()->cascadeOnDelete(); // يحذف الصور تلقائيًا عند حذف المنتج
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete(); // يحذف الصور تلقائيًا عند حذف المنتج
            $table->string('path'); // مسار الصورة داخل التخزين
            $table->boolean('is_primary')->default(false); // هل هي الصورة الرئيسية؟
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};

// ---------------------------- note ----------------------------
// constrained(): تفترض أن جدول products موجود وأن العمود المرتبط هو id.

// cascadeOnDelete(): عند حذف المنتج، تُحذف كل صوره تلقائيًا.

// path: يُخزن مسار الصورة (مثلاً: products/images/abc.jpg).

// is_primary: مفيد لتحديد الصورة الرئيسية للعرض في الواجهة.