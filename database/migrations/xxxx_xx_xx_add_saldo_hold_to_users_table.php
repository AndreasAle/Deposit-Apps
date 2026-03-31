<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      if (!Schema::hasColumn('users', 'saldo_hold')) {
        $table->unsignedBigInteger('saldo_hold')->default(0)->after('saldo');
      }
    });
  }

  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      if (Schema::hasColumn('users', 'saldo_hold')) {
        $table->dropColumn('saldo_hold');
      }
    });
  }
};
