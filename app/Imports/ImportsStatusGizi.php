<?php

namespace App\Imports;

use App\Models\StatusGizi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Carbon\Carbon;

HeadingRowFormatter::extend('custom', function ($value) {
    $value = strtolower($value);
    $value = str_replace('/', '_', $value);
    $value = str_replace(' ', '_', $value);
    return $value;
});

HeadingRowFormatter::default('custom');

class ImportsStatusGizi implements ToCollection, WithHeadingRow
{
    protected $type_id;
    protected $month;
    protected $year;

    public function __construct($type_id, $month, $year)
    {
        $this->type_id = $type_id;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection(Collection $rows)
    {
        $data = [];
        
        foreach ($rows as $row) {
            if (empty($row['nik'])) {
                continue;
            }

            $data[] = [
                'nik'               => $row['nik'] ?? null,
                'nama'              => $row['nama'] ?? null,
                'jk'                => $row['jk'] ?? null,
                'tgl_lahir'         => $row['tgl_lahir'] ?? null,
                'bb_lahir'          => $row['bb_lahir'] ?? null,
                'tb_lahir'          => $row['tb_lahir'] ?? null,
                'nama_ortu'         => $row['nama_ortu'] ?? null,
                'prov'              => $row['prov'] ?? null,
                'kab_kota'          => $row['kab_kota'] ?? null,
                'kec'               => $row['kec'] ?? null,
                'puskesmas'         => $row['pukesmas'] ?? null,
                'desa_kel'          => $row['desa_kel'] ?? null,
                'posyandu'          => $row['posyandu'] ?? null,
                'rt'                => $row['rt'] ?? null,
                'rw'                => $row['rw'] ?? null,
                'alamat'            => $row['alamat'] ?? null,
                'usia_saat_ukur'    => $row['usia_saat_ukur'] ?? null,
                'tanggal_pengukuran'=> $row['tanggal_pengukuran'] ?? null,
                'berat'             => $row['berat'] ?? null,
                'tinggi'            => $row['tinggi'] ?? null,
                'cara_ukur'         => $row['cara_ukur'] ?? null,
                'lila'              => $row['lila'] ?? null,
                'bb_u'              => $row['bb_u'] ?? null,
                'zs_bb_u'           => $row['zs_bb_u'] ?? null,
                'tb_u'              => $row['tb_u'] ?? null,
                'zs_tb_u'           => $row['zs_tb_u'] ?? null,
                'bb_tb'             => $row['bb_tb'] ?? null,
                'zs_bb_tb'          => $row['zs_bb_tb'] ?? null,
                'naik_berat_badan'  => $row['naik_berat_badan'] ?? null,
                'jml_vit_a'         => $row['jml_vit_a'] ?? null,
                'kpsp'              => $row['kpsp'] ?? null,
                'kia'               => $row['kia'] ?? null,
                'detail'            => $row['detail'] ?? null,
                'type_id'           => $this->type_id,
                'month'             => $this->month,
                'year'              => $this->year,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];

            // Insert in chunks of 500 for better performance
            if (count($data) >= 500) {
                StatusGizi::insert($data);
                $data = [];
            }
        }

        // Insert any remaining records
        if (!empty($data)) {
            StatusGizi::insert($data);
        }
    }
}