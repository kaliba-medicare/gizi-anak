<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusGizi extends Model
{
    use HasFactory;

    protected $table = 'status_gizis';
    protected $guarded = [];

    public function type(){
        return $this->belongsTo(Type::class);
    }
}
