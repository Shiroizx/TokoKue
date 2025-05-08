<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        // Load order with relationships
        $order->load(['user', 'orderItems.product']);
        
        // If subtotal not set, calculate and fix it
        if ($order->subtotal == 0 || $order->subtotal === null) {
            $subtotal = $order->orderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
            
            $order->subtotal = $subtotal;
            $order->save();
        }
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validatedData['status'];
        
        // Update the timestamp fields based on status change
        if ($oldStatus != $newStatus) {
            if ($newStatus == 'shipped' && !$order->shipped_at) {
                $order->shipped_at = Carbon::now();
            } else if ($newStatus == 'delivered' && !$order->delivered_at) {
                $order->delivered_at = Carbon::now();
            } else if ($newStatus == 'cancelled' && !$order->canceled_at) {
                $order->canceled_at = Carbon::now();
            }
        }

        $order->update($validatedData);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order status updated successfully');
    }

    /**
     * Update the payment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $order->update($validatedData);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Payment status updated successfully');
    }

    /**
     * Update the tracking number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTracking(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'tracking_number' => 'nullable|string|max:255',
        ]);

        // If order status is still processing and tracking number is added
        // automatically change status to shipped
        if ($order->status === 'processing' && !empty($validatedData['tracking_number']) && empty($order->tracking_number)) {
            $order->status = 'shipped';
            $order->shipped_at = Carbon::now();
        }

        $order->update($validatedData);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Tracking number updated successfully');
    }

    /**
     * Update shipping information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateShipping(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'shipping_courier' => 'nullable|string|max:50',
            'shipping_service' => 'nullable|string|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'total_weight' => 'nullable|numeric|min:0',
        ]);

        $order->update($validatedData);

        // Recalculate total amount if shipping cost has changed
        if (isset($validatedData['shipping_cost'])) {
            $order->total_amount = ($order->subtotal + $order->shipping_cost) - ($order->discount ?? 0);
            $order->save();
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Shipping information updated successfully');
    }

    /**
     * Update order timestamps.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTimestamps(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
        ]);

        $order->update($validatedData);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order timestamps updated successfully');
    }
    
    /**
     * Generate and print invoice PDF.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function printInvoice(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        
        // Calculate subtotal if needed
        if ($order->subtotal == 0 || $order->subtotal === null) {
            $subtotal = $order->orderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
            
            $order->subtotal = $subtotal;
            $order->save();
        }
        
        $pdf = PDF::loadView('admin.orders.invoice', compact('order'));
        return $pdf->stream('invoice-order-'.$order->id.'.pdf');
    }
    
    /**
     * Export order data as CSV/Excel.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function exportOrder(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        
        $fileName = 'order-'.$order->id.'.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = [
            'Order ID', 'Date', 'Customer', 'Email', 'Status', 'Payment Status', 
            'Payment Method', 'Tracking Number', 'Shipping Courier', 'Shipping Service',
            'Shipping Cost', 'Total Weight', 'Subtotal', 'Discount', 'Total'
        ];
        
        $callback = function() use($order, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            $row = [
                $order->id,
                $order->created_at->format('d M Y H:i'),
                $order->user->name,
                $order->user->email,
                ucfirst($order->status),
                ucfirst($order->payment_status),
                $order->payment_method ?? 'N/A',
                $order->tracking_number ?? 'N/A',
                $order->shipping_courier ?? 'N/A',
                $order->shipping_service ?? 'N/A',
                $order->shipping_cost,
                $order->total_weight,
                $order->subtotal,
                $order->discount,
                $order->total_amount
            ];
            
            fputcsv($file, $row);
            
            // Add items details
            fputcsv($file, ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
            fputcsv($file, ['Product', 'SKU', 'Price', 'Quantity', 'Subtotal']);
            
            foreach ($order->orderItems as $item) {
                $rowItem = [
                    $item->product ? $item->product->name : 'Product Removed',
                    $item->product && isset($item->product->sku) ? $item->product->sku : 'N/A',
                    $item->price,
                    $item->quantity,
                    $item->price * $item->quantity
                ];
                
                fputcsv($file, $rowItem);
            }
            
            // Add address information
            fputcsv($file, ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
            fputcsv($file, ['Shipping Address Information']);
            fputcsv($file, ['Recipient Name', $order->recipient_name ?? 'N/A']);
            fputcsv($file, ['Recipient Phone', $order->recipient_phone ?? 'N/A']);
            fputcsv($file, ['Address', $order->shipping_address ?? 'N/A']);
            fputcsv($file, ['Province', $order->province_name ?? 'N/A']);
            fputcsv($file, ['City', $order->city_name ?? 'N/A']);
            fputcsv($file, ['District', $order->district ?? 'N/A']);
            fputcsv($file, ['Village', $order->village ?? 'N/A']);
            fputcsv($file, ['Postal Code', $order->postal_code ?? 'N/A']);
            fputcsv($file, ['Residence Type', $order->residence_type ?? 'N/A']);
            
            // Add timestamps
            fputcsv($file, ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);
            fputcsv($file, ['Order Timestamps']);
            fputcsv($file, ['Created At', $order->created_at->format('d M Y H:i:s')]);
            fputcsv($file, ['Updated At', $order->updated_at->format('d M Y H:i:s')]);
            fputcsv($file, ['Shipped At', $order->shipped_at ? $order->shipped_at->format('d M Y H:i:s') : 'N/A']);
            fputcsv($file, ['Delivered At', $order->delivered_at ? $order->delivered_at->format('d M Y H:i:s') : 'N/A']);
            fputcsv($file, ['Canceled At', $order->canceled_at ? $order->canceled_at->format('d M Y H:i:s') : 'N/A']);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Cancel the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelOrder(Order $order)
    {
        if ($order->status == 'cancelled') {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Order is already cancelled.');
        }
        
        $order->status = 'cancelled';
        $order->canceled_at = Carbon::now();
        $order->save();
        
        // Optional: Return product stock
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->stock += $item->quantity;
                $item->product->save();
            }
        }
        
        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order has been cancelled.');
    }
}