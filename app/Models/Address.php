<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['receiver_name', 'address_name', 'address_details', 'phone', 'postal_code'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
