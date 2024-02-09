<?php

namespace App\Models;

use App\Models\Meja;
use App\Models\User;
use App\Models\Sorder;
use App\Models\Transaksi;
use App\Models\Detailorder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'id_user',
        'no_meja',
        'status_order',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailorder()
    {
        return $this->hasMany(DetailOrder::class, 'id_order');
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    public function sorder()
    {
        return $this->belongsTo(Sorder::class, 'status_order', 'id');
    }
}
