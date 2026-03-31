// database/migrations/xxxx_xx_xx_xxxxxx_create_referral_commissions_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('referrer_id');        // penerima komisi
            $table->unsignedBigInteger('referred_user_id');   // yang melakukan deposit/buy

            $table->string('source_type', 20); // 'deposit' | 'buy'
            $table->unsignedBigInteger('source_id'); // deposit_id / user_investment_id

            $table->decimal('base_amount', 18, 2);
            $table->decimal('rate', 6, 4); // 0.0500 / 0.0300
            $table->decimal('commission_amount', 18, 2);

            $table->timestamps();

            $table->index(['referrer_id']);
            $table->index(['referred_user_id']);
            $table->unique(['source_type', 'source_id']); // anti dobel payout

            $table->foreign('referrer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('referred_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
    }
};
