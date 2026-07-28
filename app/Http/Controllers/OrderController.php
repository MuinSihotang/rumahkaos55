<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User; 
use Midtrans\Notification;
use Filament\Notifications\Notification as FilamentNotification; 

class OrderController extends Controller
{
    public function notification(Request $request)
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id; 
        $fraud = $notif->fraud_status;

        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $oldStatus = $order->status;

        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $order->status = 'pending';
            } else {
                $order->status = 'processing'; 
            }
        } else if ($transaction == 'settlement') {
            $order->status = 'processing'; 
        } else if ($transaction == 'pending') {
            $order->status = 'pending';
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $order->status = 'cancelled';
        }

        $order->save();

        if ($oldStatus !== 'processing' && $order->status === 'processing') {
            
            $admins = User::where('id', 1)->get(); 

            foreach ($admins as $admin) {
                FilamentNotification::make()
                    ->title('Pembayaran Berhasil! 🎉')
                    // PERBAIKAN: order_id -> order_number | total_price -> grand_total
                    ->body("Pesanan {$order->order_number} telah lunas sebesar Rp " . number_format($order->grand_total, 0, ',', '.') . ". Segera proses pesanannya!")
                    ->success()
                    ->sendToDatabase($admin);
            }
        }

        return response()->json(['message' => 'Notification handled successfully']);
    }
}