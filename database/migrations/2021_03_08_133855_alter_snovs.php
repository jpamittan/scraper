<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSnovs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snovs', function (Blueprint $table) {
            $table->dropColumn('snovcol');
            $table->string('payload', 500)->after('snov_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('snovs', function (Blueprint $table) {
            $table->string('snovcol')->after('snov_data');
            $table->dropColumn('payload');
        });
    }
}
