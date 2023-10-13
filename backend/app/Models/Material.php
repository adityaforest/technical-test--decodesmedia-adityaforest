<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    public function detailOrders() {
        return $this->hasMany(DetailOrder::class);
    }

    public function stock() {
        return $this->hasOne(Stock::class);
    }
}
