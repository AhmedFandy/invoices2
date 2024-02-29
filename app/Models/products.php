<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class products extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'Product_name',
        'description',
        'section_id',
        'Created_by'
    ];


    public function sections()
    {
        return $this->belongsTo(sections::class , 'section_id');
    }

    
}