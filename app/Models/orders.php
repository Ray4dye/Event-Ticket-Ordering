<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class orders extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function orderDetails()
    {
        return $this->hasMany(order_details::class, 'order_id');
    }
}
