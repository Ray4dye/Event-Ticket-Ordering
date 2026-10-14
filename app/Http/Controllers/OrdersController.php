<?php

namespace App\Http\Controllers;

use App\Models\events;
use App\Models\orders;
use App\Models\order_details;
use Illuminate\Http\Request; // <-- TAMBAHKAN BARIS INI UNTUK MENGGUNAKAN FASILITAS REQUEST
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN BARIS INI UNTUK MENGGUNAKAN FASILITAS TRANSAKSI DATABASE

class OrdersController extends Controller
{
    /**
     * Menampilkan katalog event untuk pelanggan (Halaman Utama).
        */
    public function index()
{
    $events = events::all();
    return view('customer.index', compact('events'));
}

    /**
     * Memproses checkout pesanan pelanggan.
     */
    public function store(Request $request)
    {
        // Validasi data pemesan dan item yang dipilih
        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_email'  => 'required|string|max:255',
            'items'           => 'required|array',
            'items.*'         => 'nullable|integer|min:0',
        ]);

        // Filter hanya events yang jumlah/qty dipesan > 0
        $orderedItems = array_filter($request->items, fn($qty) => $qty > 0);

        if (empty($orderedItems)) {
            return back()->with('error', 'Pilih minimal satu Event!');
        }

        // Gunakan DB Transaction agar penyimpanan data order & detail aman
        DB::beginTransaction();
        try {
            // 1. Simpan Header Order
            $orders = orders::create([
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_price'    => 0, // Dihitung di bawah
                'status'         => 'Pending',
            ]);

            $totalPrice = 0;

            // 2. Simpan Detail Order & Hitung Subtotal
            foreach ($orderedItems as $eventsId => $quantity) {
                $events = events::findOrFail($eventsId);
                $subtotal = $events->ticket_price * $quantity;
                $totalPrice += $subtotal;

                order_details::create([
                    'order_id' => $orders->id,
                    'event_id' => $events->id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            // 3. Update Total Harga di Header Order
            $orders->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('customer.index')->with('success', 'Pesanan berhasil dibuat!, Total Harga: Rp ' . number_format($totalPrice));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan rekap pesanan di Dashboard Admin.
     */
    public function adminDashboard()
    {
        // Mengambil seluruh pesanan beserta detail item & data events terkait (diurutkan dari yang terbaru)
        $orders = orders::with('orderDetails.event')->latest()->get();
        return view('dashboard', compact('orders'));
    }

    /**
     * Update status pesanan oleh Admin.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Cancelled',
        ]);

        $orders = orders::findOrFail($id);
        $orders->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan #' . $orders->id . ' berhasil diperbarui!');
    }
}