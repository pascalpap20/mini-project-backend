<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemPenjualanRequest;
use App\Http\Requests\UpdateItemPenjualanRequest;
use App\Models\Barang;
use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemPenjualanController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $nota_id = $request->query('nota_id');

        $item_penjualan = ItemPenjualan::with('barang_single')->get();
        if ($nota_id) {
            $item_penjualan = ItemPenjualan::where('nota', '=', $nota_id)->with('barang_single')->get();
        }
        return response()->json([
            "success" => true,
            "count" => count($item_penjualan),
            "data" => $item_penjualan,
        ], 200);
    }

    public function store(StoreItemPenjualanRequest $request, $penjualan_id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $penjualan = Penjualan::where('id_nota', '=', $penjualan_id)->firstOrFail();
            $update_subtotal = $penjualan["subtotal"];

            foreach ($validated["item_penjualan"] as $item) {
                $cek_item = $penjualan->item_penjualan()
                    ->where('kode_barang', '=', $item["kode_barang"])
                    ->first();
                // $qty = 0;
                if ($cek_item && strtoupper($item["kode_barang"]) == strtoupper($cek_item["kode_barang"])) {
                    $qty = $cek_item["qty"] + $item["qty"];
                    $cek_item->update([
                        // "kode_barang" => $item["kode_barang"],
                        "qty" => $qty,
                    ]);
                } else {
                    // $qty = $item["qty"];
                    $penjualan->item_penjualan()->create([
                        "kode_barang" => strtoupper($item["kode_barang"]),
                        "qty" => $item["qty"],
                    ]);
                }

                $barang = Barang::where('kode', '=', strtoupper($item["kode_barang"]))->firstOrFail();
                $update_subtotal += $barang["harga"] * $item["qty"];
            }

            $penjualan["subtotal"] = $update_subtotal;
            $penjualan->save();
            // return $penjualan;
            DB::commit();
            return response()->json([
                "message" => "Berhasil menambahkan penjualan",
                "success" => true,
            ], 201);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);

        }
    }

    // public function show($penjualan_id, $item_penjualan_id)
    // {
    //     try {
    //         $penjualan = Penjualan::where('id_nota', '=', $penjualan_id)->firstOrFail();
    //         $item_penjualan = $penjualan->item_penjualan()->where('nota', '=', $item_penjualan_id)->get();
    //         return response()->json([
    //             "data" => $item_penjualan,
    //             "success" => true,
    //         ], 200);

    //     } catch (Exception $e) {
    //         return response()->json([
    //             "message" => $e->getMessage(),
    //             "success" => false,
    //         ], 400);

    //     }
    // }

    public function update(UpdateItemPenjualanRequest $request, $penjualan_id, $kode_barang_id)
    {
        DB::beginTransaction();
        try {
            $jumlah_subtotal = 0;
            $validated = $request->validated();
            $item_penjualan = ItemPenjualan::where([
                ['nota', '=', strtoupper($penjualan_id)],
                ['kode_barang', '=', strtoupper($kode_barang_id)]])
                ->firstOrFail();
            if (strtoupper($validated["kode_barang"]) != strtoupper($item_penjualan["kode_barang"])) {
                $item_penjualan_merged = ItemPenjualan::where([
                    ['nota', '=', strtoupper($penjualan_id)],
                    ['kode_barang', '=', strtoupper($validated["kode_barang"])]])
                    ->firstOrFail();

                $item_penjualan_merged->update([
                    "kode_barang" => strtoupper($validated["kode_barang"]),
                    "qty" => $validated["qty"] + $item_penjualan_merged["qty"],
                ]);

                $item_penjualan->delete();
            } else {
                $item_penjualan->update([
                    "kode_barang" => strtoupper($validated["kode_barang"]),
                    "qty" => $validated["qty"],
                ]);
            }

            $hitung_barang = ItemPenjualan::where('nota', '=', $penjualan_id)->get();
            foreach ($hitung_barang as $item) {
                $barang = Barang::where('kode', '=', strtoupper($item["kode_barang"]))->firstOrFail();
                $jumlah_subtotal += $barang["harga"] * $item["qty"];
            }

            Penjualan::where('id_nota', '=', $penjualan_id)->update([
                "subtotal" => $jumlah_subtotal,
            ]);

            DB::commit();

            return response()->json([
                "message" => "Berhasil mengubah penjualan",
                "success" => true,
            ], 200);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);

        }
    }

    public function destroy($penjualan_id, $kode_barang_id)
    {
        DB::beginTransaction();
        try {
            $jumlah_subtotal = 0;
            $item_penjualan = ItemPenjualan::where([
                ['nota', '=', strtoupper($penjualan_id)],
                ['kode_barang', '=', strtoupper($kode_barang_id)]])
                ->firstOrFail();

            $item_penjualan->delete();

            $hitung_barang = ItemPenjualan::where('nota', '=', $penjualan_id)->get();
            foreach ($hitung_barang as $item) {
                $barang = Barang::where('kode', '=', strtoupper($item["kode_barang"]))->firstOrFail();
                $jumlah_subtotal += $barang["harga"] * $item["qty"];
            }

            Penjualan::where('id_nota', '=', $penjualan_id)->update([
                "subtotal" => $jumlah_subtotal,
            ]);

            DB::commit();

            return response()->json([
                "message" => "Berhasil menghapus penjualan",
                "success" => true,
            ], 200);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);

        }
    }
}
