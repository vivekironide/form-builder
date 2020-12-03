<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'description', 'email', 'phone', 'reference_no'];

    public function reply()
    {
        return $this->hasOne( TicketReply::class);
    }
}
