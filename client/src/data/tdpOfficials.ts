export interface TdpOfficialRow {
  tt: number;
  tdp: string;
  biThuName: string;
  biThuPhone?: string;
  toTruongName: string;
  toTruongPhone?: string;
  cskvName?: string;
  cskvPhone?: string;
  matTanName: string;
  matTanPhone?: string;
  nguoiCaoTuoi: string;
  phuNu: string;
  nongDan: string;
  ccb: string;
  doanThanhNien: string;
}

export const CSKV_MAP: Record<string, { name: string; phone: string }> = {
  'ngọc tú': { name: 'Thiếu tá Nguyễn Minh Tiến', phone: '0359.290.686' },
  'duy minh': { name: 'Đại úy Trần Hữu Tiến', phone: '0986.361.395' },
  'đông linh trang': { name: 'Thiếu úy Vũ Văn Hào', phone: '0796.191.310' },
  'động linh trang': { name: 'Thiếu úy Vũ Văn Hào', phone: '0796.191.310' },
  'chuồng': { name: 'Đại úy Nguyễn Văn Việt', phone: '0972.280.538' },
  'bạch xá': { name: 'Đại úy Đoàn Văn Chương', phone: '0911.940.111' },
  'hoàng đồng': { name: 'Thiếu tá Ngô Vinh Quang', phone: '0977.597.118' },
  'hương cát': { name: 'Thượng úy Đinh Xuân Trường', phone: '0585.288.686' },
  'duy hải': { name: 'Thượng úy Đinh Xuân Trường', phone: '0585.288.686' },
  'ngọc động': { name: 'Đại úy Vũ Ngọc Quang', phone: '0978.530.570' },
  'đông hải': { name: 'Thiếu tá Nguyễn Văn Tuân', phone: '0866.697.088' }
};

