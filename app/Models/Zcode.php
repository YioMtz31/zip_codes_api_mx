<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settlement;

class Zcode extends Model
{
    use HasFactory;
    protected $primaryKey = 'zip_code';

    public $timestamps = false;


    protected $fillable = [
        'zip_code',
        'locality',
        'state',
        'state_code',
        'zip_code_key',
        'municipality',
        'municipality_code'
    ];

    /**
     * Get the zip code settlements.
     */
    public function settlements()
    {
        return $this->hasMany(Settlement::class, 'zip_code');
    }
}
