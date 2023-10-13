<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'purchase_order_id',
        'note',
        'created_by'
        // Add other fillable attributes as needed
    ];

    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
