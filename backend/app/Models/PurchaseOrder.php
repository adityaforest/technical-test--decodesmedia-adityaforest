<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'supplier_id',
        'status'        
        // Add other fillable attributes as needed
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }

    public function activity() {
        return $this->hasMany(Activity::class);
    }
}
