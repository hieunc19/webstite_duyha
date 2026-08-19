<x-filament-panels::page>
    <style>
        .cr-wrap {
            color: #1e293b;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            max-width: 760px;
        }
        .dark .cr-wrap {
            color: #f1f5f9;
        }
        .cr-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }
        .dark .cr-card {
            background: #0f172a;
            border-color: #1e293b;
            box-shadow: none;
        }
        
        .cr-dropzone {
            display: block;
            position: relative;
            border: 2px dashed #94a3b8;
            border-radius: 0.85rem;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 1.25rem 1rem;
        }
        .dark .cr-dropzone {
            background: #1e293b;
            border-color: #475569;
        }
        .cr-dropzone:hover {
            border-color: #0284c7;
            background: #f0f9ff;
        }
        .dark .cr-dropzone:hover {
            border-color: #38bdf8;
            background: #0f172a;
        }
        .cr-hidden-file {
            display: none !important;
        }
        .cr-dropzone-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            text-align: left;
        }
        .cr-dropzone-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            background: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .dark .cr-dropzone-icon {
            background: rgba(2, 132, 199, 0.2);
            color: #38bdf8;
        }
        .cr-dropzone-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            display: block;
        }
        .cr-dropzone-main {
            font-size: 0.875rem;
            font-weight: 800;
            color: #0f172a;
            display: block;
        }
        .dark .cr-dropzone-main {
            color: #f8fafc;
        }
        .cr-dropzone-sub {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            display: block;
            margin-top: 0.15rem;
        }
        .dark .cr-dropzone-sub {
            color: #94a3b8;
        }

        .cr-btn-submit {
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
        .cr-btn-submit:hover {
            background: #0369a1;
            transform: translateY(-1px);
        }
        .cr-btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 0.5rem;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .cr-btn-danger:hover {
            background: #fee2e2;
            border-color: #fca5a5;
        }
        .cr-preview-box {
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            overflow: hidden;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .dark .cr-preview-box {
            background: #020617;
            border-color: #1e293b;
        }
        .cr-preview-img {
            max-width: 260px;
            max-height: 200px;
            object-fit: contain;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 0.25rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }
        .dark .cr-preview-img {
            border-color: #334155;
            background: #0f172a;
        }
    </style>

    <div class="cr-wrap">

        <form wire:submit.prevent="save" class="cr-card" style="display: flex; flex-direction: column; gap: 1.25rem;">
            
            <!-- Header -->
            <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                <span style="font-size: 0.95rem; font-weight: 800; color: #0f172a;" class="dark:text-white">
                    📅 Cập nhật File ảnh Lịch Tiếp công dân
                </span>
            </div>

            <!-- Custom Styled Upload Dropzone -->
            <div>
                <label class="cr-dropzone">
                    <input type="file" wire:model="imageFile" accept="image/*" class="cr-hidden-file" />
                    <div class="cr-dropzone-inner">
                        <div class="cr-dropzone-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <div>
                            <span class="cr-dropzone-main">Bấm vào đây để chọn File ảnh Lịch tiếp công dân</span>
                            <span class="cr-dropzone-sub">Định dạng hỗ trợ: JPG, PNG, WEBP (Bản scan / ảnh chụp)</span>
                        </div>
                    </div>
                </label>

                <div wire:loading wire:target="imageFile" style="font-size: 0.75rem; color: #0284c7; font-weight: 700; margin-top: 0.4rem;">
                    ⏳ Đang nạp ảnh lên...
                </div>
            </div>

            <!-- Live Image Preview Area -->
            <div class="cr-preview-box">
                @if($imageFile)
                    <img src="{{ $imageFile->temporaryUrl() }}" alt="Ảnh vừa chọn" class="cr-preview-img" />
                    <span style="font-size: 0.75rem; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 0.2rem 0.6rem; border-radius: 9999px;">
                        Ảnh mới vừa chọn (Chưa bấm Lưu)
                    </span>
                @elseif(!empty($this->currentImageUrl))
                    <div style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;" class="dark:border-slate-800">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;" class="dark:text-slate-400">
                            Ảnh đang hiển thị trên website:
                        </span>
                        <div>
                            <button type="button" wire:click="deleteImage" wire:confirm="Bạn có chắc muốn gỡ ảnh này không?" class="cr-btn-danger">
                                🗑️ Gỡ ảnh
                            </button>
                        </div>
                    </div>
                    <img src="{{ $this->currentImageUrl }}" alt="Ảnh lịch tiếp công dân" class="cr-preview-img" />
                @else
                    <div style="text-align: center; padding: 1.5rem 1rem; color: #94a3b8;">
                        <p style="font-size: 0.85rem; font-weight: 700; color: #64748b; margin: 0 0 0.25rem 0;">Chưa có file ảnh lịch tiếp công dân</p>
                        <p style="font-size: 0.75rem; margin: 0;">Vui lòng bấm vào khung bên trên để chọn file ảnh và bấm <strong>Lưu &amp; Cập nhật</strong>.</p>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div style="padding-top: 0.25rem;">
                <button type="submit" class="cr-btn-submit">
                    <svg style="width: 1.1rem; height: 1.1rem;" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span>Lưu &amp; Cập nhật Lịch tiếp dân</span>
                </button>
            </div>

        </form>

    </div>
</x-filament-panels::page>
