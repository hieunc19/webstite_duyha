<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Define target directory
$mainTargetDir = __DIR__ . '/../client/src/data';

if (!is_dir($mainTargetDir)) {
    mkdir($mainTargetDir, 0755, true);
}

function saveJsonBoth($filename, $data, $mainTargetDir) {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($mainTargetDir . '/' . $filename, $json);
    $publicTargetDir = __DIR__ . '/../client/public/data';
    if (!is_dir($publicTargetDir)) {
        mkdir($publicTargetDir, 0755, true);
    }
    file_put_contents($publicTargetDir . '/' . $filename, $json);

    // Đồng bộ trực tiếp vào client/dist/data (nơi Nginx serve frontend trên VPS)
    $distDir = __DIR__ . '/../client/dist';
    if (is_dir($distDir)) {
        $distTargetDir = $distDir . '/data';
        if (!is_dir($distTargetDir)) {
            mkdir($distTargetDir, 0755, true);
        }
        file_put_contents($distTargetDir . '/' . $filename, $json);
    }
}

// 1. Provinces
echo "Dumping provinces...\n";
$provinces = \App\Models\Province::all()->map(function($p) {
    return [
        'code' => $p->code,
        'name' => $p->name,
        'full_name' => $p->full_name,
        'latitude' => (float)$p->latitude,
        'longitude' => (float)$p->longitude
    ];
});
saveJsonBoth('provinces.json', $provinces, $mainTargetDir);

// 2. Wards (Administrative Units)
echo "Dumping administrative units...\n";
$wards = \App\Models\AdministrativeUnit::all()->map(function($u) {
    return [
        'id' => $u->id,
        'code' => $u->code,
        'name' => $u->name,
        'type' => $u->type,
        'latitude' => (float)$u->latitude,
        'longitude' => (float)$u->longitude,
        'link' => $u->link,
        'boundary' => $u->boundary_data,
        'province_code' => $u->province_code,
        'district_name' => $u->district_name
    ];
});
saveJsonBoth('wards.json', $wards, $mainTargetDir);

$formatStorage = function($img) {
    if (!$img) return '/hero-bg.jpg';
    if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://') || str_starts_with($img, '/storage/') || str_starts_with($img, '/')) {
        return $img;
    }
    return '/storage/' . ltrim($img, '/');
};

// 3. Places
echo "Dumping places...\n";
$places = \App\Models\Place::all()->map(function($place) use ($formatStorage) {
    return [
        'id' => $place->id,
        'name' => $place->name,
        'category' => $place->category,
        'status' => $place->status,
        'address' => $place->address,
        'phone' => $place->phone,
        'lat' => (float)$place->lat,
        'lng' => (float)$place->lng,
        'image' => $formatStorage($place->image),
        'administrative_unit_id' => $place->administrative_unit_id,
        'description' => $place->description
    ];
});
saveJsonBoth('places.json', $places, $mainTargetDir);

// 4. Neighborhoods
echo "Dumping neighborhoods...\n";
$allNeighborhoods = \App\Models\Neighborhood::all()->map(function($n) {
    return [
        'id' => $n->id,
        'name' => $n->name,
        'type' => $n->type,
        'group_code' => $n->group_code,
        'leader_name' => $n->leader_name,
        'leader_phone' => $n->leader_phone,
        'households' => (int)$n->households,
        'people' => (int)$n->people,
        'area_ha' => (float)($n->area_ha ?? 0),
        'status' => $n->status
    ];
});

$cleanName = function($name) {
    return trim(preg_replace('/^(TDP|Tổ dân phố)\s+/iu', '', $name));
};

$newNeighborhoods = $allNeighborhoods->where('type', 'new')->sort(function($a, $b) use ($cleanName) {
    return strcoll($cleanName($a['name']), $cleanName($b['name']));
})->values();

$groupOrder = [];
foreach ($newNeighborhoods as $idx => $item) {
    $groupOrder[$item['group_code']] = $idx;
}

