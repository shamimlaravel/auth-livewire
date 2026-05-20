<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otp_tokens', function (Blueprint $table) {
            if (! Schema::hasColumn('otp_tokens', 'identifiable')) {
                $table->string('identifiable')->after('id')->nullable();
            }
            if (! Schema::hasColumn('otp_tokens', 'channel')) {
                $table->string('channel', 50)->default('sms')->after('identifiable');
            }
        });
    }

    public function down(): void
    {
        Schema::table('otp_tokens', function (Blueprint $table) {
            $table->dropColumn(['identifiable', 'channel']);
        });
    }
};
