<x-filament-panels::page>
    <style>
        .fb-wrap {
            color: #1e293b;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            max-width: 850px;
        }
        .dark .fb-wrap {
            color: #f1f5f9;
        }
        .fb-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }
        .dark .fb-card {
            background: #0f172a;
            border-color: #1e293b;
            box-shadow: none;
        }
        .fb-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            border-radius: 0.55rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .fb-link-sky {
            background: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .fb-link-sky:hover {
            background: #e0f2fe;
        }
        .dark .fb-link-sky {
            background: rgba(3, 105, 161, 0.2);
            color: #7dd3fc;
            border-color: rgba(3, 105, 161, 0.4);
        }
        .fb-link-emerald {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .fb-link-emerald:hover {
            background: #d1fae5;
        }
        .dark .fb-link-emerald {
            background: rgba(4, 120, 87, 0.2);
            color: #6ee7b7;
            border-color: rgba(4, 120, 87, 0.4);
        }
        .fb-link-btn svg {
            width: 1rem;
            height: 1rem;
            display: block;
        }

        .fb-form-section {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }
        .fb-field-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        .fb-label {
            font-size: 0.775rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #334155;
        }
        .dark .fb-label {
            color: #cbd5e1;
        }
        .fb-req {
            color: #ef4444;
        }
        .fb-input {
            width: 100%;
            padding: 0.7rem 0.95rem;
            border-radius: 0.7rem;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            font-size: 0.85rem;
            color: #0f172a;
            outline: none;
            transition: all 0.15s ease;
            box-sizing: border-box;
        }
        .dark .fb-input {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        .fb-input:focus {
            border-color: #0284c7;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }
        .dark .fb-input:focus {
            background: #0f172a;
            border-color: #38bdf8;
        }
        .fb-hint {
            font-size: 0.725rem;
            color: #64748b;
            margin: 0;
            line-height: 1.4;
        }
        .dark .fb-hint {
            color: #94a3b8;
        }

        .fb-btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.65rem 1.35rem;
            border-radius: 0.7rem;
            background: #0284c7;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(2, 132, 199, 0.3);
            transition: all 0.15s ease;
        }
        .fb-btn-submit:hover {
            background: #0369a1;
            transform: translateY(-1px);
        }
        .fb-btn-submit:active {
            transform: translateY(0);
        }
        .fb-btn-submit svg {
            width: 1.1rem;
            height: 1.1rem;
            display: block;
        }

        .fb-guide-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 0.8rem;
            padding: 1.1rem;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }
        .dark .fb-guide-box {
            background: rgba(34, 197, 94, 0.08);
            border-color: rgba(34, 197, 94, 0.25);
        }
        .fb-guide-title {
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #166534;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .dark .fb-guide-title {
            color: #86efac;
        }
        .fb-guide-list {
            margin: 0;
            padding-left: 1.25rem;
            font-size: 0.8rem;
            color: #14532d;
            line-height: 1.6;
        }
        .dark .fb-guide-list {
            color: #bbf7d0;
        }
        .fb-guide-list li {
            margin-bottom: 0.3rem;
        }
    </style>

    <div class="fb-wrap">

        <!-- Settings Form Card -->
        <div class="fb-form-section">
            <form wire:submit.prevent="save" class="fb-card fb-form-section">
                
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="font-size: 0.95rem; font-weight: 800; color: #0f172a;" class="dark:text-white">
                        ⚙️ Cấu hình Liên kết Google Form &amp; Google Sheets
                    </span>

                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        @if(!empty($googleFormUrl))
                            <a href="{{ $googleFormUrl }}" target="_blank" class="fb-link-btn fb-link-sky">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                                <span>Mở Form gốc</span>
                            </a>
                        @endif

                        @if(!empty($googleSheetUrl))
                            <a href="{{ $googleSheetUrl }}" target="_blank" class="fb-link-btn fb-link-emerald">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5" />
                                </svg>
                                <span>Mở Google Sheets</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Google Form URL -->
                <div class="fb-field-group">
                    <label class="fb-label">
                        Đường link Google Form <span class="fb-req">*</span>
                    </label>
                    <input 
                        type="url" 
                        wire:model.defer="googleFormUrl" 
                        placeholder="https://docs.google.com/forms/d/e/.../viewform"
                        class="fb-input"
                        required
                    />
                    <p class="fb-hint">
                        💡 Dán link Google Form của bạn. Hệ thống tự động nhận diện và gửi phản ánh của người dân từ website vào Google Form này.
                    </p>
                </div>

                <!-- Google Sheets Result URL -->
                <div class="fb-field-group">
                    <label class="fb-label">
                        Đường link Google Sheets xem kết quả (Tùy chọn)
                    </label>
                    <input 
                        type="url" 
                        wire:model.defer="googleSheetUrl" 
                        placeholder="https://docs.google.com/spreadsheets/d/.../edit"
                        class="fb-input"
                    />
                    <p class="fb-hint">
                        💡 Dán link Google Sheets liên kết để cán bộ click mở xem kết quả nhanh chóng.
                    </p>
                </div>

                <!-- Submit Action Button -->
                <div style="padding-top: 0.25rem;">
                    <button type="submit" class="fb-btn-submit">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>Lưu cấu hình</span>
                    </button>
                </div>
            </form>

            <!-- Instructions Card -->
            <div class="fb-guide-box">
                <h4 class="fb-guide-title">
                    <span>📋 Hướng dẫn quản trị qua Google Form &amp; Google Sheets:</span>
                </h4>
                <ol class="fb-guide-list">
                    <li>Tạo biểu mẫu Google Form với 4 câu hỏi: <strong>Họ và tên</strong>, <strong>Số điện thoại</strong>, <strong>Tiêu đề</strong>, <strong>Nội dung chi tiết</strong>.</li>
                    <li>Bấm nút <strong>"Liên kết với Trang tính"</strong> trên Google Form để dữ liệu tự động đổ về Google Sheets.</li>
                    <li>Sao chép đường link Google Form dán vào ô bên trên và bấm <strong>Lưu cấu hình</strong>. Mọi phản ánh gửi từ website sẽ tự động nhảy vào Google Form và Google Sheets của bạn.</li>
                </ol>
            </div>
        </div>

    </div>
</x-filament-panels::page>