$oldNeighborhoods = $allNeighborhoods->where('type', 'old')->sort(function($a, $b) use ($groupOrder, $cleanName) {
    $orderA = $groupOrder[$a['group_code']] ?? 999;
    $orderB = $groupOrder[$b['group_code']] ?? 999;
    if ($orderA !== $orderB) {
        return $orderA - $orderB;
    }
    return strcoll($cleanName($a['name']), $cleanName($b['name']));
})->values();

$neighborhoods = $newNeighborhoods->merge($oldNeighborhoods);
saveJsonBoth('neighborhoods.json', $neighborhoods, $mainTargetDir);

// 5. Celebration Events
echo "Dumping celebration events...\n";
$events = \App\Models\CelebrationEvent::all()->map(function($e) {
    return [
        'id' => $e->id,
        'name' => $e->name,
        'month' => (int)$e->month,
        'day' => (int)$e->day,
        'description' => $e->description,
        'is_featured' => (bool)$e->is_featured,
        'status' => $e->status
    ];
});
saveJsonBoth('celebration_events.json', $events, $mainTargetDir);

// 6. Meritorious Families (Policy Batches with Excel files)
echo "Dumping meritorious families...\n";
$families = \App\Models\MeritoriousFamily::where('status', 'active')->orderBy('created_at', 'desc')->get()->map(function($f) {
    return [
        'id' => $f->id,
        'name' => $f->name,
        'file_path' => $f->file_path,
        'file_url' => $f->file_url,
        'file_name' => $f->file_name ?: 'Danh-sach-chinh-sach.xlsx',
        'file_size' => $f->file_size,
        'description' => $f->description,
        'status' => $f->status,
        'created_at' => $f->created_at?->format('d/m/Y H:i'),
        'period_date' => $f->period_date ?: ($f->created_at?->format('d/m/Y') ?? '')
    ];
});
saveJsonBoth('meritorious_families.json', $families, $mainTargetDir);

// 7. Officials
echo "Dumping officials...\n";
$officials = \App\Models\Official::all()->map(function($o) use ($formatStorage) {
    return [
        'id' => $o->id,
        'name' => $o->name,
        'role' => $o->role,
        'phone' => $o->phone,
        'neighborhood_name' => $o->neighborhood_name,
        'avatar_color' => $o->avatar_color,
        'avatar' => $formatStorage($o->avatar),
        'department' => $o->department,
        'status' => $o->status
    ];
});
saveJsonBoth('officials.json', $officials, $mainTargetDir);

// 8. Departments
echo "Dumping departments...\n";
$departments = \App\Models\Department::where('status', 'active')
    ->orderBy('sort_order', 'asc')
    ->get()
    ->map(function($d) {
        return [
            'id' => $d->id,
            'code' => $d->code,
            'name' => $d->name,
            'color' => $d->color,
            'sort_order' => (int) $d->sort_order,
            'status' => $d->status,
            'description' => $d->description
        ];
    });
saveJsonBoth('departments.json', $departments, $mainTargetDir);

// 9. TDP Officials (Cadres)
echo "Dumping TDP officials...\n";
$newNeighborhoods = \App\Models\Neighborhood::where('type', 'new')->get();
$tdpOfficials = $newNeighborhoods->map(function($o, $idx) {
    return [
        'tt' => $idx + 1,
        'tdp' => $o->name,
        'biThuName' => $o->bi_thu_name ?? '',
        'biThuPhone' => $o->bi_thu_phone ?? '',
        'toTruongName' => $o->to_truong_name ?? '',
        'toTruongPhone' => $o->to_truong_phone ?? '',
        'cskvName' => $o->cskv_name ?? '',
        'cskvPhone' => $o->cskv_phone ?? '',
        'matTanName' => $o->mat_tan_name ?? '',
        'matTanPhone' => $o->mat_tan_phone ?? '',
        'nguoiCaoTuoi' => $o->nguoi_cao_tuoi ?? '',
        'nguoiCaoTuoiPhone' => $o->nguoi_cao_tuoi_phone ?? '',
        'phuNu' => $o->phu_nu ?? '',
        'phuNuPhone' => $o->phu_nu_phone ?? '',
        'nongDan' => $o->nong_dan ?? '',
        'nongDanPhone' => $o->nong_dan_phone ?? '',
        'ccb' => $o->ccb ?? '',
        'ccbPhone' => $o->ccb_phone ?? '',
        'doanThanhNien' => $o->doan_thanh_nien ?? '',
        'doanThanhNienPhone' => $o->doan_thanh_nien_phone ?? ''
    ];
});
saveJsonBoth('tdp_officials.json', $tdpOfficials, $mainTargetDir);

