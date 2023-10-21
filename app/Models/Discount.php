<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\MoneyCast;
class Discount extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'discounts';
    protected $casts = [
        'discount_amount' => MoneyCast::class,
    ];
}
