<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\StatusGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataAnakController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'month' => 'numeric|required|min:1',
            'year' => 'numeric|required|min:2020',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }
        $anakLaki= StatusGizi::where('jk', 'L')->where('month', $request->month)->where('year', $request->year)->where('type_id', $request->type_id)->count();
        $anakPerempuan = StatusGizi::where('jk', 'P')->where('month', $request->month)->where('year', $request->year)->where('type_id', $request->type_id)->count();
        $data_anak = StatusGizi::where('month', $request->month)->where('year', $request->year)->where('type_id', $request->type_id)->join('types', 'status_gizis.type_id', '=', 'types.id')->select('status_gizis.*','types.name as status_gizi')->get();

        return response()->json([
            'anak_laki' => $anakLaki,
            'anak_perempuan' => $anakPerempuan,
            'data_anak' => $data_anak,
        ], 200);
    }

    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tgl_lahir' => 'required',
            'nama_ortu' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }
        $data_anak = StatusGizi::where('status_gizis.nama', $request->nama)
    ->where('status_gizis.tgl_lahir', $request->tgl_lahir)
    ->where('status_gizis.nama_ortu', $request->nama_ortu)
    ->join('types', 'status_gizis.type_id', '=', 'types.id')
    ->select('status_gizis.*', 'types.name as status_gizi')
    ->get()
    ->groupBy('status_gizi');


        return response()->json([
            'data_anak' => $data_anak,
        ], 200);
    }
}