// 9. Settings
echo "Dumping settings...\n";
$cards = \App\Models\Setting::where('group', 'stats')
    ->orderBy('sort_order', 'asc')
    ->get()
    ->map(function ($item) {
        $iconMap = [
            'stat_1' => ['icon' => 'holiday_village', 'bg' => 'bg-blue-500/10', 'color' => 'text-blue-600 dark:text-blue-400'],
            'stat_2' => ['icon' => 'group', 'bg' => 'bg-emerald-500/10', 'color' => 'text-emerald-600 dark:text-emerald-400'],
            'stat_3' => ['icon' => 'diversity_3', 'bg' => 'bg-amber-500/10', 'color' => 'text-amber-600 dark:text-amber-400'],
            'stat_4' => ['icon' => 'map', 'bg' => 'bg-purple-500/10', 'color' => 'text-purple-600 dark:text-purple-400'],
        ];
        $style = $iconMap[$item->key] ?? ['icon' => 'analytics', 'bg' => 'bg-blue-500/10', 'color' => 'text-blue-600'];

        return [
            'key'        => $item->key,
            'name'       => $item->name,
            'value'      => $item->value,
            'label'      => $item->label,
            'sort_order' => (int) $item->sort_order,
            'icon'       => $style['icon'],
            'bg'         => $style['bg'],
            'color'      => $style['color'],
        ];
    });

$s1 = \App\Models\Setting::where('key', 'stat_1')->first();
$s2 = \App\Models\Setting::where('key', 'stat_2')->first();
$s3 = \App\Models\Setting::where('key', 'stat_3')->first();
$s4 = \App\Models\Setting::where('key', 'stat_4')->first();

$settingsData = [
    'cards'      => $cards->toArray(),
    'stat_1_val' => $s1 ? $s1->value : '10',
    'stat_1_lbl' => $s1 ? $s1->label : 'Tổng số tổ dân phố',
    'stat_2_val' => $s2 ? $s2->value : '6.767',
    'stat_2_lbl' => $s2 ? $s2->label : 'Tổng số hộ gia đình',
    'stat_3_val' => $s3 ? $s3->value : '23.615',
    'stat_3_lbl' => $s3 ? $s3->label : 'Tổng số nhân khẩu',
    'stat_4_val' => $s4 ? $s4->value : '15,46 km²',
    'stat_4_lbl' => $s4 ? $s4->label : 'Diện tích (1.546,30 ha)',
];
saveJsonBoth('settings.json', $settingsData, $mainTargetDir);

// 10. Homepage Sections (Layout Builder)
echo "Dumping homepage sections...\n";
$managedHomepageCodes = [
    'header_navbar',
    'hero_banner',
    'stats_cards',
    'agencies_grid',
    'procedures_utilities',
    'hdsd_procedure',
    'footer_section',
];
$homepageSections = \App\Models\HomepageSection::where(function ($query) use ($managedHomepageCodes) {
    $query
        ->whereIn('section_code', $managedHomepageCodes)
        ->orWhere('section_code', 'like', 'custom_%');
})->orderBy('sort_order', 'asc')->get()->map(function($sec) {
    return [
        'id' => $sec->id,
        'section_code' => $sec->section_code,
        'name' => $sec->name,
        'custom_title' => $sec->custom_title,
        'custom_subtitle' => $sec->custom_subtitle,
        'is_visible' => (bool)$sec->is_visible,
        'sort_order' => (int)$sec->sort_order,
        'settings' => $sec->settings
    ];
});
saveJsonBoth('homepage_sections.json', $homepageSections, $mainTargetDir);

