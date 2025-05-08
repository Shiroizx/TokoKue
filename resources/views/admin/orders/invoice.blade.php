<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            color: #333;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details table td {
            padding: 5px 0;
        }
        .invoice-details table td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .customer-details {
            margin-bottom: 30px;
        }
        .customer-details h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-items th {
            background-color: #f8f8f8;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .invoice-items td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .invoice-items .total-row td {
            border-top: 2px solid #ddd;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                <div class="invoice-title">INVOICE</div>
                <div>#{{ $order->id }}</div>
            </div>
            <div>
                <img src="{{ asset('images/logo2.png') }}" alt="Logo" style="max-width: 150px;">
            </div>
        </div>
        
        <div class="invoice-details">
            <table>
                <tr>
                    <td>Date Issued:</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
                <tr>
                    <td>Payment Status:</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                </tr>
                <tr>
                    <td>Payment Method:</td>
                    <td>{{ $order->payment_method ?? 'Not specified' }}</td>
                </tr>
                @if($order->tracking_number)
                <tr>
                    <td>Tracking Number:</td>
                    <td>{{ $order->tracking_number }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <div class="customer-details">
            <div style="display: flex; width: 100%;">
                <div style="width: 50%;">
                    <h3>Bill To:</h3>
                    <p>
                        {{ $order->user->name }}<br>
                        {{ $order->user->email }}<br>
                        {{ $order->user->phone ?? 'No phone provided' }}
                    </p>
                </div>
                <div style="width: 50%;">
                    <h3>Ship To:</h3>
                    <p>
                        {{ $order->recipient_name ?? $order->user->name }}<br>
                        {{ $order->shipping_address ?? 'No address provided' }}<br>
                        {{ $order->recipient_phone ?? $order->user->phone ?? 'No phone provided' }}
                    </p>
                </div>
            </div>
        </div>
        
        <table class="invoice-items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product ? $item->product->name : 'Product Removed' }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                
                <tr>
                    <td colspan="3" class="text-right">Subtotal:</td>
                    <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                
                @if($order->shipping_cost)
                <tr>
                    <td colspan="3" class="text-right">Shipping Cost:</td>
                    <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                @endif
                
                @if($order->discount)
                <tr>
                    <td colspan="3" class="text-right">Discount:</td>
                    <td class="text-right">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                </tr>
                @endif
                
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total:</td>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>For any inquiries, please contact our customer service at Kelompok2@example.com</p>
        </div>
    </div>
</body>
</html>