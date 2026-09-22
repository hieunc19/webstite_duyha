<?php

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/places', function () {
    return Place::where('status', 'active')
        ->get()
        ->map(function ($place) {
            return [
                'id' => $place->id,
                'name' => $place->name,
                'category' => $place->category,
                'status' => $place->status,
                'address' => $place->address,
                'phone' => $place->phone,
                'lat' => (float) $place->lat,
                'lng' => (float) $place->lng,
                'image' => $place->image ? (Str::startsWith($place->image, 'http') ? $place->image : '/api/storage/' . ltrim($place->image, '/')) : null,
                'administrative_unit_id' => $place->administrative_unit_id,
                'description' => $place->description,
            ];
        });
});

Route::get('/places/{id}', function (int $id) {
    $place = Place::findOrFail($id);

    return [
        'id' => $place->id,
        'name' => $place->name,
        'category' => $place->category,
        'status' => $place->status,
        'address' => $place->address,
        'phone' => $place->phone,
        'lat' => (float) $place->lat,
        'lng' => (float) $place->lng,
        'image' => $place->image ? (Str::startsWith($place->image, 'http') ? $place->image : '/api/storage/' . ltrim($place->image, '/')) : null,
        'administrative_unit_id' => $place->administrative_unit_id,
        'description' => $place->description,
    ];
});


Route::get('/storage/{path}', function (string $path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $filePath = Storage::disk('public')->path($path);

    return response()->file($filePath, [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
        'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
    ]);
})->where('path', '.*');

Route::get('/provinces', function () {
    return \App\Models\Province::orderBy('name')->get(['code', 'name', 'full_name', 'latitude', 'longitude']);
});

Route::get('/provinces/{code}/wards', function (string $code) {
    return \App\Models\AdministrativeUnit::where('province_code', $code)
        ->orderBy('name')
        ->get(['id', 'code', 'name', 'type', 'latitude', 'longitude', 'district_name']);
});

Route::get('/provinces/{code}/boundary', function (string $code) {
    $geojsonBaseDir = base_path('../scratch/vietnamese-provinces-database/json/geojson');
    $directories = glob($geojsonBaseDir . '/' . $code . '_*');
    if (!empty($directories)) {
        $dirPath = $directories[0];
        $dirName = basename($dirPath);
        $filePath = $dirPath . '/' . $dirName . '.geojson';
        if (file_exists($filePath)) {
            return response()->file($filePath, [
                'Content-Type' => 'application/json',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            ]);
        }
    }
    return response()->json(['error' => 'Province boundary not found'], 404);
});


Route::get('/administrative-units/{id}', function (int $id) {
    $unit = \App\Models\AdministrativeUnit::findOrFail($id);
    return [
        'id' => $unit->id,
        'code' => $unit->code,
        'name' => $unit->name,
        'type' => $unit->type,
        'lat' => (float) $unit->latitude,
        'lng' => (float) $unit->longitude,
        'link' => $unit->link,
        'boundary' => $unit->boundary_data,
    ];
});

Route::get('/administrative-units', function () {
    // Trả về danh sách xã của Ninh Bình (37) để tương thích ngược
    return \App\Models\AdministrativeUnit::where('province_code', '37')
        ->get()
        ->map(function ($unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'type' => $unit->type,
                'lat' => (float) $unit->latitude,
                'lng' => (float) $unit->longitude,
                'link' => $unit->link,
                'boundary' => $unit->boundary_data,
            ];
        });
});

Route::get('/neighborhoods', function () {
    return \App\Models\Neighborhood::all()
        ->map(function ($n) {
            return [
                'id' => $n->id,
                'name' => $n->name,
                'type' => $n->type,
                'group_code' => $n->group_code,
                'leader_name' => $n->leader_name,
                'leader_phone' => $n->leader_phone,
                'households' => (int) $n->households,
                'people' => (int) $n->people,
                'area_ha' => (float) ($n->area_ha ?? 0),
                'status' => $n->status ?? 'active',
                'bi_thu_name' => $n->bi_thu_name,
                'bi_thu_phone' => $n->bi_thu_phone,
                'to_truong_name' => $n->to_truong_name,
                'to_truong_phone' => $n->to_truong_phone,
                'cskv_name' => $n->cskv_name,
                'cskv_phone' => $n->cskv_phone,
                'mat_tan_name' => $n->mat_tan_name,
                'mat_tan_phone' => $n->mat_tan_phone,
                'nguoi_cao_tuoi' => $n->nguoi_cao_tuoi,
                'nguoi_cao_tuoi_phone' => $n->nguoi_cao_tuoi_phone,
                'phu_nu' => $n->phu_nu,
                'phu_nu_phone' => $n->phu_nu_phone,
                'nong_dan' => $n->nong_dan,
                'nong_dan_phone' => $n->nong_dan_phone,
                'ccb' => $n->ccb,
                'ccb_phone' => $n->ccb_phone,
                'doan_thanh_nien' => $n->doan_thanh_nien,
                'doan_thanh_nien_phone' => $n->doan_thanh_nien_phone,
            ];
        });
});

