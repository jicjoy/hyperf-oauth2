<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    public const TABLE ='oauth_clients';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned()->nullable(true);
            $table->string('name','100')->nullable(false);
            $table->string('secret',100)->nullable(false);
            $table->string('redirect');
            $table->tinyInteger('personal_access_client')->default(0);
            $table->tinyInteger('password_client')->default(0);
            $table->tinyInteger('revoked')->default(0);
            $table->tinyInteger('is_confidential')->default(0);
 
            $table->timestamps();
            $table->index(['name','user_id']);
            $table->index('user_id');
 
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
