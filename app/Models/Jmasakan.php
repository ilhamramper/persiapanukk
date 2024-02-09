<?php

namespace App\Models;

use App\Models\Masakan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jmasakan extends Model
{
    use HasFactory;

    public function masakan() {
        return $this->hasMany(Masakan::class);
    }
}
