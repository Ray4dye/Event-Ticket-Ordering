<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class events extends Model
{
    use HasFactory;
    
    protected $table = 'events';
    protected $guarded = ['id'];
}
