<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyForItemPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_penjualan', function (Blueprint $table) {
            //
            $table->foreign('nota')->references('id_nota')->on('penjualan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_penjualan', function (Blueprint $table) {
            //
            $table->dropForeign('nota');
            $table->dropIndex('nota');
            $table->dropColumn('nota');

        });
    }
}
