<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Place;
use App\Models\AdministrativeUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Admin Account
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Places
        $places = array (
  0 => 
  array (
    'name' => 'UBND Phường Duy Hà',
    'category' => 'government',
    'address' => 'Đường Lê Thái Tổ, Phường Duy Hà, Tỉnh Ninh Bình',
    'phone' => '02263835112',
    'lat' => 20.6478448,
    'lng' => 105.914737,
    'description' => 'Trụ sở làm việc chính thức của Ủy ban nhân dân và Hội đồng nhân dân phường Duy Hà, nơi tiếp nhận và giải quyết các thủ tục hành chính, dịch vụ công cộng cho người dân trên địa bàn.',
    'image' => 'https://images.unsplash.com/photo-1577086664693-894d8405334a?q=80&w=600&auto=format&fit=crop',
    'images_360' => NULL,
    'administrative_unit_id' => NULL,
    'status' => 'active',
  ),
  1 => 
  array (
    'name' => 'Trụ sở Công an Phường Duy Hà',
    'category' => 'police',
    'address' => 'Đường Lê Thái Tổ, Phường Duy Hà, Tỉnh Ninh Bình',
    'phone' => '02263835112',
    'lat' => 20.6479,
    'lng' => 105.9135,
    'description' => 'Cơ quan công an cấp cơ sở thực hiện chức năng bảo vệ an ninh quốc gia, giữ gìn trật tự an toàn xã hội, quản lý cư trú và hỗ trợ phòng chống tội phạm trên địa bàn phường Duy Hà.',
    'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=600&auto=format&fit=crop',
    'images_360' => NULL,
    'administrative_unit_id' => NULL,
    'status' => 'active',
  ),
  2 => 
  array (
    'name' => 'Ủy Ban MTTQ Việt Nam Phường Duy Hà',
    'category' => 'government',
    'address' => NULL,
    'phone' => '02263835112',
    'lat' => 20.647055,
    'lng' => 105.900467,
    'description' => NULL,
    'image' => 'places/01KYPG5FXF3KZT5BT3HV44WESC.jpg',
    'images_360' => NULL,
    'administrative_unit_id' => NULL,
    'status' => 'active',
  ),
  3 => 
  array (
    'name' => 'Bệnh viện đa khoa Hà Nội Đồng Văn',
    'category' => 'health',
    'address' => 'Lô TM3, TM4, TDP, Đông Hải, Duy Hà, Ninh Bình, Việt Nam',
    'phone' => '02263835112',
    'lat' => 20.642822,
    'lng' => 105.910378,
    'description' => NULL,
    'image' => 'places/01KZKG7NYX0GSNH1XJWBDPAKR8.jpg',
    'images_360' => NULL,
    'administrative_unit_id' => NULL,
    'status' => 'active',
  ),
  4 => 
  array (
    'name' => 'Trường Tiểu học Duy Hà',
    'category' => 'school',
    'address' => 'Ngõ 12, Đường Lê Thái Tổ, Phường Duy Hà, Tỉnh Ninh Bình',
    'phone' => NULL,
    'lat' => 20.65,
    'lng' => 105.913,
    'description' => 'Trường tiểu học công lập đạt chuẩn quốc gia cấp độ 1, với cơ sở vật chất khang trang, đáp ứng nhu cầu giáo dục tiểu học chất lượng cao cho con em trong khu vực.',
    'image' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=600&auto=format&fit=crop',
    'images_360' => NULL,
    'administrative_unit_id' => NULL,
    'status' => 'active',
  ),
  5 => 
  array (
    'name' => 'Trường THCS Duy Hà',
    'category' => 'school',
    'address' => 'Đường Nguyễn Huệ, Phường Duy Hà, Tỉnh Ninh Bình',
    'phone' => NULL,
    'lat' => 20.6485,
    'lng' => 105.9095,
    'description' => 'Trường Trung học cơ sở Duy Hà có đội ngũ giáo viên giàu kinh nghiệm, đạt nhiều thành tích xuất sắc trong công tác bồi dưỡng học sinh giỏi và giáo dục toàn diện.',
    'image' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=600&auto=format&fit=crop',
    'images_360' => NULL,
    'administrative_unit_id' => NULL,
    'status' => 'active',
  ),
  6 => 
  array (
    'name' => 'Trạm Y tế Phường Duy Hà',
    'category' => 'health',
    'address' => 'Đường Trần Hưng Đạo, Phường Duy Hà, Tỉnh Ninh Bình',
    'phone' => NULL,
    'lat' => 20.6465,
    'lng' => 105.9085,
    'description' => 'Cơ sở y tế ban đầu của phường Duy Hà chịu trách nhiệm khám chữa bệnh cơ bản, tiêm chủng mở rộng, chăm sóc sức khỏe bà mẹ trẻ em và phòng chống dịch bệnh tại cộng đồng.',
    'image' => 'https://images.unsplash.com/photo-1586773860418-d37222d8fce2?q=80&w=600&auto=format&fit=crop',
    'images_360' => NULL,
    'administrative_unit_id' => NULL,
    'status' => 'active',
  ),
);
        $duyHaUnit = AdministrativeUnit::where('code', '13336')->first();
        foreach ($places as $p) {
            if ($duyHaUnit) {
                $p['administrative_unit_id'] = $duyHaUnit->id;
            }
            Place::updateOrCreate(['name' => $p['name']], $p);
        }

        // 3. Neighborhoods
        $neighborhoods = array (
  0 => 
  array (
    'name' => 'TDP Duy Minh',
    'type' => 'new',
    'group_code' => 'duy-minh',
    'leader_name' => 'Đại úy Trần Hữu Tiến',
    'leader_phone' => '0986.361.395',
    'households' => 560,
    'people' => 1927,
    'area_ha' => 63.0,
    'status' => 'active',
    'bi_thu_name' => 'Lê Xuân Hiến',
    'bi_thu_phone' => '0378582168',
    'to_truong_name' => 'Đặng Quang Thiện',
    'to_truong_phone' => NULL,
    'cskv_name' => 'Đại úy Trần Hữu Tiến',
    'cskv_phone' => '0986.361.395',
    'mat_tan_name' => 'Lê Xuân Hiến',
    'mat_tan_phone' => '0378582168',
    'nguoi_cao_tuoi' => 'Bạch Tường Vân',
    'phu_nu' => 'Nguyễn T.Thanh Thủy',
    'nong_dan' => 'Đặng Quốc Việt',
    'ccb' => 'Vũ Văn Mười',
    'doan_thanh_nien' => 'Đỗ Thị Loan',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  1 => 
  array (
    'name' => 'TDP Ngọc Tú',
    'type' => 'new',
    'group_code' => 'ngoc-tu',
    'leader_name' => 'Thiếu tá Nguyễn Minh Tiến',
    'leader_phone' => '0359.290.686',
    'households' => 730,
    'people' => 2513,
    'area_ha' => 418.6,
    'status' => 'active',
    'bi_thu_name' => 'Trương Thị Lê',
    'bi_thu_phone' => '0963566121',
    'to_truong_name' => 'Phan Văn Trịnh',
    'to_truong_phone' => '0988475861',
    'cskv_name' => 'Thiếu tá Nguyễn Minh Tiến',
    'cskv_phone' => '0359.290.686',
    'mat_tan_name' => 'Trương Thị Lê',
    'mat_tan_phone' => '0963566121',
    'nguoi_cao_tuoi' => 'Hà Minh Khoái',
    'phu_nu' => 'Dương T.Thanh Thảo',
    'nong_dan' => 'Nguyễn Thị La',
    'ccb' => 'Dương Tuấn Anh',
    'doan_thanh_nien' => 'Nguyễn Văn Biên',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  2 => 
  array (
    'name' => 'TDP Động Linh Trang',
    'type' => 'new',
    'group_code' => 'dong-linh-trang',
    'leader_name' => 'Thiếu úy Vũ Văn Hào',
    'leader_phone' => '0796.191.310',
    'households' => 616,
    'people' => 2134,
    'area_ha' => 84.5,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn T. Minh Thoa',
    'bi_thu_phone' => '0984687445',
    'to_truong_name' => 'Nguyễn Văn Tĩnh',
    'to_truong_phone' => '0946844268',
    'cskv_name' => 'Thiếu úy Vũ Văn Hào',
    'cskv_phone' => '0796.191.310',
    'mat_tan_name' => 'Nguyễn Tiến Thụy',
    'mat_tan_phone' => '0977293567',
    'nguoi_cao_tuoi' => 'Kiều Tiến Năng',
    'phu_nu' => 'Phạm Thị Khánh',
    'nong_dan' => 'Lý Thị Ngẩn',
    'ccb' => 'Nguyễn Tiến Vinh',
    'doan_thanh_nien' => 'Nguyễn Thị Linh',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  3 => 
  array (
    'name' => 'TDP Chuông',
    'type' => 'new',
    'group_code' => 'chuong',
    'leader_name' => 'Đại úy Nguyễn Văn Việt',
    'leader_phone' => '0972.280.538',
    'households' => 712,
    'people' => 2348,
    'area_ha' => 65.0,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Đức Bình',
    'bi_thu_phone' => '0987862138',
    'to_truong_name' => 'Nguyễn Đức Bình',
    'to_truong_phone' => '0987862138',
    'cskv_name' => 'Đại úy Nguyễn Văn Việt',
    'cskv_phone' => '0972.280.538',
    'mat_tan_name' => 'Dương T.Minh Huệ ',
    'mat_tan_phone' => '0349285723',
    'nguoi_cao_tuoi' => 'Nguyễn Minh Quang',
    'phu_nu' => 'Dương T.Thu Hương',
    'nong_dan' => 'Nguyễn Văn Toản',
    'ccb' => 'Nguyễn Hoàng Bảo',
    'doan_thanh_nien' => 'Lê Hoàng Dũng',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  4 => 
  array (
    'name' => 'TDP Bạch Xá',
    'type' => 'new',
    'group_code' => 'bach-xa',
    'leader_name' => 'Đại úy Đoàn Văn Chương',
    'leader_phone' => '0911.940.111',
    'households' => 663,
    'people' => 2404,
    'area_ha' => 131.4,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Hữu Hào',
    'bi_thu_phone' => '0326951177',
    'to_truong_name' => 'Chu Văn Mấu',
    'to_truong_phone' => '0364293625',
    'cskv_name' => 'Đại úy Đoàn Văn Chương',
    'cskv_phone' => '0911.940.111',
    'mat_tan_name' => 'Nguyễn Hữu Hào',
    'mat_tan_phone' => '0326951177',
    'nguoi_cao_tuoi' => 'Nguyễn Văn Hoạch',
    'phu_nu' => 'Nguyễn Thị Hoa',
    'nong_dan' => 'Nguyễn Thị Diễn',
    'ccb' => 'Nguyễn Hữu Hội',
    'doan_thanh_nien' => 'Lương Thùy Dung',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  5 => 
  array (
    'name' => 'TDP Hoàng Đông',
    'type' => 'new',
    'group_code' => 'hoang-dong',
    'leader_name' => 'Thiếu tá Ngô Vinh Quang',
    'leader_phone' => '0977.597.118',
    'households' => 770,
    'people' => 2742,
    'area_ha' => 139.6,
    'status' => 'active',
    'bi_thu_name' => 'Đỗ Hữu Biên',
    'bi_thu_phone' => '0902236670',
    'to_truong_name' => 'Vũ Đức Hùng',
    'to_truong_phone' => '0973750628',
    'cskv_name' => 'Thiếu tá Ngô Vinh Quang',
    'cskv_phone' => '0977.597.118',
    'mat_tan_name' => 'Vũ Văn Tuyên',
    'mat_tan_phone' => '0984863383',
    'nguoi_cao_tuoi' => 'Đỗ Văn Hòa',
    'phu_nu' => 'Tạ Thị Hồng Lê',
    'nong_dan' => 'Trương Thị Nhàn',
    'ccb' => 'Nguyễn Văn Hòa',
    'doan_thanh_nien' => 'Vũ Mai Hương',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  6 => 
  array (
    'name' => 'TDP Hương Cát',
    'type' => 'new',
    'group_code' => 'huong-cat',
    'leader_name' => 'Thượng úy Đinh Xuân Trường',
    'leader_phone' => '0585.288.686',
    'households' => 561,
    'people' => 2046,
    'area_ha' => 187.8,
    'status' => 'active',
    'bi_thu_name' => 'Lê Văn Đãi',
    'bi_thu_phone' => '0396217476',
    'to_truong_name' => 'Nguyễn Quốc Việt',
    'to_truong_phone' => '0963848132',
    'cskv_name' => 'Thượng úy Đinh Xuân Trường',
    'cskv_phone' => '0585.288.686',
    'mat_tan_name' => 'Lê Văn Đãi',
    'mat_tan_phone' => '0396217476',
    'nguoi_cao_tuoi' => 'Nguyễn Văn Cải',
    'phu_nu' => 'Đỗ Thị Thau',
    'nong_dan' => 'Nguyễn Thị Yên',
    'ccb' => 'Lê Văn Điệp',
    'doan_thanh_nien' => 'Lê Văn Thành',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  7 => 
  array (
    'name' => 'TDP Duy Hải',
    'type' => 'new',
    'group_code' => 'duy-hai',
    'leader_name' => 'Thượng úy Đinh Xuân Trường',
    'leader_phone' => '0585.288.686',
    'households' => 725,
    'people' => 2527,
    'area_ha' => 165.1,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Văn Thức',
    'bi_thu_phone' => '0976791743',
    'to_truong_name' => 'Hoàng Văn Dũng',
    'to_truong_phone' => '0963848132',
    'cskv_name' => 'Thượng úy Đinh Xuân Trường',
    'cskv_phone' => '0585.288.686',
    'mat_tan_name' => 'Vũ Khắc Chuật',
    'mat_tan_phone' => '0352327214',
    'nguoi_cao_tuoi' => 'Trần Thị Thanh',
    'phu_nu' => 'Trần Thị Oanh',
    'nong_dan' => 'Nguyễn Hữu Dư',
    'ccb' => 'Lê Văn Thức',
    'doan_thanh_nien' => 'Nguyễn Ngọc Tùng',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  8 => 
  array (
    'name' => 'TDP Ngọc Động',
    'type' => 'new',
    'group_code' => 'ngoc-dong',
    'leader_name' => 'Đại úy Vũ Ngọc Quang',
    'leader_phone' => '0978.530.570',
    'households' => 796,
    'people' => 2806,
    'area_ha' => 155.5,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Văn Thọ',
    'bi_thu_phone' => '0988181452',
    'to_truong_name' => 'Nguyễn Thanh Hà',
    'to_truong_phone' => '0962497817',
    'cskv_name' => 'Đại úy Vũ Ngọc Quang',
    'cskv_phone' => '0978.530.570',
    'mat_tan_name' => 'Nguyễn Văn Thọ',
    'mat_tan_phone' => '0988181452',
    'nguoi_cao_tuoi' => 'Nguyễn Văn Thìn',
    'phu_nu' => 'Nguyễn Thị Thanh',
    'nong_dan' => 'Phạm Xuân Quỳnh',
    'ccb' => 'Nguyễn Đình Tuyến',
    'doan_thanh_nien' => 'Nguyễn Anh Tuấn',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  9 => 
  array (
    'name' => 'TDP Đông Hải',
    'type' => 'new',
    'group_code' => 'dong-hai',
    'leader_name' => 'Thiếu tá Nguyễn Văn Tuân',
    'leader_phone' => '0866.697.088',
    'households' => 634,
    'people' => 2168,
    'area_ha' => 135.8,
    'status' => 'active',
    'bi_thu_name' => 'Lê Minh Dũng',
    'bi_thu_phone' => '0914196813',
    'to_truong_name' => 'Lê Minh Dũng',
    'to_truong_phone' => '0914196813',
    'cskv_name' => 'Thiếu tá Nguyễn Văn Tuân',
    'cskv_phone' => '0866.697.088',
    'mat_tan_name' => 'Nghiêm Thái Hoa',
    'mat_tan_phone' => '0915493685',
    'nguoi_cao_tuoi' => 'Nguyễn Thị Nhi',
    'phu_nu' => 'Đỗ Thị Huyền',
    'nong_dan' => 'Đàm Thị Minh',
    'ccb' => 'Đỗ Thị Đào',
    'doan_thanh_nien' => 'Vũ Quốc Văn',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  10 => 
  array (
    'name' => 'TDP Tú',
    'type' => 'old',
    'group_code' => 'ngoc-tu',
    'leader_name' => 'Thiếu tá Nguyễn Minh Tiến',
    'leader_phone' => '0359.290.686',
    'households' => 327,
    'people' => 1158,
    'area_ha' => 130.5,
    'status' => 'active',
    'bi_thu_name' => 'Trương Thị Lê',
    'bi_thu_phone' => '0963566121',
    'to_truong_name' => 'Phan Văn Trịnh',
    'to_truong_phone' => '0988475861',
    'cskv_name' => 'Thiếu tá Nguyễn Minh Tiến',
    'cskv_phone' => '0359.290.686',
    'mat_tan_name' => 'Trương Thị Lê',
    'mat_tan_phone' => '0963566121',
    'nguoi_cao_tuoi' => 'Hà Minh Khoái',
    'phu_nu' => 'Dương T.Thanh Thảo',
    'nong_dan' => 'Nguyễn Thị La',
    'ccb' => 'Dương Tuấn Anh',
    'doan_thanh_nien' => 'Nguyễn Văn Biên',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  11 => 
  array (
    'name' => 'TDP Ninh Lão',
    'type' => 'old',
    'group_code' => 'duy-minh',
    'leader_name' => 'Đại úy Trần Hữu Tiến',
    'leader_phone' => '0986.361.395',
    'households' => 357,
    'people' => 1249,
    'area_ha' => 42.6,
    'status' => 'active',
    'bi_thu_name' => 'Lê Xuân Hiến',
    'bi_thu_phone' => '0378582168',
    'to_truong_name' => 'Đặng Quang Thiện',
    'to_truong_phone' => NULL,
    'cskv_name' => 'Đại úy Trần Hữu Tiến',
    'cskv_phone' => '0986.361.395',
    'mat_tan_name' => 'Lê Xuân Hiến',
    'mat_tan_phone' => '0378582168',
    'nguoi_cao_tuoi' => 'Bạch Tường Vân',
    'phu_nu' => 'Nguyễn T.Thanh Thủy',
    'nong_dan' => 'Đặng Quốc Việt',
    'ccb' => 'Vũ Văn Mười',
    'doan_thanh_nien' => 'Đỗ Thị Loan',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  12 => 
  array (
    'name' => 'TDP Động Linh',
    'type' => 'old',
    'group_code' => 'dong-linh-trang',
    'leader_name' => 'Thiếu úy Vũ Văn Hào',
    'leader_phone' => '0796.191.310',
    'households' => 318,
    'people' => 1096,
    'area_ha' => 62.9,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn T. Minh Thoa',
    'bi_thu_phone' => '0984687445',
    'to_truong_name' => 'Nguyễn Văn Tĩnh',
    'to_truong_phone' => '0946844268',
    'cskv_name' => 'Thiếu úy Vũ Văn Hào',
    'cskv_phone' => '0796.191.310',
    'mat_tan_name' => 'Nguyễn Tiến Thụy',
    'mat_tan_phone' => '0977293567',
    'nguoi_cao_tuoi' => 'Kiều Tiến Năng',
    'phu_nu' => 'Phạm Thị Khánh',
    'nong_dan' => 'Lý Thị Ngẩn',
    'ccb' => 'Nguyễn Tiến Vinh',
    'doan_thanh_nien' => 'Nguyễn Thị Linh',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  13 => 
  array (
    'name' => 'TDP Trịnh',
    'type' => 'old',
    'group_code' => 'dong-linh-trang',
    'leader_name' => 'Thiếu úy Vũ Văn Hào',
    'leader_phone' => '0796.191.310',
    'households' => 298,
    'people' => 1038,
    'area_ha' => 21.6,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn T. Minh Thoa',
    'bi_thu_phone' => '0984687445',
    'to_truong_name' => 'Nguyễn Văn Tĩnh',
    'to_truong_phone' => '0946844268',
    'cskv_name' => 'Thiếu úy Vũ Văn Hào',
    'cskv_phone' => '0796.191.310',
    'mat_tan_name' => 'Nguyễn Tiến Thụy',
    'mat_tan_phone' => '0977293567',
    'nguoi_cao_tuoi' => 'Kiều Tiến Năng',
    'phu_nu' => 'Phạm Thị Khánh',
    'nong_dan' => 'Lý Thị Ngẩn',
    'ccb' => 'Nguyễn Tiến Vinh',
    'doan_thanh_nien' => 'Nguyễn Thị Linh',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  14 => 
  array (
    'name' => 'TDP Chuông',
    'type' => 'old',
    'group_code' => 'chuong',
    'leader_name' => 'Đại úy Nguyễn Văn Việt',
    'leader_phone' => '0972.280.538',
    'households' => 712,
    'people' => 2348,
    'area_ha' => 34.2,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Đức Bình',
    'bi_thu_phone' => '0987862138',
    'to_truong_name' => 'Nguyễn Đức Bình',
    'to_truong_phone' => '0987862138',
    'cskv_name' => 'Đại úy Nguyễn Văn Việt',
    'cskv_phone' => '0972.280.538',
    'mat_tan_name' => 'Dương T.Minh Huệ ',
    'mat_tan_phone' => '0349285723',
    'nguoi_cao_tuoi' => 'Nguyễn Minh Quang',
    'phu_nu' => 'Dương T.Thu Hương',
    'nong_dan' => 'Nguyễn Văn Toản',
    'ccb' => 'Nguyễn Hoàng Bảo',
    'doan_thanh_nien' => 'Lê Hoàng Dũng',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  15 => 
  array (
    'name' => 'TDP Bạch Xá',
    'type' => 'old',
    'group_code' => 'bach-xa',
    'leader_name' => 'Đại úy Đoàn Văn Chương',
    'leader_phone' => '0911.940.111',
    'households' => 663,
    'people' => 2404,
    'area_ha' => 131.4,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Hữu Hào',
    'bi_thu_phone' => '0326951177',
    'to_truong_name' => 'Chu Văn Mấu',
    'to_truong_phone' => '0364293625',
    'cskv_name' => 'Đại úy Đoàn Văn Chương',
    'cskv_phone' => '0911.940.111',
    'mat_tan_name' => 'Nguyễn Hữu Hào',
    'mat_tan_phone' => '0326951177',
    'nguoi_cao_tuoi' => 'Nguyễn Văn Hoạch',
    'phu_nu' => 'Nguyễn Thị Hoa',
    'nong_dan' => 'Nguyễn Thị Diễn',
    'ccb' => 'Nguyễn Hữu Hội',
    'doan_thanh_nien' => 'Lương Thùy Dung',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  16 => 
  array (
    'name' => 'TDP An Nhân',
    'type' => 'old',
    'group_code' => 'hoang-dong',
    'leader_name' => 'Thiếu tá Ngô Vinh Quang',
    'leader_phone' => '0977.597.118',
    'households' => 201,
    'people' => 669,
    'area_ha' => 20.3,
    'status' => 'active',
    'bi_thu_name' => 'Đỗ Hữu Biên',
    'bi_thu_phone' => '0902236670',
    'to_truong_name' => 'Vũ Đức Hùng',
    'to_truong_phone' => '0973750628',
    'cskv_name' => 'Thiếu tá Ngô Vinh Quang',
    'cskv_phone' => '0977.597.118',
    'mat_tan_name' => 'Vũ Văn Tuyên',
    'mat_tan_phone' => '0984863383',
    'nguoi_cao_tuoi' => 'Đỗ Văn Hòa',
    'phu_nu' => 'Tạ Thị Hồng Lê',
    'nong_dan' => 'Trương Thị Nhàn',
    'ccb' => 'Nguyễn Văn Hòa',
    'doan_thanh_nien' => 'Vũ Mai Hương',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  17 => 
  array (
    'name' => 'TDP Hoàng Thượng',
    'type' => 'old',
    'group_code' => 'hoang-dong',
    'leader_name' => 'Thiếu tá Ngô Vinh Quang',
    'leader_phone' => '0977.597.118',
    'households' => 326,
    'people' => 1174,
    'area_ha' => 46.8,
    'status' => 'active',
    'bi_thu_name' => 'Đỗ Hữu Biên',
    'bi_thu_phone' => '0902236670',
    'to_truong_name' => 'Vũ Đức Hùng',
    'to_truong_phone' => '0973750628',
    'cskv_name' => 'Thiếu tá Ngô Vinh Quang',
    'cskv_phone' => '0977.597.118',
    'mat_tan_name' => 'Vũ Văn Tuyên',
    'mat_tan_phone' => '0984863383',
    'nguoi_cao_tuoi' => 'Đỗ Văn Hòa',
    'phu_nu' => 'Tạ Thị Hồng Lê',
    'nong_dan' => 'Trương Thị Nhàn',
    'ccb' => 'Nguyễn Văn Hòa',
    'doan_thanh_nien' => 'Vũ Mai Hương',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  18 => 
  array (
    'name' => 'TDP Hoàng Hạ',
    'type' => 'old',
    'group_code' => 'hoang-dong',
    'leader_name' => 'Thiếu tá Ngô Vinh Quang',
    'leader_phone' => '0977.597.118',
    'households' => 243,
    'people' => 899,
    'area_ha' => 72.5,
    'status' => 'active',
    'bi_thu_name' => 'Đỗ Hữu Biên',
    'bi_thu_phone' => '0902236670',
    'to_truong_name' => 'Vũ Đức Hùng',
    'to_truong_phone' => '0973750628',
    'cskv_name' => 'Thiếu tá Ngô Vinh Quang',
    'cskv_phone' => '0977.597.118',
    'mat_tan_name' => 'Vũ Văn Tuyên',
    'mat_tan_phone' => '0984863383',
    'nguoi_cao_tuoi' => 'Đỗ Văn Hòa',
    'phu_nu' => 'Tạ Thị Hồng Lê',
    'nong_dan' => 'Trương Thị Nhàn',
    'ccb' => 'Nguyễn Văn Hòa',
    'doan_thanh_nien' => 'Vũ Mai Hương',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  19 => 
  array (
    'name' => 'TDP Hương Cát',
    'type' => 'old',
    'group_code' => 'huong-cat',
    'leader_name' => 'Thượng úy Đinh Xuân Trường',
    'leader_phone' => '0585.288.686',
    'households' => 561,
    'people' => 2046,
    'area_ha' => 187.8,
    'status' => 'active',
    'bi_thu_name' => 'Lê Văn Đãi',
    'bi_thu_phone' => '0396217476',
    'to_truong_name' => 'Nguyễn Quốc Việt',
    'to_truong_phone' => '0963848132',
    'cskv_name' => 'Thượng úy Đinh Xuân Trường',
    'cskv_phone' => '0585.288.686',
    'mat_tan_name' => 'Lê Văn Đãi',
    'mat_tan_phone' => '0396217476',
    'nguoi_cao_tuoi' => 'Nguyễn Văn Cải',
    'phu_nu' => 'Đỗ Thị Thau',
    'nong_dan' => 'Nguyễn Thị Yên',
    'ccb' => 'Lê Văn Điệp',
    'doan_thanh_nien' => 'Lê Văn Thành',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  20 => 
  array (
    'name' => 'TDP Tam Giáp',
    'type' => 'old',
    'group_code' => 'duy-hai',
    'leader_name' => 'Thượng úy Đinh Xuân Trường',
    'leader_phone' => '0585.288.686',
    'households' => 379,
    'people' => 1284,
    'area_ha' => 84.6,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Văn Thức',
    'bi_thu_phone' => '0976791743',
    'to_truong_name' => 'Hoàng Văn Dũng',
    'to_truong_phone' => '0963848132',
    'cskv_name' => 'Thượng úy Đinh Xuân Trường',
    'cskv_phone' => '0585.288.686',
    'mat_tan_name' => 'Vũ Khắc Chuật',
    'mat_tan_phone' => '0352327214',
    'nguoi_cao_tuoi' => 'Trần Thị Thanh',
    'phu_nu' => 'Trần Thị Oanh',
    'nong_dan' => 'Nguyễn Hữu Dư',
    'ccb' => 'Lê Văn Thức',
    'doan_thanh_nien' => 'Nguyễn Ngọc Tùng',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  21 => 
  array (
    'name' => 'TDP Tứ Giáp',
    'type' => 'old',
    'group_code' => 'duy-hai',
    'leader_name' => 'Thượng úy Đinh Xuân Trường',
    'leader_phone' => '0585.288.686',
    'households' => 346,
    'people' => 1243,
    'area_ha' => 82.5,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Văn Thức',
    'bi_thu_phone' => '0976791743',
    'to_truong_name' => 'Hoàng Văn Dũng',
    'to_truong_phone' => '0963848132',
    'cskv_name' => 'Thượng úy Đinh Xuân Trường',
    'cskv_phone' => '0585.288.686',
    'mat_tan_name' => 'Vũ Khắc Chuật',
    'mat_tan_phone' => '0352327214',
    'nguoi_cao_tuoi' => 'Trần Thị Thanh',
    'phu_nu' => 'Trần Thị Oanh',
    'nong_dan' => 'Nguyễn Hữu Dư',
    'ccb' => 'Lê Văn Thức',
    'doan_thanh_nien' => 'Nguyễn Ngọc Tùng',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  22 => 
  array (
    'name' => 'TDP Ngọc Động',
    'type' => 'old',
    'group_code' => 'ngoc-dong',
    'leader_name' => 'Đại úy Vũ Ngọc Quang',
    'leader_phone' => '0978.530.570',
    'households' => 796,
    'people' => 2806,
    'area_ha' => 155.5,
    'status' => 'active',
    'bi_thu_name' => 'Nguyễn Văn Thọ',
    'bi_thu_phone' => '0988181452',
    'to_truong_name' => 'Nguyễn Thanh Hà',
    'to_truong_phone' => '0962497817',
    'cskv_name' => 'Đại úy Vũ Ngọc Quang',
    'cskv_phone' => '0978.530.570',
    'mat_tan_name' => 'Nguyễn Văn Thọ',
    'mat_tan_phone' => '0988181452',
    'nguoi_cao_tuoi' => 'Nguyễn Văn Thìn',
    'phu_nu' => 'Nguyễn Thị Thanh',
    'nong_dan' => 'Phạm Xuân Quỳnh',
    'ccb' => 'Nguyễn Đình Tuyến',
    'doan_thanh_nien' => 'Nguyễn Anh Tuấn',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  23 => 
  array (
    'name' => 'TDP Đông Hải',
    'type' => 'old',
    'group_code' => 'dong-hai',
    'leader_name' => 'Thiếu tá Nguyễn Văn Tuân',
    'leader_phone' => '0866.697.088',
    'households' => 634,
    'people' => 2168,
    'area_ha' => 164.6,
    'status' => 'active',
    'bi_thu_name' => 'Lê Minh Dũng',
    'bi_thu_phone' => '0914196813',
    'to_truong_name' => 'Lê Minh Dũng',
    'to_truong_phone' => '0914196813',
    'cskv_name' => 'Thiếu tá Nguyễn Văn Tuân',
    'cskv_phone' => '0866.697.088',
    'mat_tan_name' => 'Nghiêm Thái Hoa',
    'mat_tan_phone' => '0915493685',
    'nguoi_cao_tuoi' => 'Nguyễn Thị Nhi',
    'phu_nu' => 'Đỗ Thị Huyền',
    'nong_dan' => 'Đàm Thị Minh',
    'ccb' => 'Đỗ Thị Đào',
    'doan_thanh_nien' => 'Vũ Quốc Văn',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  24 => 
  array (
    'name' => 'TDP Trung',
    'type' => 'old',
    'group_code' => 'duy-minh',
    'leader_name' => 'Đại úy Trần Hữu Tiến',
    'leader_phone' => '0986.361.395',
    'households' => 203,
    'people' => 678,
    'area_ha' => 20.4,
    'status' => 'active',
    'bi_thu_name' => 'Lê Xuân Hiến',
    'bi_thu_phone' => '0378582168',
    'to_truong_name' => 'Đặng Quang Thiện',
    'to_truong_phone' => NULL,
    'cskv_name' => 'Đại úy Trần Hữu Tiến',
    'cskv_phone' => '0986.361.395',
    'mat_tan_name' => 'Lê Xuân Hiến',
    'mat_tan_phone' => '0378582168',
    'nguoi_cao_tuoi' => 'Bạch Tường Vân',
    'phu_nu' => 'Nguyễn T.Thanh Thủy',
    'nong_dan' => 'Đặng Quốc Việt',
    'ccb' => 'Vũ Văn Mười',
    'doan_thanh_nien' => 'Đỗ Thị Loan',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  25 => 
  array (
    'name' => 'TDP Ngọc Thị',
    'type' => 'old',
    'group_code' => 'ngoc-tu',
    'leader_name' => 'Thiếu tá Nguyễn Minh Tiến',
    'leader_phone' => '0359.290.686',
    'households' => 403,
    'people' => 1355,
    'area_ha' => 288.1,
    'status' => 'active',
    'bi_thu_name' => 'Trương Thị Lê',
    'bi_thu_phone' => '0963566121',
    'to_truong_name' => 'Phan Văn Trịnh',
    'to_truong_phone' => '0988475861',
    'cskv_name' => 'Thiếu tá Nguyễn Minh Tiến',
    'cskv_phone' => '0359.290.686',
    'mat_tan_name' => 'Trương Thị Lê',
    'mat_tan_phone' => '0963566121',
    'nguoi_cao_tuoi' => 'Hà Minh Khoái',
    'phu_nu' => 'Dương T.Thanh Thảo',
    'nong_dan' => 'Nguyễn Thị La',
    'ccb' => 'Dương Tuấn Anh',
    'doan_thanh_nien' => 'Nguyễn Văn Biên',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
);
        foreach ($neighborhoods as $n) {
            \App\Models\Neighborhood::updateOrCreate(['name' => $n['name'], 'type' => $n['type']], $n);
        }

        // 4. Meritorious Families
        if (Schema::hasTable('meritorious_families')) {
            \App\Models\MeritoriousFamily::truncate();
            $families = array (
  0 => 
  array (
    'name' => 'Danh sách tri ân 27/07/2026',
    'file_path' => 'meritorious_files/01M05KG4J30SDY1KNWYA0HBX41.xlsx',
    'file_name' => 'DS Thương binh, bệnh binh, thờ cúng liệt sĩ... DUY HÀ.xlsx',
    'file_size' => NULL,
    'description' => 'Danh sách vinh danh ',
    'period_date' => NULL,
    'type' => NULL,
    'year' => 2026,
    'neighborhood_id' => NULL,
    'address' => NULL,
    'representative_name' => NULL,
    'phone' => NULL,
    'benefit_details' => NULL,
    'gift_amount' => NULL,
    'gift_details' => NULL,
    'celebration_event_id' => NULL,
    'status' => 'active',
    'file_url' => 'http://127.0.0.1:8005/storage/meritorious_files/01M05KG4J30SDY1KNWYA0HBX41.xlsx',
  ),
);
            foreach ($families as $f) {
                \App\Models\MeritoriousFamily::create($f);
            }
        }

        // 5. Departments
        if (Schema::hasTable('departments')) {
            \App\Models\Department::truncate();
            $departments = array (
  0 => 
  array (
    'code' => 'dang_uy',
    'name' => 'Đảng ủy Phường',
    'color' => 'primary',
    'description' => 'Khối Đảng ủy và cơ quan Đảng Phường Duy Hà',
    'sort_order' => 1,
    'status' => 'active',
  ),
  1 => 
  array (
    'code' => 'chinh_quyen',
    'name' => 'UBND / Chính quyền',
    'color' => 'success',
    'description' => 'UBND và các phòng ban quản lý Nhà nước',
    'sort_order' => 2,
    'status' => 'active',
  ),
  2 => 
  array (
    'code' => 'ttpvhcc',
    'name' => 'Hành chính công',
    'color' => 'info',
    'description' => 'Trung tâm Phục vụ Hành chính công Phường Duy Hà',
    'sort_order' => 3,
    'status' => 'active',
  ),
  3 => 
  array (
    'code' => 'cskv',
    'name' => 'Cảnh sát khu vực (CSKV)',
    'color' => 'danger',
    'description' => 'Khối Cảnh sát khu vực quản lý an ninh các Tổ dân phố',
    'sort_order' => 4,
    'status' => 'inactive',
  ),
  4 => 
  array (
    'code' => 'cong_an',
    'name' => 'Công an Phường Duy Hà',
    'color' => 'warning',
    'description' => 'Ban chỉ huy và lực lượng Công an Phường Duy Hà',
    'sort_order' => 5,
    'status' => 'inactive',
  ),
);
            foreach ($departments as $d) {
                \App\Models\Department::create($d);
            }
        }

        // 6. Officials
        if (Schema::hasTable('officials')) {
            \App\Models\Official::truncate();
            $officials = array (
  0 => 
  array (
    'name' => 'Ngô Thị Lan Hương',
    'role' => 'Bí thư Đảng ủy, chủ tịch HĐND',
    'phone' => '0983542466',
    'neighborhood_name' => 
    array (
      0 => 'TDP Đảng ủy Phường Duy Hà',
    ),
    'avatar_color' => '#DC2626',
    'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=200&auto=format&fit=crop',
    'department' => 'dang_uy',
    'status' => 'active',
  ),
  1 => 
  array (
    'name' => 'Trần Hạng Vũ',
    'role' => 'Phó Bí thư thường trực',
    'phone' => '0986583169',
    'neighborhood_name' => 
    array (
      0 => 'TDP Đảng ủy Phường Duy Hà',
    ),
    'avatar_color' => '#1D4ED8',
    'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=200&auto=format&fit=crop',
    'department' => 'dang_uy',
    'status' => 'active',
  ),
  2 => 
  array (
    'name' => 'Đàm Thanh Minh',
    'role' => 'Chánh văn phòng Đảng ủy',
    'phone' => '0916185222',
    'neighborhood_name' => 
    array (
      0 => 'TDP Đảng ủy Phường Duy Hà',
    ),
    'avatar_color' => '#2563EB',
    'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=200&auto=format&fit=crop',
    'department' => 'dang_uy',
    'status' => 'active',
  ),
  3 => 
  array (
    'name' => 'Nguyễn Như Uy',
    'role' => 'Phó Bí thư Đảng ủy, Chủ tịch UBND',
    'phone' => '0912220182',
    'neighborhood_name' => 
    array (
      0 => 'TDP UBND Phường Duy Hà',
    ),
    'avatar_color' => '#059669',
    'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=200&auto=format&fit=crop',
    'department' => 'chinh_quyen',
    'status' => 'active',
  ),
  4 => 
  array (
    'name' => 'Nguyễn Duy Khiêm',
    'role' => 'Chánh VP HĐND-UBND',
    'phone' => '0942893028',
    'neighborhood_name' => 
    array (
      0 => 'TDP UBND Phường Duy Hà',
    ),
    'avatar_color' => '#0284C7',
    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop',
    'department' => 'chinh_quyen',
    'status' => 'active',
  ),
  5 => 
  array (
    'name' => 'Kiều Ngọc Kiên',
    'role' => 'Trưởng phòng Văn hoá - Xã hội',
    'phone' => '0973268310',
    'neighborhood_name' => 
    array (
      0 => 'TDP UBND Phường Duy Hà',
    ),
    'avatar_color' => '#7C3AED',
    'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200&auto=format&fit=crop',
    'department' => 'chinh_quyen',
    'status' => 'active',
  ),
  6 => 
  array (
    'name' => 'Nguyễn Đức Thuận',
    'role' => 'Trưởng phòng Kinh tế hạ tầng',
    'phone' => '0912164372',
    'neighborhood_name' => 
    array (
      0 => 'TDP UBND Phường Duy Hà',
    ),
    'avatar_color' => '#D97706',
    'avatar' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?q=80&w=200&auto=format&fit=crop',
    'department' => 'chinh_quyen',
    'status' => 'active',
  ),
  7 => 
  array (
    'name' => 'Nguyễn Tiến Đạt',
    'role' => 'Giám đốc TTPVHCC',
    'phone' => '0915802179',
    'neighborhood_name' => 
    array (
      0 => 'TDP Trung tâm Phục vụ Hành chính công',
    ),
    'avatar_color' => '#10B981',
    'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=200&auto=format&fit=crop',
    'department' => 'ttpvhcc',
    'status' => 'active',
  ),
);
            foreach ($officials as $o) {
                \App\Models\Official::create($o);
            }
        }

        // 7. TDP Officials
        if (Schema::hasTable('tdp_officials')) {
            \App\Models\TdpOfficial::truncate();
            $tdpOfficials = array (
  0 => 
  array (
    'tdp_name' => 'Ngọc Tú',
    'bi_thu_name' => 'Trương Thị Lê',
    'bi_thu_phone' => '0963.566.121',
    'to_truong_name' => 'Phan Văn Trịnh',
    'to_truong_phone' => '0988.475.861',
    'mat_tan_name' => 'Trương Thị Lê',
    'mat_tan_phone' => '0963.566.121',
    'nguoi_cao_tuoi' => 'Hà Minh Khoái',
    'phu_nu' => 'Dương T.Thanh Thảo',
    'nong_dan' => 'Nguyễn Thị La',
    'ccb' => 'Dương Tuấn Anh',
    'doan_thanh_nien' => 'Nguyễn Văn Biên',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  1 => 
  array (
    'tdp_name' => 'Duy Minh',
    'bi_thu_name' => 'Lê Xuân Hiến',
    'bi_thu_phone' => '0378.582.168',
    'to_truong_name' => 'Đặng Quang Thiện',
    'to_truong_phone' => '',
    'mat_tan_name' => 'Lê Xuân Hiến',
    'mat_tan_phone' => '0378.582.168',
    'nguoi_cao_tuoi' => 'Bạch Tường Vân',
    'phu_nu' => 'Nguyễn T.Thanh Thủy',
    'nong_dan' => 'Đặng Quốc Việt',
    'ccb' => 'Vũ Văn Mười',
    'doan_thanh_nien' => 'Đỗ Thị Loan',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  2 => 
  array (
    'tdp_name' => 'Động Linh Trang',
    'bi_thu_name' => 'Nguyễn T. Minh Thoa',
    'bi_thu_phone' => '0984.687.445',
    'to_truong_name' => 'Nguyễn Văn Tỉnh',
    'to_truong_phone' => '0946.844.268',
    'mat_tan_name' => 'Nguyễn Tiến Thụy',
    'mat_tan_phone' => '0977.293.567',
    'nguoi_cao_tuoi' => 'Kiều Tiến Năng',
    'phu_nu' => 'Phạm Thị Khánh',
    'nong_dan' => 'Lý Thị Ngẩn',
    'ccb' => 'Nguyễn Tiến Vinh',
    'doan_thanh_nien' => 'Trần Thị Diệp',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  3 => 
  array (
    'tdp_name' => 'Chuồng',
    'bi_thu_name' => 'Ngô Bá Tùy',
    'bi_thu_phone' => '0985.834.898',
    'to_truong_name' => 'Đinh Viết Lượng',
    'to_truong_phone' => '0966.835.154',
    'mat_tan_name' => 'Đỗ Tiến Lạc',
    'mat_tan_phone' => '0983.196.969',
    'nguoi_cao_tuoi' => 'Vũ Duy Cương',
    'phu_nu' => 'Phạm Thị Oanh',
    'nong_dan' => 'Ngô Duy Lượng',
    'ccb' => 'Bùi Hải Triều',
    'doan_thanh_nien' => 'Lương Thị Nhâm',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  4 => 
  array (
    'tdp_name' => 'Bạch Xá',
    'bi_thu_name' => 'Đỗ Hồng Tình',
    'bi_thu_phone' => '0982.265.239',
    'to_truong_name' => 'Đỗ Đức Dưỡng',
    'to_truong_phone' => '0974.345.244',
    'mat_tan_name' => 'Đặng Tiến Cường',
    'mat_tan_phone' => '0966.453.916',
    'nguoi_cao_tuoi' => 'Phùng Đăng Long',
    'phu_nu' => 'Nguyễn T.Thanh Hương',
    'nong_dan' => 'Phạm Văn Trường',
    'ccb' => 'Phùng Đăng Cự',
    'doan_thanh_nien' => 'Ngô Thanh Trường',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  5 => 
  array (
    'tdp_name' => 'Hoàng Đồng',
    'bi_thu_name' => 'Vũ Tuấn Khương',
    'bi_thu_phone' => '0946.765.798',
    'to_truong_name' => 'Lê Quốc Tuấn',
    'to_truong_phone' => '0912.165.039',
    'mat_tan_name' => 'Nguyễn Thị Lợi',
    'mat_tan_phone' => '0387.588.325',
    'nguoi_cao_tuoi' => 'Vũ Xuân Bình',
    'phu_nu' => 'Vũ Thị Dung',
    'nong_dan' => 'Chu Duy Khoa',
    'ccb' => 'Lương Văn Trụ',
    'doan_thanh_nien' => 'Nguyễn Tuấn Anh',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  6 => 
  array (
    'tdp_name' => 'Hương Cát',
    'bi_thu_name' => 'Phùng Quốc Trình',
    'bi_thu_phone' => '0912.448.375',
    'to_truong_name' => 'Phùng Tiến Độ',
    'to_truong_phone' => '0914.902.148',
    'mat_tan_name' => 'Phùng Quốc Trình',
    'mat_tan_phone' => '0912.448.375',
    'nguoi_cao_tuoi' => 'Ngô Duy Lượng',
    'phu_nu' => 'Nguyễn Thị Xinh',
    'nong_dan' => 'Vũ Văn Đệ',
    'ccb' => 'Đỗ Duy Quát',
    'doan_thanh_nien' => 'Phùng Ngọc Nam',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  7 => 
  array (
    'tdp_name' => 'Duy Hải',
    'bi_thu_name' => 'Đào Nhật Tân',
    'bi_thu_phone' => '0976.136.938',
    'to_truong_name' => 'Phan Duy Tự',
    'to_truong_phone' => '0383.375.836',
    'mat_tan_name' => 'Trần Thị Lương',
    'mat_tan_phone' => '0332.822.168',
    'nguoi_cao_tuoi' => 'Nguyễn Duy Khiêm',
    'phu_nu' => 'Phạm Thị Dậu',
    'nong_dan' => 'Trần Duy Khiêm',
    'ccb' => 'Nguyễn Văn Luận',
    'doan_thanh_nien' => 'Đào Tuấn Anh',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  8 => 
  array (
    'tdp_name' => 'Ngọc Động',
    'bi_thu_name' => 'Nguyễn Tiến Chỉnh',
    'bi_thu_phone' => '0912.457.813',
    'to_truong_name' => 'Bùi Hữu Lịch',
    'to_truong_phone' => '0912.234.057',
    'mat_tan_name' => 'Đinh Văn Hải',
    'mat_tan_phone' => '0915.664.321',
    'nguoi_cao_tuoi' => 'Đinh Văn Thành',
    'phu_nu' => 'Trương Thị Chi',
    'nong_dan' => 'Phạm Văn Hưng',
    'ccb' => 'Nguyễn Văn Lực',
    'doan_thanh_nien' => 'Bùi Tuấn Vũ',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
  9 => 
  array (
    'tdp_name' => 'Đông Hải',
    'bi_thu_name' => 'Hoàng Văn Hùng',
    'bi_thu_phone' => '0983.456.789',
    'to_truong_name' => 'Phạm Văn Đức',
    'to_truong_phone' => '0978.123.456',
    'mat_tan_name' => 'Hoàng Văn Hùng',
    'mat_tan_phone' => '0983.456.789',
    'nguoi_cao_tuoi' => 'Đỗ Văn Thắng',
    'phu_nu' => 'Lê Thị Mai',
    'nong_dan' => 'Trần Văn Bình',
    'ccb' => 'Nguyễn Văn Hưng',
    'doan_thanh_nien' => 'Phạm Tuấn Đạt',
    'nguoi_cao_tuoi_phone' => NULL,
    'phu_nu_phone' => NULL,
    'nong_dan_phone' => NULL,
    'ccb_phone' => NULL,
    'doan_thanh_nien_phone' => NULL,
  ),
);
            foreach ($tdpOfficials as $t) {
                \App\Models\TdpOfficial::create($t);
            }
        }

        // 8. Settings
        if (Schema::hasTable('settings')) {
            $settings = array (
  0 => 
  array (
    'key' => 'stat_1',
    'name' => 'Thẻ 1: Tổng số Tổ dân phố',
    'value' => '10',
    'label' => 'Tổng số tổ dân phố',
    'group' => 'stats',
    'sort_order' => 1,
    'is_visible' => 1,
  ),
  1 => 
  array (
    'key' => 'stat_2',
    'name' => 'Thẻ 2: Tổng số Hộ gia đình',
    'value' => '6.767',
    'label' => 'Tổng số hộ gia đình',
    'group' => 'stats',
    'sort_order' => 2,
    'is_visible' => 1,
  ),
  2 => 
  array (
    'key' => 'stat_3',
    'name' => 'Thẻ 3: Tổng số Nhân khẩu',
    'value' => '23.615',
    'label' => 'Tổng số nhân khẩu',
    'group' => 'stats',
    'sort_order' => 3,
    'is_visible' => 1,
  ),
  3 => 
  array (
    'key' => 'stat_4',
    'name' => 'Thẻ 4: Diện tích địa bàn',
    'value' => '15,46 km²',
    'label' => 'Diện tích (1.546,30 ha)',
    'group' => 'stats',
    'sort_order' => 4,
    'is_visible' => 1,
  ),
  4 => 
  array (
    'key' => 'sec_banner',
    'name' => 'Banner tiêu đề Cổng tra cứu',
    'value' => 'homepage-section-banner',
    'label' => 'Khối Banner tiêu đề chính',
    'group' => 'homepage_sections',
    'sort_order' => 1,
    'is_visible' => 1,
  ),
  5 => 
  array (
    'key' => 'sec_stats',
    'name' => '4 Thẻ số liệu thống kê',
    'value' => 'homepage-section-stats',
    'label' => 'Khối 4 thẻ thống kê nhanh',
    'group' => 'homepage_sections',
    'sort_order' => 2,
    'is_visible' => 1,
  ),
  6 => 
  array (
    'key' => 'sec_agencies',
    'name' => 'Cơ quan hành chính',
    'value' => 'homepage-section-agencies',
    'label' => 'Khối Danh sách Cơ quan hành chính',
    'group' => 'homepage_sections',
    'sort_order' => 3,
    'is_visible' => 1,
  ),
  7 => 
  array (
    'key' => 'sec_merger',
    'name' => 'Thông tin sáp nhập Tổ dân phố',
    'value' => 'homepage-section-tdp-merger',
    'label' => 'Khối Bảng so sánh phương án sáp nhập TDP',
    'group' => 'homepage_sections',
    'sort_order' => 4,
    'is_visible' => 1,
  ),
  8 => 
  array (
    'key' => 'sec_officials',
    'name' => 'Cán bộ & CSKV phụ trách địa bàn',
    'value' => 'homepage-section-officials',
    'label' => 'Khối Danh sách Cán bộ & CSKV phụ trách',
    'group' => 'homepage_sections',
    'sort_order' => 5,
    'is_visible' => 1,
  ),
  9 => 
  array (
    'key' => 'sec_meritorious',
    'name' => 'Sự kiện kỷ niệm & Gia đình có công',
    'value' => 'homepage-section-meritorious',
    'label' => 'Khối Sự kiện kỷ niệm & Gia đình có công',
    'group' => 'homepage_sections',
    'sort_order' => 6,
    'is_visible' => 1,
  ),
  10 => 
  array (
    'key' => 'feedback_google_form_url',
    'name' => 'Link Google Form Phản ánh kiến nghị',
    'value' => 'https://docs.google.com/forms/d/e/1FAIpQLSd20haR98MOF72uV0p1Nr-p3EBgJkbEhcU4eQrF651Cw9PZOA/viewform?usp=sharing&ouid=101953080783634965403',
    'label' => 'Đường link Google Form',
    'group' => 'feedback',
    'sort_order' => 1,
    'is_visible' => 1,
  ),
  11 => 
  array (
    'key' => 'feedback_google_sheet_url',
    'name' => 'Link Google Sheets xem kết quả',
    'value' => 'https://docs.google.com/spreadsheets/d/1bFlwM0LgjSO6ar6krKrXEBTei47pCGZXPdHdq7QXYFA/edit?usp=sharing',
    'label' => 'Đường link Google Sheets',
    'group' => 'feedback',
    'sort_order' => 2,
    'is_visible' => 1,
  ),
  12 => 
  array (
    'key' => 'feedback_is_enabled',
    'name' => 'Trạng thái nhận phản ánh',
    'value' => '1',
    'label' => 'Bật nhận phản ánh',
    'group' => 'feedback',
    'sort_order' => 3,
    'is_visible' => 1,
  ),
  13 => 
  array (
    'key' => 'feedback_google_sheet_webhook_url',
    'name' => 'Webhook tự động đồng bộ Google Sheets',
    'value' => '',
    'label' => 'Webhook Google Sheets',
    'group' => 'feedback',
    'sort_order' => 1,
    'is_visible' => 1,
  ),
  14 => 
  array (
    'key' => 'citizen_reception_title',
    'name' => 'Tiêu đề Lịch tiếp công dân',
    'value' => 'LỊCH TIẾP CÔNG DÂN ĐỊNH KỲ NĂM 2026',
    'label' => 'Tiêu đề',
    'group' => 'citizen_reception',
    'sort_order' => 1,
    'is_visible' => 1,
  ),
  15 => 
  array (
    'key' => 'citizen_reception_image',
    'name' => 'File ảnh Lịch tiếp công dân',
    'value' => 'citizen-reception/9sqeGdJe0XnZharSDI0g0EydQlVdVDWdcoFzXCeH.jpg',
    'label' => 'Ảnh lịch tiếp dân',
    'group' => 'citizen_reception',
    'sort_order' => 2,
    'is_visible' => 1,
  ),
  16 => 
  array (
    'key' => 'citizen_reception_schedule_time',
    'name' => 'Thời gian tiếp công dân',
    'value' => 'Thứ 5 hàng tuần (Sáng: 07h30 - 11h30 | Chiều: 13h30 - 17h00)',
    'label' => 'Thời gian tiếp dân',
    'group' => 'citizen_reception',
    'sort_order' => 3,
    'is_visible' => 1,
  ),
  17 => 
  array (
    'key' => 'citizen_reception_location',
    'name' => 'Địa điểm tiếp công dân',
    'value' => 'Phòng Tiếp công dân — Trụ sở UBND Phường Duy Hà (Số 01 đường Lê Lợi, TP. Ninh Bình)',
    'label' => 'Địa điểm',
    'group' => 'citizen_reception',
    'sort_order' => 4,
    'is_visible' => 1,
  ),
  18 => 
  array (
    'key' => 'citizen_reception_officer',
    'name' => 'Chủ trì tiếp công dân',
    'value' => 'Đồng chí Chủ tịch UBND Phường và các Phó Chủ tịch UBND Phường',
    'label' => 'Người chủ trì',
    'group' => 'citizen_reception',
    'sort_order' => 5,
    'is_visible' => 1,
  ),
  19 => 
  array (
    'key' => 'citizen_reception_notes',
    'name' => 'Nội dung dặn dò / Quy định',
    'value' => 'Công dân khi đến khiếu nại, tố cáo, kiến nghị, phản ánh cần xuất trình Căn cước công dân và các giấy tờ, tài liệu liên quan đến nội dung phản ánh.',
    'label' => 'Ghi chú',
    'group' => 'citizen_reception',
    'sort_order' => 6,
    'is_visible' => 1,
  ),
);
            foreach ($settings as $s) {
                \App\Models\Setting::updateOrCreate(['name' => $s['name']], $s);
            }
        }

        // 9. Celebration Events
        if (Schema::hasTable('celebration_events')) {
            \App\Models\CelebrationEvent::truncate();
            $celebrationEvents = array (
  0 => 
  array (
    'name' => 'Kỷ niệm Ngày Giải phóng miền Nam, Thống nhất đất nước (30/04) & Quốc tế Lao động (01/05)',
    'month' => 4,
    'day' => 30,
    'description' => 'Tuyên dương tinh thần đoàn kết toàn dân và tri ân thế hệ cha anh đã có công lao to lớn đưa đất nước đi đến ngày thống nhất, hòa bình, đồng thời tôn vinh giai cấp công nhân lao động.',
    'is_featured' => false,
    'status' => 'active',
  ),
  1 => 
  array (
    'name' => 'Kỷ niệm ngày Thương binh - Liệt sĩ (27/07)',
    'month' => 7,
    'day' => 27,
    'description' => 'Hoạt động tri ân sâu sắc, đời đời nhớ ơn các anh hùng liệt sĩ, các thương binh, bệnh binh và những gia đình chính sách đã hy sinh xương máu cho nền độc lập tự do của Tổ quốc.',
    'is_featured' => true,
    'status' => 'active',
  ),
  2 => 
  array (
    'name' => 'Kỷ niệm Ngày Quốc khánh nước Cộng hòa Xã hội Chủ nghĩa Việt Nam (02/09)',
    'month' => 9,
    'day' => 2,
    'description' => 'Tri ân các gia đình có công với cách mạng, lão thành cách mạng và nhân dân đã có nhiều cống hiến cho sự nghiệp khai sinh ra nước Việt Nam Dân chủ Cộng hòa.',
    'is_featured' => false,
    'status' => 'active',
  ),
  3 => 
  array (
    'name' => 'Kỷ niệm Ngày thành lập Quân đội Nhân dân Việt Nam (22/12)',
    'month' => 12,
    'day' => 22,
    'description' => 'Tôn vinh các gia đình cựu chiến binh, quân nhân xuất ngũ có hoàn cảnh khó khăn và những cá nhân đã có nhiều cống hiến vẻ vang trong lực lượng vũ trang nhân dân Việt Nam.',
    'is_featured' => false,
    'status' => 'active',
  ),
);
            foreach ($celebrationEvents as $ce) {
                \App\Models\CelebrationEvent::create($ce);
            }
        }

        // 10. Procedures
        if (Schema::hasTable('procedures')) {
            \App\Models\Procedure::truncate();
            $procedures = array (
  0 => 
  array (
    'code' => 'Mẫu TK-CT01',
    'title' => 'Tờ khai đăng ký tạm trú trực tuyến',
    'category' => 'residence',
    'category_text' => 'Cư trú & Hộ khẩu',
    'desc' => '- Bước 1: Cá nhân, tổ chức chuẩn bị hồ sơ theo quy định của pháp luật.
- Bước 2: Cá nhân, tổ chức nộp hồ sơ tại Công an cấp xã.
- Bước 3: Khi tiếp nhận hồ sơ đăng ký tạm trú, cơ quan đăng ký cư trú kiểm tra tính pháp lý và nội dung hồ sơ:
+ Trường hợp hồ sơ đã đầy đủ, hợp lệ thì tiếp nhận hồ sơ và cấp Phiếu tiếp nhận hồ sơ và hẹn trả kết quả (mẫu CT04 ban hành kèm theo Thông tư số 56/2021/TT-BCA) cho người đăng ký;
+ Trường hợp hồ sơ đủ điều kiện nhưng chưa đủ hồ sơ thì hướng dẫn bổ sung, hoàn thiện và cấp Phiếu hướng dẫn bổ sung, hoàn thiện hồ sơ (mẫu CT05 ban hành kèm theo Thông tư số 56/2021/TT-BCA) cho người đăng ký;
+ Trường hợp hồ sơ không đủ điều kiện thì từ chối và cấp Phiếu từ chối tiếp nhận, giải quyết hồ sơ (mẫu CT06 ban hành kèm theo Thông tư số 56/2021/TT-BCA) cho người đăng ký.
- Bước 4: Cá nhân, tổ chức nộp lệ phí đăng ký cư trú theo quy định.
- Bước 5: Căn cứ theo ngày hẹn trên Phiếu tiếp nhận hồ sơ và hẹn trả kết quả để nhận thông báo kết quả giải quyết thủ tục đăng ký cư trú (nếu có).',
    'fee' => 'Miễn phí',
    'agency' => 'Công an Phường',
    'docs' => 
    array (
      0 => 
      array (
        'name' => '- Tờ khai thay đổi thông tin cư trú (Mẫu CT01 ban hành kèm theo Thông tư số 56/2021/TT-BCA); đối với người đăng ký tạm trú là người chưa thành niên thì trong tờ khai phải ghi rõ ý kiến đồng ý của cha, mẹ hoặc người giám hộ, trừ trường hợp đã có ý kiến đồng ý bằng văn bản;
- Giấy tờ, tài liệu chứng minh chỗ ở hợp pháp.',
        'quantity' => '01 bản chính, 01 bản sao',
        'file' => 'procedure-documents/vanhoc-17676197898811201182047.jpg',
      ),
    ),
    'attachment' => NULL,
    'download_url' => 'https://dichvucong.gov.vn',
    'sort_order' => 1,
    'is_active' => true,
  ),
  1 => 
  array (
    'code' => 'Mẫu TK-CT05',
    'title' => 'Tờ khai thông báo lưu trú qua đêm',
    'category' => 'residence',
    'category_text' => 'Cư trú & Hộ khẩu',
    'desc' => 'Tiếp nhận thông tin khách -> Ghi nhận Sổ tiếp nhận -> Nộp thông báo qua Cổng DVC / VNeID / Phần mềm ASM -> Hệ thống tự động chuyển Công an Phường.',
    'fee' => 'Miễn phí',
    'agency' => 'Công an Phường',
    'docs' => 
    array (
      0 => 
      array (
        'name' => '1. Thông tin định danh/CCCD của người lưu trú
2. Địa chỉ cơ sở/hộ gia đình tiếp nhận lưu trú',
        'quantity' => '01 bản chính',
        'file' => NULL,
      ),
    ),
    'attachment' => NULL,
    'download_url' => 'https://dichvucong.gov.vn',
    'sort_order' => 2,
    'is_active' => true,
  ),
);
            foreach ($procedures as $p) {
                \App\Models\Procedure::create($p);
            }
        }

        // 11. Procedure Videos
        if (Schema::hasTable('procedure_videos')) {
            \App\Models\ProcedureVideo::truncate();
            $procedureVideos = array (
  0 => 
  array (
    'title' => 'Video hướng dẫn Nộp hồ sơ Đăng ký tạm trú trực tuyến trên Cổng DVC Bộ Công an',
    'category' => 'residence',
    'video_url' => 'https://www.youtube.com/watch?v=e1-HHDIVCp8',
    'sort_order' => 1,
    'is_active' => true,
  ),
  1 => 
  array (
    'title' => 'Video hướng dẫn Kích hoạt tài khoản Định danh điện tử VNeID Mức 2',
    'category' => 'vneid',
    'video_url' => 'https://www.youtube.com/watch?v=V5uJGqQdK54',
    'sort_order' => 2,
    'is_active' => true,
  ),
);
            foreach ($procedureVideos as $vid) {
                \App\Models\ProcedureVideo::create($vid);
            }
        }

        // 12. Procedure Shared Categories
        if (Schema::hasTable('procedure_categories')) {
            \App\Models\ProcedureCategory::truncate();
            $categories = array (
  0 => 
  array (
    'slug' => 'residence',
    'name' => 'Cư trú & Định danh điện tử',
    'color' => 'info',
    'sort_order' => 1,
    'is_active' => true,
  ),
  1 => 
  array (
    'slug' => 'vneid',
    'name' => 'Định danh VNeID',
    'color' => 'success',
    'sort_order' => 2,
    'is_active' => true,
  ),
  2 => 
  array (
    'slug' => 'civil',
    'name' => 'Hộ tịch & Chứng thực',
    'color' => 'warning',
    'sort_order' => 3,
    'is_active' => true,
  ),
  3 => 
  array (
    'slug' => 'land',
    'name' => 'Đất đai & Xây dựng',
    'color' => 'danger',
    'sort_order' => 4,
    'is_active' => true,
  ),
  4 => 
  array (
    'slug' => 'social',
    'name' => 'An sinh xã hội & Người có công',
    'color' => 'primary',
    'sort_order' => 5,
    'is_active' => true,
  ),
  5 => 
  array (
    'slug' => 'other',
    'name' => 'Lĩnh vực khác',
    'color' => 'gray',
    'sort_order' => 6,
    'is_active' => true,
  ),
);
            foreach ($categories as $cat) {
                \App\Models\ProcedureCategory::create($cat);
            }
        }

        // 13. Policies & Regulations
        if (Schema::hasTable('policies')) {
            \App\Models\Policy::truncate();
            $policies = array (
  0 => 
  array (
    'title' => 'Nghị định quy định chi tiết một số điều của Luật Cư trú năm 2020',
    'code' => 'Nghị định 144/2021/NĐ-CP',
    'category' => 'residence',
    'agency' => 'Chính phủ',
    'issue_date' => '31/12/2021',
    'status' => 'Đang có hiệu lực',
    'summary' => 'Quy định chi tiết về hồ sơ, thủ tục đăng ký thường trú, đăng ký tạm trú, thông báo lưu trú và khai báo tạm vắng.',
    'highlights' => NULL,
    'download_url' => 'policy-documents/demo-policy.pdf',
    'is_active' => true,
    'sort_order' => 1,
  ),
  1 => 
  array (
    'title' => 'Quyết định phê duyệt Đề án phát triển ứng dụng dữ liệu về dân cư, định danh và xác thực điện tử (Đề án 06)',
    'code' => 'Quyết định 06/QĐ-TTg',
    'category' => 'residence',
    'agency' => 'Thủ tướng Chính phủ',
    'issue_date' => '06/01/2022',
    'status' => 'Đang có hiệu lực',
    'summary' => 'Ứng dụng dữ liệu dân cư, định danh và xác thực điện tử phục vụ chuyển đổi số quốc gia giai đoạn 2022 - 2025.',
    'highlights' => NULL,
    'download_url' => 'policy-documents/demo-policy.pdf',
    'is_active' => true,
    'sort_order' => 2,
  ),
  2 => 
  array (
    'title' => 'Nghị quyết quy định chính sách hỗ trợ tiền ăn, trợ cấp cho trợ cấp xã hội và người có công Phường Duy Hà',
    'code' => 'Nghị quyết 12/2023/NQ-HĐND',
    'category' => 'social',
    'agency' => 'HĐND Tỉnh Ninh Bình',
    'issue_date' => '15/07/2023',
    'status' => 'Đang có hiệu lực',
    'summary' => 'Quy định mức hỗ trợ quà lễ tết, chi phí khám chữa bệnh định kỳ và trợ cấp khó khăn đột xuất cho gia đình thương binh, liệt sĩ.',
    'highlights' => NULL,
    'download_url' => 'policy-documents/demo-policy.pdf',
    'is_active' => true,
    'sort_order' => 3,
  ),
  3 => 
  array (
    'title' => 'Luật Đất đai năm 2024 về hạn mức giao đất, cấp Giấy chứng nhận và nghĩa vụ tài chính đất đai',
    'code' => 'Luật Đất đai 31/2024/QH15',
    'category' => 'land',
    'agency' => 'Quốc hội',
    'issue_date' => '18/01/2024',
    'status' => 'Đang có hiệu lực',
    'summary' => 'Quy định về chế độ sử dụng đất, quyền và nghĩa vụ của người sử dụng đất, bồi thường giải phóng mặt bằng và cấp Sổ đỏ.',
    'highlights' => NULL,
    'download_url' => 'policy-documents/demo-policy.pdf',
    'is_active' => true,
    'sort_order' => 4,
  ),
);
            foreach ($policies as $pol) {
                \App\Models\Policy::create($pol);
            }
        }

        // 14. Waste Schedules
        if (Schema::hasTable('waste_schedules')) {
            \App\Models\WasteSchedule::truncate();
            $wasteSchedules = array (
  0 => 
  array (
    'tdp_name' => 'TDP Ngọc Tú',
    'morning_shift' => '06h00 - 07h30',
    'evening_shift' => '17h30 - 19h00',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 2,
    'collection_days' => 
    array (
      0 => 'thu_3',
      1 => 'thu_6',
    ),
  ),
  1 => 
  array (
    'tdp_name' => 'TDP Chuông',
    'morning_shift' => '06h00 - 07h30',
    'evening_shift' => '17h00 - 18h30',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 4,
    'collection_days' => 
    array (
      0 => 'thu_4',
      1 => 'thu_7',
    ),
  ),
  2 => 
  array (
    'tdp_name' => 'TDP Bạch Xá',
    'morning_shift' => '05h30 - 07h00',
    'evening_shift' => '17h30 - 19h00',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 5,
    'collection_days' => 
    array (
      0 => 'thu_3',
      1 => 'thu_6',
    ),
  ),
  3 => 
  array (
    'tdp_name' => 'TDP Hoàng Đông',
    'morning_shift' => '06h00 - 07h30',
    'evening_shift' => '18h00 - 19h30',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 6,
    'collection_days' => 
    array (
      0 => 'thu_2',
      1 => 'thu_6',
    ),
  ),
  4 => 
  array (
    'tdp_name' => 'TDP Hương Cát',
    'morning_shift' => '05h00 - 06h30',
    'evening_shift' => '16h30 - 18h00',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 7,
    'collection_days' => 
    array (
      0 => 'thu_3',
      1 => 'thu_7',
    ),
  ),
  5 => 
  array (
    'tdp_name' => 'TDP Duy Hải',
    'morning_shift' => '06h00 - 07h30',
    'evening_shift' => '17h30 - 19h00',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 8,
    'collection_days' => 
    array (
      0 => 'thu_4',
      1 => 'chu_nhat',
    ),
  ),
  6 => 
  array (
    'tdp_name' => 'TDP Ngọc Động',
    'morning_shift' => '05h30 - 07h00',
    'evening_shift' => '17h00 - 18h30',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 9,
    'collection_days' => 
    array (
      0 => 'thu_2',
      1 => 'thu_5',
    ),
  ),
  7 => 
  array (
    'tdp_name' => 'TDP Đông Hải',
    'morning_shift' => '05h00 - 06h30',
    'evening_shift' => '16h30 - 18h00',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 10,
    'collection_days' => 
    array (
      0 => 'thu_3',
      1 => 'thu_6',
    ),
  ),
  8 => 
  array (
    'tdp_name' => 'TDP Duy Minh',
    'morning_shift' => '05h30 - 07h00',
    'evening_shift' => '17h00 - 18h30',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 0,
    'collection_days' => 
    array (
      0 => 'thu_2',
      1 => 'thu_5',
    ),
  ),
  9 => 
  array (
    'tdp_name' => 'TDP Động Linh Trang',
    'morning_shift' => '05h30 - 07h00',
    'evening_shift' => '17h00 - 18h30',
    'saturday_recycle' => '08h00 - 11h00',
    'main_routes' => NULL,
    'collection_point' => NULL,
    'responsible_unit' => 'Đội vệ sinh môi trường Phường Duy Hà',
    'contact_phone' => NULL,
    'is_active' => true,
    'sort_order' => 0,
    'collection_days' => 
    array (
      0 => 'thu_2',
      1 => 'thu_5',
    ),
  ),
);
            foreach ($wasteSchedules as $ws) {
                \App\Models\WasteSchedule::create($ws);
            }
        }

        // 15. Form Documents
        if (Schema::hasTable('form_documents')) {
            \App\Models\FormDocument::truncate();
            $formDocs = array (
  0 => 
  array (
    'code' => 'Mẫu TK-KS',
    'title' => 'Tờ khai đăng ký khai sinh',
    'description' => 'Dùng cho cha, mẹ hoặc người thân thích đi đăng ký khai sinh lần đầu cho trẻ em mới sinh.',
    'category' => 'ho_tich',
    'category_text' => 'Hộ tịch & Tư pháp',
    'agency' => 'Bộ phận Tư pháp - Hộ tịch',
    'fee' => 'Miễn phí',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Mục (1) Kính gửi: Ghi rõ \'Ủy ban nhân dân phường Duy Hà\'.',
      1 => 'Mục (2) Người yêu cầu: Ghi đầy đủ họ tên, ngày sinh, số CCCD, nơi cư trú của người đi khai sinh.',
      2 => 'Mục (3) Thông tin người được khai sinh: Ghi họ, chữ đệm, tên trẻ bằng chữ in hoa; ngày tháng năm sinh; nơi sinh; quê quán; dân tộc; quốc tịch.',
      3 => 'Mục (4) Thông tin Cha & Mẹ: Ghi đầy đủ họ tên, năm sinh, dân tộc, quốc tịch, nơi cư trú của cả cha và mẹ theo CCCD.',
    ),
    'docs' => 
    array (
      0 => 'Giấy chứng sinh (bản chính do cơ sở y tế cấp).',
      1 => 'Bản sao hoặc xuất trình CCCD của cha và mẹ.',
      2 => 'Giấy chứng nhận kết hôn của cha mẹ (nếu có đăng ký kết hôn).',
    ),
    'notes' => 'Trong thời hạn 60 ngày kể từ ngày sinh con, cha hoặc mẹ có trách nhiệm đăng ký khai sinh cho con.',
    'is_active' => true,
    'sort_order' => 1,
  ),
  1 => 
  array (
    'code' => 'Mẫu TK-KH',
    'title' => 'Tờ khai đăng ký kết hôn',
    'description' => 'Dùng cho hai bên nam nữ có nguyện vọng xác lập quan hệ hôn nhân hợp pháp theo Luật Hôn nhân và Gia đình.',
    'category' => 'ho_tich',
    'category_text' => 'Hộ tịch & Tư pháp',
    'agency' => 'Bộ phận Tư pháp - Hộ tịch',
    'fee' => 'Miễn phí',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Cột \'Bên Nam\' & \'Bên Nữ\': Khai chi tiết Họ tên, ngày tháng năm sinh, dân tộc, quốc tịch, nghề nghiệp, nơi cư trú.',
      1 => 'Số thẻ CCCD/Hộ chiếu: Khai chính xác số, ngày cấp, nơi cấp.',
      2 => 'Tình trạng hôn nhân: Ghi rõ \'Chưa đăng ký kết hôn lần nào\' hoặc \'Đã ly hôn theo Bản án/Quyết định số...\'.',
      3 => 'Hai bên nam nữ cùng ký và ghi rõ họ tên ở cuối tờ khai.',
    ),
    'docs' => 
    array (
      0 => 'CCCD gắn chip của cả hai bên nam và nữ.',
      1 => 'Giấy xác nhận tình trạng hôn nhân của bên không thường trú tại Phường Duy Hà.',
      2 => 'Bản sao Bản án/Quyết định ly hôn có hiệu lực pháp luật (nếu trước đó đã từng kết hôn và ly hôn).',
    ),
    'notes' => 'Cả hai bên nam và nữ phải có mặt trực tiếp tại trụ sở UBND Phường khi làm thủ tục và ký vào Sổ đăng ký kết hôn.',
    'is_active' => true,
    'sort_order' => 2,
  ),
  2 => 
  array (
    'code' => 'Mẫu TK-XNHN',
    'title' => 'Tờ khai cấp Giấy xác nhận tình trạng hôn nhân',
    'description' => 'Dùng để xác nhận tình trạng độc thân phục vụ mục đích kết hôn, vay vốn ngân hàng, mua bán chuyển nhượng bất động sản.',
    'category' => 'ho_tich',
    'category_text' => 'Hộ tịch & Tư pháp',
    'agency' => 'Bộ phận Tư pháp - Hộ tịch',
    'fee' => 'Miễn phí',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Ghi đầy đủ thông tin nhân thân của người yêu cầu cấp giấy.',
      1 => 'Mục \'Trong thời gian cư trú tại...\': Ghi rõ các khoảng thời gian cư trú từ đủ 18 tuổi đến nay.',
      2 => 'Mục \'Mục đích sử dụng\': Ghi rõ mục đích như \'Để đăng ký kết hôn với anh/chị...\' hoặc \'Để làm thủ tục chuyển nhượng quyền sử dụng đất\'.',
    ),
    'docs' => 
    array (
      0 => 'Bản chính CCCD của người yêu cầu.',
      1 => 'Trường hợp đã ly hôn thì nộp bản sao Trích lục ghi chú ly hôn hoặc Bản án ly hôn.',
    ),
    'notes' => 'Giấy xác nhận tình trạng hôn nhân có giá trị 6 tháng kể từ ngày cấp.',
    'is_active' => true,
    'sort_order' => 3,
  ),
  3 => 
  array (
    'code' => 'Mẫu TK-KT',
    'title' => 'Tờ khai đăng ký khai tử',
    'description' => 'Dùng cho thân nhân người đã mất đăng ký khai tử theo quy định của pháp luật.',
    'category' => 'ho_tich',
    'category_text' => 'Hộ tịch & Tư pháp',
    'agency' => 'Bộ phận Tư pháp - Hộ tịch',
    'fee' => 'Miễn phí',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Khai thông tin người đi khai tử và mối quan hệ với người đã mất.',
      1 => 'Khai thông tin người đã mất: Họ tên, năm sinh, nơi cư trú cuối cùng, thời gian chết, địa điểm chết, nguyên nhân chết.',
    ),
    'docs' => 
    array (
      0 => 'Giấy báo tử (do Trạm y tế hoặc Bệnh viện cấp).',
      1 => 'CCCD của người đã mất và người đi khai tử.',
    ),
    'notes' => 'Thời hạn đăng ký khai tử là trong vòng 15 ngày kể từ ngày có người chết.',
    'is_active' => true,
    'sort_order' => 4,
  ),
  4 => 
  array (
    'code' => 'Mẫu 04a/ĐK',
    'title' => 'Đơn đăng ký, cấp Giấy chứng nhận quyền sử dụng đất (Sổ đỏ)',
    'description' => 'Dùng cho hộ gia đình, cá nhân xin cấp Sổ đỏ lần đầu đối với thửa đất đang sử dụng.',
    'category' => 'dat_dai',
    'category_text' => 'Địa chính & Đất đai',
    'agency' => 'Bộ phận Địa chính - Xây dựng',
    'fee' => 'Theo quy định HĐND tỉnh',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Phần 1: Kê khai thông tin người sử dụng đất (vợ, chồng hoặc đồng sở hữu).',
      1 => 'Phần 2: Kê khai chi tiết thửa đất: Thửa đất số, Tờ bản đồ số, Địa chỉ, Diện tích (m²), Mục đích sử dụng, Thời hạn sử dụng, Nguồn gốc sử dụng đất.',
      2 => 'Phần 3: Kê khai tài sản gắn liền với đất (nhà ở, công trình xây dựng nếu có).',
    ),
    'docs' => 
    array (
      0 => 'Giấy tờ về quyền sử dụng đất theo Điều 137 Luật Đất đai 2024.',
      1 => 'Trích lục bản đồ địa chính hoặc trích đo địa chính thửa đất.',
      2 => 'Chứng từ thực hiện nghĩa vụ tài chính (nếu có).',
    ),
    'notes' => 'Cần lấy xác nhận nguồn gốc sử dụng đất không có tranh chấp từ Tổ trưởng TDP trước khi nộp.',
    'is_active' => true,
    'sort_order' => 5,
  ),
  5 => 
  array (
    'code' => 'Mẫu 09/ĐK',
    'title' => 'Đơn đăng ký biến động đất đai, tài sản gắn liền với đất',
    'description' => 'Dùng khi chuyển nhượng, tặng cho, thừa kế, chia tách thửa đất, đổi tên chủ sử dụng đất trên sổ đỏ.',
    'category' => 'dat_dai',
    'category_text' => 'Địa chính & Đất đai',
    'agency' => 'Bộ phận Địa chính - Xây dựng',
    'fee' => 'Theo quy định HĐND tỉnh',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Khai thông tin chủ sử dụng đất cũ và mới.',
      1 => 'Nội dung biến động: Ghi rõ \'Chuyển nhượng quyền sử dụng đất theo Hợp đồng số...\' hoặc \'Tặng cho con đẻ...\'.',
      2 => 'Kê khai cam kết các nghĩa vụ thuế, lệ phí trước bạ.',
    ),
    'docs' => 
    array (
      0 => 'Bản chính Giấy chứng nhận quyền sử dụng đất (Sổ đỏ).',
      1 => 'Hợp đồng chuyển nhượng/tặng cho đã được công chứng/chứng thực.',
      2 => 'Tờ khai thuế thu nhập cá nhân và lệ phí trước bạ.',
    ),
    'notes' => 'Nộp tại Bộ phận Một cửa trong vòng 30 ngày kể từ ngày công chứng hợp đồng.',
    'is_active' => true,
    'sort_order' => 6,
  ),
  6 => 
  array (
    'code' => 'Mẫu ĐK-XD',
    'title' => 'Đơn đề nghị cấp phép sửa chữa, cải tạo nhà ở riêng lẻ',
    'description' => 'Dùng khi có nhu cầu sửa chữa, nâng tầng, cải tạo công trình nhà ở làm thay đổi kết cấu chịu lực.',
    'category' => 'dat_dai',
    'category_text' => 'Địa chính & Đất đai',
    'agency' => 'Bộ phận Địa chính - Xây dựng',
    'fee' => '50.000đ - 100.000đ',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Khai thông tin chủ đầu tư xây dựng.',
      1 => 'Địa điểm sửa chữa, hiện trạng công trình trước khi sửa.',
      2 => 'Quy mô cải tạo: Diện tích xây dựng tầng 1, tổng diện tích sàn, số tầng nâng thêm, chiều cao công trình.',
    ),
    'docs' => 
    array (
      0 => 'Bản sao Sổ đỏ thửa đất.',
      1 => 'Bản vẽ hiện trạng và bản vẽ phương án sửa chữa cải tạo.',
      2 => 'Ảnh chụp hiện trạng mặt đứng công trình tiếp giáp đường phố/ngõ xóm.',
    ),
    'notes' => 'Đảm bảo khoảng lùi xây dựng và an toàn kết cấu cho các hộ liền kề.',
    'is_active' => true,
    'sort_order' => 7,
  ),
  7 => 
  array (
    'code' => 'Mẫu ĐK-NCC',
    'title' => 'Tờ khai thông tin người có công với cách mạng',
    'description' => 'Dùng để rà soát, lập danh sách hưởng chế độ ưu đãi người có công, thân nhân liệt sĩ, thương bệnh binh.',
    'category' => 'chinh_sach',
    'category_text' => 'Lao động - TB & Xã hội',
    'agency' => 'Bộ phận Lao động - Thương binh & Xã hội',
    'fee' => 'Miễn phí',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Khai đầy đủ thông tin cá nhân của người có công.',
      1 => 'Diện đối tượng: Liệt sĩ, Thương binh (tỷ lệ thương tật %), Bệnh binh, Người nhiễm CĐHH, Bà mẹ VNAH...',
      2 => 'Số hồ sơ/Quyết định công nhận người có công.',
      3 => 'Thông tin tài khoản ngân hàng nhận tiền trợ cấp hàng tháng (nếu có).',
    ),
    'docs' => 
    array (
      0 => 'Bản sao Quyết định trợ cấp/Bằng Tổ quốc ghi công/Huân huy chương.',
      1 => 'Bản sao CCCD của người có công hoặc thân nhân được ủy quyền.',
    ),
    'notes' => 'Kê khai chính xác thông tin để phục vụ chi trả an sinh xã hội không dùng tiền mặt.',
    'is_active' => true,
    'sort_order' => 8,
  ),
  8 => 
  array (
    'code' => 'Mẫu ĐK-BTXH',
    'title' => 'Tờ khai đề nghị hưởng trợ cấp xã hội hàng tháng',
    'description' => 'Dùng cho người cao tuổi từ đủ 80 tuổi, người khuyết tật nặng/đặc biệt nặng, trẻ em mồ côi.',
    'category' => 'chinh_sach',
    'category_text' => 'Lao động - TB & Xã hội',
    'agency' => 'Bộ phận Lao động - Thương binh & Xã hội',
    'fee' => 'Miễn phí',
    'file_path' => NULL,
    'download_url' => NULL,
    'steps' => 
    array (
      0 => 'Khai thông tin người đề nghị trợ cấp và người giám hộ (nếu có).',
      1 => 'Tình trạng sức khỏe, khả năng tự phục vụ.',
      2 => 'Thu nhập và hoàn cảnh gia đình hiện tại.',
    ),
    'docs' => 
    array (
      0 => 'Giấy xác nhận mức độ khuyết tật (đối với người khuyết tật).',
      1 => 'Bản sao CCCD và sổ hộ khẩu/xác nhận cư trú.',
    ),
    'notes' => 'Hội đồng xét duyệt trợ cấp xã hội Phường Duy Hà họp xét duyệt vào tuần thứ 3 hàng tháng.',
    'is_active' => true,
    'sort_order' => 9,
  ),
);
            foreach ($formDocs as $fd) {
                \App\Models\FormDocument::create($fd);
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
