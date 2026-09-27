<x-filament-panels::page>
    <style>
        [x-cloak] {
            display: none !important;
        }
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
                    📅 Cập nhật File Lịch Tiếp công dân (Ảnh hoặc PDF)
                </span>
            </div>

            <!-- Custom Styled Upload Dropzone -->
            <div>
                <label class="cr-dropzone">
                    <input type="file" wire:model="imageFile" accept="image/*,.pdf,application/pdf" class="cr-hidden-file" />
                    <div class="cr-dropzone-inner">
                        <div class="cr-dropzone-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <div>
                            <span class="cr-dropzone-main">Bấm vào đây để chọn File Lịch tiếp công dân</span>
                            <span class="cr-dropzone-sub">Định dạng hỗ trợ: JPG, PNG, WEBP, PDF (Bản scan / ảnh chụp / văn bản PDF)</span>
                        </div>
                    </div>
                </label>

                <div wire:loading wire:target="imageFile" style="font-size: 0.75rem; color: #0284c7; font-weight: 700; margin-top: 0.4rem;">
                    ⏳ Đang nạp tệp lên...
                </div>
            </div>

            <!-- Live Image / PDF Preview Area -->
            <div class="cr-preview-box">
                @if($imageFile)
                    @if($this->isUploadedPdf)
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1rem; text-align: center;">
                            <div style="width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                📄
                            </div>
                            <span style="font-size: 0.85rem; font-weight: 800; color: #0f172a;" class="dark:text-white">
                                {{ $imageFile->getClientOriginalName() }}
                            </span>
                            <span style="font-size: 0.75rem; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 0.2rem 0.6rem; border-radius: 9999px;">
                                File PDF mới vừa chọn (Chưa bấm Lưu)
                            </span>
                        </div>
                    @else
                        <img src="{{ $imageFile->temporaryUrl() }}" alt="Ảnh vừa chọn" class="cr-preview-img" />
                        <span style="font-size: 0.75rem; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 0.2rem 0.6rem; border-radius: 9999px;">
                            Ảnh mới vừa chọn (Chưa bấm Lưu)
                        </span>
                    @endif
                @elseif(!empty($this->currentImageUrl))
                    <div style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;" class="dark:border-slate-800">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;" class="dark:text-slate-400">
                            Tệp đang hiển thị trên website:
                        </span>
                        <div x-data="{ showConfirm: false }">
                            <button type="button" @click="showConfirm = true" class="cr-btn-danger">
                                🗑️ Gỡ file
                            </button>

                            <!-- Modern Confirmation Modal Teleported to Body -->
                            <template x-teleport="body">
                                <div x-show="showConfirm" 
                                     x-cloak 
                                     style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 999999; display: flex; align-items: center; justify-content: center; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); padding: 1rem; overflow-y: auto;"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     @keydown.escape.window="showConfirm = false">
                                    
                                    <div @click.away="showConfirm = false"
                                         style="margin: auto; background: #ffffff; border-radius: 1.25rem; width: 100%; max-width: 440px; padding: 1.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); border: 1px solid #e2e8f0; text-align: center; position: relative;"
                                         class="dark:bg-slate-900 dark:border-slate-800 dark:text-white"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95">
                                         
                                        <div style="width: 3.75rem; height: 3.75rem; margin: 0 auto 1.25rem auto; border-radius: 9999px; background-color: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center;" class="dark:bg-red-950/50 dark:text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 2rem; height: 2rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </div>

                                        <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0;" class="dark:text-white">
                                            Xác nhận gỡ tệp tin
                                        </h3>

                                        <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 1.5rem 0; line-height: 1.5;" class="dark:text-slate-400">
                                            Bạn có chắc chắn muốn gỡ tệp Lịch tiếp công dân này không? Tệp đang hiển thị sẽ bị xóa khỏi hệ thống và website.
                                        </p>

                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                                            <button type="button" 
                                                    @click="showConfirm = false"
                                                    style="flex: 1; padding: 0.65rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 700; color: #475569; background: #f1f5f9; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.15s ease;"
                                                    class="dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700">
                                                Hủy bỏ
                                            </button>
                                            <button type="button" 
                                                    @click="showConfirm = false; $wire.deleteImage()"
                                                    style="flex: 1; padding: 0.65rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 700; color: #ffffff; background: #dc2626; border: 1px solid #b91c1c; cursor: pointer; transition: all 0.15s ease; box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);"
                                                    class="hover:bg-red-700 active:scale-95">
                                                Đồng ý gỡ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    @if($this->isCurrentPdf)
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.65rem; padding: 1rem; text-align: center;">
                            <div style="width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                📕
                            </div>
                            <span style="font-size: 0.85rem; font-weight: 800; color: #0f172a;" class="dark:text-white">
                                File Văn bản PDF Lịch tiếp công dân
                            </span>
                            <a href="{{ $this->currentImageUrl }}" target="_blank" style="font-size: 0.78rem; font-weight: 700; color: #0284c7; background: #f0f9ff; border: 1px solid #bae6fd; padding: 0.35rem 0.85rem; border-radius: 0.5rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <span>Xem file PDF trực tiếp</span>
                                <span>↗</span>
                            </a>
                        </div>
                    @else
                        <img src="{{ $this->currentImageUrl }}" alt="Ảnh lịch tiếp công dân" class="cr-preview-img" />
                    @endif
                @else
                    <div style="text-align: center; padding: 1.5rem 1rem; color: #94a3b8;">
                        <p style="font-size: 0.85rem; font-weight: 700; color: #64748b; margin: 0 0 0.25rem 0;">Chưa có file lịch tiếp công dân</p>
                        <p style="font-size: 0.75rem; margin: 0;">Vui lòng bấm vào khung bên trên để chọn file Ảnh hoặc PDF và bấm <strong>Lưu &amp; Cập nhật</strong>.</p>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div style="padding-top: 0.25rem;">
                <button type="submit" class="cr-btn-submit">
                    <svg style="width: 1.1rem; height: 1.1rem;" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span>Lưu &amp; Cập nhật File Lịch tiếp dân</span>
                </button>
            </div>

        </form>

    </div>
</x-filament-panels::page>
