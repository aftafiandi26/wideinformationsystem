# Leave_ApplyNewController Documentation

## Overview
`Leave_ApplyNewController` adalah controller baru yang dibuat untuk menangani aplikasi cuti dengan menggunakan `LeaveBalanceService` yang sudah direfactor. Controller ini menyediakan API endpoints untuk aplikasi cuti yang lebih modern dan terstruktur.

## Features

### 1. **Leave Balance Management**
- Menampilkan informasi balance cuti user
- Menghitung cuti tahunan dan exdo yang tersedia
- Menangani perhitungan forfeited leave

### 2. **Leave Application Management**
- Validasi aplikasi cuti sebelum submit
- Store aplikasi cuti baru
- Pengecekan ketersediaan cuti
- Validasi berdasarkan status karyawan

### 3. **Leave History & Categories**
- Menampilkan riwayat cuti user
- Mengambil daftar kategori cuti
- Pagination untuk history
- Pending applications

### 4. **Dashboard Summary**
- Summary balance cuti untuk dashboard
- Informasi lengkap cuti tersedia
- Status employment

## API Endpoints

### 1. **GET /leave-apply-new**
- **Purpose**: Menampilkan halaman aplikasi cuti baru
- **Method**: GET
- **Response**: View dengan data balance cuti

### 2. **GET /leave-apply-new/balance-data**
- **Purpose**: Mengambil data balance cuti (AJAX)
- **Method**: GET
- **Response**: JSON dengan data balance lengkap

### 3. **POST /leave-apply-new/validate**
- **Purpose**: Validasi aplikasi cuti
- **Method**: POST
- **Parameters**:
  - `leave_type`: annual/exdo
  - `start_date`: tanggal mulai
  - `end_date`: tanggal selesai
  - `total_days`: jumlah hari
- **Response**: JSON dengan status validasi

### 4. **GET /leave-apply-new/categories**
- **Purpose**: Mengambil daftar kategori cuti
- **Method**: GET
- **Response**: JSON dengan list kategori

### 5. **GET /leave-apply-new/history**
- **Purpose**: Mengambil riwayat cuti user
- **Method**: GET
- **Parameters**:
  - `limit`: jumlah data (default: 10)
  - `offset`: offset data (default: 0)
- **Response**: JSON dengan riwayat cuti

### 6. **GET /leave-apply-new/summary**
- **Purpose**: Summary balance cuti untuk dashboard
- **Method**: GET
- **Response**: JSON dengan summary lengkap

### 7. **POST /leave-apply-new/can-apply**
- **Purpose**: Cek apakah user bisa apply cuti
- **Method**: POST
- **Parameters**:
  - `leave_type`: jenis cuti
- **Response**: JSON dengan status permission

### 8. **POST /leave-apply-new/store**
- **Purpose**: Store aplikasi cuti baru
- **Method**: POST
- **Parameters**:
  - `leave_category_id`: ID kategori cuti
  - `start_date`: tanggal mulai
  - `end_date`: tanggal selesai
  - `total_days`: jumlah hari
  - `reason`: alasan cuti
- **Response**: JSON dengan status store

### 9. **GET /leave-apply-new/pending**
- **Purpose**: Mengambil aplikasi cuti yang pending
- **Method**: GET
- **Response**: JSON dengan data pending applications

## Controller Methods

### 1. **index()**
- Menampilkan halaman utama aplikasi cuti baru
- Menggunakan `getUserLeaveBalance()` untuk data balance

### 2. **getUserLeaveBalance($userId, $user)**
- Method private untuk menghitung balance cuti
- Menggunakan `LeaveBalanceService`
- Return array dengan data balance lengkap

### 3. **getLeaveBalanceData()**
- API endpoint untuk AJAX requests
- Return JSON dengan data balance

### 4. **validateLeaveApplication(Request $request)**
- Validasi aplikasi cuti
- Cek ketersediaan cuti berdasarkan jenis
- Return status validasi

### 5. **getLeaveCategories()**
- Mengambil daftar kategori cuti dari database
- Return JSON dengan list kategori

