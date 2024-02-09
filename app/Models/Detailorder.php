<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Sorder;
use App\Models\Masakan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Detailorder extends Model
{
    use HasFactory;
    protected $table = 'detail_orders';

    protected $fillable = [
        'id_order',
        'id_masakan',
        'qty',
        'keterangan',
        'status_detail_order',
        'alasan',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    public function masakan()
    {
        return $this->belongsTo(Masakan::class, 'id_masakan');
    }

    public function sorder()
    {
        return $this->belongsTo(Sorder::class, 'status_detail_order');
    }

    public function getStatusOrderAttribute()
    {
        if ($this->sorder) {
            return $this->sorder->status_order;
        }

        return null;
    }
}
