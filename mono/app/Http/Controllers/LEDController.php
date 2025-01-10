<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LEDController extends Controller
{
    private $espIp = 'http://192.168.178.80/leds';

    public function update(Request $request)
    {
        $state = $request->input('state');
        if (!in_array($state, ['red', 'yellow', 'green', 'all', 'off'])) {
            return response()->json(['error' => 'Invalid state'], 400);
        }

        try {
            $response = Http::post($this->espIp, ['state' => $state]);
            return response()->json(['message' => 'LED updated', 'esp_response' => $response->status()]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
