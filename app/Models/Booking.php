<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\MoneyCast;
class Booking extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'bookings';
    protected $casts = [
        'total_price' => MoneyCast::class,
        'extracharge_amount' => MoneyCast::class,
         ];
    public function user()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function senderaddress()
    {
        return $this->belongsTo(Senderaddress::class);
    }
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }
}
