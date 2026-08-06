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
        Schema::create('meritorious_families', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // Thuong binh, Liet si, Benh binh, Nguoi co cong, Gia dinh chinh sach
            $table->foreignId('neighborhood_id')->nullable()->constrained('neighborhoods')->nullOnDelete();
            $table->string('address')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('benefit_details')->nullable(); // Thong tin qua tang, che do
            $table->foreignId('celebration_event_id')->constrained('celebration_events')->cascadeOnDelete();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meritorious_families');
    }
};
