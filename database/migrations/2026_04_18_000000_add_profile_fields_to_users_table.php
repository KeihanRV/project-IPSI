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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('password');
            $table->date('birth_date')->nullable()->after('phone_number');
            $table->string('birthplace')->nullable()->after('birth_date');
            $table->string('province')->nullable()->after('birthplace');
            $table->string('city')->nullable()->after('province');
            $table->string('district')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('district');
            $table->text('address')->nullable()->after('postal_code');
            $table->string('profile_picture')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'birth_date',
                'birthplace',
                'province',
                'city',
                'district',
                'postal_code',
                'address',
                'profile_picture',
            ]);
        });
    }
};
