<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirController extends Controller 
{ 
    public function getProvinces() 
    { 
        try {
            $response = Http::withHeaders([ 
                'key' => env('RAJAONGKIR_API_KEY') 
            ])->get(env('RAJAONGKIR_BASE_URL') . '/province'); 

            // Log jika request tidak berhasil
            if (!$response->successful()) {
                Log::error('RajaOngkir Provinces API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return response()->json($response->json()); 
        } catch (\Exception $e) {
            // Log error yang terjadi
            Log::error('RajaOngkir Provinces Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat provinsi: ' . $e->getMessage()
            ], 500);
        }
    } 
 
    public function getCities(Request $request) 
    { 
        try {
            $provinceId = $request->input('province_id'); 

            // Validasi input
            if (!$provinceId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Province ID tidak boleh kosong'
                ], 400);
            }

            $response = Http::withHeaders([ 
                'key' => env('RAJAONGKIR_API_KEY') 
            ])->get(env('RAJAONGKIR_BASE_URL') . '/city', [ 
                'province' => $provinceId 
            ]); 

            // Log jika request tidak berhasil
            if (!$response->successful()) {
                Log::error('RajaOngkir Cities API Error', [
                    'province_id' => $provinceId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
 
            return response()->json($response->json()); 
        } catch (\Exception $e) {
            // Log error yang terjadi
            Log::error('RajaOngkir Cities Exception', [
                'province_id' => $request->input('province_id'),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat kota: ' . $e->getMessage()
            ], 500);
        }
    } 
 
    public function getCost(Request $request) 
    { 
        try {
            $origin = $request->input('origin'); 
            $destination = $request->input('destination'); 
            $weight = $request->input('weight'); 
            $courier = $request->input('courier'); 

            // Validasi input
            $validationErrors = [];
            if (!$origin) $validationErrors[] = 'Origin';
            if (!$destination) $validationErrors[] = 'Destination';
            if (!$weight) $validationErrors[] = 'Weight';
            if (!$courier) $validationErrors[] = 'Courier';

            if (!empty($validationErrors)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Input tidak valid: ' . implode(', ', $validationErrors)
                ], 400);
            }
 
            $response = Http::withHeaders([ 
                'key' => env('RAJAONGKIR_API_KEY') 
            ])->post(env('RAJAONGKIR_BASE_URL') . '/cost', [ 
                'origin' => $origin, 
                'destination' => $destination, 
                'weight' => $weight, 
                'courier' => $courier, 
            ]); 

            // Log jika request tidak berhasil
            if (!$response->successful()) {
                Log::error('RajaOngkir Cost API Error', [
                    'origin' => $origin,
                    'destination' => $destination,
                    'weight' => $weight,
                    'courier' => $courier,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
 
            return response()->json($response->json()); 
        } catch (\Exception $e) {
            // Log error yang terjadi
            Log::error('RajaOngkir Cost Exception', [
                'origin' => $request->input('origin'),
                'destination' => $request->input('destination'),
                'weight' => $request->input('weight'),
                'courier' => $request->input('courier'),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghitung biaya: ' . $e->getMessage()
            ], 500);
        }
    } 
}