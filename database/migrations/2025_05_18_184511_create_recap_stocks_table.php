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
        Schema::create('recap_stocks', function (Blueprint $table) {
            $table->id();
            $table->date('period');
            $table->string('type_product');
            $table->integer('id_master_product');
            $table->string('qty_start');
            $table->string('qty_end');
            $table->string('weight_start');
            $table->string('weight_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recap_stocks');
    }
};
