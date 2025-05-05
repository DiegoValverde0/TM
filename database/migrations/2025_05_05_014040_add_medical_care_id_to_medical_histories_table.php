<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMedicalCareIdToMedicalHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('medical_care_id')->nullable()->after('id');

            // Si deseas establecer una relación con la tabla medical_cares
            $table->foreign('medical_care_id')->references('id')->on('medical_cares')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            $table->dropForeign(['medical_care_id']);
            $table->dropColumn('medical_care_id');
        });
    }
}