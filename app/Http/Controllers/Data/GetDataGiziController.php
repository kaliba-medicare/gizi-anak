<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\StatusGizi;
use Illuminate\Http\Request;

class GetDataGiziController extends Controller
{
    

    public function getAllCountStunting(Request $request)
    {
        $params = $request->query('params');
        
        // Validate the parameter
        if (empty($params)) {
            return response()->json([], 400);
        }

        try {
            $kecamatans = json_decode($params, true);
            if (!is_array($kecamatans)) {
                return response()->json([], 400);
            }

            $results = [];
            
            foreach ($kecamatans as $kecamatan) {
                $count = StatusGizi::where('type_id', 1)
                    ->where('month', '4')
                    ->where('year', '2025')
                    ->where('kec', 'like', '%' . $kecamatan . '%')
                    ->count();

                $results[$kecamatan] = $count;
            }

            return response()->json($results);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data'
            ], 500);
        }
    }

    public function getAllCountGiziForDesa(Request $request)
    {
        $params = $request->query('params');
        
        // Validate the parameter
        if (empty($params)) {
            return response()->json([], 400);
        }

        try {
            $desa = json_decode($params, true);
            if (!is_array($desa)) {
                return response()->json([], 400);
            }

            $results = [];
            
            foreach ($desa as $item) {
                $count = StatusGizi::where('type_id', 1)
                    ->where('month', '4')
                    ->where('year', '2025')
                    ->where('desa_kel', 'like', '%' . $item . '%')
                    ->count();

                $results[$item] = $count;
            }

            return response()->json($results);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data'
            ], 500);
        }
    }
}