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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->string('article_code', 50)->nullable()->index();
            $table->string('manufacture_code')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->unsignedBigInteger('product_type_id')->nullable()->index();
            $table->string('short_description')->nullable();
            $table->decimal('mrp', 10, 2)->nullable()->index();
            $table->decimal('price', 10, 2)->nullable()->index();
            $table->decimal('sale_price', 10, 2)->nullable()->index();
            $table->datetime('sale_start')->nullable()->index();
            $table->datetime('sale_end')->nullable();
            $table->decimal('supplier_price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->string('season', 25)->nullable();
            $table->string('supplier_ref')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable()->index();
            $table->date('in_date')->nullable();
            $table->date('last_date')->nullable();
            $table->unsignedBigInteger('size_scale_id')->nullable()->index();
            $table->unsignedBigInteger('min_size_id')->nullable()->index();
            $table->unsignedBigInteger('max_size_id')->nullable()->index();
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->index();
            $table->boolean('are_barcodes_printed')->default(0); // To detect if article being created, but not printed yet
            $table->boolean('barcodes_printed_for_all')->default(0); // To detect if product articles were printed before, but not printed for new quantity
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('product_type_id')->references('id')->on('product_types')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null');
            $table->foreign('size_scale_id')->references('id')->on('size_scales')->onDelete('cascade');
            $table->foreign('min_size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->foreign('max_size_id')->references('id')->on('sizes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
