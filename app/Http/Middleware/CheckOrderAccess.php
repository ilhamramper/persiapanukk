<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Order;

class CheckOrderAccess
{
    public function handle(Request $request, Closure $next)
    {
        $orderId = $request->route('id');

        // Pengecekan apakah order dengan id tersebut ada
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak valid.');
        }

        // Pengecekan apakah user yang sedang login memiliki akses ke pesanan tersebut
        if ($order->id_user !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke Pesanan ini.');
        }

        return $next($request);
    }
}
