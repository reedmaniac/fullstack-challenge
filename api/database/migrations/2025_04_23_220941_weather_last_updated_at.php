<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('current_weather');
            $table->timestamp('current_weather_last_checked_at')->nullable();
            $table->timestamp('current_weather_last_updated_at')->nullable();

            $table->text('forecast');
            $table->timestamp('forecast_last_checked_at')->nullable();
            $table->timestamp('forecast_last_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'current_weather',
                'current_weather_last_checked_at',
                'current_weather_last_updated_at',
                'forecast',
                'forecast_last_checked_at',
                'forecast_last_updated_at',
            ]);
        });
    }
};
