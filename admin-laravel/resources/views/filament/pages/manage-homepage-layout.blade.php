<x-filament-panels::page>
    <style>
        .builder-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
        }
        .dark .builder-container {
            color: #f1f5f9;
        }
        .builder-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.95rem;
            margin-bottom: 1.25rem;
            overflow: hidden;
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease-in-out;
        }
        .dark .builder-card {
            background: #0f172a;
            border-color: #1e293b;
            box-shadow: none;
        }
        .builder-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.08);
        }
        .card-hidden {
            opacity: 0.55;
            background: #fafafa;
            border-style: dashed;
        }
        .dark .card-hidden {
            background: #090d16;
        }
        .card-bar {
            padding: 1rem 1.35rem;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .dark .card-bar {
            background: #1e293b/60;
            border-bottom-color: #1e293b;
        }
        .sec-order-tag {
            background: #3b82f6;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 0.25rem 0.6rem;
            border-radius: 0.45rem;
            letter-spacing: 0.02em;
        }
        .sec-status-active {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .sec-status-hidden {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .btn-ui {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.95rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-ui-move {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #334155;
        }
        .btn-ui-move:hover:not(:disabled) {
            background: #f1f5f9;
            color: #0f172a;
        }
        .btn-ui-move:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }
        .btn-ui-toggle-on {
            background: #ecfdf5;
            color: #047857;
            border-color: #a7f3d0;
        }
        .btn-ui-toggle-on:hover {
            background: #d1fae5;
        }
        .btn-ui-toggle-off {
            background: #f1f5f9;
            color: #475569;
            border-color: #cbd5e1;
        }
        .btn-ui-toggle-off:hover {
            background: #e2e8f0;
        }
        .btn-ui-edit {
            background: #2563eb;
            color: #ffffff;
        }
        .btn-ui-edit:hover {
            background: #1d4ed8;
        }
        .card-preview-body {
            padding: 1.25rem 1.35rem;
        }
        .custom-upload-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 1.1rem;
            background: #f0f9ff;
            color: #0284c7;
            border: 1px dashed #0284c7;
            border-radius: 0.55rem;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            width: 100%;
        }
        .custom-upload-btn:hover {
            background: #e0f2fe;
            border-color: #0369a1;
            color: #0369a1;
        }
        .modal-field-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.35rem;
        }
        .modal-input {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            outline: none;
            transition: border-color 0.15s ease;
        }
        .modal-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
    </style>

    <div class="builder-container">
        <!-- Section Cards List -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($sections as $index => $sec)
                <div class="builder-card {{ $sec['is_visible'] ? '' : 'card-hidden' }}">
                    <!-- Card Top Toolbar Bar -->
                    <div class="card-bar">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 1.05rem; font-weight: 800; color: #0f172a;" class="dark:text-white">{{ $sec['name'] }}</span>
                            @if($sec['is_visible'])
                                <span class="sec-status-active">
                                    <svg style="width: 11px; height: 11px; fill: currentColor;" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    Đang hiển thị
                                </span>
                            @else
                                <span class="sec-status-hidden">
                                    <svg style="width: 11px; height: 11px; fill: currentColor;" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    Đã ẩn
                                </span>
                            @endif
                        </div>

                        <!-- Professional Large UI Buttons -->
                        <div style="display: flex; align-items: center; gap: 0.45rem;">
                            <button wire:click="toggleVisibility({{ $sec['id'] }})" class="btn-ui {{ $sec['is_visible'] ? 'btn-ui-toggle-on' : 'btn-ui-toggle-off' }}">
                                @if($sec['is_visible'])
                                    <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Hiển thị
                                @else
                                    <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                    Đang ẩn
                                @endif
                            </button>
                            <button wire:click="openEditModal({{ $sec['id'] }})" class="btn-ui btn-ui-edit">
                                <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Chỉnh sửa
                            </button>
                        </div>
                    </div>

                    <!-- Clean Expanded Preview Frame -->
                    <div class="card-preview-body">
                        @if($sec['section_code'] === 'header_navbar')
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.9rem 1.25rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.85rem;">
                                    <div style="width: 44px; height: 44px; border-radius: 9999px; overflow: hidden; background: #3399fe; border: 2px solid #38bdf8; shrink: 0; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ $sec['settings']['site_logo'] ?? '/logo.jpg' }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='/logo.jpg'" />
                                    </div>
                                    <div>
                                        <div style="font-size: 1.05rem; font-weight: 800; color: #0284c7; line-height: 1.2;">{{ $sec['custom_title'] ?: 'CỔNG TRA CỨU THÔNG TIN' }}</div>
                                        <div style="font-size: 0.82rem; color: #64748b; font-weight: 700; text-transform: uppercase; margin-top: 0.15rem;">{{ $sec['custom_subtitle'] ?: 'PHƯỜNG DUY HÀ — TÌNH NINH BÌNH' }}</div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.45rem; font-size: 0.85rem; font-weight: 700; color: #334155;">
                                    <span style="background: #3399fe; color: #fff; padding: 0.35rem 0.75rem; border-radius: 0.4rem;">{{ $sec['settings']['nav_home_label'] ?? 'Trang chủ' }}</span>
                                    <span style="background: #e2e8f0; padding: 0.35rem 0.75rem; border-radius: 0.4rem;">{{ $sec['settings']['nav_meritorious_label'] ?? 'Gia đình có công' }}</span>
                                    <span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.4rem;">🗺️ {{ $sec['settings']['nav_map_label'] ?? 'Bản đồ Duy Hà' }}</span>
                                </div>
                            </div>

                        @elseif($sec['section_code'] === 'hero_banner')
                            <div style="background: linear-gradient(135deg, #991b1b 0%, #450a0a 100%); color: #fff; padding: 1.5rem 1.25rem; border-radius: 0.6rem; text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 0.85rem; margin-bottom: 0.65rem;">
                                    <img src="{{ $sec['settings']['logo_doan_url'] ?? '/logo-doan.png' }}" style="height: 36px; object-fit: contain;" onerror="this.onerror=null; this.src='/logo-doan.png'" />
                                    <img src="{{ $sec['settings']['logo_thanh_nien_url'] ?? '/logo-thanh-nien.jpg' }}" style="height: 36px; border-radius: 9999px; object-fit: cover;" onerror="this.onerror=null; this.src='/logo-thanh-nien.jpg'" />
                                </div>
                                <div style="font-size: 1.25rem; font-weight: 900; color: #fef08a; text-transform: uppercase; letter-spacing: -0.01em;">{{ $sec['custom_title'] ?: 'CỔNG TRA CỨU THÔNG TIN PHƯỜNG DUY HÀ' }}</div>
                            </div>

                        @elseif($sec['section_code'] === 'stats_cards')
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.65rem;" class="dark:text-white">{{ $sec['custom_title'] ?: 'Chỉ số thống kê địa bàn' }}</div>
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.65rem; text-align: center; font-size: 0.95rem;">
                                <div style="background: #eff6ff; padding: 0.75rem; border-radius: 0.5rem; color: #1e40af;"><b>{{ $stat1Val }}</b> {{ $stat1Lbl }}</div>
                                <div style="background: #ecfdf5; padding: 0.75rem; border-radius: 0.5rem; color: #065f46;"><b>{{ $stat2Val }}</b> {{ $stat2Lbl }}</div>
                                <div style="background: #fffbeb; padding: 0.75rem; border-radius: 0.5rem; color: #92400e;"><b>{{ $stat3Val }}</b> {{ $stat3Lbl }}</div>
                                <div style="background: #faf5ff; padding: 0.75rem; border-radius: 0.5rem; color: #6b21a8;"><b>{{ $stat4Val }}</b> {{ $stat4Lbl }}</div>
                            </div>

                        @elseif($sec['section_code'] === 'agencies_grid')
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;" class="dark:text-white">{{ $sec['custom_title'] ?: 'Danh sách cơ quan hành chính' }}</div>
                            <div style="font-size: 0.85rem; color: #64748b;">Khối hiển thị danh sách các trụ sở UBND Phường, Trụ sở Công an, Trạm Y tế, Trường học và Các công trình công cộng</div>

                        @elseif($sec['section_code'] === 'tdp_merger')
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.45rem;" class="dark:text-white">{{ $sec['custom_title'] ?: 'Thông tin sáp nhập tổ dân phố' }}</div>
                            <div style="display: flex; gap: 0.75rem; font-size: 0.85rem;">
                                <div style="flex:1; background:#f0f9ff; padding:0.55rem 0.85rem; border-radius:0.45rem; color:#0369a1; border: 1px solid #bae6fd;"><b>{{ $sec['settings']['old_table_title'] ?? 'TRƯỚC SÁP NHẬP (16 TỔ DÂN PHỐ CỦ)' }}</b></div>
                                <div style="flex:1; background:#ecfdf5; padding:0.55rem 0.85rem; border-radius:0.45rem; color:#047857; border: 1px solid #a7f3d0;"><b>{{ $sec['settings']['new_table_title'] ?? 'SAU SÁP NHẬP (10 TỔ DÂN PHỐ MỚI)' }}</b></div>
                            </div>

                        @elseif($sec['section_code'] === 'officials_directory')
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;" class="dark:text-white">{{ $sec['custom_title'] ?: 'Danh bạ Cán bộ & CSKV phụ trách địa bàn' }}</div>
                            <div style="font-size: 0.85rem; color: #64748b;">Khối hiển thị danh sách cán bộ chuyên trách và cảnh sát khu vực phụ trách từng tổ dân phố</div>

                        @elseif($sec['section_code'] === 'meritorious_families')
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;" class="dark:text-white">{{ $sec['custom_title'] ?: 'Hoạt động Đền ơn đáp nghĩa & Gia đình có công' }}</div>
                            <div style="font-size: 0.85rem; color: #64748b;">Khối hiển thị sự kiện kỷ niệm và danh sách tôn vinh Mẹ VNAH, gia đình chính sách</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Professional Large Modal Dialog -->
        @if($showModal)
            <div style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px);">
                <div style="background: #ffffff; width: 100%; max-width: 580px; border-radius: 0.95rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); overflow: hidden; border: 1px solid #e2e8f0;">
                    <!-- Modal Title Header -->
                    <div style="padding: 1rem 1.35rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a;">Chỉnh sửa Cấu hình Khối Giao diện</div>
                        <button wire:click="closeModal" style="border: none; background: none; font-size: 1.25rem; font-weight: 700; color: #64748b; cursor: pointer;">&times;</button>
                    </div>

                    <!-- Modal Body Form -->
                    <div style="padding: 1.35rem; display: flex; flex-direction: column; gap: 1rem;">
                        @if($isHeaderModal)
                            <div>
                                <label class="modal-field-label">Logo chính Phường Duy Hà</label>
                                <div style="display: flex; align-items: center; gap: 0.85rem; background: #f8fafc; padding: 0.85rem; border-radius: 0.65rem; border: 1px solid #e2e8f0;">
                                    <div style="width: 52px; height: 52px; border-radius: 9999px; overflow: hidden; background: #3399fe; border: 2px solid #38bdf8; shrink: 0; display: flex; align-items: center; justify-content: center;">
                                        <?php
                                            $tmpLogo = null;
                                            if ($logoUpload && is_object($logoUpload) && method_exists($logoUpload, 'temporaryUrl')) {
                                                try { $tmpLogo = $logoUpload->temporaryUrl(); } catch (\Throwable $e) {}
                                            }
                                        ?>
                                        @if($tmpLogo)
                                            <img src="{{ $tmpLogo }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                        @else
                                            <img src="{{ $siteLogo }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='/logo.jpg'" />
                                        @endif
                                    </div>
                                    <div style="flex: 1; display: flex; flex-direction: column; gap: 0.45rem;">
                                        <label style="margin:0;">
                                            <div class="custom-upload-btn">
                                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                <span>{{ $logoUpload ? 'Đã chọn ảnh mới (Bấm Lưu để áp dụng)' : 'Bấm để Chọn file ảnh từ máy tính' }}</span>
                                            </div>
                                            <input type="file" wire:model="logoUpload" accept="image/*" style="display: none;" />
                                        </label>
                                        <input type="text" wire:model="siteLogo" class="modal-input" placeholder="Hoặc dán URL: /logo.jpg" style="font-size: 0.8rem; padding: 0.4rem 0.6rem;" />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="modal-field-label">Tiêu đề chính Header (Dòng 1)</label>
                                <input type="text" wire:model="customTitle" class="modal-input" placeholder="CỔNG TRA CỨU THÔNG TIN" />
                            </div>
                            <div>
                                <label class="modal-field-label">Tiêu đề phụ Header (Dòng 2)</label>
                                <input type="text" wire:model="customSubtitle" class="modal-input" placeholder="PHƯỜNG DUY HÀ — TÌNH NINH BÌNH" />
                            </div>
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 0.85rem;">
                                <div style="font-size: 0.85rem; font-weight: 800; color: #0284c7; margin-bottom: 0.5rem;">Nhãn chữ Menu Điều hướng:</div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.25rem;">Menu Tab 1</label>
                                        <input type="text" wire:model="navHomeLabel" class="modal-input" />
                                    </div>
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.25rem;">Menu Tab 2</label>
                                        <input type="text" wire:model="navMeritoriousLabel" class="modal-input" />
                                    </div>
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.25rem;">Menu Tab 3 (Bản đồ)</label>
                                        <input type="text" wire:model="navMapLabel" class="modal-input" />
                                    </div>
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.25rem;">Nút Quản trị</label>
                                        <input type="text" wire:model="adminBtnLabel" class="modal-input" />
                                    </div>
                                </div>
                            </div>
                        @elseif($isHeroModal)
                            <div>
                                <label class="modal-field-label">Tiêu đề chính Khối Banner Hero</label>
                                <input type="text" wire:model="customTitle" class="modal-input" />
                            </div>

                            <div style="border-top: 1px solid #e2e8f0; padding-top: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
                                <div>
                                    <label class="modal-field-label">Logo 1 (Đoàn TNCS Hồ Chí Minh)</label>
                                    <div style="display: flex; align-items: center; gap: 0.75rem; background: #f8fafc; padding: 0.65rem 0.85rem; border-radius: 0.6rem; border: 1px solid #e2e8f0;">
                                        <?php
                                            $tmpDoan = null;
                                            if ($logoDoanUpload && is_object($logoDoanUpload) && method_exists($logoDoanUpload, 'temporaryUrl')) {
                                                try { $tmpDoan = $logoDoanUpload->temporaryUrl(); } catch (\Throwable $e) {}
                                            }
                                        ?>
                                        @if($tmpDoan)
                                            <img src="{{ $tmpDoan }}" style="width: 34px; height: 34px; object-fit: contain;" />
                                        @else
                                            <img src="{{ $logoDoanUrl }}" style="width: 34px; height: 34px; object-fit: contain;" onerror="this.onerror=null; this.src='/logo-doan.png'" />
                                        @endif
                                        <label style="flex: 1; margin: 0;">
                                            <div class="custom-upload-btn" style="padding: 0.5rem 0.85rem; font-size: 0.8rem;">
                                                <span>{{ $logoDoanUpload ? 'Đã chọn ảnh mới' : 'Bấm Chọn file ảnh Đoàn' }}</span>
                                            </div>
                                            <input type="file" wire:model="logoDoanUpload" accept="image/*" style="display: none;" />
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="modal-field-label">Logo 2 (Hội LHTN Việt Nam)</label>
                                    <div style="display: flex; align-items: center; gap: 0.75rem; background: #f8fafc; padding: 0.65rem 0.85rem; border-radius: 0.6rem; border: 1px solid #e2e8f0;">
                                        <?php
                                            $tmpThanhNien = null;
                                            if ($logoThanhNienUpload && is_object($logoThanhNienUpload) && method_exists($logoThanhNienUpload, 'temporaryUrl')) {
                                                try { $tmpThanhNien = $logoThanhNienUpload->temporaryUrl(); } catch (\Throwable $e) {}
                                            }
                                        ?>
                                        @if($tmpThanhNien)
                                            <img src="{{ $tmpThanhNien }}" style="width: 34px; height: 34px; border-radius: 9999px; object-fit: cover;" />
                                        @else
                                            <img src="{{ $logoThanhNienUrl }}" style="width: 34px; height: 34px; border-radius: 9999px; object-fit: cover;" onerror="this.onerror=null; this.src='/logo-thanh-nien.jpg'" />
                                        @endif
                                        <label style="flex: 1; margin: 0;">
                                            <div class="custom-upload-btn" style="padding: 0.5rem 0.85rem; font-size: 0.8rem;">
                                                <span>{{ $logoThanhNienUpload ? 'Đã chọn ảnh mới' : 'Bấm Chọn file ảnh Hội' }}</span>
                                            </div>
                                            <input type="file" wire:model="logoThanhNienUpload" accept="image/*" style="display: none;" />
                                        </label>
                                    </div>
                                </div>
                            </div>

                        @elseif($isStatsModal)
                            <div>
                                <label class="modal-field-label">Tiêu đề chính Khối Chỉ số Thống kê</label>
                                <input type="text" wire:model="customTitle" class="modal-input" />
                            </div>

                            <div style="border-top: 1px solid #e2e8f0; padding-top: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
                                <div style="font-size: 0.85rem; font-weight: 800; color: #0284c7;">Cấu hình 4 Thẻ Số liệu Thống kê Địa bàn:</div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; background: #f8fafc; padding: 0.65rem 0.85rem; border-radius: 0.55rem; border: 1px solid #e2e8f0;">
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #1e40af; display: block; margin-bottom: 0.2rem;">Thẻ 1 (Giá trị)</label>
                                        <input type="text" wire:model="stat1Val" class="modal-input" placeholder="10" />
                                    </div>
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.2rem;">Thẻ 1 (Nhãn hiển thị)</label>
                                        <input type="text" wire:model="stat1Lbl" class="modal-input" placeholder="Tổ dân phố" />
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; background: #f8fafc; padding: 0.65rem 0.85rem; border-radius: 0.55rem; border: 1px solid #e2e8f0;">
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #065f46; display: block; margin-bottom: 0.2rem;">Thẻ 2 (Giá trị)</label>
                                        <input type="text" wire:model="stat2Val" class="modal-input" placeholder="6.767" />
                                    </div>
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.2rem;">Thẻ 2 (Nhãn hiển thị)</label>
                                        <input type="text" wire:model="stat2Lbl" class="modal-input" placeholder="Hộ gia đình" />
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; background: #f8fafc; padding: 0.65rem 0.85rem; border-radius: 0.55rem; border: 1px solid #e2e8f0;">
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #92400e; display: block; margin-bottom: 0.2rem;">Thẻ 3 (Giá trị)</label>
                                        <input type="text" wire:model="stat3Val" class="modal-input" placeholder="23.615" />
                                    </div>
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.2rem;">Thẻ 3 (Nhãn hiển thị)</label>
                                        <input type="text" wire:model="stat3Lbl" class="modal-input" placeholder="Nhân khẩu" />
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; background: #f8fafc; padding: 0.65rem 0.85rem; border-radius: 0.55rem; border: 1px solid #e2e8f0;">
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #6b21a8; display: block; margin-bottom: 0.2rem;">Thẻ 4 (Giá trị)</label>
                                        <input type="text" wire:model="stat4Val" class="modal-input" placeholder="15,46 km²" />
                                    </div>
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 0.2rem;">Thẻ 4 (Nhãn hiển thị)</label>
                                        <input type="text" wire:model="stat4Lbl" class="modal-input" placeholder="Diện tích địa bàn" />
                                    </div>
                                </div>
                            </div>

                        @elseif($isTdpMergerModal)
                            <div>
                                <label class="modal-field-label">Tiêu đề Khối Bảng Sáp nhập</label>
                                <input type="text" wire:model="customTitle" class="modal-input" />
                            </div>

                            <div style="border-top: 1px solid #e2e8f0; padding-top: 0.85rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                                <div>
                                    <label style="font-size: 0.78rem; font-weight: 700; color: #1d4ed8; display: block; margin-bottom: 0.25rem;">Tiêu đề Bảng TDP Cũ</label>
                                    <input type="text" wire:model="oldTableTitle" class="modal-input" />
                                </div>
                                <div>
                                    <label style="font-size: 0.78rem; font-weight: 700; color: #047857; display: block; margin-bottom: 0.25rem;">Tiêu đề Bảng TDP Mới</label>
                                    <input type="text" wire:model="newTableTitle" class="modal-input" />
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="modal-field-label">Tiêu đề hiển thị ngoài Trang chủ</label>
                                <input type="text" wire:model="customTitle" class="modal-input" placeholder="Nhập tiêu đề..." />
                            </div>
                        @endif
                    </div>

                    <!-- Modal Actions Footer -->
                    <div style="padding: 0.9rem 1.35rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 0.65rem;">
                        <button wire:click="closeModal" style="padding: 0.5rem 1.1rem; background: #e2e8f0; color: #334155; font-weight: 700; font-size: 0.85rem; border-radius: 0.5rem; border: none; cursor: pointer;">Hủy thao tác</button>
                        <button wire:click="saveSection" style="padding: 0.5rem 1.35rem; background: #2563eb; color: #ffffff; font-weight: 700; font-size: 0.85rem; border-radius: 0.5rem; border: none; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
