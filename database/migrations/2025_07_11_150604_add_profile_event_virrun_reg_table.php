<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileEventVirrunRegTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_virrun_reg', function (Blueprint $table) {
            $table->string('profileUrl')->nullable()->after('ebib');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_virrun_reg', function (Blueprint $table) {
            $table->dropColumn('profileUrl');
        });
    }
}
