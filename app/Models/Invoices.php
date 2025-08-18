<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Invoices extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            # Find the last invoice if it exists
            $lastInvoice = self::orderBy('id', 'desc')->first();

            if ($lastInvoice) {
                # Increment the invoice number
                $invoice->invoice_number = 'INV-POS-0' .($lastInvoice->id+1);
            } else {
                # If no previous invoices, start with 'IN-POS-01'
                $invoice->invoice_number = 'INV-POS-01';
            }
        });
    }
    public function getInvoiceMetaAttribute()
    {
        return DB::table('invoices_meta')
            ->where('invoice_id', $this->id)
            ->get();
    }
}