Route::get('/tdp-officials', function () {
    // Map new TDPs for the 10 official modal rows
    return \App\Models\Neighborhood::where('type', 'new')->get()->map(function ($item, $index) {
        return [
            'tt'                  => $index + 1,
            'tdp'                 => preg_replace('/^(TDP|Tổ dân phố)\s+/ui', '', trim($item->name)),
            'biThuName'           => $item->bi_thu_name ?? '',
            'biThuPhone'          => $item->bi_thu_phone ?? '',
            'toTruongName'        => $item->to_truong_name ?? '',
            'toTruongPhone'       => $item->to_truong_phone ?? '',
            'cskvName'            => $item->cskv_name ?? '',
            'cskvPhone'           => $item->cskv_phone ?? '',
            'matTanName'          => $item->mat_tan_name ?? '',
            'matTanPhone'         => $item->mat_tan_phone ?? '',
            'nguoiCaoTuoi'        => $item->nguoi_cao_tuoi ?? '',
            'nguoiCaoTuoiPhone'   => $item->nguoi_cao_tuoi_phone ?? '',
            'phuNu'               => $item->phu_nu ?? '',
            'phuNuPhone'          => $item->phu_nu_phone ?? '',
            'nongDan'             => $item->nong_dan ?? '',
            'nongDanPhone'        => $item->nong_dan_phone ?? '',
            'ccb'                 => $item->ccb ?? '',
            'ccbPhone'            => $item->ccb_phone ?? '',
            'doanThanhNien'       => $item->doan_thanh_nien ?? '',
            'doanThanhNienPhone'  => $item->doan_thanh_nien_phone ?? '',
        ];
    });
});

Route::get('/settings', function () {
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

    return response()->json([
        'cards'      => $cards,
        'stat_1_val' => $s1 ? $s1->value : '10',
        'stat_1_lbl' => $s1 ? $s1->label : 'Tổng số tổ dân phố',
        'stat_2_val' => $s2 ? $s2->value : '6.767',
        'stat_2_lbl' => $s2 ? $s2->label : 'Tổng số hộ gia đình',
        'stat_3_val' => $s3 ? $s3->value : '23.615',
        'stat_3_lbl' => $s3 ? $s3->label : 'Tổng số nhân khẩu',
        'stat_4_val' => $s4 ? $s4->value : '15,46 km²',
        'stat_4_lbl' => $s4 ? $s4->label : 'Diện tích (1.546,30 ha)',
    ]);
});

Route::get('/homepage-sections', function () {
    $managedCodes = [
        'header_navbar',
        'hero_banner',
        'stats_cards',
        'agencies_grid',
        'quick_utilities',
        'procedures_utilities',
        'hdsd_procedure',
        'footer_section',
    ];

    return \App\Models\HomepageSection::where(function ($query) use ($managedCodes) {
            $query
                ->whereIn('section_code', $managedCodes)
                ->orWhere('section_code', 'like', 'custom_%');
        })
        ->orderBy('sort_order', 'asc')
        ->get()
        ->map(function ($sec) {
        return [
            'id'              => $sec->id,
            'section_code'    => $sec->section_code,
            'name'            => $sec->name,
            'custom_title'    => $sec->custom_title,
            'custom_subtitle' => $sec->custom_subtitle,
            'is_visible'      => (bool) $sec->is_visible,
            'sort_order'      => (int) $sec->sort_order,
            'settings'        => $sec->settings,
        ];
    });
});

Route::get('/subpage-banners', function () {
    $setting = \App\Models\Setting::where('key', 'subpage_banners')->first();
    if ($setting && !empty($setting->value)) {
        $decoded = json_decode($setting->value, true);
        if (is_array($decoded)) {
            // Uploaded files live on Laravel's public disk. Always expose
            // them through the API so a separate frontend/static Nginx root
            // does not try to resolve /storage as a frontend file.
            foreach ($decoded as &$banner) {
                if (!is_array($banner) || empty($banner['bg_image']) || !is_string($banner['bg_image'])) {
                    continue;
                }

                if (Str::startsWith($banner['bg_image'], '/storage/')) {
                    $banner['bg_image'] = '/api' . $banner['bg_image'];
                } elseif (Str::startsWith($banner['bg_image'], 'storage/')) {
                    $banner['bg_image'] = '/api/' . $banner['bg_image'];
                }
            }
            unset($banner);

            return response()->json($decoded);
        }
    }

    $page = new \App\Filament\Pages\ManageSubpageBanners();
    return response()->json($page->defaultBanners());
});