export const ALL_TDP_OFFICIALS: TdpOfficialRow[] = [
  {
    tt: 1,
    tdp: 'Ngọc Tú',
    biThuName: 'Trương Thị Lê',
    biThuPhone: '0963.566.121',
    toTruongName: 'Phan Văn Trịnh',
    toTruongPhone: '0988.475.861',
    cskvName: 'Thiếu tá Nguyễn Minh Tiến',
    cskvPhone: '0359.290.686',
    matTanName: 'Trương Thị Lê',
    matTanPhone: '0963.566.121',
    nguoiCaoTuoi: 'Hà Minh Khoái',
    phuNu: 'Dương T.Thanh Thảo',
    nongDan: 'Nguyễn Thị La',
    ccb: 'Dương Tuấn Anh',
    doanThanhNien: 'Nguyễn Văn Biên'
  },
  {
    tt: 2,
    tdp: 'Duy Minh',
    biThuName: 'Lê Xuân Hiến',
    biThuPhone: '0378.582.168',
    toTruongName: 'Đặng Quang Thiện',
    toTruongPhone: '',
    cskvName: 'Đại úy Trần Hữu Tiến',
    cskvPhone: '0986.361.395',
    matTanName: 'Lê Xuân Hiến',
    matTanPhone: '0378.582.168',
    nguoiCaoTuoi: 'Bạch Tường Vân',
    phuNu: 'Nguyễn T.Thanh Thủy',
    nongDan: 'Đặng Quốc Việt',
    ccb: 'Vũ Văn Mười',
    doanThanhNien: 'Đỗ Thị Loan'
  },
  {
    tt: 3,
    tdp: 'Đông Linh Trang',
    biThuName: 'Nguyễn T. Minh Thoa',
    biThuPhone: '0984.687.445',
    toTruongName: 'Nguyễn Văn Tỉnh',
    toTruongPhone: '0946.844.268',
    cskvName: 'Thiếu úy Vũ Văn Hào',
    cskvPhone: '0796.191.310',
    matTanName: 'Nguyễn Tiến Thụy',
    matTanPhone: '0977.293.567',
    nguoiCaoTuoi: 'Kiều Tiến Năng',
    phuNu: 'Phạm Thị Khánh',
    nongDan: 'Lý Thị Ngẩn',
    ccb: 'Nguyễn Tiến Vinh',
    doanThanhNien: 'Nguyễn Thị Linh'
  },
  {
    tt: 4,
    tdp: 'Chuồng',
    biThuName: 'Ngô Bá Tùy',
    biThuPhone: '0985.834.898',
    toTruongName: 'Đinh Viết Lượng',
    toTruongPhone: '0966.835.154',
    cskvName: 'Đại úy Nguyễn Văn Việt',
    cskvPhone: '0972.280.538',
    matTanName: 'Đỗ Tiến Lạc',
    matTanPhone: '0983.196.969',
    nguoiCaoTuoi: 'Vũ Duy Cương',
    phuNu: 'Phạm Thị Oanh',
    nongDan: 'Ngô Duy Lượng',
    ccb: 'Bùi Hải Triều',
    doanThanhNien: 'Lương Thị Nhâm'
  },
  {
    tt: 5,
    tdp: 'Hương Cát',
    biThuName: 'Lê Văn Đãi',
    biThuPhone: '0396.217.476',
    toTruongName: 'Nguyễn Quốc Việt',
    toTruongPhone: '0963.848.132',
    cskvName: 'Thượng úy Đinh Xuân Trường',
    cskvPhone: '0585.288.686',
    matTanName: 'Lê Văn Đãi',
    matTanPhone: '0396.217.476',
    nguoiCaoTuoi: 'Nguyễn Văn Cải',
    phuNu: 'Đỗ Thị Thau',
    nongDan: 'Nguyễn Thị Yên',
    ccb: 'Lê Văn Điệp',
    doanThanhNien: 'Lê Văn Thành'
  },
  {
    tt: 6,
    tdp: 'Duy Hải',
    biThuName: 'Nguyễn Văn Thức',
    biThuPhone: '0976.791.743',
    toTruongName: 'Hoàng Văn Dũng',
    toTruongPhone: '0963.848.132',
    cskvName: 'Thượng úy Đinh Xuân Trường',
    cskvPhone: '0585.288.686',
    matTanName: 'Vũ Khắc Chuột',
    matTanPhone: '0352.327.214',
    nguoiCaoTuoi: 'Trần Thị Thanh',
    phuNu: 'Trần Thị Oanh',
    nongDan: 'Nguyễn Hữu Dư',
    ccb: 'Lê Văn Thức',
    doanThanhNien: 'Nguyễn Ngọc Tùng'
  },
  {
    tt: 7,
    tdp: 'Đông Hải',
    biThuName: 'Lê Minh Dũng',
    biThuPhone: '0914.196.813',
    toTruongName: 'Lê Minh Dũng',
    toTruongPhone: '0914.196.813',
    cskvName: 'Thiếu tá Nguyễn Văn Tuân',
    cskvPhone: '0866.697.088',
    matTanName: 'Nghiêm Thái Hoa',
    matTanPhone: '0915.493.685',
    nguoiCaoTuoi: 'Nguyễn Thị Nhi',
    phuNu: 'Đỗ Thị Huyền',
    nongDan: 'Đàm Thị Minh',
    ccb: 'Đỗ Thị Đào',
    doanThanhNien: 'Vũ Quốc Văn'
  },
  {
    tt: 8,
    tdp: 'Ngọc Động',
    biThuName: 'Nguyễn Văn Thọ',
    biThuPhone: '0988.181.452',
    toTruongName: 'Nguyễn Thanh Hà',
    toTruongPhone: '0962.497.817',
    cskvName: 'Đại úy Vũ Ngọc Quang',
    cskvPhone: '0978.530.570',
    matTanName: 'Nguyễn Văn Thọ',
    matTanPhone: '0988.181.452',
    nguoiCaoTuoi: 'Nguyễn Văn Thìn',
    phuNu: 'Nguyễn Thị Thanh',
    nongDan: 'Phạm Xuân Quỳnh',
    ccb: 'Nguyễn Đình Tuyến',
    doanThanhNien: 'Nguyễn Anh Tuấn'
  },
  {
    tt: 9,
    tdp: 'Hoàng Đồng',
    biThuName: 'Đỗ Hữu Biên',
    biThuPhone: '0902.236.670',
    toTruongName: 'Vũ Đức Hùng',
    toTruongPhone: '0973.750.628',
    cskvName: 'Thiếu tá Ngô Vinh Quang',
    cskvPhone: '0977.597.118',
    matTanName: 'Vũ Văn Tuyên',
    matTanPhone: '0984.863.383',
    nguoiCaoTuoi: 'Đỗ Văn Hòa',
    phuNu: 'Tạ Thị Hồng Lê',
    nongDan: 'Trương Thị Nhân',
    ccb: 'Nguyễn Văn Hòa',
    doanThanhNien: 'Vũ Mai Hương'
  },
  {
    tt: 10,
    tdp: 'Bạch Xá',
    biThuName: 'Nguyễn Hữu Hào',
    biThuPhone: '0326.951.177',
    toTruongName: 'Chu Văn Mẫu',
    toTruongPhone: '0364.293.625',
    cskvName: 'Đại úy Đoàn Văn Chương',
    cskvPhone: '0911.940.111',
    matTanName: 'Nguyễn Hữu Hào',
    matTanPhone: '0326.951.177',
    nguoiCaoTuoi: 'Nguyễn Văn Hoạch',
    phuNu: 'Nguyễn Thị Hoa',
    nongDan: 'Nguyễn Thị Diễn',
    ccb: 'Nguyễn Hữu Hội',
    doanThanhNien: 'Lương Thùy Dung'
  }
];
