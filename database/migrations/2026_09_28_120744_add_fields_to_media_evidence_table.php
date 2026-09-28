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
        Schema::table('media_evidence', function (Blueprint $table) {
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // The rider or hub staff
            $table->string('type')->default('pickup'); // pickup, delivery, hub_inward, ndr_issue, damage_claim
            $table->string('media_type')->default('video'); // video, image
            $table->string('media_url');
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_evidence', function (Blueprint $table) {
            $table->dropForeign(['shipment_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['shipment_id', 'user_id', 'type', 'media_type', 'media_url', 'remarks']);
        });
    }
};
