<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    public const TABLE ='oauth_auth_codes';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->string('id',100)->primary();
            $table->bigInteger('user_id');
            $table->string('client_id',100)->nullable();
            $table->json('scopes');
            $table->tinyInteger('revoked')->default(0);
            $table->dateTime('expires_at');
            $table->index('user_id');
            $table->index('client_id');
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
