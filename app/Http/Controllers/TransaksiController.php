<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Order;
use App\Models\Transaksi;
use App\Models\Detailorder;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function transaksi()
    {
        $orders = Order::with(['user', 'sorder', 'detailorder.masakan'])
            ->whereNotIn('status_order', [6, 7])
            ->get();

        return view('kasir.transaksi', compact('orders'));
    }

    public function updateStatusPembayaran(Request $request)
    {
        $orderId = $request->input('order_id');

        // Update status_order pada tabel orders
        Order::where('id', $orderId)->update(['status_order' => 4]);

        // Update status_detail_order pada tabel detail_orders
        Detailorder::where('id_order', $orderId)->update(['status_detail_order' => 4]);

        return response()->json(['message' => 'Order berhasil dibayar']);
    }

    public function batalOrder(Request $request)
    {
        $orderId = $request->input('order_id');
        $mejaId = $request->input('meja_id');

        // Update status_order pada tabel orders
        Order::where('id', $orderId)->update(['status_order' => 7]);

        // Update status_detail_order pada tabel detail_orders
        Detailorder::where('id_order', $orderId)->update(['status_detail_order' => 7]);

        // Update status pada tabel nomejas
        Meja::where('no_meja', $mejaId)->update(['status' => 'Tersedia']);


        return response()->json(['message' => 'Order berhasil dibatalkan']);
    }

    public function prosesOrder(Request $request)
    {
        // Ambil data dari permintaan AJAX
        $userId = $request->input('user_id');
        $orderId = $request->input('order_id');
        $tanggal = $request->input('tanggal');
        $totalBayar = $request->input('total_bayar');

        // Simpan data ke dalam tabel transaksis
        Transaksi::create([
            'id_user' => $userId,
            'id_order' => $orderId,
            'tanggal' => $tanggal,
            'total_bayar' => $totalBayar,
        ]);

        // Update status_order dan status_detail_order menjadi 5
        Order::where('id', $orderId)->update(['status_order' => 5]);
        Detailorder::where('id_order', $orderId)->update(['status_detail_order' => 5]);

        return response()->json(['message' => 'Order berhasil diproses']);
    }

    public function detailTransaksi($id)
    {
        $detailOrders = Detailorder::with('sorder')
            ->where('id_order', $id)
            ->get();

        return view('kasir.detailtransaksi', compact('detailOrders', 'id'));
    }

    public function pesananSelesai(Request $request)
    {
        $detailOrderId = $request->input('detail_order_id');
        $orderId = $request->input('order_id');
        $mejaId = $request->input('meja_id');

        // Update status_detail_order pada tabel detail_orders
        Detailorder::where('id', $detailOrderId)->update(['status_detail_order' => 6]);

        // Cek apakah masih ada data lain dengan status_detail_order = 5
        $orderDetailCount = Detailorder::where('id_order', $orderId)->where('status_detail_order', 5)->count();

        if ($orderDetailCount == 0) {
            // Jika tidak ada data lain dengan status_detail_order = 5, update status_order pada tabel orders
            Order::where('id', $orderId)->update(['status_order' => 6]);
            // Update status pada tabel nomejas
            Meja::where('no_meja', $mejaId)->update(['status' => 'Tersedia']);
        }

        return response()->json(['message' => 'Pesanan berhasil diselesaikan']);
    }

    public function pesananBatal(Request $request)
    {
        $detailOrderId = $request->input('detail_order_id');
        $orderId = $request->input('order_id');
        $mejaId = $request->input('meja_id');
        $alasan = $request->input('alasan'); // Ambil nilai alasan dari request

        // Update status_detail_order dan alasan pada tabel detail_orders
        Detailorder::where('id', $detailOrderId)->update([
            'status_detail_order' => 7,
            'alasan' => $alasan,
        ]);

        // Cek apakah masih ada data lain dengan status_detail_order = 5
        $orderDetailCount = Detailorder::where('id_order', $orderId)->where('status_detail_order', 5)->count();

        if ($orderDetailCount == 0) {
            // Jika tidak ada data lain dengan status_detail_order = 5, update status_order pada tabel orders
            Order::where('id', $orderId)->update(['status_order' => 6]);
            // Update status pada tabel nomejas
            Meja::where('no_meja', $mejaId)->update(['status' => 'Tersedia']);
        }

        return response()->json(['message' => 'Pesanan berhasil diselesaikan']);
    }

    public function riwayatTransaksi()
    {
        $orders = Order::with(['user', 'sorder', 'detailorder.masakan'])
            ->whereNotIn('status_order', [1, 2, 3, 4, 5])
            ->get();

        return view('kasir.riwayattransaksi', compact('orders'));
    }
}
