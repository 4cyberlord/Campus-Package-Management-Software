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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number')->unique();
            $table->string('sender_name');
            $table->string('courier');
            $table->string('package_type');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'received', 'ready_for_pickup', 'picked_up'])->default('pending');
            $table->date('expected_pickup_date')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->boolean('authorized_pickup')->default(false);
            $table->string('authorized_person_name')->nullable();
            $table->string('authorized_person_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
