<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBillingInvoiceDetails extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function service()
    {
        return $this->belongsTo(MServices::class, 'm_service_id');
    }
}
