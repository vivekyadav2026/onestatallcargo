<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Couriers
        if (!Schema::hasTable('couriers')) {
            Schema::create('couriers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('api_code')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('couriers', function (Blueprint $table) {
                if (!Schema::hasColumn('couriers', 'name')) $table->string('name')->after('id');
                if (!Schema::hasColumn('couriers', 'api_code')) $table->string('api_code')->unique()->after('name');
                if (!Schema::hasColumn('couriers', 'is_active')) $table->boolean('is_active')->default(true)->after('api_code');
            });
        }

        // 2. Hubs
        if (!Schema::hasTable('hubs')) {
            Schema::create('hubs', function (Blueprint $table) {
                $table->id();
                $table->string('hub_code')->unique();
                $table->string('name');
                $table->string('city');
                $table->string('pincode');
                $table->integer('capacity')->default(0);
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('manager_id')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('hubs', function (Blueprint $table) {
                if (!Schema::hasColumn('hubs', 'hub_code')) {
                    $table->string('hub_code')->unique()->after('id');
                    $table->string('name')->after('hub_code');
                    $table->string('city');
                    $table->string('pincode');
                    $table->integer('capacity')->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->unsignedBigInteger('manager_id')->nullable();
                }
            });
        }

        // 3. Shipments
        if (!Schema::hasTable('shipments')) {
            Schema::create('shipments', function (Blueprint $table) {
                $table->id();
                $table->string('awb_number')->unique();
                $table->unsignedBigInteger('user_id');
                $table->string('receiver_name');
                $table->string('receiver_phone');
                $table->text('delivery_address');
                $table->string('delivery_city');
                $table->string('delivery_pincode');
                $table->decimal('weight_kg', 8, 2);
                $table->decimal('length_cm', 8, 2)->nullable();
                $table->decimal('width_cm', 8, 2)->nullable();
                $table->decimal('height_cm', 8, 2)->nullable();
                $table->boolean('is_cod')->default(false);
                $table->decimal('invoice_value', 10, 2);
                $table->decimal('total_amount', 10, 2);
                $table->string('status')->default('Manifested');
                $table->string('video_evidence_url')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('shipments', function (Blueprint $table) {
                if (!Schema::hasColumn('shipments', 'awb_number')) {
                    $table->string('awb_number')->unique()->after('id');
                    $table->unsignedBigInteger('user_id');
                    $table->string('receiver_name');
                    $table->string('receiver_phone');
                    $table->text('delivery_address');
                    $table->string('delivery_city');
                    $table->string('delivery_pincode');
                    $table->decimal('weight_kg', 8, 2);
                    $table->decimal('length_cm', 8, 2)->nullable();
                    $table->decimal('width_cm', 8, 2)->nullable();
                    $table->decimal('height_cm', 8, 2)->nullable();
                    $table->boolean('is_cod')->default(false);
                    $table->decimal('invoice_value', 10, 2);
                    $table->decimal('total_amount', 10, 2);
                    $table->string('status')->default('Manifested');
                    $table->string('video_evidence_url')->nullable();
                }
            });
        }

        // 4. Rates
        if (!Schema::hasTable('rates')) {
            Schema::create('rates', function (Blueprint $table) {
                $table->id();
                $table->string('zone_type');
                $table->decimal('base_rate', 8, 2);
                $table->decimal('additional_weight_rate', 8, 2);
                $table->decimal('rto_surcharge', 8, 2);
                $table->decimal('cod_surcharge', 8, 2);
                $table->timestamps();
            });
        } else {
            Schema::table('rates', function (Blueprint $table) {
                if (!Schema::hasColumn('rates', 'zone_type')) {
                    $table->string('zone_type')->after('id');
                    $table->decimal('base_rate', 8, 2);
                    $table->decimal('additional_weight_rate', 8, 2);
                    $table->decimal('rto_surcharge', 8, 2);
                    $table->decimal('cod_surcharge', 8, 2);
                }
            });
        }
    }

    public function down(): void
    {
        // ...
    }
};
