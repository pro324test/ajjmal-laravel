<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'quantity',
        'unit_price',
        'total_price',
        'product_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'product_snapshot' => 'array',
        ];
    }

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the order item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the vendor that owns the order item
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
