<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Order;
use App\Models\Masakan;
use App\Models\Jmasakan;
use App\Models\Detailorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();
        $orders = Order::with('sorder')
            ->where('id_user', $userId)
            ->whereIn('status_order', [1, 2, 3, 4, 5])
            ->get();

        // Pengecekan 1: Apakah ada data pada tabel orders dengan id_user = id dari user yang sedang login
        $hasOrderForUser = $orders->isNotEmpty();

        // Pengecekan 2: Apakah data terbaru pada tabel orders dengan id_user = id dari user yang sedang login memiliki value pada kolom status_order = 3, 4, 5, 6, atau 7
        $latestOrder = $orders->last();
        $isLatestOrderCompleted = $latestOrder && in_array($latestOrder->status_order, [3, 4, 5, 6, 7]);

        return view('user.order', [
            'orders' => $orders,
            'isBuatOrderDisabled' => $hasOrderForUser && !$isLatestOrderCompleted,
        ]);
    }

    public function riwayatOrder()
    {
        $userId = auth()->id();
        $orders = Order::with('sorder')
            ->where('id_user', $userId)
            ->whereNotIn('status_order', [1, 2, 3, 4, 5])
            ->get();

        return view('user.riwayatorder', [
            'orders' => $orders,
        ]);
    }

    public function createOrder()
    {
        $nomejas = Meja::where('status', 'Tersedia')->get();

        return view('user.buatorder', compact('nomejas',));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'no_meja' => 'required|exists:nomejas,no_meja,status,Tersedia',
        ]);

        $tanggal = now()->toDateString();
        $id_user = auth()->user()->id;

        $order = new Order();
        $order->no_meja = $request->input('no_meja');
        $order->tanggal = $tanggal;
        $order->id_user = $id_user;

        $order->save();

        Meja::where('no_meja', $request->input('no_meja'))->update(['status' => 'Tidak Tersedia']);

        return redirect()->route('order')->with('success', 'Order created successfully.');
    }

    public function makeOrder($nomeja)
    {
        $jmasakans = Jmasakan::all();

        $masakans = Masakan::with('jmasakan')
            ->where('status_masakan', 'Tersedia')
            ->orderBy('created_at', 'desc')
            ->get();

        // Menambahkan masakans dengan status 'Tidak Tersedia' setelah masakans yang tersedia
        $masakansTidakTersedia = Masakan::with('jmasakan')
            ->where('status_masakan', 'Tidak Tersedia')
            ->orderBy('created_at', 'desc')
            ->get();

        $masakans = $masakans->merge($masakansTidakTersedia);

        // Mengambil id order terbaru yang sesuai dengan user yang sedang login dan id tersebut memiliki status_order != 3, 4, 5, 6, atau 7
        $idorder = Order::where('id_user', Auth::id())
            ->whereNotIn('status_order', [3, 4, 5, 6, 7])
            ->latest()
            ->value('id');

        // Mengambil data dari tabel orders yang sesuai
        $userId = auth()->user()->id;
        $order = Order::where('id_user', $userId)->whereNotIn('status_order', [3, 4, 5, 6, 7])->latest()->first();
        $jumlahData = 0;

        // Jika ditemukan data pada tabel orders
        if ($order) {
            // Ambil jumlah data dari tabel detail_orders yang memiliki id_order sesuai dengan id dari tabel orders
            $jumlahData = Detailorder::where('id_order', $order->id)->count();
        }

        return view('user.masakan', compact('jmasakans', 'masakans', 'jumlahData', 'idorder'));
    }

    public function storeDorder(Request $request)
    {
        $request->validate([
            'idmasakan' => 'required|exists:masakans,id',
            'qty' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek apakah masakan tersedia
        $masakanStatus = Masakan::where('id', $request->input('idmasakan'))
            ->where('status_masakan', 'Tersedia')
            ->exists();

        if (!$masakanStatus) {
            return redirect()->back()->with('error', 'Masakan tidak tersedia.');
        }

        $id_order = Order::where('id_user', Auth::id())
            ->whereNotIn('status_order', [3, 4, 5, 6, 7])
            ->latest()
            ->value('id');

        // Cek apakah detail_order dengan id_masakan yang sama sudah ada
        $existingDetailOrder = Detailorder::where('id_masakan', $request->input('idmasakan'))
            ->where('id_order', $id_order)
            ->first();

        if ($existingDetailOrder) {
            // Jika sudah ada, kirim pesan error dan redirect back tanpa melakukan perubahan
            return redirect()->back()->with('error', 'Pesanan tersebut sudah anda buat.');
        }

        // Jika belum ada, buat detail_order baru
        Detailorder::firstOrCreate(
            ['id_order' => $id_order, 'id_masakan' => $request->input('idmasakan')],
            [
                'qty' => $request->input('qty'),
                'keterangan' => $request->input('keterangan'),
            ],
        );

        Order::where('id', $id_order)->update(['status_order' => 2]);

        return redirect()->back()->with('success', 'Pesanan created successfully.');
    }

    public function dorder($id)
    {
        $dorders = Detailorder::with('masakan')
            ->where('id_order', $id)
            ->get();
        $orderStatus = Order::where('id', $id)->value('status_order');
        $idmeja = Order::where('id_user', Auth::id())
            ->latest()
            ->value('no_meja');

        return view('user.detailorder', compact('dorders', 'idmeja', 'orderStatus'));
    }

    public function updateDorder(Request $request)
    {
        $request->validate([
            'qty' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $dorder = Detailorder::findOrFail($request->input('iddorder'));
        $dorder->qty = $request->qty;
        $dorder->keterangan = $request->keterangan;

        $dorder->save();

        return redirect()->back()->with('success', 'Pesanan updated successfully');
    }

    public function deleteDorder($id)
    {
        $dorder = Detailorder::findOrFail($id);
        $dorder->delete();

        return redirect()->back()->with('success', 'Pesanan deleted successfully.');
    }

    public function simpanPesanan(Request $request)
    {
        $id_order = $request->input('id_order');

        // Update status_order in the orders table
        Order::where('id', $id_order)->update(['status_order' => 3]);

        // Update status_detail_orders in the detail_orders table
        Detailorder::where('id_order', $id_order)->update(['status_detail_order' => 3]);

        return redirect()->route('order')->with('success', 'Pesanan berhasil disimpan.');
    }
}
