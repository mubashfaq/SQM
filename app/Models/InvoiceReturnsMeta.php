<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReturnsMeta extends Model
{
    use HasFactory;
    protected $table = 'invoice_returns_meta';

    public function products(){
        return $this->hasOne(Product::class,'id', 'product_id')->select('id','title');
    }
}