// 10.1 Subpage Banners Config
echo "Dumping subpage banners config...\n";
$bannersSetting = \App\Models\Setting::where('key', 'subpage_banners')->first();
$subpageBanners = [];
if ($bannersSetting && !empty($bannersSetting->value)) {
    $decodedBanners = json_decode($bannersSetting->value, true);
    if (is_array($decodedBanners)) {
        $subpageBanners = $decodedBanners;
    }
}
if (empty($subpageBanners)) {
    $subpageMgr = new \App\Filament\Pages\ManageSubpageBanners();
    $subpageBanners = $subpageMgr->defaultBanners();
}
saveJsonBoth('subpage_banners.json', $subpageBanners, $mainTargetDir);

// 11. Feedback & Petitions Config (Google Form & Sheets URL)
echo "Dumping feedback config...\n";
$fbFormUrl = \App\Models\Setting::where('key', 'feedback_google_form_url')->value('value') ?? '';
$fbSheetUrl = \App\Models\Setting::where('key', 'feedback_google_sheet_url')->value('value') ?? '';
$fbEnabled = \App\Models\Setting::where('key', 'feedback_is_enabled')->value('value') ?? '1';
$fbTitle = \App\Models\Setting::where('key', 'feedback_title')->value('value') ?? 'Phản ánh và kiến nghị';
$fbSubtitle = \App\Models\Setting::where('key', 'feedback_subtitle')->value('value') ?? 'Kênh tiếp nhận và giải quyết ý kiến phản ánh trực tuyến của công dân';

$feedbackConfig = [
    'google_form_url' => $fbFormUrl,
    'google_sheet_url' => $fbSheetUrl,
    'is_enabled' => (bool) $fbEnabled,
    'title' => $fbTitle,
    'subtitle' => $fbSubtitle,
    'updated_at' => date('Y-m-d H:i:s'),
];
saveJsonBoth('feedback_config.json', $feedbackConfig, $mainTargetDir);

$fbProcessRaw = \App\Models\Setting::where('key', 'feedback_process_guide')->value('value');
$fbProcessData = $fbProcessRaw ? json_decode($fbProcessRaw, true) : [
    'title' => 'QUY TRÌNH 4 BƯỚC TIẾP NHẬN',
    'steps' => [
        [
            'title' => 'Bước 1: Tiếp nhận phản ánh',
            'desc' => 'Người dân gửi thông tin phản ánh qua hệ thống.',
        ],
        [
            'title' => 'Bước 2: Phân loại và chuyển xử lý',
            'desc' => 'Phản ánh được phân loại và chuyển đến bộ phận phụ trách.',
        ],
        [
            'title' => 'Bước 3: Kiểm tra, xác minh',
            'desc' => 'Cán bộ phụ trách kiểm tra thực tế và xác minh nội dung phản ánh.',
        ],
        [
            'title' => 'Bước 4: Phối hợp giải quyết',
            'desc' => 'Các bộ phận liên quan thực hiện xử lý theo chức năng và thẩm quyền.',
        ],
    ],
];
saveJsonBoth('feedback_process.json', $fbProcessData, $mainTargetDir);

