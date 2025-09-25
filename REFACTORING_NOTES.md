# Refactoring CoordinatorLeaveBalanceController

## Perubahan yang Dilakukan

### 1. **Pembuatan Service Class**
- Dibuat `app/Services/LeaveBalanceService.php`
- Memisahkan logika bisnis dari controller
- Meningkatkan maintainability dan testability

### 2. **Perbaikan Security Issues**
- **SQL Injection Fix**: Mengganti raw SQL dengan query builder yang aman
- **Hardcoded Date Fix**: Menghapus hardcoded date "2023-03-09"
- **Input Validation**: Menggunakan parameter binding

### 3. **Code Quality Improvements**
- **Duplikasi Kode**: Menghapus duplikasi kode `$bla` calculation
- **Variable Naming**: Menggunakan nama yang lebih deskriptif
- **Code Structure**: Memisahkan logika ke method-method terpisah

### 4. **Compatibility dengan PHP 5.6**
- Menggunakan syntax yang kompatibel dengan PHP 5.6
- Tidak menggunakan fitur PHP 7+ seperti type hints yang strict
- Menggunakan array() syntax untuk kompatibilitas

## Struktur Baru

### LeaveBalanceService Methods:
- `getAnnualLeaveTaken($userId)` - Mengambil cuti tahunan yang sudah diambil
- `calculateExdoBalance($userId)` - Menghitung balance exdo
- `calculateForfeitedLeave($userId)` - Menghitung cuti yang hangus
- `calculateAnnualLeaveBalance($user, $annualTaken)` - Menghitung balance cuti tahunan
- `calculateFinalBalance($annualBalance, $forfeitedData, $empStatus)` - Menghitung balance akhir

### Controller Changes:
- Dependency injection untuk LeaveBalanceService
- Method `indexNewApply()` menjadi lebih clean dan readable
- Data yang dikembalikan tetap sama untuk backward compatibility

## Keuntungan Refactoring:

1. **Security**: Menghilangkan SQL injection vulnerability
2. **Maintainability**: Kode lebih mudah dipelihara dan dipahami
3. **Testability**: Logika bisnis bisa di-test secara terpisah
4. **Reusability**: Service class bisa digunakan di controller lain
5. **Readability**: Kode lebih mudah dibaca dan dipahami

## Backward Compatibility:
- Semua data yang dikembalikan ke view tetap sama
- Array `$try` memiliki struktur yang sama
- Tidak ada breaking changes untuk frontend
