<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LedController extends Controller
{

    public function index(){
        return view('camera2');
    }

    public function updateState(Request $request)
    {
        $validated = $request->validate([
            'state' => 'required|string|max:10'
        ]);

        $state = $validated['state'];

        // Simpan atau proses data sesuai kebutuhan Anda
        // Misalnya, log state ke file atau database
        \Log::info("Received state: $state");

        // Contoh: Mengembalikan respons sukses
        return response()->json([
            'message' => 'State received successfully',
            'state' => $state
        ]);
    }
}
