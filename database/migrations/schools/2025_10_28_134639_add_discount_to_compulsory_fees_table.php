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
        Schema::table('compulsory_fees', function (Blueprint $table) {
            $table->double('discount', 8, 2)->default(0)->after('due_charges');
			$table->enum('discount_type', ['fixed', 'percentage'])->default('fixed')->after('due_charges');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compulsory_fees', function (Blueprint $table) {
            $table->dropColumn('discount');
			$table->dropColumn('discount_type');
			
        });
    }
};
