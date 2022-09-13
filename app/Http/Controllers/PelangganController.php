<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Exception;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    //
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return response()->json([
            "count" => count($pelanggan),
            "success" => true,
            "data" => $pelanggan,
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $pelanggan = Pelanggan::create([
                "id_pelanggan" => '',
                "nama" => $request["nama"],
                "domisili" => $request["domisili"],
                "jenis_kelamin" => $request["jenis_kelamin"],
            ]);

            return response()->json([
                "data" => $pelanggan,
                "success" => true,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e,
                "success" => false,
            ], 400);
        }
    }

    public function show($pelanggan_id)
    {
        //
        try {
            $pelanggan = Pelanggan::where('id_pelanggan', '=', $pelanggan_id)->firstOrFail();
            return response()->json([
                "data" => $pelanggan,
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Tidak ada pelanggan dengan id " . $pelanggan_id,
                "success" => false,
            ], 400);
        }
    }

    public function update(Request $request, $pelanggan_id)
    {
        try {
            $update_pelanggan = Pelanggan::where('id_pelanggan', '=', $pelanggan_id)->firstOrFail();
            $update_pelanggan->update([
                "nama" => $request["nama"],
                "domisili" => $request["domisili"],
                "jenis_kelamin" => $request["jenis_kelamin"],
            ]);

            return response()->json([
                "data" => $update_pelanggan,
                "success" => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false,
            ], 400);
        }
    }

    public function destroy($pelanggan_id)
    {
        try {
            $hapus_pelanggan = Pelanggan::where('id_pelanggan', '=', $pelanggan_id)->firstOrFail();
            $hapus_pelanggan->delete();

            return response()->json([
                "message" => "Berhasil menghapus pelanggan dengan id " . strtoupper($pelanggan_id),
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
