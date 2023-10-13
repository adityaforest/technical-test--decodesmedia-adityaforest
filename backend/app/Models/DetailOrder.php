<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'material_id',
        'amount',
        'received'        
        // Add other fillable attributes as needed
    ];

    public function material() {
        return $this->belongsTo(Material::class);
    }
}
