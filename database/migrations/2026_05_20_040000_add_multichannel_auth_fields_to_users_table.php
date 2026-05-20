<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('two_factor_confirmed_at');
            }
            if (! Schema::hasColumn('users', 'two_factor_channel')) {
                $table->string('two_factor_channel')->nullable()->after('two_factor_enabled');
            }
            if (! Schema::hasColumn('users', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->unique()->after('phone');
            }
            if (! Schema::hasColumn('users', 'telegram_chat_id')) {
                $table->string('telegram_chat_id')->nullable()->unique()->after('whatsapp_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_enabled',
                'two_factor_channel',
                'whatsapp_number',
                'telegram_chat_id',
            ]);
        });
    }
};
