<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User; // Penting: Import model User untuk mencari admin
use Midtrans\Notification;
use Filament\Notifications\Notification as FilamentNotification; // Import class Notifikasi Filament

class OrderController extends Controller
{
    public function notification(Request $request)
    {
        // Mengambil notifikasi dari Midtrans
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id; // Ini adalah ID order Anda
        $fraud = $notif->fraud_status;

        // Ganti order_id menjadi order_number
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $oldStatus = $order->status;

        // Logika perubahan status berdasarkan response Midtrans
        if ($transaction == 'capture') {
            if ($fraud == 'challenge') {
                $order->status = 'pending';
            } else {
                // 2. UBAH DI SINI: Gunakan 'processing', bukan 'diproses'
                $order->status = 'processing'; 
            }
        } else if ($transaction == 'settlement') {
            // 2. UBAH DI SINI: Gunakan 'processing'
            $order->status = 'processing'; 
        } else if ($transaction == 'pending') {
            $order->status = 'pending';
        } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $order->status = 'cancelled'; // Lebih baik disamakan dengan 'cancelled' di database
        }

        $order->save();

        // -------------------------------------------------------------------
        // LOGIKA KIRIM NOTIFIKASI KE ADMIN FILAMENT
        // -------------------------------------------------------------------
        
        // Cek apakah status baru saja berubah menjadi 'diproses'
        if ($oldStatus !== 'processing' && $order->status === 'processing') {
            
            // CARI SIAPA ADMINNYA
            // PERHATIAN: Sesuaikan kueri ini dengan cara kamu mengenali admin di tabel users.
            // Contoh 1: Jika admin adalah user dengan id = 1
            $admins = User::where('id', 1)->get(); 
            
            // Contoh 2: Jika kamu punya kolom 'role' = 'admin'
            // $admins = User::where('role', 'admin')->get();

            // Kirim notifikasi ke masing-masing admin
            foreach ($admins as $admin) {
                FilamentNotification::make()
                    ->title('Pembayaran Berhasil! 🎉')
                    ->body("Pesanan {$order->order_id} telah lunas sebesar Rp " . number_format($order->total_price, 0, ',', '.') . ". Segera proses pesanannya!")
                    ->success() // Memberikan warna hijau dan icon centang
                    ->sendToDatabase($admin); // Simpan ke database agar muncul di lonceng notifikasi Filament
            }
        }

        return response()->json(['message' => 'Notification handled successfully']);
    }
}