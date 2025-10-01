<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desas';

    protected $fillable = [
        'nama_desa',
        'kecamatan',
    ];

    public function posyandus()
    {
        return $this->hasMany(Posyandu::class);
    }
}
