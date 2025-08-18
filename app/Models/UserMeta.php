<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'user_metas';

    protected $fillable = [
        'user_id', 'option', 'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
