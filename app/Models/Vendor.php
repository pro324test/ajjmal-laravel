<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'slug',
        'description',
        'logo',
        'banner',
        'address',
        'phone',
        'email',
        'website',
        'commission_rate',
        'status',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the user that owns the vendor
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the vendor
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the order items for the vendor
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if vendor is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if vendor is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
