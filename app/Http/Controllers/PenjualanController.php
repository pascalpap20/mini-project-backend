<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Models\Barang;
use App\Models\Penjualan;
use Exception;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $penjualan = Penjualan::with(['item_penjualan', 'item_penjualan.barang'])->get();
        return response()->json([
            "success" => true,
            "count" => count($penjualan),
            "data" => $penjualan,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePenjualanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePenjualanRequest $request)
    {
        //
        DB::beginTransaction();
        try {
            $jumlah_subtotal = 0;
            $validated = $request->validated();
            $penjualan = Penjualan::create([
                "id_nota" => '',
                "kode_pelanggan" => strtoupper($validated["kode_pelanggan"]),
                "subtotal" => 0,
            ]);
            foreach ($validated["item_penjualan"] as $item) {
                $penjualan->item_penjualan()->create([
                    "nota" => $penjualan["id_nota"],
                    "kode_barang" => strtoupper($item["kode_barang"]),
                    "qty" => $item["qty"],
                ]);

                $barang = Barang::where('kode', '=', strtoupper($item["kode_barang"]))->firstOrFail();
                $jumlah_subtotal += $barang["harga"] * $item["qty"];
            }

            $penjualan->subtotal = $jumlah_subtotal;
            $penjualan->save();

            DB::commit();

            return response()->json([
                "data" => "Berhasil menambahkan penjualan",
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function show($penjualan_id)
    {
        //
        try {
            $penjualan = Penjualan::where('id_nota', '=', $penjualan_id)
                ->with(['item_penjualan', 'item_penjualan.barang'])
                ->firstOrFail();

            return response()->json([
                "success" => true,
                "data" => $penjualan,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePenjualanRequest  $request
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePenjualanRequest $request, $penjualan_id)
    {
        //
        try {
            $validated = $request->validated();
            $update_penjualan = Penjualan::where('id_nota', '=', $penjualan_id)->firstOrFail();
            $update_penjualan->update([
                "kode_pelanggan" => strtoupper($validated["kode_pelanggan"]),
                "subtotal" => $validated["subtotal"],
            ]);

            return response()->json([
                "data" => $update_penjualan,
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy($penjualan_id)
    {
        //
        try {
            $hapus_penjualan = Penjualan::where('id_nota', '=', $penjualan_id)->firstOrFail();
            $hapus_penjualan->delete();

            return response()->json([
                "data" => "Berhasil menghapus barang dengan id " . strtoupper($penjualan_id),
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);
        }

    }
}