// 12. Citizen Reception Schedule (Lịch tiếp công dân)
echo "Dumping citizen reception schedule...\n";
$crTitle = \App\Models\Setting::where('key', 'citizen_reception_title')->value('value') ?? 'LỊCH TIẾP CÔNG DÂN ĐỊNH KỲ NĂM 2026';
$crImage = \App\Models\Setting::where('key', 'citizen_reception_image')->value('value') ?? '';
$crTime = \App\Models\Setting::where('key', 'citizen_reception_schedule_time')->value('value') ?? 'Thứ 5 hàng tuần (Sáng: 07h30 - 11h30 | Chiều: 13h30 - 17h00)';
$crLocation = \App\Models\Setting::where('key', 'citizen_reception_location')->value('value') ?? 'Phòng Tiếp công dân — Trụ sở UBND Phường Duy Hà (Số 01 đường Lê Lợi, TP. Ninh Bình)';
$crOfficer = \App\Models\Setting::where('key', 'citizen_reception_officer')->value('value') ?? 'Đồng chí Chủ tịch UBND Phường và các Phó Chủ tịch UBND Phường';
$crNotes = \App\Models\Setting::where('key', 'citizen_reception_notes')->value('value') ?? 'Công dân khi đến khiếu nại, tố cáo, kiến nghị, phản ánh cần xuất trình Căn cước công dân và các giấy tờ, tài liệu liên quan đến nội dung phản ánh.';
$crRulesRaw = \App\Models\Setting::where('key', 'citizen_reception_rules')->value('value');
$crRules = $crRulesRaw ? json_decode($crRulesRaw, true) : [
    'Xuất trình giấy tờ tùy thân (Căn cước công dân hoặc VNeID Mức 2) khi vào phòng tiếp dân.',
    'Trình bày nội dung rõ ràng, trung thực và cung cấp chứng cứ, tài liệu liên quan đến vụ việc.',
    'Giữ gìn trật tự, trang phục lịch sự, chấp hành hướng dẫn của cán bộ tiếp dân.',
    'Nghiêm cấm mang theo vũ khí, chất cháy nổ hoặc các vật dụng gây nguy hiểm vào trụ sở.',
];

$citizenReceptionData = [
    'title' => $crTitle,
    'image' => $formatStorage($crImage),
    'schedule_time' => $crTime,
    'location' => $crLocation,
    'officer' => $crOfficer,
    'notes' => $crNotes,
    'rules' => $crRules,
    'updated_at' => date('Y-m-d H:i:s'),
];
saveJsonBoth('citizen_reception.json', $citizenReceptionData, $mainTargetDir);

// 13. Administrative Procedures (Thủ tục hành chính)
echo "Dumping administrative procedures...\n";
$procCategoriesMap = \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray();
$procedures = \App\Models\Procedure::where('is_active', true)
    ->orderBy('sort_order', 'asc')
    ->orderBy('id', 'desc')
    ->get()
    ->map(function($p) use ($procCategoriesMap) {
        $docsList = collect($p->docs ?? [])->map(function($doc) {
            if (is_array($doc)) {
                $file = $doc['file'] ?? null;
                $fileUrl = null;
                if (!empty($file)) {
                    $fileUrl = \Illuminate\Support\Str::startsWith($file, 'http') ? $file : ('/storage/' . ltrim($file, '/'));
                }
                return [
                    'name' => $doc['name'] ?? '',
                    'quantity' => $doc['quantity'] ?? '01 bản chính',
                    'file' => $file,
                    'file_url' => $fileUrl,
                ];
            }
            return [
                'name' => (string) $doc,
                'quantity' => '01 bản chính',
                'file' => null,
                'file_url' => null,
            ];
        })->values();

        $attachmentUrl = null;
        $firstDocWithFile = collect($docsList)->first(function ($d) {
            return !empty($d['file_url']);
        });

        if ($firstDocWithFile) {
            $attachmentUrl = $firstDocWithFile['file_url'];
        } elseif (!empty($p->attachment)) {
            $attachmentUrl = \Illuminate\Support\Str::startsWith($p->attachment, 'http') ? $p->attachment : ('/storage/' . ltrim($p->attachment, '/'));
        } elseif (!empty($p->download_url) && !str_contains($p->download_url, 'dichvucong.gov.vn')) {
            $attachmentUrl = $p->download_url;
        }

        return [
            'id' => $p->id,
            'code' => $p->code ?? ('TTHC-' . str_pad($p->id, 3, '0', STR_PAD_LEFT)),
            'title' => $p->title,
            'name' => $p->title,
            'category' => $p->category,
            'categoryText' => $p->category_text ?? ($procCategoriesMap[$p->category] ?? 'Thủ tục hành chính'),
            'desc' => $p->desc,
            'fee' => $p->fee ?? 'Miễn phí',
            'agency' => $p->agency ?? 'UBND Phường',
            'docs' => $docsList,
            'attachment_url' => $attachmentUrl,
            'created_at' => $p->created_at ? $p->created_at->format('d/m/Y') : null,
        ];
    });
