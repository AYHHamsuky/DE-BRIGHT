<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('student')->index()->after('email');
            $table->boolean('is_active')->default(true)->after('role');
        });

        // Everyone who could log in before roles existed was a site admin.
        DB::table('users')->update(['role' => 'super_admin']);

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('reg_number', 40)->unique();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('level', 40)->nullable();
            $table->string('admission_session', 20)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('state_of_origin', 60)->nullable();
            $table->string('address', 300)->nullable();
            $table->string('guardian_name', 120)->nullable();
            $table->string('guardian_phone', 30)->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
        });

        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('session', 20);
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('amount')->comment('In kobo');
            $table->string('description', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference', 60)->unique();
            $table->unsignedBigInteger('amount')->comment('In kobo');
            $table->string('method', 20);
            $table->string('status', 20)->default('pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('note', 500)->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('fees');
        Schema::dropIfExists('students');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
