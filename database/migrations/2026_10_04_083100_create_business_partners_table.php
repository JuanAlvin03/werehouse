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
        Schema::create('business_partners', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->enum('partner_type', ['SUPPLIER', 'CUSTOMER', 'BOTH']);
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('partner_type');
            $table->index('is_active');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('business_partner_id')->nullable()->after('to_warehouse_id');
            $table->foreign('business_partner_id')
                ->references('id')
                ->on('business_partners')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['business_partner_id']);
            $table->dropColumn('business_partner_id');
        });

        Schema::dropIfExists('business_partners');
    }
};