saveJsonBoth('procedures.json', $procedures, $mainTargetDir);

// 14. Procedure Videos (Video hướng dẫn thủ tục)
echo "Dumping procedure videos...\n";
$procedureVideos = \App\Models\ProcedureVideo::where('is_active', true)
    ->orderBy('sort_order', 'asc')
    ->orderBy('id', 'desc')
    ->get()
    ->map(function($v) use ($procCategoriesMap) {
        $url = trim($v->video_url ?? '');
        if (!empty($url)) {
            // Google Drive auto-convert
            if (str_contains($url, 'drive.google.com')) {
                if (!str_contains($url, '/preview')) {
                    if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $m) || preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $m)) {
                        $url = 'https://drive.google.com/file/d/' . $m[1] . '/preview';
                    }
                }
            }
            // YouTube auto-convert
            elseif (!str_contains($url, 'youtube.com/embed/')) {
                if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/))([\w-]{11})/', $url, $m)) {
                    $url = 'https://www.youtube.com/embed/' . $m[1] . '?controls=1&rel=0&enablejsapi=1';
                }
            }
        }

        return [
            'id' => $v->id,
            'title' => $v->title,
            'category' => $v->category,
            'categoryText' => $procCategoriesMap[$v->category] ?? 'Lĩnh vực khác',
            'videoUrl' => $url,
            'sort_order' => $v->sort_order,
        ];
    });
saveJsonBoth('procedure_videos.json', $procedureVideos, $mainTargetDir);

// 15. Policies & Regulations (Chính sách & Quy định)
echo "Dumping policies...\n";
$sharedCategoryMap = \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray();
$policies = \App\Models\Policy::where('is_active', true)
    ->orderBy('sort_order', 'asc')
    ->orderBy('id', 'desc')
    ->get()
    ->map(function($p) use ($sharedCategoryMap) {
        $downloadUrl = '#';
        if (!empty($p->download_url)) {
            if (str_starts_with($p->download_url, 'http://') || str_starts_with($p->download_url, 'https://') || $p->download_url === '#') {
                $downloadUrl = $p->download_url;
            } else {
                $downloadUrl = '/storage/' . ltrim($p->download_url, '/');
            }
        }

        return [
            'id' => $p->id,
            'title' => $p->title,
            'code' => $p->code ?? '',
            'category' => $p->category,
            'categoryText' => $sharedCategoryMap[$p->category] ?? 'Lĩnh vực khác',
            'date' => $p->issue_date ?? '',
            'agency' => $p->agency ?? '',
            'status' => $p->status ?? 'Đang có hiệu lực',
            'summary' => $p->summary ?? '',
            'highlights' => $p->highlights ?? [],
            'downloadUrl' => $downloadUrl,
            'sort_order' => $p->sort_order,
        ];
    });
saveJsonBoth('policies.json', $policies, $mainTargetDir);

// 16. Waste Schedules & Classification Guide
echo "Dumping waste schedules & classification guide...\n";
if (\Illuminate\Support\Facades\Schema::hasTable('waste_schedules')) {
    $wasteSchedules = \App\Models\WasteSchedule::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->orderBy('id', 'asc')
        ->get();
    saveJsonBoth('waste_schedules.json', $wasteSchedules, $mainTargetDir);
}

