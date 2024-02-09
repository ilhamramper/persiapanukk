<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Detailorder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sorder extends Model
{
    use HasFactory;
    protected $table = 'status_orders';

    public function order() {
        return $this->hasMany(Order::class);
    }

    public function detailorder() {
        return $this->hasMany(Detailorder::class);
    }
}
