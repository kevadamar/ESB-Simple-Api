<?php

namespace App\Models;

use App\Utils\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBillingInvoices extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function from_destination()
    {
        return $this->belongsTo(MDestinations::class, 'from_destination_id');
    }

    public function to_destination()
    {
        return $this->belongsTo(MDestinations::class, 'to_destination_id');
    }

    public function detail()
    {
        return $this->hasMany(TBillingInvoiceDetails::class, 't_billing_invoice_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('search')) {
            $key = $request->search;
            $query->where('code', 'like', '%' . $key . '%')
                ->orWhere('description', 'like', '%' . $key . '%');
        }

        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            default:
                                $query->where($key, 'like', '%' . $val . '%');
                                break;
                        }
                    }
                }
            }
        }

        return $query;
    }

    // menambahkan column untuk api
    public static function columns()
    {
        return Helpers::columns([
            'Invoice No' => 'string',
            'Subject' => 'string'
        ]);
    }
}
