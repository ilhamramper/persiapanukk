<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;
    protected $table = 'nomejas';

    protected $fillable = [
        'no_meja',
        'status',
    ];

    public function order() {
        return $this->hasMany(Order::class);
    }
}
