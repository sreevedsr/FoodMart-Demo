<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',          // if using custom primary key
        'user_id',
        'address_id',
        'total_amount',
        'payment_status',
        'order_status',
    ];

    public $incrementing = false; // because primary key will be string
    protected $keyType = 'string'; // primary key type

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->id)) {
                $order->id = strtoupper(Str::random(10)); // generates a 10-character random string
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
