<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\UserAddress;

// 1. TAMBAHKAN IMPORT MIDTRANS DI SINI
use Midtrans\Config;
use Midtrans\Transaction;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil riwayat pesanan (Data Awal)
        $orders = Order::with(['items.variant.product'])
            ->where('user_id', $user->id)
            ->latest() // Urutkan dari pesanan terbaru
            ->get();

        // 2. SETUP KONFIGURASI MIDTRANS UNTUK CEK STATUS OTOMATIS
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $statusChanged = false;

        // 3. LOGIKA AUTO-CHECK STATUS TANPA WEBHOOK/NGROK
        foreach ($orders as $order) {
            // Hanya buang resource untuk mengecek pesanan yang masih 'pending'
            if ($order->status === 'pending') {
                try {
                    // Tanya langsung ke server Midtrans menggunakan order_number (Invoice)
                    $status = Transaction::status($order->order_number);
                    $transactionStatus = $status->transaction_status;

                    // Jika di Midtrans terbaca lunas, update database lokal menjadi 'processing'
                    if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                        $order->update(['status' => 'processing']);
                        $statusChanged = true;
                    } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                        $order->update(['status' => 'cancelled']);
                        $statusChanged = true;
                    }
                } catch (\Exception $e) {
                    // Abaikan jika order belum ada di Midtrans (Pelanggan belum klik bayar sama sekali)
                    continue;
                }
            }
        }

        // 4. Jika ada pesanan yang statusnya baru saja berubah, ambil ulang datanya dari database
        if ($statusChanged) {
            $orders = Order::with(['items.variant.product'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        $addresses = $user->addresses;

        return view('profile', compact('user', 'orders', 'addresses'));
    }

    // METHOD BARU: Tambah Alamat
    public function storeAddress(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        $user = Auth::user();
        $isFirstAddress = $user->addresses()->count() === 0;

        // Jika ini alamat pertama, atau user menceklis "Jadikan Utama"
        $setAsPrimary = $isFirstAddress || $request->has('is_primary');

        if ($setAsPrimary) {
            // Ubah semua alamat lain menjadi tidak utama dulu
            $user->addresses()->update(['is_primary' => false]);
        }

        $user->addresses()->create([
            'receiver_name' => $request->receiver_name,
            'phone_number' => $request->phone_number,
            'full_address' => $request->full_address,
            'district' => $request->district,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'is_primary' => $setAsPrimary,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    // METHOD BARU: Update Alamat
    public function updateAddress(Request $request, $id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        if ($request->has('is_primary')) {
            Auth::user()->addresses()->where('id', '!=', $id)->update(['is_primary' => false]);
            $address->is_primary = true;
        }

        $address->update($request->except('is_primary'));

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    // METHOD BARU: Hapus Alamat
    public function destroyAddress($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        
        $wasPrimary = $address->is_primary;
        $address->delete();

        // Jika yang dihapus adalah alamat utama, jadikan alamat lain (jika ada) sebagai utama
        if ($wasPrimary) {
            $newPrimary = Auth::user()->addresses()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Alamat berhasil dihapus!');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Email biasanya tidak diubah sembarangan tanpa verifikasi ulang, jadi kita fokus pada nama dan password
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], 
        ]);

        $user->name = $request->name;

        // Hanya update password jika form diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // METHOD BARU: Cetak Invoice
    public function invoice($id)
    {
        // Cari pesanan berdasarkan ID dan pastikan itu milik user yang sedang login
        $order = Order::with(['items.variant.product', 'user'])->where('id', $id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->firstOrFail();

        return view('invoice', compact('order'));
    }
}