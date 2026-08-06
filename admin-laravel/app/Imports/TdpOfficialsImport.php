<?php

namespace App\Imports;

use App\Models\TdpOfficial;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TdpOfficialsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Support flexible heading row names in Vietnamese
        $tdpName = $row['ten_to_dan_pho'] 
            ?? $row['to_dan_pho'] 
            ?? $row['tdp'] 
            ?? $row['ten_tdp'] 
            ?? null;

        if (!$tdpName) {
            return null;
        }

        return TdpOfficial::updateOrCreate(
            ['tdp_name' => trim($tdpName)],
            [
                'bi_thu_name'     => $row['bi_thu_chi_bo'] ?? $row['bi_thu'] ?? null,
                'bi_thu_phone'    => $row['sdt_bi_thu'] ?? $row['so_dien_thoai_bi_thu'] ?? null,
                'to_truong_name'  => $row['to_truong_tdp'] ?? $row['to_truong'] ?? null,
                'to_truong_phone' => $row['sdt_to_truong'] ?? $row['so_dien_thoai_to_truong'] ?? null,
                'mat_tan_name'    => $row['truong_ban_mat_tan'] ?? $row['mat_tan'] ?? null,
                'mat_tan_phone'   => $row['sdt_mat_tan'] ?? $row['so_dien_thoai_mat_tan'] ?? null,
                'nguoi_cao_tuoi'  => $row['chi_hoi_truong_nguoi_cao_tuoi'] ?? $row['nguoi_cao_tuoi'] ?? null,
                'phu_nu'          => $row['chi_hoi_truong_hoi_phu_nu'] ?? $row['phu_nu'] ?? null,
                'nong_dan'        => $row['chi_hoi_truong_hoi_nong_dan'] ?? $row['nong_dan'] ?? null,
                'ccb'             => $row['chi_hoi_truong_hoi_ccb'] ?? $row['ccb'] ?? null,
                'doan_thanh_nien'  => $row['bi_thu_chi_doan_thanh_nien'] ?? $row['doan_thanh_nien'] ?? null,
            ]
        );
    }
}
