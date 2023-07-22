<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('event_title', 200)->index();
            $table->timestamp('event_start_date')->index();
            $table->timestamp('event_end_date')->index();
            $table->bigInteger('organization_id', false, true);
            $table->foreign('organization_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
