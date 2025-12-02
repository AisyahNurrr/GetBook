<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void {
    Schema::table('transactions', function (Blueprint $table) {
        $table->string('telepon')->nullable()->after('user_id');
        $table->text('alamat')->nullable()->after('telepon');
        $table->string('metode_bayar')->nullable()->after('alamat');
    });
}

    public function down(): void {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['telepon', 'alamat', 'metode_bayar']);
        });
    }
};
