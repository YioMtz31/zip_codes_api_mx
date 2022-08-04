<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $fillable = [
        'zip_code',
        'id_asenta_cpcons',
        'd_asenta',
        'd_zona',
        'd_tipo_asenta'
    ];
}