### 6. **getLeaveHistory(Request $request)**
- Mengambil riwayat cuti dengan pagination
- Join dengan tabel leave_category
- Return JSON dengan data dan metadata

### 7. **getLeaveBalanceSummary()**
- Summary balance untuk dashboard
- Data lengkap cuti tahunan, exdo, dan forfeited
- Return JSON dengan summary

### 8. **canApplyLeave(Request $request)**
- Cek permission untuk apply cuti
- Validasi status user dan employment
- Return status permission

### 9. **storeLeaveApplication(Request $request)**
- Store aplikasi cuti baru ke database
- Validasi balance sebelum store
- Return status store dengan leave_id

### 10. **getPendingApplications()**
- Mengambil aplikasi cuti yang masih pending
- Filter berdasarkan formStat = 0
- Return JSON dengan data pending

## Usage Examples

### 1. **Get Leave Balance Data**
```javascript
// AJAX call to get balance data
$.get('/leave-apply-new/balance-data', function(response) {
    if (response.success) {
        console.log('Annual Leave Available:', response.data.summary.total_annual_available);
        console.log('Exdo Available:', response.data.summary.remaining_exdo);
    }
});
```

### 2. **Validate Leave Application**
```javascript
// Validate before submitting
$.post('/leave-apply-new/validate', {
    leave_type: 'annual',
    start_date: '2024-01-15',
    end_date: '2024-01-17',
    total_days: 3
}, function(response) {
    if (response.success) {
        alert('Application is valid!');
    } else {
        alert('Error: ' + response.message);
    }
});
```

### 3. **Store Leave Application**
```javascript
// Store new leave application
$.post('/leave-apply-new/store', {
    leave_category_id: 1,
    start_date: '2024-01-15',
    end_date: '2024-01-17',
    total_days: 3,
    reason: 'Family vacation'
}, function(response) {
    if (response.success) {
        alert('Leave application submitted successfully!');
        console.log('Leave ID:', response.leave_id);
    } else {
        alert('Error: ' + response.message);
    }
});
```

### 4. **Get Pending Applications**
```javascript
// Get pending applications
$.get('/leave-apply-new/pending', function(response) {
    if (response.success) {
        console.log('Pending Applications:', response.data);
    }
});
```

## Dependencies

### Required Services:
- `LeaveBalanceService` - untuk perhitungan balance
- `User` model - untuk data user
- `Auth` facade - untuk authentication
- `Carbon` - untuk date handling

### Database Tables:
- `users` - data user
- `leave_transaction` - transaksi cuti
- `leave_category` - kategori cuti
- `initial_leave` - cuti awal
- `forfeited` - cuti hangus
- `forfeited_counts` - hitungan cuti hangus

## Security Features

1. **Authentication**: Semua endpoint memerlukan login
2. **Authorization**: Cek status user aktif
3. **Input Validation**: Validasi input request
4. **SQL Injection Protection**: Menggunakan query builder
5. **Employment Status Check**: Validasi status karyawan
6. **Balance Validation**: Cek balance sebelum store

## Compatibility

- **PHP Version**: 5.6+
- **Laravel Version**: 5.x
- **Database**: MySQL/MariaDB
- **Frontend**: jQuery, AJAX compatible

## Error Handling

Controller menggunakan standard Laravel error handling:
- Validation errors untuk input
- Database errors untuk query
- Authentication errors untuk access
- JSON responses untuk API endpoints

## Performance Considerations

1. **Service Layer**: Logika bisnis dipisahkan untuk reusability
2. **Query Optimization**: Menggunakan query builder yang efisien
3. **Pagination**: History data menggunakan pagination
4. **Validation**: Pre-validation sebelum database operations
5. **Caching**: Bisa ditambahkan caching untuk data yang sering diakses

## Key Differences from Previous Controller

1. **Additional Methods**: 
   - `storeLeaveApplication()` - untuk store aplikasi
   - `getPendingApplications()` - untuk pending apps

2. **Enhanced Validation**: 
   - Balance validation sebelum store
   - Employment status validation

3. **Better Error Handling**: 
   - Comprehensive validation
   - Clear error messages

4. **Complete CRUD**: 
   - Create, Read operations
   - Validation dan storage
