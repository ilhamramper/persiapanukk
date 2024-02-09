<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Order;

class CheckOrderMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $noMeja = $request->route('nomeja');

        // Cek apakah ada order dengan no_meja yang sesuai
        $latestOrder = Order::where('no_meja', $noMeja)
            ->latest('created_at') // Mengambil yang terbaru berdasarkan created_at
            ->first();

        if (!$latestOrder) {
            return redirect()->back()->with('error', 'Nomor Meja tidak valid.');
        }

        // Cek apakah order milik user yang sedang login
        $userId = auth()->user()->id;
        if ($latestOrder->id_user != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke Nomor Meja ini.');
        }

        // Cek status_order
        if (in_array($latestOrder->status_order, [3, 4, 5])) {
            return redirect()->back()->with('error', 'Order sudah diproses.');
        }

        if ($latestOrder->status_order == 6) {
            return redirect()->back()->with('error', 'Order sudah selesai.');
        }

        if ($latestOrder->status_order == 7) {
            return redirect()->back()->with('error', 'Order dibatalkan.');
        }        

        return $next($request);
    }
}