Route::get('/celebration-events/active', function (Request $request) {
    $event = \App\Models\CelebrationEvent::where('is_featured', true)
        ->where('status', 'active')
        ->first();

    if (!$event) {
        $event = \App\Models\CelebrationEvent::where('status', 'active')->first();
    }

    if (!$event) {
        return response()->json(null);
    }

    $families = $event->meritoriousFamilies()
        ->where('status', 'active')
        ->with('neighborhood')
        ->get()
        ->map(function ($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'type' => $f->type,
                'neighborhood_name' => $f->neighborhood ? $f->neighborhood->name : null,
                'address' => $f->address,
                'representative_name' => $f->representative_name,
                'phone' => $f->phone,
                'benefit_details' => $f->benefit_details,
            ];
        });

    return [
        'id' => $event->id,
        'name' => $event->name,
        'month' => (int) $event->month,
        'day' => (int) $event->day,
        'is_featured' => (bool) $event->is_featured,
        'description' => $event->description,
        'families' => $families,
    ];
});

Route::get('/departments', function () {
    return \App\Models\Department::where('status', 'active')
        ->orderBy('sort_order', 'asc')
        ->get()
        ->map(function ($d) {
            return [
                'id' => $d->id,
                'code' => $d->code,
                'name' => $d->name,
                'color' => $d->color,
                'sort_order' => (int) $d->sort_order,
                'status' => $d->status,
                'description' => $d->description,
            ];
        });
});

Route::get('/officials', function () {
    return \App\Models\Official::where('status', 'active')
        ->orderBy('id')
        ->get()
        ->map(function ($o) {
            return [
                'id' => $o->id,
                'name' => $o->name,
                'role' => $o->role,
                'phone' => $o->phone,
                'neighborhood_name' => $o->neighborhood_name,
                'avatar_color' => $o->avatar_color,
                'avatar' => $o->avatar,
                'department' => $o->department,
            ];
        });
});

Route::get('/celebration-events', function () {
    return \App\Models\CelebrationEvent::where('status', 'active')
        ->orderBy('month')
        ->orderBy('day')
        ->get()
        ->map(function ($e) {
            return [
                'id' => $e->id,
                'name' => $e->name,
                'month' => (int) $e->month,
                'day' => (int) $e->day,
                'description' => $e->description,
                'is_featured' => (bool) $e->is_featured,
                'status' => $e->status,
            ];
        });
});

Route::get('/meritorious-families', function () {
    return \App\Models\MeritoriousFamily::where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($f) {
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
                'period_date' => $f->period_date ?: ($f->created_at?->format('d/m/Y') ?? ''),
            ];
        });
});

Route::get('/feedback-config', function () {
    $fbFormUrl = \App\Models\Setting::where('key', 'feedback_google_form_url')->value('value') ?? '';
    $fbSheetUrl = \App\Models\Setting::where('key', 'feedback_google_sheet_url')->value('value') ?? '';
    $fbEnabled = \App\Models\Setting::where('key', 'feedback_is_enabled')->value('value') ?? '1';
    $fbTitle = \App\Models\Setting::where('key', 'feedback_title')->value('value') ?? 'Phản ánh và kiến nghị';
    $fbSubtitle = \App\Models\Setting::where('key', 'feedback_subtitle')->value('value') ?? 'Kênh tiếp nhận và giải quyết ý kiến phản ánh trực tuyến của công dân';

    return response()->json([
        'google_form_url' => $fbFormUrl,
        'google_sheet_url' => $fbSheetUrl,
        'is_enabled' => (bool) $fbEnabled,
        'title' => $fbTitle,
        'subtitle' => $fbSubtitle,
    ]);
});

Route::get('/feedback-process', function () {
    $fbProcessRaw = \App\Models\Setting::where('key', 'feedback_process_guide')->value('value');
    $fbProcessData = $fbProcessRaw ? json_decode($fbProcessRaw, true) : [
        'title' => 'QUY TRÌNH 4 BƯỚC TIẾP NHẬN',
        'steps' => [
            ['title' => 'Bước 1: Tiếp nhận phản ánh', 'desc' => 'Người dân gửi thông tin phản ánh qua hệ thống.'],
            ['title' => 'Bước 2: Phân loại và chuyển xử lý', 'desc' => 'Phản ánh được phân loại và chuyển đến bộ phận phụ trách.'],
            ['title' => 'Bước 3: Kiểm tra, xác minh', 'desc' => 'Cán bộ phụ trách kiểm tra thực tế và xác minh nội dung phản ánh.'],
            ['title' => 'Bước 4: Phối hợp giải quyết', 'desc' => 'Các bộ phận liên quan thực hiện xử lý theo chức năng và thẩm quyền.'],
        ],
    ];
    return response()->json($fbProcessData);
});


