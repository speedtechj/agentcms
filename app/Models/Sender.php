<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'senders';
    // public function booking()
    // {
    //     return $this->hasMany(Booking::class);
    // }
}