$wasteGuideRaw = \App\Models\Setting::where('key', 'waste_classification_guide')->value('value');
$wasteGuideData = $wasteGuideRaw ? json_decode($wasteGuideRaw, true) : [
    'title' => 'Hướng dẫn phân loại rác tại nguồn',
    'subtitle' => 'Thực hiện Luật Bảo vệ môi trường — Chung tay xây dựng Phường Duy Hà Xanh - Sạch - Văn minh',
    'categories' => [
        [
            'title' => '1. RÁC HỮU CƠ (DỄ PHÂN HỦY)',
            'desc' => 'Thức ăn thừa, rau củ quả thải loại, vỏ trái cây, bã chè, lá cây nhỏ...',
            'icon' => 'compost',
            'theme' => 'emerald',
        ],
        [
            'title' => '2. RÁC TÁI CHẾ (PHẾ LIỆU)',
            'desc' => 'Bìa carton, giấy báo cũ, chai lọ nhựa, vỏ lon kim loại, đồ nhựa gia dụng...',
            'icon' => 'inventory_2',
            'theme' => 'amber',
        ],
        [
            'title' => '3. RÁC THẢI CÒN LẠI (VÔ CƠ)',
            'desc' => 'Túi nilon bẩn, hộp xốp, tã bỉm, gốm sứ vỡ, rác quét nhà, cành cây tỉa...',
            'icon' => 'delete_sweep',
            'theme' => 'slate',
        ],
    ],
    'regulation' => 'Bỏ rác đúng giờ trước khi xe đến 15-30 phút. Hành vi vứt rác bừa bãi ra vỉa hè, lòng đường bị phạt tiền từ 500.000đ — 2.000.000đ theo Nghị định 45/2022/NĐ-CP.',
];
saveJsonBoth('waste_classification_guide.json', $wasteGuideData, $mainTargetDir);

// 17. Form Documents
echo "Dumping form documents...\n";
if (\Illuminate\Support\Facades\Schema::hasTable('form_documents')) {
    $formDocs = \App\Models\FormDocument::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->orderBy('id', 'asc')
        ->get()
        ->map(function ($f) {
            $downloadUrl = '#';
            if (!empty($f->download_url)) {
                $downloadUrl = $f->download_url;
            } elseif (!empty($f->file_path)) {
                $downloadUrl = '/storage/' . ltrim($f->file_path, '/');
            }
            return [
                'id' => $f->id,
                'code' => $f->code ?? '',
                'title' => $f->title,
                'name' => $f->title,
                'description' => $f->description ?? '',
                'purpose' => $f->description ?? '',
                'category' => $f->category,
                'category_name' => $f->category_text ?? 'Thủ tục hành chính',
                'agency' => $f->agency ?? 'Bộ phận Một cửa',
                'fee' => $f->fee ?? 'Miễn phí',
                'file_path' => $f->file_path,
                'download_url' => $downloadUrl,
                'downloadUrl' => $downloadUrl,
                'steps' => $f->steps ?? [],
                'docs' => $f->docs ?? [],
                'notes' => $f->notes ?? '',
            ];
        });
    saveJsonBoth('form_documents.json', $formDocs, $mainTargetDir);
}

// 18. Procedure Categories
echo "Dumping procedure categories...\n";
if (\Illuminate\Support\Facades\Schema::hasTable('procedure_categories')) {
    $procCats = \App\Models\ProcedureCategory::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->orderBy('id', 'asc')
        ->get();
    saveJsonBoth('procedure_categories.json', $procCats, $mainTargetDir);
}

// Copy storage files to client/public/storage
function copyStorageToClientPublic() {
    $source = __DIR__ . '/storage/app/public';
    $dest = dirname(__DIR__) . '/client/public/storage';

    if (!file_exists($source)) return;

    $dir = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);

    foreach ($iterator as $item) {
        $subPath = $iterator->getSubPathName();
        $targetPath = $dest . '/' . $subPath;

        if ($item->isDir()) {
            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0755, true);
            }
        } else {
            $targetDir = dirname($targetPath);
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            copy($item->getPathname(), $targetPath);
        }
    }
}
copyStorageToClientPublic();

echo "All data dumped successfully to client/src/data!\n";
