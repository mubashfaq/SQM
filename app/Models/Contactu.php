<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactu extends Model
{
    use HasFactory;

    protected $fillable = [
        'f_name',
        'l_name',
        'email',
        'phone',
        'subject',
        'message',
        'order_id',
        'file_path',
    ];
}
