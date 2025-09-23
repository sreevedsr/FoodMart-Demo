<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'phone', 'address_line', 'city', 'state', 'pincode'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
