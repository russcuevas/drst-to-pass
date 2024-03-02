<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'total_sold',
    ];
}
