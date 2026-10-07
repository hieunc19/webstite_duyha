<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedure_support_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('contact_type')->default('support'); // support | hotline
            $table->string('field_name')->nullable();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('phone', 30);
            $table->string('avatar')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('procedure_support_contacts')->insert([
            [
                'contact_type' => 'support',
                'field_name' => 'Văn hóa - Xã hội',
                'name' => 'Nguyễn Thị Hồng Hạnh',
                'role' => 'Cán bộ phụ trách lĩnh vực Văn hóa - Xã hội',
                'phone' => '0913022016',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contact_type' => 'support',
                'field_name' => 'Tư pháp',
                'name' => 'Dương Văn Đáng',
                'role' => 'Cán bộ phụ trách lĩnh vực Tư pháp',
                'phone' => '0983198762',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contact_type' => 'support',
                'field_name' => 'Hộ tịch',
                'name' => 'Lê Hồng Tuân',
                'role' => 'Cán bộ phụ trách lĩnh vực Hộ tịch',
                'phone' => '0969698832',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contact_type' => 'support',
                'field_name' => 'Nông nghiệp, Môi trường, Kinh tế hạ tầng & Đô thị',
                'name' => 'Hoàng Mạnh Tuấn',
                'role' => 'Cán bộ phụ trách lĩnh vực Nông nghiệp, Môi trường, Kinh tế hạ tầng & Đô thị',
                'phone' => '0963868736',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contact_type' => 'hotline',
                'field_name' => 'Đường dây nóng phản ánh, kiến nghị',
                'name' => 'Nguyễn Như Uy',
                'role' => 'Chủ tịch UBND phường',
                'phone' => '0912220182',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contact_type' => 'hotline',
                'field_name' => 'Đường dây nóng phản ánh, kiến nghị',
                'name' => 'Nguyễn Tiến Đạt',
                'role' => 'Giám đốc Trung tâm Phục vụ hành chính công phường Duy Hà',
                'phone' => '0915802179',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('procedure_support_contacts');
    }
};
