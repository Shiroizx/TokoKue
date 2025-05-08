<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyMidtransSignature
{
    public function handle(Request $request, Closure $next)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $json = json_decode($request->getContent(), true);
        
        if (!$json) {
            return response()->json(['status' => 'error', 'message' => 'Invalid JSON'], 400);
        }
        
        if (!isset($json['order_id']) || !isset($json['status_code']) || !isset($json['gross_amount'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }
        
        // Verifikasi signature
        $signature = hash('sha512', 
            $json['order_id'] . 
            $json['status_code'] . 
            $json['gross_amount'] . 
            $serverKey
        );
        
        $receivedSignature = $request->header('X-Midtrans-Signature');
        
        // Skip verifikasi untuk testing atau jika header signature tidak ada
        if (!$receivedSignature || env('APP_ENV') === 'local') {
            return $next($request);
        }
        
        if (hash_equals($signature, $receivedSignature)) {
            return $next($request);
        }
        
        return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
    }
}