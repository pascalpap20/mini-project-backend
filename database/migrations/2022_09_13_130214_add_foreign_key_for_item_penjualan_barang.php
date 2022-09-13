<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyForItemPenjualanBarang extends Migration
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
            $table->foreign('kode_barang')->references('kode')->on('barang');

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
            $table->dropForeign('kode_barang');
            $table->dropIndex('kode_barang');
            $table->dropColumn('kode_barang');

        });
    }
}
