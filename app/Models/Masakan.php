<?php

namespace App\Models;

use App\Models\Jmasakan;
use App\Models\Detailorder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Masakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_jmasakan',
        'nama_masakan',
        'harga',
        'status_masakan',
        'image',
    ];

    public function detailorder() {
        return $this->hasMany(Detailorder::class);
    }

    public function jmasakan() {
        return $this->belongsTo(Jmasakan::class, 'id_jmasakan');
    }
}
