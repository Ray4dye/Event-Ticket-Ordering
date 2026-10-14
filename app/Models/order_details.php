<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class order_details extends Model
{
    use hasFactory;

    protected $table = 'order_details';
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(orders::class, 'order_id');
    }

    public function event()
    {
        return $this->belongsTo(events::class, 'event_id');
    }
}
