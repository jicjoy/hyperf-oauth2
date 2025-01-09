<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    public const TABLE ='oauth_refresh_tokens';
   
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->string('id',100)->nullable(false);
            $table->bigInteger('user_id')->nullable(true);
            $table->string('access_token_id',100)->nullable(false);
            $table->tinyInteger('revoked')->default(0);
            $table->dateTime('expires_at');
  
            $table->index('access_token_id');
 
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
