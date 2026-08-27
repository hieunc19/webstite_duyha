<x-filament-panels::page>
    <style>
        .banner-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1.15rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            transition: all 0.15s ease;
        }
        .banner-row:hover {
            border-color: #38bdf8;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.06);
        }
        .btn-banner {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.42rem 0.9rem;
            border-radius: 0.5rem;
            font-size: 0.82rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-banner:active {
            transform: scale(0.98);
        }
        .btn-banner-edit {
            background: #0284c7;
            color: #ffffff;
        }
        .btn-banner-edit:hover {
            background: #0369a1;
        }
        .btn-banner-cancel {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-banner-cancel:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .modal-field-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
            display: block;
            margin-bottom: 0.3rem;
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

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <?php
            $grouped = [];
            foreach ($banners as $key => $b) {
                $grp = $b['group_name'] ?? 'Danh mục chung';
                $grouped[$grp][$key] = $b;
            }
        ?>

        <!-- Grouped by Mega Menu categories -->
        @foreach($grouped as $groupTitle => $groupItems)
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="font-size: 0.85rem; font-weight: 900; color: #143e78; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="width: 4px; height: 16px; background: #0284c7; border-radius: 2px;"></span>
                    <span>{{ $groupTitle }}</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($groupItems as $key => $b)
                        <div class="banner-row">
                            <!-- Left: Submenu Name only -->
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: #f1f5f9; color: #475569; font-size: 0.75rem; font-weight: 800; shrink: 0;">
                                    {{ $loop->iteration }}
                                </span>
                                <div style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">
                                    {{ $b['page_name'] }}
                                </div>
                            </div>

                            <!-- Right: Actions Toolbar -->
                            <div style="display: flex; align-items: center; gap: 0.65rem; shrink: 0;">
                                <a href="{{ $b['page_url'] }}" target="_blank" style="font-size: 0.78rem; color: #0284c7; font-weight: 700; text-decoration: none; padding: 0.42rem 0.75rem; border-radius: 0.5rem; background: #f0f9ff; border: 1px solid #bae6fd; display: inline-flex; align-items: center; gap: 0.25rem;" title="Xem trang thực tế">
                                    <span>Xem trang</span>
                                    <span>↗</span>
                                </a>
                                <button wire:click="openEditModal('{{ $key }}')" class="btn-banner btn-banner-edit">
                                    <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Chỉnh sửa</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Edit Subpage Banner Modal Dialog -->
        @if($isModalOpen)
            <div style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px);">
                <div style="background: #ffffff; width: 100%; max-width: 650px; max-height: 90vh; border-radius: 0.95rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); overflow-y: auto; border: 1px solid #e2e8f0;">
                    <!-- Modal Header -->
                    <div style="padding: 1rem 1.35rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10;">
                        <div>
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0f172a;">Chỉnh sửa Banner: {{ $editPageName }}</div>
                        </div>
                        <button wire:click="closeModal" style="border: none; background: none; font-size: 1.25rem; font-weight: 700; color: #64748b; cursor: pointer;">&times;</button>
                    </div>

                    <!-- Modal Body Form -->
                    <div style="padding: 1.35rem; display: flex; flex-direction: column; gap: 1rem;">
                        <!-- Banner Background Upload Box -->
                        <div style="background: #f0f9ff; border: 1px dashed #0284c7; border-radius: 0.65rem; padding: 0.85rem; display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="font-size: 0.82rem; font-weight: 800; color: #0369a1;">📷 Ảnh nền Banner (Background Image):</div>
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div style="width: 90px; height: 56px; border-radius: 0.45rem; overflow: hidden; background: #0f172a; border: 1px solid #cbd5e1; shrink: 0;">
                                    @if($bgUpload)
                                        <img src="{{ $bgUpload->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                    @else
                                        <?php
                                            $previewSrc = $editBgImage ?: '/hero-bg.jpg';
                                            if (!Str::startsWith($previewSrc, 'http') && !Str::startsWith($previewSrc, '/storage/') && !Str::startsWith($previewSrc, '/')) {
                                                $previewSrc = '/storage/' . $previewSrc;
                                            }
                                        ?>
                                        <img src="{{ $previewSrc }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='/hero-bg.jpg';" />
                                    @endif
                                </div>
                                <div style="flex: 1;">
                                    <input type="file" wire:model="bgUpload" accept="image/*" class="modal-input" style="font-size: 0.78rem; padding: 0.35rem;" />
                                    <div style="font-size: 0.7rem; color: #64748b; margin-top: 0.2rem;">Hỗ trợ ảnh JPG, PNG, WEBP (Khuyến nghị tỷ lệ 16:5 hoặc chiều rộng tối thiểu 1200px).</div>
                                </div>
                            </div>
                        </div>

                        <!-- Badge Text -->
                        <div>
                            <label class="modal-field-label">Huy hiệu góc trên (Badge text)</label>
                            <input type="text" wire:model="editBadgeText" class="modal-input" placeholder="VD: Hệ thống danh bạ liên lạc chính thức" />
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="modal-field-label">Tiêu đề chính Banner (Title)</label>
                            <input type="text" wire:model="editTitle" class="modal-input" placeholder="Nhập tiêu đề trang..." />
                        </div>

                        <!-- Subtitle -->
                        <div>
                            <label class="modal-field-label">Mô tả / Tiêu đề phụ (Subtitle)</label>
                            <textarea wire:model="editSubtitle" class="modal-input" rows="3" placeholder="Nhập mô tả giới thiệu nội dung trang..."></textarea>
                        </div>

                        <!-- Citizen Reception Rules Section (Nội quy tiếp công dân) -->
                        @if($editingKey === 'citizen_reception')
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem; margin-top: 0.25rem; display: flex; flex-direction: column; gap: 0.75rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: gap; gap: 0.5rem;">
                                    <div style="font-size: 0.88rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.4rem;">
                                        <span style="font-size: 1.1rem;">⚖️</span>
                                        <span>Nội quy tiếp công dân (Hiển thị bên phải trang tiếp dân):</span>
                                    </div>
                                    <button type="button" wire:click="addReceptionRule" style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.75rem; border-radius: 0.5rem; background: #f0fdf4; border: 1px solid #86efac; color: #16a34a; font-size: 0.78rem; font-weight: 800; cursor: pointer; transition: all 0.15s ease;">
                                        <span>+ Thêm điều mới</span>
                                    </button>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                                    @forelse($receptionRules as $rIdx => $rVal)
                                        <div style="display: flex; align-items: flex-start; gap: 0.5rem; background: #f8fafc; padding: 0.5rem 0.65rem; border-radius: 0.6rem; border: 1px solid #e2e8f0;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #f59e0b; color: #ffffff; font-size: 0.72rem; font-weight: 800; flex-shrink: 0; margin-top: 0.25rem;">
                                                {{ $rIdx + 1 }}
                                            </span>
                                            <textarea wire:model="receptionRules.{{ $rIdx }}" class="modal-input" rows="2" style="font-size: 0.82rem; padding: 0.45rem 0.65rem; flex: 1;" placeholder="Nhập nội dung điều quy định {{ $rIdx + 1 }}..."></textarea>
                                            <button type="button" wire:click="removeReceptionRule({{ $rIdx }})" style="border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; border-radius: 0.45rem; padding: 0.45rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; margin-top: 0.15rem;" title="Xóa điều này">
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    @empty
                                        <div style="text-align: center; padding: 0.85rem; color: #94a3b8; font-size: 0.78rem; font-weight: 600;">
                                            Chưa có điều khoản nội quy nào. Bấm <strong>"+ Thêm điều mới"</strong> để bổ sung.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- Waste Classification Guide Section (Lịch thu gom rác) -->
                        @if($editingKey === 'waste_schedule')
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem; margin-top: 0.25rem; display: flex; flex-direction: column; gap: 0.85rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                                    <div style="font-size: 0.88rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.4rem;">
                                        <span style="font-size: 1.1rem;">♻️</span>
                                        <span>Hướng dẫn phân loại rác &amp; Quy định (Phần bên dưới trang):</span>
                                    </div>
                                    <button type="button" wire:click="addWasteCategory" style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.75rem; border-radius: 0.5rem; background: #f0fdf4; border: 1px solid #86efac; color: #16a34a; font-size: 0.78rem; font-weight: 800; cursor: pointer; transition: all 0.15s ease;">
                                        <span>+ Thêm nhóm rác mới</span>
                                    </button>
                                </div>

                                <!-- Guide Title & Subtitle -->
                                <div style="display: grid; grid-template-columns: 1fr; gap: 0.65rem;">
                                    <div>
                                        <label class="modal-field-label">Tiêu đề khối hướng dẫn</label>
                                        <input type="text" wire:model="wasteGuideTitle" class="modal-input" placeholder="VD: Hướng dẫn phân loại rác tại nguồn" />
                                    </div>
                                    <div>
                                        <label class="modal-field-label">Mô tả / Phụ đề khối hướng dẫn</label>
                                        <input type="text" wire:model="wasteGuideSubtitle" class="modal-input" placeholder="VD: Thực hiện Luật Bảo vệ môi trường — Chung tay xây dựng Phường Duy Hà Xanh - Sạch - Văn minh" />
                                    </div>
                                </div>

                                <!-- Categories List -->
                                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                                    <label class="modal-field-label" style="margin-bottom: 0;">Các nhóm phân loại rác:</label>
                                    @forelse($wasteCategories as $cIdx => $cVal)
                                        <div style="display: flex; flex-direction: column; gap: 0.4rem; background: #f8fafc; padding: 0.65rem 0.75rem; border-radius: 0.65rem; border: 1px solid #e2e8f0;">
                                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                                                <div style="display: flex; align-items: center; gap: 0.5rem; flex: 1;">
                                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #10b981; color: #ffffff; font-size: 0.72rem; font-weight: 800; flex-shrink: 0;">
                                                        {{ $cIdx + 1 }}
                                                    </span>
                                                    <input type="text" wire:model="wasteCategories.{{ $cIdx }}.title" class="modal-input" style="font-size: 0.82rem; padding: 0.35rem 0.65rem; font-weight: 800;" placeholder="Tên nhóm rác (VD: 1. Rác hữu cơ)..." />
                                                </div>
                                                <button type="button" wire:click="removeWasteCategory({{ $cIdx }})" style="border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; border-radius: 0.45rem; padding: 0.4rem 0.55rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;" title="Xóa nhóm này">
                                                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                            <div>
                                                <textarea wire:model="wasteCategories.{{ $cIdx }}.desc" class="modal-input" rows="2" style="font-size: 0.8rem; padding: 0.4rem 0.65rem;" placeholder="Mô tả chi tiết các loại rác thuộc nhóm này (VD: Thức ăn thừa, rau củ quả...)..."></textarea>
                                            </div>

                                            <!-- Category Image Upload Box -->
                                            <div style="display: flex; align-items: center; gap: 0.75rem; background: #ffffff; padding: 0.45rem 0.6rem; border-radius: 0.5rem; border: 1px dashed #cbd5e1;">
                                                <div style="width: 52px; height: 52px; border-radius: 0.45rem; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    @if(isset($wasteCategoryUploads[$cIdx]) && $wasteCategoryUploads[$cIdx])
                                                        <img src="{{ $wasteCategoryUploads[$cIdx]->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                                    @elseif(!empty($cVal['image']))
                                                        <img src="{{ $cVal['image'] }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='/hero-bg.jpg';" />
                                                    @else
                                                        <span style="font-size: 1.25rem;">🖼️</span>
                                                    @endif
                                                </div>
                                                <div style="flex: 1; display: flex; flex-direction: column; gap: 0.2rem;">
                                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                                        <span style="font-size: 0.75rem; font-weight: 700; color: #475569;">📷 Ảnh minh họa nhóm rác:</span>
                                                        @if(!empty($cVal['image']) || (isset($wasteCategoryUploads[$cIdx]) && $wasteCategoryUploads[$cIdx]))
                                                            <button type="button" wire:click="deleteWasteCategoryImage({{ $cIdx }})" style="font-size: 0.7rem; color: #dc2626; background: none; border: none; cursor: pointer; font-weight: 700;">
                                                                ✕ Gỡ ảnh
                                                            </button>
                                                        @endif
                                                    </div>
                                                    <input type="file" wire:model="wasteCategoryUploads.{{ $cIdx }}" accept="image/*" class="modal-input" style="font-size: 0.75rem; padding: 0.25rem;" />
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div style="text-align: center; padding: 0.85rem; color: #94a3b8; font-size: 0.78rem; font-weight: 600;">
                                            Chưa có nhóm phân loại rác nào. Bấm <strong>"+ Thêm nhóm rác mới"</strong> để bổ sung.
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Regulation Text -->
                                <div>
                                    <label class="modal-field-label">⚖️ Nội dung quy định / xử phạt (Khối cảnh báo bên dưới)</label>
                                    <textarea wire:model="wasteRegulation" class="modal-input" rows="2" placeholder="Nhập quy định xử phạt vi phạm bỏ rác..."></textarea>
                                </div>
                            </div>
                        @endif

                        <!-- Feedback Process Guide Section (Quy trình tiếp nhận phản ánh kiến nghị) -->
                        @if($editingKey === 'feedback')
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem; margin-top: 0.25rem; display: flex; flex-direction: column; gap: 0.85rem;">
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                                    <div style="font-size: 0.88rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.4rem;">
                                        <span style="font-size: 1.1rem;">🔀</span>
                                        <span>Quy trình tiếp nhận &amp; Xử lý (Cột bên phải trang Phản ánh):</span>
                                    </div>
                                    <button type="button" wire:click="addFeedbackStep" style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.75rem; border-radius: 0.5rem; background: #eff6ff; border: 1px solid #93c5fd; color: #1d4ed8; font-size: 0.78rem; font-weight: 800; cursor: pointer; transition: all 0.15s ease;">
                                        <span>+ Thêm bước mới</span>
                                    </button>
                                </div>

                                <!-- Process Title -->
                                <div>
                                    <label class="modal-field-label">Tiêu đề khối quy trình</label>
                                    <input type="text" wire:model="feedbackProcessTitle" class="modal-input" placeholder="VD: QUY TRÌNH 4 BƯỚC TIẾP NHẬN" />
                                </div>

                                <!-- Steps List -->
                                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                                    <label class="modal-field-label" style="margin-bottom: 0;">Các bước thực hiện tiếp nhận &amp; xử lý:</label>
                                    @forelse($feedbackProcessSteps as $sIdx => $sVal)
                                        <div style="display: flex; flex-direction: column; gap: 0.4rem; background: #f8fafc; padding: 0.65rem 0.75rem; border-radius: 0.65rem; border: 1px solid #e2e8f0;">
                                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                                                <div style="display: flex; align-items: center; gap: 0.5rem; flex: 1;">
                                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #2563eb; color: #ffffff; font-size: 0.72rem; font-weight: 800; flex-shrink: 0;">
                                                        {{ $sIdx + 1 }}
                                                    </span>
                                                    <input type="text" wire:model="feedbackProcessSteps.{{ $sIdx }}.title" class="modal-input" style="font-size: 0.82rem; padding: 0.35rem 0.65rem; font-weight: 800;" placeholder="Tên bước (VD: Bước {{ $sIdx + 1 }}: Tiếp nhận phản ánh)..." />
                                                </div>
                                                <button type="button" wire:click="removeFeedbackStep({{ $sIdx }})" style="border: 1px solid #fecaca; background: #fef2f2; color: #dc2626; border-radius: 0.45rem; padding: 0.4rem 0.55rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;" title="Xóa bước này">
                                                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                            <div>
                                                <textarea wire:model="feedbackProcessSteps.{{ $sIdx }}.desc" class="modal-input" rows="2" style="font-size: 0.8rem; padding: 0.4rem 0.65rem;" placeholder="Nội dung chi tiết của bước này..."></textarea>
                                            </div>
                                        </div>
                                    @empty
                                        <div style="text-align: center; padding: 0.85rem; color: #94a3b8; font-size: 0.78rem; font-weight: 600;">
                                            Chưa có bước quy trình nào. Bấm <strong>"+ Thêm bước mới"</strong> để bổ sung.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Actions -->
                    <div style="padding: 0.85rem 1.35rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 0.65rem;">
                        <button wire:click="closeModal" class="btn-banner btn-banner-cancel">
                            Hủy bỏ
                        </button>
                        <button wire:click="saveBanner" class="btn-banner btn-banner-edit" style="padding: 0.55rem 1.35rem;">
                            <span wire:loading.remove wire:target="saveBanner">Lưu Banner</span>
                            <span wire:loading wire:target="saveBanner">Đang lưu...</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
