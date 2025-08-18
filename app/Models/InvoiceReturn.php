<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReturn extends Model
{
    use HasFactory;

    public function user(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function invoice(){
        return $this->hasOne(Invoices::class,'invoice_number','invoice_number');
    }

    public function invoice_return_meta(){
        return $this->hasMany(InvoiceReturnsMeta::class, 'invoice_return_id', 'id')->with('products');
    }
}
