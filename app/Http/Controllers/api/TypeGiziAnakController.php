<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeGiziAnakController extends Controller
{
    public function index()
    {
        $typeGizi =Type::all();
        return response()->json([
            'status' => 'success',
            'data' => $typeGizi
        ], 200);
    }
}
