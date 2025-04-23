<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('reporting_manager_id')->constrained('users')->onDelete('cascade');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('projects');
    }
};

