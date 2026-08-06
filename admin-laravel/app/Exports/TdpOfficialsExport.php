<?php

namespace App\Exports;

use App\Models\TdpOfficial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TdpOfficialsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return TdpOfficial::all()->map(function ($item) {
            return [
                'tdp_name'        => $item->tdp_name,
                'bi_thu_name'     => $item->bi_thu_name,
                'bi_thu_phone'    => $item->bi_thu_phone,
                'to_truong_name'  => $item->to_truong_name,
                'to_truong_phone' => $item->to_truong_phone,
                'mat_tan_name'    => $item->mat_tan_name,
                'mat_tan_phone'   => $item->mat_tan_phone,
                'nguoi_cao_tuoi'  => $item->nguoi_cao_tuoi,
                'phu_nu'          => $item->phu_nu,
                'nong_dan'        => $item->nong_dan,
                'ccb'             => $item->ccb,
                'doan_thanh_nien'  => $item->doan_thanh_nien,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tên Tổ dân phố',
            'Bí thư Chi bộ',
            'SĐT Bí thư',
            'Tổ trưởng TDP',
            'SĐT Tổ trưởng',
            'Trưởng ban Mặt trận',
            'SĐT Trưởng ban Mặt trận',
            'Chi hội trưởng Người cao tuổi',
            'Chi hội trưởng Hội Phụ nữ',
            'Chi hội trưởng Hội Nông dân',
            'Chi hội trưởng Hội CCB',
            'Bí thư Chi đoàn Thanh niên',
        ];
    }
}
