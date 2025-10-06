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
        $year = $request->query('year', date('Y')); // Default to current year
        $month = $request->query('month', 'all'); // Default to 'all'
        $category = $request->query('category', 1); // Default to Stunting (type_id = 1)
        
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
                $query = StatusGizi::where('type_id', $category)
                    ->where('year', $year);
                
                // Apply month filter if not 'all'
                if ($month !== 'all') {
                    $query->where('month', $month);
                }
                
                $count = $query->where('kec', 'like', '%' . $kecamatan . '%')
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
        $year = $request->query('year', date('Y')); // Default to current year
        $month = $request->query('month', 'all'); // Default to 'all'
        $category = $request->query('category', 1); // Default to Stunting (type_id = 1)
        
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
                $query = StatusGizi::where('type_id', $category)
                    ->where('year', $year);
                
                // Apply month filter if not 'all'
                if ($month !== 'all') {
                    $query->where('month', $month);
                }
                
                $count = $query->where('desa_kel', 'like', '%' . $item . '%')
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