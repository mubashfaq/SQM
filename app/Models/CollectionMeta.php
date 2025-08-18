<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class CollectionMeta extends Model
{
    use HasFactory;
    protected $table = 'collections_meta';

    protected $fillable = ['collection_id', 'option', 'value'];
    public $timestamps = false;

    public function Collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
