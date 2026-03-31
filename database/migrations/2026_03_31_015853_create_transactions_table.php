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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();

        // relasi ke user & item
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('item_id')->constrained()->cascadeOnDelete();

        // tanggal pinjam & kembali
        $table->date('borrow_date');
        $table->date('return_date')->nullable();

        // status transaksi
        $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