Route::post('/submit-feedback', function (Request $request) {
    $validated = $request->validate([
        'fullname' => 'required|string|max:255',
        'phone' => 'required|string|max:50',
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    // 1. Save in local database
    $feedback = \App\Models\Feedback::create([
        'fullname' => $validated['fullname'],
        'phone' => $validated['phone'],
        'title' => $validated['title'],
        'content' => $validated['content'],
        'status' => 'pending',
        'ip_address' => $request->ip(),
        'synced_to_sheets' => false,
    ]);

    // 2. Forward to Google Form. The feedback is always retained locally first,
    // so a temporary Google/network outage never loses a citizen submission.
    $googleFormSync = [
        'configured' => false,
        'synced' => false,
        'error' => null,
    ];

    $googleFormUrl = \App\Models\Setting::where('key', 'feedback_google_form_url')->value('value');
    if (!empty($googleFormUrl)) {
        $googleFormSync['configured'] = true;

        try {
            $formResponseUrl = preg_replace('/(\/(viewform|edit)).*$/', '/formResponse', $googleFormUrl);
            if (!str_contains($formResponseUrl, '/formResponse')) {
                $formResponseUrl = rtrim($formResponseUrl, '/') . '/formResponse';
            }

            // Extract the real entry IDs from the public Google Form. Never
            // cache fabricated fallback IDs: Google accepts those requests
            // with HTTP 200 but silently discards every submitted field.
            $entryMapCacheKey = 'gf_entries_v2_' . md5($googleFormUrl);
            $entryMap = \Illuminate\Support\Facades\Cache::get($entryMapCacheKey);

            if (!is_array($entryMap)) {
                $viewUrl = preg_replace('/(\/(formResponse|viewform|edit)).*$/', '/viewform', $googleFormUrl);
                $schemaResponse = \Illuminate\Support\Facades\Http::connectTimeout(5)->timeout(10)->get($viewUrl);

                if (!$schemaResponse->ok() || !preg_match('/var FB_PUBLIC_LOAD_DATA_ = (\[.+?\]);<\/script>/s', $schemaResponse->body(), $matches)) {
                    throw new \RuntimeException('Không thể đọc cấu trúc trường của Google Form.');
                }

                $data = json_decode($matches[1], true);
                $questions = $data[1][1] ?? [];
                $entryMap = [];
                $allEntries = [];

                foreach ($questions as $question) {
                    $questionTitle = mb_strtolower(trim($question[1] ?? ''));
                    $entryId = $question[4][0][0] ?? null;

                    if (!$entryId) {
                        continue;
                    }

                    $entryName = 'entry.' . $entryId;
                    $allEntries[] = $entryName;

                    if (str_contains($questionTitle, 'họ') || str_contains($questionTitle, 'tên') || str_contains($questionTitle, 'người gửi') || str_contains($questionTitle, 'name')) {
                        $entryMap['fullname'] = $entryName;
                    } elseif (str_contains($questionTitle, 'điện thoại') || str_contains($questionTitle, 'sđt') || str_contains($questionTitle, 'phone') || str_contains($questionTitle, 'liên hệ') || str_contains($questionTitle, 'số')) {
                        $entryMap['phone'] = $entryName;
                    } elseif (str_contains($questionTitle, 'tiêu đề') || str_contains($questionTitle, 'chủ đề') || str_contains($questionTitle, 'title') || str_contains($questionTitle, 'vấn đề')) {
                        $entryMap['title'] = $entryName;
                    } elseif (str_contains($questionTitle, 'nội dung') || str_contains($questionTitle, 'chi tiết') || str_contains($questionTitle, 'content') || str_contains($questionTitle, 'ý kiến') || str_contains($questionTitle, 'mô tả')) {
                        $entryMap['content'] = $entryName;
                    }
                }

                // The first four public questions are the four required fields
                // in the managed form. This fallback is allowed only when it
                // comes from the form schema fetched successfully above.
                foreach (['fullname', 'phone', 'title', 'content'] as $index => $field) {
                    if (empty($entryMap[$field]) && isset($allEntries[$index])) {
                        $entryMap[$field] = $allEntries[$index];
                    }
                }

                if (count(array_filter($entryMap)) !== 4) {
                    throw new \RuntimeException('Google Form không có đủ bốn trường bắt buộc để tiếp nhận phản ánh.');
                }

                \Illuminate\Support\Facades\Cache::put($entryMapCacheKey, $entryMap, now()->addHour());
            }

            $postData = [];
            if (!empty($entryMap['fullname'])) $postData[$entryMap['fullname']] = $validated['fullname'];
            if (!empty($entryMap['phone'])) $postData[$entryMap['phone']] = $validated['phone'];
            if (!empty($entryMap['title'])) $postData[$entryMap['title']] = $validated['title'];
            if (!empty($entryMap['content'])) $postData[$entryMap['content']] = $validated['content'];

            if (!empty($postData)) {
                $gfRes = \Illuminate\Support\Facades\Http::asForm()
                    ->connectTimeout(5)
                    ->timeout(10)
                    ->retry(2, 250, throw: false)
                    ->post($formResponseUrl, $postData);

                if ($gfRes->successful()) {
                    $googleFormSync['synced'] = true;
                    $feedback->update(['synced_to_sheets' => true]);
                } else {
                    $googleFormSync['error'] = 'Google Form không xác nhận tiếp nhận dữ liệu.';
                    \Illuminate\Support\Facades\Log::warning('Google Form sync failed', [
                        'feedback_id' => $feedback->id,
                        'status' => $gfRes->status(),
                    ]);
                }
            } else {
                $googleFormSync['error'] = 'Không tìm thấy các trường nhận dữ liệu của Google Form.';
            }
        } catch (\Throwable $e) {
            $googleFormSync['error'] = 'Không thể kết nối tới Google Form. Dữ liệu đã được lưu an toàn trong hệ thống.';
            \Illuminate\Support\Facades\Log::warning('Google Form sync error', [
                'feedback_id' => $feedback->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // 3. Forward to Google Sheets Webhook if configured
    $webhookSync = [
        'configured' => false,
        'synced' => false,
        'error' => null,
    ];

    $webhookUrl = \App\Models\Setting::where('key', 'feedback_google_sheet_webhook_url')->value('value');
    if (!empty($webhookUrl)) {
        $webhookSync['configured'] = true;

        try {
            $webhookResponse = \Illuminate\Support\Facades\Http::connectTimeout(5)->timeout(10)->post($webhookUrl, [
                'id' => $feedback->id,
                'fullname' => $validated['fullname'],
                'phone' => $validated['phone'],
                'title' => $validated['title'],
                'content' => $validated['content'],
                'created_at' => now()->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s'),
            ]);

            if ($webhookResponse->successful()) {
                $webhookSync['synced'] = true;
                $feedback->update(['synced_to_sheets' => true]);
            } else {
                $webhookSync['error'] = 'Webhook Google Sheets không xác nhận tiếp nhận dữ liệu.';
            }
        } catch (\Throwable $e) {
            $webhookSync['error'] = 'Không thể kết nối tới webhook Google Sheets.';
            \Illuminate\Support\Facades\Log::warning('Google Sheets webhook sync error', [
                'feedback_id' => $feedback->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    $externallySynced = $googleFormSync['synced'] || $webhookSync['synced'];

    return response()->json([
        'status' => $externallySynced ? 'success' : 'accepted_with_warning',
        'message' => $externallySynced
            ? 'Ý kiến phản ánh của bạn đã được tiếp nhận thành công!'
            : 'Ý kiến phản ánh đã được lưu trong hệ thống, nhưng chưa thể chuyển tiếp tới Google.',
        'id' => $feedback->id,
        'sync' => [
            'google_form' => $googleFormSync,
            'google_sheets_webhook' => $webhookSync,
        ],
    ], $externallySynced ? 201 : 202);
});

Route::get('/citizen-reception', function () {
    $title = \App\Models\Setting::where('key', 'citizen_reception_title')->value('value') ?? 'LỊCH TIẾP CÔNG DÂN ĐỊNH KỲ NĂM 2026';
    $rawImage = \App\Models\Setting::where('key', 'citizen_reception_image')->value('value') ?? '';
    $time = \App\Models\Setting::where('key', 'citizen_reception_schedule_time')->value('value') ?? 'Thứ 5 hàng tuần (Sáng: 07h30 - 11h30 | Chiều: 13h30 - 17h00)';
    $location = \App\Models\Setting::where('key', 'citizen_reception_location')->value('value') ?? 'Phòng Tiếp công dân — Trụ sở UBND Phường Duy Hà (Số 01 đường Lê Lợi, TP. Ninh Bình)';
    $officer = \App\Models\Setting::where('key', 'citizen_reception_officer')->value('value') ?? 'Đồng chí Chủ tịch UBND Phường và các Phó Chủ tịch UBND Phường';
    $notes = \App\Models\Setting::where('key', 'citizen_reception_notes')->value('value') ?? 'Công dân khi đến khiếu nại, tố cáo, kiến nghị, phản ánh cần xuất trình Căn cước công dân và các giấy tờ, tài liệu liên quan đến nội dung phản ánh.';
    $rulesRaw = \App\Models\Setting::where('key', 'citizen_reception_rules')->value('value');
    $rules = $rulesRaw ? json_decode($rulesRaw, true) : [
        'Xuất trình giấy tờ tùy thân (Căn cước công dân hoặc VNeID Mức 2) khi vào phòng tiếp dân.',
        'Trình bày nội dung rõ ràng, trung thực và cung cấp chứng cứ, tài liệu liên quan đến vụ việc.',
        'Giữ gìn trật tự, trang phục lịch sự, chấp hành hướng dẫn của cán bộ tiếp dân.',
        'Nghiêm cấm mang theo vũ khí, chất cháy nổ hoặc các vật dụng gây nguy hiểm vào trụ sở.',
    ];

    $imageUrl = null;
    if (!empty($rawImage)) {
        $imageUrl = \Illuminate\Support\Str::startsWith($rawImage, 'http') ? $rawImage : '/api/storage/' . ltrim($rawImage, '/');
    }

    return response()->json([
        'title' => $title,
        'image' => $rawImage,
        'image_url' => $imageUrl,
        'schedule_time' => $time,
        'location' => $location,
        'officer' => $officer,
        'notes' => $notes,
        'rules' => $rules,
    ]);
});

Route::get('/waste-classification-guide', function () {
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

    return response()->json($wasteGuideData);
});

Route::get('/policies', function () {
    $categoryMap = \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray();

    return response()->json(
        \App\Models\Policy::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($p) use ($categoryMap) {
                $downloadUrl = '#';

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
                    'categoryText' => $categoryMap[$p->category] ?? 'Lĩnh vực khác',
                    'date' => $p->issue_date ?? '',
                    'agency' => $p->agency ?? '',
                    'status' => $p->status ?? 'Đang có hiệu lực',
                    'summary' => $p->summary ?? '',
                    'highlights' => $p->highlights ?? [],
                    'downloadUrl' => $downloadUrl,
                    'sort_order' => $p->sort_order,
                ];
            })
    );
});

Route::get('/waste-schedules', function () {
    return response()->json(
        \App\Models\WasteSchedule::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
    );
});

Route::get('/form-documents', function () {
    return response()->json(
        \App\Models\FormDocument::where('is_active', true)
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
            })
    );
});

Route::get('/procedure-categories', function () {
    return response()->json(
        \App\Models\ProcedureCategory::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
    );
});

/**
 * Single-request homepage payload.
 *
 * The homepage needs several read-only datasets. Combining those reads into
 * one request reduces PHP-FPM process churn on small VPS instances while the
 * individual endpoints above remain available for existing pages/integrations.
 */
Route::get('/homepage-data', function () {
    $places = \App\Models\Place::where('status', 'active')
        ->get()
        ->map(function ($place) {
            return [
                'id' => $place->id,
                'name' => $place->name,
                'category' => $place->category,
                'status' => $place->status,
                'address' => $place->address,
                'phone' => $place->phone,
                'lat' => (float) $place->lat,
                'lng' => (float) $place->lng,
                'image' => $place->image ? (\Illuminate\Support\Str::startsWith($place->image, 'http') ? $place->image : '/api/storage/' . ltrim($place->image, '/')) : null,
                'administrative_unit_id' => $place->administrative_unit_id,
                'description' => $place->description,
            ];
        })
        ->values();

    $officials = \App\Models\Official::where('status', 'active')
        ->orderBy('id')
        ->get()
        ->map(function ($official) {
            return [
                'id' => $official->id,
                'name' => $official->name,
                'role' => $official->role,
                'phone' => $official->phone,
                'neighborhood_name' => $official->neighborhood_name,
                'avatar_color' => $official->avatar_color,
                'avatar' => $official->avatar,
                'department' => $official->department,
            ];
        })
        ->values();

    $departments = \App\Models\Department::where('status', 'active')
        ->orderBy('sort_order', 'asc')
        ->get()
        ->map(function ($department) {
            return [
                'id' => $department->id,
                'code' => $department->code,
                'name' => $department->name,
                'color' => $department->color,
                'sort_order' => (int) $department->sort_order,
                'status' => $department->status,
                'description' => $department->description,
            ];
        })
        ->values();

    $neighborhoods = \App\Models\Neighborhood::all()
        ->map(function ($neighborhood) {
            return [
                'id' => $neighborhood->id,
                'name' => $neighborhood->name,
                'type' => $neighborhood->type,
                'group_code' => $neighborhood->group_code,
                'leader_name' => $neighborhood->leader_name,
                'leader_phone' => $neighborhood->leader_phone,
                'households' => (int) $neighborhood->households,
                'people' => (int) $neighborhood->people,
                'area_ha' => (float) ($neighborhood->area_ha ?? 0),
                'status' => $neighborhood->status ?? 'active',
                'bi_thu_name' => $neighborhood->bi_thu_name,
                'bi_thu_phone' => $neighborhood->bi_thu_phone,
                'to_truong_name' => $neighborhood->to_truong_name,
                'to_truong_phone' => $neighborhood->to_truong_phone,
                'cskv_name' => $neighborhood->cskv_name,
                'cskv_phone' => $neighborhood->cskv_phone,
                'mat_tan_name' => $neighborhood->mat_tan_name,
                'mat_tan_phone' => $neighborhood->mat_tan_phone,
                'nguoi_cao_tuoi' => $neighborhood->nguoi_cao_tuoi,
                'nguoi_cao_tuoi_phone' => $neighborhood->nguoi_cao_tuoi_phone,
                'phu_nu' => $neighborhood->phu_nu,
                'phu_nu_phone' => $neighborhood->phu_nu_phone,
                'nong_dan' => $neighborhood->nong_dan,
                'nong_dan_phone' => $neighborhood->nong_dan_phone,
                'ccb' => $neighborhood->ccb,
                'ccb_phone' => $neighborhood->ccb_phone,
                'doan_thanh_nien' => $neighborhood->doan_thanh_nien,
                'doan_thanh_nien_phone' => $neighborhood->doan_thanh_nien_phone,
            ];
        })
        ->values();

    $families = \App\Models\MeritoriousFamily::where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($family) {
            return [
                'id' => $family->id,
                'name' => $family->name,
                'file_path' => $family->file_path,
                'file_url' => $family->file_url,
                'file_name' => $family->file_name ?: 'Danh-sach-chinh-sach.xlsx',
                'file_size' => $family->file_size,
                'description' => $family->description,
                'status' => $family->status,
                'created_at' => $family->created_at?->format('d/m/Y H:i'),
                'period_date' => $family->period_date ?: ($family->created_at?->format('d/m/Y') ?? ''),
            ];
        })
        ->values();

    $tdpOfficials = \App\Models\Neighborhood::where('type', 'new')
        ->get()
        ->map(function ($item, $index) {
            return [
                'tt' => $index + 1,
                'tdp' => preg_replace('/^(TDP|Tổ dân phố)\s+/ui', '', trim($item->name)),
                'biThuName' => $item->bi_thu_name ?? '',
                'biThuPhone' => $item->bi_thu_phone ?? '',
                'toTruongName' => $item->to_truong_name ?? '',
                'toTruongPhone' => $item->to_truong_phone ?? '',
                'cskvName' => $item->cskv_name ?? '',
                'cskvPhone' => $item->cskv_phone ?? '',
                'matTanName' => $item->mat_tan_name ?? '',
                'matTanPhone' => $item->mat_tan_phone ?? '',
                'nguoiCaoTuoi' => $item->nguoi_cao_tuoi ?? '',
                'nguoiCaoTuoiPhone' => $item->nguoi_cao_tuoi_phone ?? '',
                'phuNu' => $item->phu_nu ?? '',
                'phuNuPhone' => $item->phu_nu_phone ?? '',
                'nongDan' => $item->nong_dan ?? '',
                'nongDanPhone' => $item->nong_dan_phone ?? '',
                'ccb' => $item->ccb ?? '',
                'ccbPhone' => $item->ccb_phone ?? '',
                'doanThanhNien' => $item->doan_thanh_nien ?? '',
                'doanThanhNienPhone' => $item->doan_thanh_nien_phone ?? '',
            ];
        })
        ->values();

    $stats = \App\Models\Setting::where('group', 'stats')
        ->orderBy('sort_order', 'asc')
        ->get();
    $statsByKey = $stats->keyBy('key');
    $iconMap = [
        'stat_1' => ['icon' => 'holiday_village', 'bg' => 'bg-blue-500/10', 'color' => 'text-blue-600 dark:text-blue-400'],
        'stat_2' => ['icon' => 'group', 'bg' => 'bg-emerald-500/10', 'color' => 'text-emerald-600 dark:text-emerald-400'],
        'stat_3' => ['icon' => 'diversity_3', 'bg' => 'bg-amber-500/10', 'color' => 'text-amber-600 dark:text-amber-400'],
        'stat_4' => ['icon' => 'map', 'bg' => 'bg-purple-500/10', 'color' => 'text-purple-600 dark:text-purple-400'],
    ];
    $cards = $stats->map(function ($item) use ($iconMap) {
        $style = $iconMap[$item->key] ?? ['icon' => 'analytics', 'bg' => 'bg-blue-500/10', 'color' => 'text-blue-600'];
        return [
            'key' => $item->key,
            'name' => $item->name,
            'value' => $item->value,
            'label' => $item->label,
            'sort_order' => (int) $item->sort_order,
            'icon' => $style['icon'],
            'bg' => $style['bg'],
            'color' => $style['color'],
        ];
    })->values();

    $defaultStats = [
        'stat_1' => ['value' => '10', 'label' => 'Tổng số tổ dân phố'],
        'stat_2' => ['value' => '6.767', 'label' => 'Tổng số hộ gia đình'],
        'stat_3' => ['value' => '23.615', 'label' => 'Tổng số nhân khẩu'],
        'stat_4' => ['value' => '15,46 km²', 'label' => 'Diện tích (1.546,30 ha)'],
    ];
    $settings = ['cards' => $cards];
    foreach ($defaultStats as $key => $default) {
        $setting = $statsByKey->get($key);
        $settings[$key . '_val'] = $setting?->value ?? $default['value'];
        $settings[$key . '_lbl'] = $setting?->label ?? $default['label'];
    }

    $managedCodes = [
        'header_navbar',
        'hero_banner',
        'stats_cards',
        'agencies_grid',
        'quick_utilities',
        'procedures_utilities',
        'hdsd_procedure',
        'footer_section',
    ];
    $sections = \App\Models\HomepageSection::where(function ($query) use ($managedCodes) {
        $query
            ->whereIn('section_code', $managedCodes)
            ->orWhere('section_code', 'like', 'custom_%');
    })
        ->orderBy('sort_order', 'asc')
        ->get()
        ->map(function ($section) {
            return [
                'id' => $section->id,
                'section_code' => $section->section_code,
                'name' => $section->name,
                'custom_title' => $section->custom_title,
                'custom_subtitle' => $section->custom_subtitle,
                'is_visible' => (bool) $section->is_visible,
                'sort_order' => (int) $section->sort_order,
                'settings' => $section->settings,
            ];
        })
        ->values();

    return response()->json([
        'places' => $places,
        'officials' => $officials,
        'departments' => $departments,
        'neighborhoods' => $neighborhoods,
        'meritorious_families' => $families,
        'tdp_officials' => $tdpOfficials,
        'settings' => $settings,
        'homepage_sections' => $sections,
        'waste_schedules' => \App\Models\WasteSchedule::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get(),
        'procedure_categories' => \App\Models\ProcedureCategory::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get(),
    ]);
});

Route::get('/procedures', function () {
    $categoriesMap = \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray();
    return response()->json(
        \App\Models\Procedure::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($p) use ($categoriesMap) {
                $docsList = collect($p->docs ?? [])->map(function ($doc) {
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
                })->values()->all();

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
                    'categoryText' => $p->category_text ?? ($categoriesMap[$p->category] ?? 'Thủ tục hành chính'),
                    'desc' => $p->desc ?? '',
                    'fee' => $p->fee ?? 'Miễn phí',
                    'agency' => $p->agency ?? 'UBND Phường',
                    'docs' => $docsList,
                    'attachment_url' => $attachmentUrl,
                    'created_at' => $p->created_at ? $p->created_at->format('d/m/Y') : '',
                ];
            })
    );
});

Route::get('/procedure-videos', function () {
    $categoriesMap = \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray();
    return response()->json(
        \App\Models\ProcedureVideo::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($v) use ($categoriesMap) {
                return [
                    'id' => $v->id,
                    'title' => $v->title,
                    'category' => $v->category,
                    'categoryText' => $categoriesMap[$v->category] ?? 'Video hướng dẫn',
                    'videoUrl' => $v->video_url,
                    'duration' => '05:00',
                    'views' => '1.0k lượt xem',
                    'desc' => $v->title,
                    'thumbnail' => '/hero-bg.jpg',
                ];
            })
    );
});

Route::get('/policies', function () {
    $categoriesMap = \App\Models\ProcedureCategory::pluck('name', 'slug')->toArray();
    return response()->json(
        \App\Models\Policy::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($pol) use ($categoriesMap) {
                $downloadUrl = '#';
                if (!empty($pol->download_url)) {
                    if (str_starts_with($pol->download_url, 'http://') || str_starts_with($pol->download_url, 'https://') || $pol->download_url === '#') {
                        $downloadUrl = $pol->download_url;
                    } else {
                        $downloadUrl = '/storage/' . ltrim($pol->download_url, '/');
                    }
                }
                return [
                    'id' => $pol->id,
                    'title' => $pol->title,
                    'code' => $pol->code ?? '',
                    'category' => $pol->category,
                    'categoryText' => $categoriesMap[$pol->category] ?? 'Chính sách & Quy định',
                    'date' => $pol->issue_date ?? '',
                    'agency' => $pol->agency ?? 'UBND Phường',
                    'status' => $pol->status ?? 'Đang có hiệu lực',
                    'summary' => $pol->summary ?? '',
                    'highlights' => $pol->highlights ?? [],
                    'downloadUrl' => $downloadUrl,
                    'download_url' => $downloadUrl,
                ];
            })
    );
});
