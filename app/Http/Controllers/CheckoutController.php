<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = ProductVariant::findOrFail($request->variant_id);

        $cart = session()->get('cart', []);
        
        $cartKey = $product->id . '-' . $variant->id;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'variant_id' => $variant->id,
                'size' => $variant->size,
                'color' => $variant->color,
                'price' => $product->base_price + $variant->price, 
                'image' => $product->image_path,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('checkout')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // METHOD BARU: Update Jumlah (Tambah/Kurang)
    public function updateCart(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$cartKey])) {
            if($request->action == 'increase') {
                $cart[$cartKey]['quantity']++;
            } elseif($request->action == 'decrease' && $cart[$cartKey]['quantity'] > 1) {
                $cart[$cartKey]['quantity']--;
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('checkout')->with('success', 'Jumlah produk diperbarui!');
    }

    // METHOD BARU: Hapus Item
    public function removeItem($cartKey)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
        }

        // Jika keranjang jadi kosong setelah item dihapus, kembalikan ke home
        if(empty(session()->get('cart'))) {
            return redirect('/')->with('error', 'Keranjang Anda kosong, silakan pilih produk terlebih dahulu.');
        }

        return redirect()->route('checkout')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect('/')->with('error', 'Keranjang Anda kosong.');
        }

        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        return view('checkout', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan checkout.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        
        // Cek lagi untuk keamanan, kalau user mencoba akses /process tapi cart kosong
        if(empty($cart)) {
            return redirect('/')->with('error', 'Keranjang Anda kosong.');
        }

        $totalAmount = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        // 1. Simpan Data Order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'INV-' . date('Ymd') . '-' . rand(100, 999),
            'status' => 'pending',
            'shipping_cost' => 0,
            'grand_total' => $totalAmount,
            'shipping_address' => $request->address,
        ]);

        // 2. Simpan Data Order Item
        foreach($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        // 3. Setup Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        // 4. Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);
        
        // Hapus session keranjang
        session()->forget('cart');

        return view('checkout-payment', compact('snapToken', 'order'));
    }

    // METHOD BARU: Bayar Ulang Pesanan Pending
    public function pay($id)
    {
        // 1. Cari pesanan berdasarkan ID dan pastikan milik user yang sedang login
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // 2. Pastikan hanya pesanan 'pending' yang bisa dibayar ulang
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini sudah diproses atau tidak dapat dibayar.');
        }

        // 3. TRIK PENTING: Update nomor invoice pesanan. 
        // Midtrans akan menolak request jika order_id (nomor invoice) sudah pernah dikirim sebelumnya.
        // Jadi kita buat ulang nomor urutnya agar terbaca sebagai transaksi baru di sisi Midtrans.
        $newOrderNumber = 'INV-' . date('Ymd') . '-' . rand(100, 999);
        $order->update([
            'order_number' => $newOrderNumber
        ]);

        // 4. Setup Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $newOrderNumber, // Gunakan nomor yang baru di-update
                'gross_amount' => $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        // 5. Generate Snap Token Baru
        $snapToken = Snap::getSnapToken($params);

        // 6. Arahkan ke halaman pembayaran (Gunakan view yang sama saat checkout normal)
        return view('checkout-payment', compact('snapToken', 'order'));
    }
}