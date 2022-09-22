<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use App\Models\Barang;
use Exception;

class BarangController extends Controller
{
    //
    public function index()
    {
        $barang = Barang::all();

        return response()->json([
            "count" => count($barang),
            "success" => true,
            "data" => $barang,
        ], 200);
    }

    public function show($barang_id)
    {
        try {
            $barang = Barang::where('kode', '=', $barang_id)->firstOrFail();
            return response()->json([
                "data" => $barang,
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ]);
        }
    }

    public function store(StoreBarangRequest $request)
    {

        try {
            $validated = $request->validated();
            $barang = Barang::create([
                "kode" => '',
                "nama" => $validated["nama"],
                "kategori" => $validated["kategori"],
                "harga" => $validated["harga"],
                "warna" => $validated["warna"],
            ]);

            return response()->json([
                "data" => $barang,
                "success" => true,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e,
                "success" => false,
            ], 400);
        }
    }

    public function update(UpdateBarangRequest $request, $barang_id)
    {
        try {
            $validated = $request->validated();
            $update_barang = Barang::where('kode', '=', $barang_id)->firstOrFail();
            $update_barang->update([
                "nama" => $validated["nama"],
                "kategori" => $validated["kategori"],
                "harga" => $validated["harga"],
                "warna" => $validated["warna"],
            ]);

            return response()->json([
                "data" => $update_barang,
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);
        }
    }

    public function destroy($barang_id)
    {
        try {
            $hapus_barang = Barang::where('kode', '=', $barang_id)->firstOrFail();
            $hapus_barang->delete();

            return response()->json([
                "message" => "Berhasil menghapus barang dengan id " . strtoupper($barang_id),
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
