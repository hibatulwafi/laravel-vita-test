<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'gender', 'birthdate', 'photo'];

    use HasFactory;

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
