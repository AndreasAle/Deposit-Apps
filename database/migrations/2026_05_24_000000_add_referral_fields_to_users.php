<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 20)->nullable()->unique()->after('phone');
            $table->unsignedBigInteger('referred_by_user_id')->nullable()->after('referral_code');

            // opsional (buat dashboard cepat)
            $table->decimal('referral_earned_total', 18, 2)->default(0)->after('saldo');

            $table->foreign('referred_by_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by_user_id']);
            $table->dropColumn(['referral_code', 'referred_by_user_id', 'referral_earned_total']);
        });
    }
};
