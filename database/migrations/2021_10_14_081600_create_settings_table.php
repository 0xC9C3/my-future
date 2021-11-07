<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary('user_id');
            $table->timestamps();

            // smtp settings
            $table->string('smtp_host');
            $table->integer('smtp_port');
            $table->string('smtp_username');
            $table->string('smtp_password');
            $table->string('smtp_encryption');
            $table->string('smtp_from_address');
            $table->string('smtp_from_name');

            // tracking
            $table->boolean('track_web_access');
            $table->boolean('track_email_access');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
