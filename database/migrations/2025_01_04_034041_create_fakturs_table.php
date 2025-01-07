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
        Schema::create('fakturs', function (Blueprint $table) {
            $table->id(); // bisa diganti sesuai dengan kebutuhan, contoh $table->id('id_faktur')
            $table->string('kode_faktur');
            $table->date('tanggal_faktur');
            $table->string('kode_customer');
            $table->foreignId('customer_id')->constrained('customers','id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('ket_faktur');
            $table->integer('total');
            $table->integer('nominal_charge');
            $table->integer('charge');
            $table->integer('total_final');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fakturs');
    }
};
