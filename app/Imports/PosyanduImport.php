<?php

namespace App\Imports;

use App\Models\Posyandu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

HeadingRowFormatter::extend('custom', function ($value) {
    $value = strtolower($value);
    $value = str_replace('/', '_', $value);
    $value = str_replace(' ', '_', $value);
    return $value;
});

HeadingRowFormatter::default('custom');

class PosyanduImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_dusun']) || empty($row['nama_posyandu']) || empty($row['desa_id'])|| empty($row['titik_koordinat'])) {
            return null;
        }

        $exists = Posyandu::where('nama_dusun', $row['nama_dusun'])
            ->where('nama_posyandu', $row['nama_posyandu'])
            ->where('latlong', $row['titik_koordinat'] )
            ->where('desa_id', $row['desa_id'])
            ->exists();

        if ($exists) {
            return null; 
        }

        return new Posyandu([
            'nama_dusun'    => $row['nama_dusun'],
            'nama_posyandu' => $row['nama_posyandu'],
            'latlong'       => $row['titik_koordinat'] ?? null,
            'desa_id'       => $row['desa_id'],
        ]);
    }
}
