<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id',100)->primary();
            $table->string('user_id',100)->nullable();
            $table->string('client_id',100)->nullable(false);
            $table->string('name','50')->nullable(value: true);
            $table->string('scopes')->nullable(true);
            $table->tinyInteger('revoked')->default(0);
            $table->dateTime('expires_at');
            $table->index(['user_id','client_id']);
            $table->index('user_id');
            $table->index('client_id');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_access_tokens');
    }
};
