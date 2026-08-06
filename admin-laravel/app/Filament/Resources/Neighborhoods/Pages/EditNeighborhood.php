<?php

namespace App\Filament\Resources\Neighborhoods\Pages;

use App\Filament\Resources\Neighborhoods\NeighborhoodResource;
use App\Models\Neighborhood;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNeighborhood extends EditRecord
{
    protected static string $resource = NeighborhoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->type === 'new') {
            $olds = Neighborhood::where('type', 'old')
                ->where('group_code', $this->record->group_code)
                ->get();
            $data['old_neighborhoods'] = $olds->map(function ($o) {
                return [
                    'id' => $o->id,
                    'name' => $o->name,
                    'type' => $o->type ?? 'old',
                    'status' => $o->status ?? 'active',
                    'households' => $o->households,
                    'people' => $o->people,
                    'area_ha' => $o->area_ha,
                ];
            })->toArray();
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['group_code']) && $this->record) {
            $data['group_code'] = $this->record->group_code;
        }
        if (isset($data['area_ha'])) {
            $data['area_ha'] = (float) str_replace(',', '.', (string)$data['area_ha']);
        }
        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->type === 'new') {
            if (isset($this->data['old_neighborhoods']) && is_array($this->data['old_neighborhoods'])) {
                $submittedItems = $this->data['old_neighborhoods'];
                $keptIds = [];

                foreach ($submittedItems as $item) {
                    $trimmedName = trim($item['name'] ?? '');
                    if (empty($trimmedName)) {
                        continue;
                    }

                    $hh = (int) ($item['households'] ?? 0);
                    $people = (int) ($item['people'] ?? 0);
                    $area = (float) str_replace(',', '.', (string)($item['area_ha'] ?? 0));

                    $oldRecord = null;
                    if (!empty($item['id'])) {
                        $oldRecord = Neighborhood::find($item['id']);
                    }
                    if (!$oldRecord) {
                        $oldRecord = Neighborhood::where('type', 'old')
                            ->where('group_code', $this->record->group_code)
                            ->where('name', $trimmedName)
                            ->first();
                    }

                    $updateData = [
                        'name' => $trimmedName,
                        'type' => $item['type'] ?? 'old',
                        'group_code' => $this->record->group_code,
                        'status' => $item['status'] ?? 'active',
                        'households' => $hh,
                        'people' => $people,
                        'area_ha' => $area,
                        'leader_name' => $this->record->leader_name,
                        'leader_phone' => $this->record->leader_phone,
                        'bi_thu_name' => $this->record->bi_thu_name,
                        'bi_thu_phone' => $this->record->bi_thu_phone,
                        'to_truong_name' => $this->record->to_truong_name,
                        'to_truong_phone' => $this->record->to_truong_phone,
                        'cskv_name' => $this->record->cskv_name,
                        'cskv_phone' => $this->record->cskv_phone,
                        'mat_tan_name' => $this->record->mat_tan_name,
                        'mat_tan_phone' => $this->record->mat_tan_phone,
                        'nguoi_cao_tuoi' => $this->record->nguoi_cao_tuoi,
                        'nguoi_cao_tuoi_phone' => $this->record->nguoi_cao_tuoi_phone,
                        'phu_nu' => $this->record->phu_nu,
                        'phu_nu_phone' => $this->record->phu_nu_phone,
                        'nong_dan' => $this->record->nong_dan,
                        'nong_dan_phone' => $this->record->nong_dan_phone,
                        'ccb' => $this->record->ccb,
                        'ccb_phone' => $this->record->ccb_phone,
                        'doan_thanh_nien' => $this->record->doan_thanh_nien,
                        'doan_thanh_nien_phone' => $this->record->doan_thanh_nien_phone,
                    ];

                    if ($oldRecord) {
                        $oldRecord->update($updateData);
                        $keptIds[] = $oldRecord->id;
                    } else {
                        $created = Neighborhood::create($updateData);
                        $keptIds[] = $created->id;
                    }
                }

                // Delete any old TDP record in this group that was removed
                Neighborhood::where('type', 'old')
                    ->where('group_code', $this->record->group_code)
                    ->whereNotIn('id', $keptIds)
                    ->delete();
            }

            @exec('php ' . base_path('dump_to_json.php') . ' > /dev/null 2>&1 &');
        }
    }
}
