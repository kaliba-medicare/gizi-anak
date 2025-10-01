<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    use HasFactory;
    protected $fillable =[
        'desa_id',
        'nama_dusun',
        'nama_posyandu',
        'latlong',

    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
