<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function history()
    {
        // Ambil semua pesanan user yang telah login
        $orders = Order::where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('user.orders.history', compact('orders'));
    }
    
    // Method untuk detail pesanan dengan eager loading untuk product images
    public function show($id)
    {
        $order = Order::findOrFail($id);
        
        // Cek apakah ada parameter pembayaran
        if(request()->has('payment_status') && request()->payment_status === 'success') {
            // Update status pembayaran
            $order->payment_method = request()->payment_method;
            $order->payment_status = 'paid';
            
            // Update status order sesuai alur bisnis Anda
            if($order->status === 'pending' || $order->status === 'pending_payment') {
                $order->status = 'processing';
            }
            
            $order->save();
            
            // Flash message notifikasi
            session()->flash('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
        }
        
        return view('user.orders.show', compact('order'));
    }
}