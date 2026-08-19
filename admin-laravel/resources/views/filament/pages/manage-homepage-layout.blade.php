<x-filament-panels::page>
    <style>
        .builder-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
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
            flex-wrap: wrap;
            gap: 1rem;
        }
        .dark .card-bar {
            background: #1e293b/60;
            border-bottom-color: #1e293b;
        }
        .sec-order-tag {
            background: #0284c7;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 0.25rem 0.65rem;
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
            padding: 0.45rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
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
        .btn-ui-order {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 0.45rem 0.65rem;
        }
        .btn-ui-order:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .btn-ui-delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .btn-ui-delete:hover {
            background: #fee2e2;
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
            padding: 0.6rem 0.8rem;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            font-size: 0.88rem;
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
        <!-- Top Toolbar Actions -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; background: #ffffff; padding: 1rem 1.25rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" class="dark:bg-slate-900 dark:border-slate-800">
            <div>
                <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a;" class="dark:text-white">Bố cục Khối Trang chủ (Page Builder)</div>
                <div style="font-size: 0.82rem; color: #64748b;" class="dark:text-slate-400">Sử dụng nút ⬆️ ⬇️ để thay đổi thứ tự hiển thị, bật/tắt hoặc chỉnh sửa nội dung từng khối</div>
            </div>
            <button wire:click="createCustomBlock" class="btn-ui" style="background: #0284c7; color: #fff; padding: 0.6rem 1.1rem; font-size: 0.88rem;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Thêm khối nội dung mới</span>
            </button>
        </div>

        <!-- Section Cards List -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($sections as $index => $sec)
                <div class="builder-card {{ $sec['is_visible'] ? '' : 'card-hidden' }}">
                    <!-- Card Top Toolbar Bar -->
                    <div class="card-bar">
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <span class="sec-order-tag">#{{ $index + 1 }}</span>
                            <span style="font-size: 1.05rem; font-weight: 800; color: #0f172a;" class="dark:text-white">{{ $sec['name'] }}</span>
                            @if($sec['is_visible'])
                                <span class="sec-status-active">
                                    <svg style="width: 10px; height: 10px; fill: currentColor;" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    Đang hiển thị
                                </span>
                            @else
                                <span class="sec-status-hidden">
                                    <svg style="width: 10px; height: 10px; fill: currentColor;" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    Đã ẩn
                                </span>
                            @endif
                        </div>

                        <!-- Professional Large UI Buttons -->
                        <div style="display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                            <!-- Order Move Up/Down Buttons -->
                            @if($index > 0)
                                <button wire:click="moveUp({{ $sec['id'] }})" class="btn-ui btn-ui-order" title="Đẩy khối này lên trên">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                                    <span>Lên</span>
                                </button>
                            @endif
                            @if($index < count($sections) - 1)
                                <button wire:click="moveDown({{ $sec['id'] }})" class="btn-ui btn-ui-order" title="Đẩy khối này xuống dưới">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    <span>Xuống</span>
                                </button>
                            @endif

                            <!-- Visibility Toggle Button -->
                            <button wire:click="toggleVisibility({{ $sec['id'] }})" class="btn-ui {{ $sec['is_visible'] ? 'btn-ui-toggle-on' : 'btn-ui-toggle-off' }}">
                                @if($sec['is_visible'])
                                    <svg style="width:15px; height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Hiển thị</span>
                                @else
                                    <svg style="width:15px; height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                    <span>Đang ẩn</span>
                                @endif
                            </button>

                            <!-- Edit Settings Button -->
                            <button wire:click="openEditModal({{ $sec['id'] }})" class="btn-ui btn-ui-edit">
                                <svg style="width:15px; height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Chỉnh sửa</span>
                            </button>

                            <!-- Delete Custom Block Button -->
                            @if(str_starts_with($sec['section_code'], 'custom_'))
                                <button wire:click="deleteCustomBlock({{ $sec['id'] }})" class="btn-ui btn-ui-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa khối này không?')">
                                    <svg style="width:15px; height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Xóa</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Clean Expanded Preview Frame -->
                    <div class="card-preview-body">
                        @if($sec['section_code'] === 'header_navbar')
                            <div style="background: #fdfefe; border: 1px solid #e2e8f0; padding: 0.85rem 1.25rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                                <div style="display: flex; align-items: center; gap: 0.85rem;">
                                    <div style="width: 44px; height: 44px; border-radius: 9999px; overflow: hidden; background: #3399fe; border: 2px solid #38bdf8; shrink: 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(51,153,254,0.3);">
                                        <img src="{{ $sec['settings']['site_logo'] ?? '/logo.jpg' }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='/logo.jpg'" />
                                    </div>
                                    <div>
                                        <div style="font-size: 1.05rem; font-weight: 900; color: #0284c7; line-height: 1.15;">{{ $sec['custom_title'] ?: 'CỔNG TRA CỨU THÔNG TIN' }}</div>
                                        <div style="font-size: 0.8rem; color: #0369a1; font-weight: 700; text-transform: uppercase; margin-top: 0.15rem;">{{ $sec['custom_subtitle'] ?: 'Phường Duy Hà — Tỉnh Ninh Bình' }}</div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; font-weight: 700; flex-wrap: wrap;">
                                    @if(!empty($sec['settings']['menu_items']) && is_array($sec['settings']['menu_items']))
                                        @foreach($sec['settings']['menu_items'] as $mItem)
                                            <span style="background: #3399fe; color: #fff; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem;">{{ $mItem['label'] }}</span>
                                        @endforeach
                                    @else
                                        <span style="background: #3399fe; color: #fff; padding: 0.4rem 0.85rem; border-radius: 0.5rem;">Trang chủ</span>
                                        <span style="background: #ecfdf5; color: #065f46; padding: 0.4rem 0.85rem; border-radius: 0.5rem; border: 1px solid #a7f3d0;">🗺️ Bản đồ Duy Hà</span>
                                    @endif
                                    <span style="background: #f0f9ff; color: #0369a1; padding: 0.4rem 0.75rem; border-radius: 0.5rem; border: 1px solid #bae6fd;">☀️ 28°C | Duy Hà</span>
                                </div>
                            </div>

                        @elseif($sec['section_code'] === 'hero_banner')
                            <?php
                                $isSecVideo = ($sec['settings']['bg_type'] ?? '') === 'video' && !empty($sec['settings']['hero_video_url']);
                            ?>
                            <div style="background: {{ $isSecVideo ? '#0f172a' : 'linear-gradient(180deg, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0.20) 45%, rgba(0, 0, 0, 0.70) 100%), url(\'' . ($sec['settings']['hero_bg_url'] ?? '/hero-bg.jpg') . '\')' }}; background-size: cover; background-position: center; color: #fff; padding: 1.5rem 1.25rem; border-radius: 0.75rem; text-align: center; border-bottom: 4px solid #3399fe; box-shadow: 0 4px 12px rgba(0,0,0,0.15); position: relative; overflow: hidden;">
                                @if($isSecVideo)
                                    <video src="{{ $sec['settings']['hero_video_url'] }}" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.55; pointer-events: none;" autoplay muted loop playsinline></video>
                                    <div style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(2,132,199,0.9); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 0.35rem; z-index: 10;">
                                        🎬 Video nền
                                    </div>
                                @endif
                                <div style="position: relative; z-index: 5;">
                                    <div style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 0.75rem;">
                                        <div style="width: 46px; height: 46px; padding: 3px; background: rgba(255,255,255,0.25); border-radius: 9999px; border: 2px solid #fde047; display: flex; align-items: center; justify-content: center;">
                                            <img src="{{ $sec['settings']['logo_doan_url'] ?? '/logo-doan.png' }}" style="width: 100%; height: 100%; object-fit: contain;" onerror="this.onerror=null; this.src='/logo-doan.png'" />
                                        </div>
                                    </div>
                                    <div style="font-size: 1.25rem; font-weight: 900; color: #fef08a; text-transform: uppercase; letter-spacing: -0.01em; text-shadow: 0 2px 8px rgba(0,0,0,0.8);">{{ $sec['custom_title'] ?: 'CỔNG TRA CỨU THÔNG TIN PHƯỜNG DUY HÀ' }}</div>
                                </div>
                            </div>

                        @elseif($sec['section_code'] === 'stats_cards')
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; text-align: center;">
                                <div style="background: #eff6ff; padding: 0.8rem; border-radius: 0.65rem; border: 1px solid #bfdbfe;">
                                    <div style="font-size: 1.35rem; font-weight: 900; color: #1e40af;">{{ $stat1Val }}</div>
                                    <div style="font-size: 0.78rem; font-weight: 700; color: #3b82f6; margin-top: 0.2rem;">{{ $stat1Lbl }}</div>
                                </div>
                                <div style="background: #ecfdf5; padding: 0.8rem; border-radius: 0.65rem; border: 1px solid #a7f3d0;">
                                    <div style="font-size: 1.35rem; font-weight: 900; color: #065f46;">{{ $stat2Val }}</div>
                                    <div style="font-size: 0.78rem; font-weight: 700; color: #059669; margin-top: 0.2rem;">{{ $stat2Lbl }}</div>
                                </div>
                                <div style="background: #fffbeb; padding: 0.8rem; border-radius: 0.65rem; border: 1px solid #fde68a;">
                                    <div style="font-size: 1.35rem; font-weight: 900; color: #92400e;">{{ $stat3Val }}</div>
                                    <div style="font-size: 0.78rem; font-weight: 700; color: #d97706; margin-top: 0.2rem;">{{ $stat3Lbl }}</div>
                                </div>
                                <div style="background: #faf5ff; padding: 0.8rem; border-radius: 0.65rem; border: 1px solid #e9d5ff;">
                                    <div style="font-size: 1.35rem; font-weight: 900; color: #6b21a8;">{{ $stat4Val }}</div>
                                    <div style="font-size: 0.78rem; font-weight: 700; color: #9333ea; margin-top: 0.2rem;">{{ $stat4Lbl }}</div>
                                </div>
                            </div>

                        @elseif($sec['section_code'] === 'footer_section')
                            <div style="background: linear-gradient(135deg, #143e78 0%, #1a4a8c 50%, #123668 100%); color: #fff; padding: 1rem 1.25rem; border-radius: 0.75rem; border-top: 3px solid #f59e0b; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 0.85rem;">
                                    <div style="width: 44px; height: 44px; border-radius: 50%; overflow: hidden; background: #3399fe; border: 2px solid #fff; shrink: 0; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ $sec['settings']['footer_logo'] ?? '/logo.jpg' }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='/logo.jpg'" />
                                    </div>
                                    <div>
                                        <div style="font-size: 0.95rem; font-weight: 900; color: #ffffff; text-transform: uppercase;">{{ $sec['settings']['org_name'] ?? ($sec['custom_title'] ?: 'ĐOÀN TNCS HỒ CHÍ MINH PHƯỜNG DUY HÀ') }}</div>
                                        <div style="font-size: 0.75rem; color: #bae6fd;">📍 {{ $sec['settings']['address'] ?? ($sec['custom_subtitle'] ?: 'Phường Duy Hà, Ninh Bình') }}</div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #e0f2fe; background: rgba(255,255,255,0.1); padding: 0.35rem 0.65rem; border-radius: 0.45rem; border: 1px solid rgba(255,255,255,0.2);">
                                    <span>📞 {{ $sec['settings']['phone'] ?? '(0229) 38253536' }}</span>
                                    <span>|</span>
                                    <span>✉️ {{ $sec['settings']['email'] ?? 'thongtin@duyha.ninhbinh.gov.vn' }}</span>
                                </div>
                            </div>

                        @elseif(str_starts_with($sec['section_code'], 'custom_'))
                            <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #fff; padding: 1.25rem 1.5rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                                <div style="space-y: 0.25rem;">
                                    @if(!empty($sec['settings']['badge']))
                                        <span style="background: #fde047; color: #854d0e; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 0.35rem; text-transform: uppercase;">{{ $sec['settings']['badge'] }}</span>
                                    @endif
                                    <div style="font-size: 1.1rem; font-weight: 800; color: #ffffff; margin-top: 0.35rem;">{{ $sec['custom_title'] }}</div>
                                    <div style="font-size: 0.85rem; color: #e0f2fe;">{{ $sec['custom_subtitle'] }}</div>
                                </div>
                                @if(!empty($sec['settings']['btn_text']))
                                    <span style="background: #ffffff; color: #0284c7; font-weight: 800; font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">{{ $sec['settings']['btn_text'] }} ↗</span>
                                @endif
                            </div>

                        @else
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;" class="dark:text-white">{{ $sec['custom_title'] ?: $sec['name'] }}</div>
                            <div style="font-size: 0.85rem; color: #64748b;">{{ $sec['custom_subtitle'] ?: 'Khối giao diện hệ thống chuẩn hóa' }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Professional Large Modal Dialog -->
        @if($showModal)
            <div style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px);">
                <div style="background: #ffffff; width: 100%; max-width: 680px; max-height: 90vh; border-radius: 0.95rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); overflow-y: auto; border: 1px solid #e2e8f0;">
                    <!-- Modal Title Header -->
                    <div style="padding: 1rem 1.35rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10;">
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
                                    <div style="flex: 1;">
                                        <label style="margin:0;">
                                            <div class="custom-upload-btn">
                                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                <span>{{ $logoUpload ? 'Đã chọn ảnh mới (Bấm Lưu để áp dụng)' : 'Bấm để Chọn file ảnh từ máy tính' }}</span>
                                            </div>
                                            <input type="file" wire:model="logoUpload" accept="image/*" style="display: none;" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="modal-field-label">Tiêu đề chính Header (Dòng 1)</label>
                                <input type="text" wire:model="customTitle" class="modal-input" placeholder="Đoàn TNCS Hồ Chí Minh phường Duy Hà" />
                            </div>
                            <div>
                                <label class="modal-field-label">Tiêu đề phụ Header (Dòng 2)</label>
                                <input type="text" wire:model="customSubtitle" class="modal-input" placeholder="CỔNG THEO DÕI TIN TỨC & TRA CỨU THÔNG TIN" />
                            </div>

                            <!-- Dynamic Menu Items Builder -->
                            <div style="border-top: 2px dashed #e2e8f0; padding-top: 1rem; margin-top: 0.5rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                    <div>
                                        <div style="font-size: 0.95rem; font-weight: 800; color: #0284c7;">Trình tạo Menu Điều hướng (Dynamic Menu Builder)</div>
                                        <div style="font-size: 0.78rem; color: #64748b;">Thêm, bớt, chỉnh sửa liên kết menu trên thanh Header của website</div>
                                    </div>
                                    <button wire:click="addMenuItem" type="button" class="btn-ui" style="background: #0284c7; color: #fff; font-size: 0.8rem;">
                                        + Thêm mục menu
                                    </button>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                                    @foreach($navMenuItems as $mIdx => $mItem)
                                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.65rem; padding: 0.85rem; display: flex; flex-direction: column; gap: 0.65rem;">
                                            <!-- Card Header: Title and Action Buttons (Lên / Xuống / Xóa) -->
                                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                                                <div style="display: flex; align-items: center; gap: 0.45rem;">
                                                    <span style="font-size: 0.85rem; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 0.2rem 0.55rem; border-radius: 0.4rem;">Mục #{{ $mIdx + 1 }}</span>
                                                </div>
                                                <div style="display: flex; align-items: center; gap: 0.35rem;">
                                                    @if($mIdx > 0)
                                                        <button wire:click="moveMenuItemUp({{ $mIdx }})" type="button" 
                                                            style="display: inline-flex; align-items: center; gap: 0.25rem; border: 1px solid #cbd5e1; background: #ffffff; color: #334155; border-radius: 0.45rem; padding: 0.35rem 0.65rem; cursor: pointer; font-size: 0.8rem; font-weight: 700; transition: all 0.15s ease;"
                                                            title="Đẩy mục này lên trên">
                                                            <svg style="width: 15px; height: 15px; stroke-width: 2.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                                            <span>Lên</span>
                                                        </button>
                                                    @endif
                                                    @if($mIdx < count($navMenuItems) - 1)
                                                        <button wire:click="moveMenuItemDown({{ $mIdx }})" type="button" 
                                                            style="display: inline-flex; align-items: center; gap: 0.25rem; border: 1px solid #cbd5e1; background: #ffffff; color: #334155; border-radius: 0.45rem; padding: 0.35rem 0.65rem; cursor: pointer; font-size: 0.8rem; font-weight: 700; transition: all 0.15s ease;"
                                                            title="Đẩy mục này xuống dưới">
                                                            <svg style="width: 15px; height: 15px; stroke-width: 2.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                                            <span>Xuống</span>
                                                        </button>
                                                    @endif
                                                    <button wire:click="removeMenuItem({{ $mIdx }})" type="button" 
                                                        style="display: inline-flex; align-items: center; gap: 0.25rem; border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; border-radius: 0.45rem; padding: 0.35rem 0.65rem; cursor: pointer; font-size: 0.8rem; font-weight: 700; transition: all 0.15s ease;"
                                                        title="Xóa mục này">
                                                        <svg style="width: 15px; height: 15px; stroke-width: 2;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Xóa</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- 2 Primary Inputs: Chọn trang & Tên hiển thị -->
                                            <div style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 0.65rem; align-items: start;">
                                                <!-- Chọn Trang / Chức năng -->
                                                <div>
                                                    <label style="font-size: 0.8rem; font-weight: 700; color: #0f172a; display: block; margin-bottom: 0.25rem;">
                                                        Chọn trang liên kết
                                                    </label>
                                                    <?php
                                                        $currentUrl = $mItem['url'] ?? '/index.html';
                                                        $allSysPages = \App\Filament\Pages\ManageHomepageLayout::getSystemPages();
                                                        $isKnownUrl = false;
                                                        foreach($allSysPages as $grp => $opts) {
                                                            if (array_key_exists($currentUrl, $opts)) {
                                                                $isKnownUrl = true;
                                                                break;
                                                            }
                                                        }
                                                        $selectedVal = $isKnownUrl ? $currentUrl : 'custom';
                                                    ?>
                                                    <select 
                                                        wire:change="onMenuItemPageChange({{ $mIdx }}, $event.target.value)" 
                                                        class="modal-input" 
                                                        style="font-size: 0.85rem; font-weight: 700; background: #ffffff; border-color: #cbd5e1;">
                                                        @foreach($allSysPages as $groupLabel => $pages)
                                                            <optgroup label="📁 {{ $groupLabel }}">
                                                                @foreach($pages as $urlKey => $pageTitle)
                                                                    <option value="{{ $urlKey }}" {{ $selectedVal === $urlKey ? 'selected' : '' }}>
                                                                        {{ $pageTitle }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Tên hiển thị -->
                                                <div>
                                                    <label style="font-size: 0.8rem; font-weight: 700; color: #0f172a; display: block; margin-bottom: 0.25rem;">
                                                        Tên hiển thị trên menu
                                                    </label>
                                                    <input type="text" wire:model="navMenuItems.{{ $mIdx }}.label" class="modal-input" placeholder="Tên nút menu" style="font-weight: 700; font-size: 0.85rem;" />
                                                </div>
                                            </div>

                                            @if(!$isKnownUrl || ($mItem['url'] ?? '') === 'custom' || str_starts_with($mItem['url'] ?? '', 'http'))
                                                <!-- Ô nhập URL chỉ hiện khi chọn Liên kết ngoài -->
                                                <div style="background: #f0fdf4; border: 1px dashed #86efac; border-radius: 0.45rem; padding: 0.45rem 0.65rem;">
                                                    <label style="font-size: 0.75rem; font-weight: 700; color: #166534; display: block; margin-bottom: 0.2rem;">
                                                        🔗 Địa chỉ Web liên kết ngoài:
                                                    </label>
                                                    <input type="text" wire:model="navMenuItems.{{ $mIdx }}.url" class="modal-input" placeholder="https://..." style="font-size: 0.82rem; padding: 0.4rem 0.6rem; background: #fff;" />
                                                </div>
                                            @endif

                                            <!-- Bottom Options: Hiển thị & Cách mở tab -->
                                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 1rem; padding-top: 0.15rem;">
                                                <label style="display: flex; align-items: center; gap: 0.35rem; cursor: pointer; margin: 0; font-size: 0.82rem; font-weight: 700; color: #334155;">
                                                    <input type="checkbox" wire:model="navMenuItems.{{ $mIdx }}.is_active" style="width: 16px; height: 16px; accent-color: #0284c7;" />
                                                    <span>Hiển thị</span>
                                                </label>
                                                <select wire:model="navMenuItems.{{ $mIdx }}.target" class="modal-input" style="width: auto; min-width: 110px; font-size: 0.8rem; padding: 0.3rem 0.6rem; font-weight: 600;">
                                                    <option value="_self">Cùng tab</option>
                                                    <option value="_blank">Tab mới ↗</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        @elseif($isHeroModal)
                            <div>
                                <label class="modal-field-label">Tiêu đề chính Khối Banner Hero</label>
                                <input type="text" wire:model="customTitle" class="modal-input" placeholder="CỔNG TRA CỨU THÔNG TIN PHƯỜNG DUY HÀ" />
                            </div>

                            <!-- Chọn Loại Nền Banner: Ảnh tĩnh hoặc Video -->
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 0.85rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                <label class="modal-field-label">Định dạng Nền Banner</label>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; background: #f1f5f9; padding: 0.35rem; border-radius: 0.6rem; border: 1px solid #e2e8f0;">
                                    <button type="button" wire:click="$set('heroBgType', 'image')" 
                                        style="padding: 0.55rem 0.75rem; border-radius: 0.45rem; font-size: 0.82rem; font-weight: 800; border: none; cursor: pointer; transition: all 0.15s ease; {{ $heroBgType === 'image' ? 'background: #0284c7; color: #ffffff; box-shadow: 0 2px 4px rgba(2,132,199,0.25);' : 'background: transparent; color: #475569;' }}">
                                        🖼️ Dùng Hình ảnh (Image)
                                    </button>
                                    <button type="button" wire:click="$set('heroBgType', 'video')" 
                                        style="padding: 0.55rem 0.75rem; border-radius: 0.45rem; font-size: 0.82rem; font-weight: 800; border: none; cursor: pointer; transition: all 0.15s ease; {{ $heroBgType === 'video' ? 'background: #0284c7; color: #ffffff; box-shadow: 0 2px 4px rgba(2,132,199,0.25);' : 'background: transparent; color: #475569;' }}">
                                        🎬 Dùng Video (.mp4, .webm)
                                    </button>
                                </div>
                            </div>

                            @if($heroBgType === 'video')
                                <!-- Khối tải Video Nền Banner -->
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    <label class="modal-field-label">Video Nền Banner chính (Hero Video Background)</label>
                                    <div style="display: flex; flex-direction: column; gap: 0.65rem; background: #f8fafc; padding: 0.85rem; border-radius: 0.65rem; border: 1px solid #e2e8f0;">
                                        <?php
                                            $tmpHeroVid = null;
                                            if ($heroVideoUpload && is_object($heroVideoUpload) && method_exists($heroVideoUpload, 'temporaryUrl')) {
                                                try { $tmpHeroVid = $heroVideoUpload->temporaryUrl(); } catch (\Throwable $e) {}
                                            }
                                            $currentVideoSrc = $tmpHeroVid ?: $heroVideoUrl;
                                        ?>
                                        <!-- Khung xem trước Video -->
                                        @if($currentVideoSrc)
                                            <div style="width: 100%; height: 160px; border-radius: 0.55rem; overflow: hidden; position: relative; background: #000; border: 2px solid #0284c7;">
                                                <video src="{{ $currentVideoSrc }}" style="width: 100%; height: 100%; object-fit: cover;" autoplay muted loop playsinline controls></video>
                                                <div style="position: absolute; top: 0.4rem; right: 0.5rem; background: rgba(2,132,199,0.85); color: #fff; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 0.35rem; pointer-events: none;">
                                                    🎬 Video đang áp dụng
                                                </div>
                                            </div>
                                        @else
                                            <div style="width: 100%; height: 110px; border-radius: 0.55rem; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f1f5f9; border: 2px dashed #cbd5e1; color: #64748b; font-size: 0.82rem; font-weight: 600;">
                                                <svg style="width: 28px; height: 28px; color: #94a3b8; margin-bottom: 0.35rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                <span>Chưa có video nào. Bấm nút bên dưới để chọn video từ máy tính.</span>
                                            </div>
                                        @endif

                                        <label style="margin: 0; width: 100%;">
                                            <div class="custom-upload-btn">
                                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                <span>{{ $heroVideoUpload ? 'Đã chọn video mới (Bấm Lưu để áp dụng)' : '🎬 Bấm để Chọn file video từ máy tính (.mp4, .webm)' }}</span>
                                            </div>
                                            <input type="file" wire:model="heroVideoUpload" accept="video/mp4,video/webm,video/ogg,video/quicktime" style="display: none;" />
                                        </label>

                                        <div style="font-size: 0.75rem; color: #64748b; line-height: 1.4;">
                                            💡 <strong>Gợi ý:</strong> Chọn video flycam/phong cảnh ngắn 15 - 30 giây, định dạng MP4/WebM, dung lượng nhẹ (&lt; 15MB) để trang chủ tải nhanh và mượt mà nhất.
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Khối tải Ảnh Nền Banner -->
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    <label class="modal-field-label">Ảnh nền Banner chính (Hero Background)</label>
                                    <div style="display: flex; flex-direction: column; gap: 0.65rem; background: #f8fafc; padding: 0.85rem; border-radius: 0.65rem; border: 1px solid #e2e8f0;">
                                        <?php
                                            $tmpHeroBg = null;
                                            if ($heroBgUpload && is_object($heroBgUpload) && method_exists($heroBgUpload, 'temporaryUrl')) {
                                                try { $tmpHeroBg = $heroBgUpload->temporaryUrl(); } catch (\Throwable $e) {}
                                            }
                                        ?>
                                        <!-- Khung xem trước ảnh nền Banner -->
                                        <div style="width: 100%; height: 130px; border-radius: 0.55rem; overflow: hidden; position: relative; border: 2px solid #cbd5e1; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                                            @if($tmpHeroBg)
                                                <img src="{{ $tmpHeroBg }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                            @else
                                                <img src="{{ $heroBgUrl }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='/hero-bg.jpg'" />
                                            @endif
                                            <div style="position: absolute; bottom: 0.4rem; right: 0.5rem; background: rgba(15,23,42,0.75); color: #fff; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 0.35rem; backdrop-filter: blur(2px);">
                                                Ảnh nền thực tế
                                            </div>
                                        </div>

                                        <label style="margin: 0; width: 100%;">
                                            <div class="custom-upload-btn">
                                                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                <span>{{ $heroBgUpload ? 'Đã chọn ảnh nền mới (Bấm Lưu để áp dụng)' : 'Bấm để Chọn ảnh nền Banner từ máy tính' }}</span>
                                            </div>
                                            <input type="file" wire:model="heroBgUpload" accept="image/*" style="display: none;" />
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <!-- Căn chỉnh Kích thước & Khung hình Banner -->
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
                                <div style="font-size: 0.85rem; font-weight: 800; color: #0284c7;">📐 Căn chỉnh Khung hình & Kích thước Banner (Tránh bị cắt xén)</div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                                    <!-- Chiều cao Banner -->
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.3rem;">
                                            Chiều cao Banner
                                        </label>
                                        <select wire:model="heroHeight" class="modal-input" style="font-size: 0.82rem; font-weight: 600;">
                                            <option value="compact">Gọn nhẹ (~280px)</option>
                                            <option value="standard">Tiêu chuẩn (~420px) [Khuyên dùng]</option>
                                            <option value="cinematic">Rộng lớn / Điện ảnh (~560px)</option>
                                            <option value="auto_16_9">Tự căn theo tỷ lệ chuẩn 16:9</option>
                                        </select>
                                    </div>

                                    <!-- Chế độ hiển thị khung hình -->
                                    <div>
                                        <label style="font-size: 0.78rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.3rem;">
                                            Chế độ phủ hình / Video
                                        </label>
                                        <select wire:model="heroFit" class="modal-input" style="font-size: 0.82rem; font-weight: 600;">
                                            <option value="cover">Tràn viền toàn cảnh (Cover - Đẹp nhất)</option>
                                            <option value="contain">Giữ trọn 100% không cắt (Contain)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Vị trí căn chỉnh tiêu điểm -->
                                <div>
                                    <label style="font-size: 0.78rem; font-weight: 700; color: #334155; display: block; margin-bottom: 0.3rem;">
                                        Tiêu điểm căn chỉnh (Tránh bị cắt mất nóc nhà / người / cảnh trên)
                                    </label>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.5rem;">
                                        <button type="button" wire:click="$set('heroPosition', 'top')" 
                                            style="padding: 0.45rem 0.5rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 700; border: 1px solid #cbd5e1; cursor: pointer; {{ $heroPosition === 'top' ? 'background: #e0f2fe; color: #0284c7; border-color: #38bdf8;' : 'background: #fff; color: #475569;' }}">
                                            ⬆️ Ưu tiên Phía trên
                                        </button>
                                        <button type="button" wire:click="$set('heroPosition', 'center')" 
                                            style="padding: 0.45rem 0.5rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 700; border: 1px solid #cbd5e1; cursor: pointer; {{ $heroPosition === 'center' ? 'background: #e0f2fe; color: #0284c7; border-color: #38bdf8;' : 'background: #fff; color: #475569;' }}">
                                            ⏺️ Căn chính giữa
                                        </button>
                                        <button type="button" wire:click="$set('heroPosition', 'bottom')" 
                                            style="padding: 0.45rem 0.5rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 700; border: 1px solid #cbd5e1; cursor: pointer; {{ $heroPosition === 'bottom' ? 'background: #e0f2fe; color: #0284c7; border-color: #38bdf8;' : 'background: #fff; color: #475569;' }}">
                                            ⬇️ Ưu tiên Phía dưới
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Logo hiển thị trong Banner Hero -->
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 0.85rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                <label class="modal-field-label">Logo biểu tượng chính giữa Banner</label>
                                <div style="display: flex; align-items: center; gap: 0.85rem; background: #f8fafc; padding: 0.75rem 0.85rem; border-radius: 0.65rem; border: 1px solid #e2e8f0;">
                                    <?php
                                        $tmpDoan = null;
                                        if ($logoDoanUpload && is_object($logoDoanUpload) && method_exists($logoDoanUpload, 'temporaryUrl')) {
                                            try { $tmpDoan = $logoDoanUpload->temporaryUrl(); } catch (\Throwable $e) {}
                                        }
                                    ?>
                                    <div style="width: 48px; height: 48px; border-radius: 9999px; overflow: hidden; background: rgba(255,255,255,0.8); border: 2px solid #cbd5e1; shrink: 0; display: flex; align-items: center; justify-content: center;">
                                        @if($tmpDoan)
                                            <img src="{{ $tmpDoan }}" style="width: 100%; height: 100%; object-fit: contain;" />
                                        @else
                                            <img src="{{ $logoDoanUrl }}" style="width: 100%; height: 100%; object-fit: contain;" onerror="this.onerror=null; this.src='/logo-doan.png'" />
                                        @endif
                                    </div>
                                    <label style="flex: 1; margin: 0;">
                                        <div class="custom-upload-btn">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            <span>{{ $logoDoanUpload ? 'Đã chọn ảnh logo mới' : 'Bấm Chọn file ảnh Logo' }}</span>
                                        </div>
                                        <input type="file" wire:model="logoDoanUpload" accept="image/*" style="display: none;" />
                                    </label>
                                </div>
                            </div>

                        @elseif($isAgenciesModal)
                            <div>
                                <label class="modal-field-label">Tiêu đề Khối</label>
                                <input type="text" wire:model="customTitle" class="modal-input" placeholder="Danh sách cơ quan hành chính" />
                            </div>
                            <div>
                                <label class="modal-field-label">Mô tả / Tiêu đề phụ</label>
                                <textarea wire:model="customSubtitle" class="modal-input" rows="2" placeholder="Tra cứu vị trí, thông tin liên hệ và hình ảnh 360° các trụ sở cơ quan công quyền"></textarea>
                            </div>

                            <!-- Lựa chọn 4 cơ quan tùy chỉnh từ DB -->
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
                                <div>
                                    <div style="font-size: 0.9rem; font-weight: 800; color: #0284c7;">🏛️ Chọn 4 Cơ quan / Đơn vị hiển thị nổi bật trên Trang chủ</div>
                                    <div style="font-size: 0.78rem; color: #64748b;">Hệ thống sẽ lấy dữ liệu thực tế từ cơ sở dữ liệu để hiển thị 4 thẻ cơ quan này trên trang chủ.</div>
                                </div>

                                <?php
                                    $allDbPlaces = \App\Models\Place::where('category', '!=', 'neighborhood')->orderBy('name')->get();
                                ?>

                                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                                    @for($i = 0; $i < 4; $i++)
                                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.6rem; padding: 0.75rem 0.85rem; display: flex; flex-direction: column; gap: 0.35rem;">
                                            <label style="font-size: 0.8rem; font-weight: 800; color: #0369a1; display: flex; align-items: center; justify-content: space-between;">
                                                <span>Vị trí hiển thị #{{ $i + 1 }}</span>
                                                @if(!empty($selectedAgencyIds[$i]))
                                                    <span style="font-size: 0.72rem; font-weight: 700; color: #16a34a;">✓ Đã chọn</span>
                                                @endif
                                            </label>
                                            <select wire:model="selectedAgencyIds.{{ $i }}" class="modal-input" style="font-size: 0.88rem; font-weight: 600; width: 100%; background: #ffffff;">
                                                <option value="">-- Chọn cơ quan từ Cơ sở dữ liệu --</option>
                                                @foreach($allDbPlaces as $pl)
                                                    <option value="{{ $pl->id }}">
                                                        {{ $pl->name }} {{ $pl->phone ? "({$pl->phone})" : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                        @elseif($isStatsModal)
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
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
                                        <input type="text" wire:model="stat4Lbl" class="modal-input" placeholder="Diện tích (1.546,30 ha)" />
                                    </div>
                                </div>
                            </div>

                        @elseif($isTdpMergerModal)
                            <div>
                                <label class="modal-field-label">Tiêu đề Bảng 1 (Tổ dân phố cũ)</label>
                                <input type="text" wire:model="oldTableTitle" class="modal-input" placeholder="TRƯỚC SÁP NHẬP (16 TỔ DÂN PHỐ CỦ)" />
                            </div>
                            <div>
                                <label class="modal-field-label">Tiêu đề Bảng 2 (Tổ dân phố mới)</label>
                                <input type="text" wire:model="newTableTitle" class="modal-input" placeholder="SAU SÁP NHẬP (10 TỔ DÂN PHỐ MỚI)" />
                            </div>

                        @elseif($isFooterModal)
                            <!-- Cấu hình Chân trang (Footer) -->
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <!-- Logo Footer Upload -->
                                <div style="background: #f0f9ff; border: 1px dashed #0284c7; border-radius: 0.65rem; padding: 0.85rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                    <div style="font-size: 0.8rem; font-weight: 800; color: #0369a1;">📷 Logo Chân trang (Footer Logo):</div>
                                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                                        <div style="width: 52px; height: 52px; border-radius: 50%; overflow: hidden; border: 2px solid #0284c7; background: #0284c7; shrink: 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                                            @if($footerLogoUpload)
                                                <img src="{{ $footerLogoUpload->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                            @else
                                                <img src="{{ Str::startsWith($footerLogo, 'http') ? $footerLogo : (Str::startsWith($footerLogo, '/storage/') ? $footerLogo : ('/storage/' . ltrim($footerLogo, '/'))) }}" 
                                                     style="width: 100%; height: 100%; object-fit: cover;" 
                                                     onerror="this.onerror=null; this.src='/logo.jpg'" />
                                            @endif
                                        </div>
                                        <div style="flex: 1;">
                                            <input type="file" wire:model="footerLogoUpload" accept="image/*" class="modal-input" style="font-size: 0.78rem; padding: 0.35rem;" />
                                            <div style="font-size: 0.7rem; color: #64748b; margin-top: 0.2rem;">Hỗ trợ ảnh JPG, PNG, WEBP (Tỷ lệ 1:1 hình tròn).</div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="modal-field-label">Tên Cơ quan / Tổ chức</label>
                                    <input type="text" wire:model="footerOrgName" class="modal-input" placeholder="ĐOÀN TNCS HỒ CHÍ MINH PHƯỜNG DUY HÀ" />
                                </div>

                                <div>
                                    <label class="modal-field-label">Địa chỉ trụ sở</label>
                                    <input type="text" wire:model="footerAddress" class="modal-input" placeholder="Số 01 đường Lê Lợi, Phường Duy Hà, thành phố Ninh Bình, tỉnh Ninh Bình" />
                                </div>

                                <div>
                                    <label class="modal-field-label">Thời gian / Giờ làm việc</label>
                                    <input type="text" wire:model="footerWorkingHours" class="modal-input" placeholder="Sáng: 7h30 - 11h30 | Chiều: 13h30 - 17h00 (Từ Thứ 2 đến Thứ 6, nghỉ T7 & CN)" />
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                                    <div>
                                        <label class="modal-field-label">Email liên hệ</label>
                                        <input type="text" wire:model="footerEmail" class="modal-input" placeholder="thongtin@duyha.ninhbinh.gov.vn" />
                                    </div>
                                    <div>
                                        <label class="modal-field-label">Số điện thoại liên hệ</label>
                                        <input type="text" wire:model="footerPhone" class="modal-input" placeholder="(0229) 38253536" />
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                                    <div>
                                        <label class="modal-field-label">Đường dẫn Facebook</label>
                                        <input type="text" wire:model="footerFacebookUrl" class="modal-input" placeholder="https://facebook.com" />
                                    </div>
                                    <div>
                                        <label class="modal-field-label">Đường dẫn Website / Cổng thông tin</label>
                                        <input type="text" wire:model="footerWebsiteUrl" class="modal-input" placeholder="https://duyha.ninhbinh.gov.vn" />
                                    </div>
                                </div>

                                <div>
                                    <label class="modal-field-label">Thông tin Bản quyền (Copyright)</label>
                                    <input type="text" wire:model="footerCopyright" class="modal-input" placeholder="Copyright © Đoàn TNCS Hồ Chí Minh phường Duy Hà. All Rights Reserved" />
                                </div>

                                <div>
                                    <label class="modal-field-label">Ghi chú trích dẫn nguồn</label>
                                    <textarea wire:model="footerSourceNote" class="modal-input" rows="2" placeholder="Ghi rõ nguồn khi phát hành lại thông tin..."></textarea>
                                </div>
                            </div>

                        @elseif($isCustomSectionModal)
                            <div>
                                <label class="modal-field-label">Huy hiệu góc (Badge)</label>
                                <input type="text" wire:model="customSectionBadge" class="modal-input" placeholder="VD: THÔNG BÁO MỚI, SỰ KIỆN..." />
                            </div>
                            <div>
                                <label class="modal-field-label">Tiêu đề chính Khối</label>
                                <input type="text" wire:model="customTitle" class="modal-input" placeholder="Nhập tiêu đề khối..." />
                            </div>
                            <div>
                                <label class="modal-field-label">Mô tả / Tiêu đề phụ</label>
                                <input type="text" wire:model="customSubtitle" class="modal-input" placeholder="Nhập mô tả ngắn gọn..." />
                            </div>
                            <div>
                                <label class="modal-field-label">Nội dung chi tiết</label>
                                <textarea wire:model="customSectionContent" class="modal-input" rows="4" placeholder="Nội dung thông báo, tuyên truyền..."></textarea>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem;">
                                <div>
                                    <label class="modal-field-label">Chữ trên nút bấm (CTA)</label>
                                    <input type="text" wire:model="customSectionBtnText" class="modal-input" placeholder="VD: Xem chi tiết" />
                                </div>
                                <div>
                                    <label class="modal-field-label">Liên kết nút bấm (URL)</label>
                                    <input type="text" wire:model="customSectionBtnUrl" class="modal-input" placeholder="VD: /procedures.html" />
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="modal-field-label">Tiêu đề Khối</label>
                                <input type="text" wire:model="customTitle" class="modal-input" placeholder="Nhập tiêu đề khối..." />
                            </div>
                            <div>
                                <label class="modal-field-label">Mô tả / Tiêu đề phụ</label>
                                <textarea wire:model="customSubtitle" class="modal-input" rows="3" placeholder="Nhập mô tả / phụ đề chi tiết..."></textarea>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Footer Actions -->
                    <div style="padding: 1rem 1.35rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 0.65rem; position: sticky; bottom: 0; z-index: 10;">
                        <button wire:click="closeModal" type="button" class="btn-ui" style="background: #f1f5f9; color: #475569; border-color: #cbd5e1;">
                            Hủy bỏ
                        </button>
                        <button wire:click="saveSection" type="button" class="btn-ui" style="background: #0284c7; color: #ffffff; padding: 0.55rem 1.25rem;">
                            Lưu cấu hình
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
