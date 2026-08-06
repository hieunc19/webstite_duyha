<?php

namespace App\Filament\Resources\TdpOfficials\Pages;

use App\Exports\TdpOfficialsExport;
use App\Imports\TdpOfficialsImport;
use App\Filament\Resources\TdpOfficials\TdpOfficialResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ListTdpOfficials extends ListRecords
{
    protected static string $resource = TdpOfficialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Thêm Cán bộ TDP'),

            Action::make('importExcel')
                ->label('📥 Nhập từ Excel')
                ->color('success')
                ->form([
                    FileUpload::make('attachment')
                        ->label('Chọn file Excel (.xlsx, .csv)')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                            'application/csv'
                        ])
                        ->required()
                        ->storeFiles(false),
                ])
                ->action(function (array $data) {
                    try {
                        $file = $data['attachment'];
                        Excel::import(new TdpOfficialsImport, $file);

                        Notification::make()
                            ->title('Thành công!')
                            ->body('Đã nhập dữ liệu danh sách Cán bộ Tổ dân phố từ file Excel thành công.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Lỗi nhập Excel!')
                            ->body('Có lỗi xảy ra trong quá trình xử lý: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('exportExcel')
                ->label('📤 Xuất file Excel')
                ->color('info')
                ->action(function () {
                    return Excel::download(new TdpOfficialsExport, 'Danh_sach_can_bo_TDP_Duy_Ha.xlsx');
                }),

            Action::make('downloadTemplate')
                ->label('📄 Tải mẫu Excel')
                ->color('gray')
                ->action(function () {
                    return Excel::download(new TdpOfficialsExport, 'Mau_Excel_Danh_sach_Can_bo_TDP.xlsx');
                }),
        ];
    }
}
