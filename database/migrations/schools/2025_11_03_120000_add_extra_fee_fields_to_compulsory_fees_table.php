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
            $table->string('extra_fee_name', 255)->nullable()->after('discount_type')->comment('Extra fee description/name');
            $table->double('extra_fee', 8, 2)->default(0)->nullable()->after('extra_fee_name')->comment('Extra fee amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compulsory_fees', function (Blueprint $table) {
            $table->dropColumn(['extra_fee_name', 'extra_fee']);
        });
    }
};

