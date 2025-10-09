# ApprovalLeaveServices Documentation

## Overview
`ApprovalLeaveServices` adalah service yang menangani logika approval untuk sistem leave management. Service ini menyediakan berbagai method untuk mengelola head of department (HOD) dan hierarchy approval.

## Features

### 1. Head of Department Management
- ✅ **Get HOD by Department** - Mengambil HOD berdasarkan department
- ✅ **Get HOD by User ID** - Mengambil HOD berdasarkan user ID
- ✅ **Get HOD for Current User** - Mengambil HOD untuk user yang sedang login
- ✅ **Get All HODs** - Mengambil semua HOD yang aktif
- ✅ **Get HODs by Categories** - Mengambil HOD berdasarkan kategori department

### 2. Validation & Checking
- ✅ **Check if User is HOD** - Memeriksa apakah user adalah HOD
- ✅ **Check Current User is HOD** - Memeriksa apakah user saat ini adalah HOD
- ✅ **Validate Approval Permission** - Memvalidasi permission untuk approve leave

### 3. Dropdown & UI Support
- ✅ **Get HOD for Dropdown** - Format data HOD untuk dropdown
- ✅ **Get HOD for Dropdown by User ID** - Format data HOD berdasarkan user ID
- ✅ **Get HOD for Dropdown for Current User** - Format data HOD untuk user saat ini

### 4. Hierarchy & Statistics
- ✅ **Get Approval Hierarchy** - Mengambil hierarchy approval
- ✅ **Get Approval Statistics** - Mengambil statistik approval
- ✅ **Get All Departments with HODs** - Mengambil semua department dengan HOD-nya

## Methods

### Core Methods

#### `getHeadOfDepartment($deptCategoryId)`
Mengambil head of department berdasarkan department category ID.

```php
$hods = ApprovalLeaveServices::getHeadOfDepartment(1);
```

**Parameters:**
- `$deptCategoryId` (int) - ID kategori department

**Returns:** `Collection` - Collection of User models

#### `getHeadOfDepartmentByUserId($userId)`
Mengambil head of department berdasarkan user ID.

```php
$hods = ApprovalLeaveServices::getHeadOfDepartmentByUserId(123);
```

**Parameters:**
- `$userId` (int) - ID user

**Returns:** `Collection` - Collection of User models

#### `getHeadOfDepartmentForCurrentUser()`
Mengambil head of department untuk user yang sedang login.

```php
$hods = ApprovalLeaveServices::getHeadOfDepartmentForCurrentUser();
```

**Returns:** `Collection` - Collection of User models

### Validation Methods

#### `isHeadOfDepartment($userId)`
Memeriksa apakah user adalah head of department.

```php
$isHod = ApprovalLeaveServices::isHeadOfDepartment(123);
```

**Parameters:**
- `$userId` (int) - ID user

**Returns:** `bool` - True jika user adalah HOD

#### `isCurrentUserHeadOfDepartment()`
Memeriksa apakah user saat ini adalah head of department.

```php
$isHod = ApprovalLeaveServices::isCurrentUserHeadOfDepartment();
```

**Returns:** `bool` - True jika user saat ini adalah HOD

#### `canApproveLeave($approverId, $applicantId)`
Memvalidasi apakah user dapat approve leave untuk user tertentu.

```php
$canApprove = ApprovalLeaveServices::canApproveLeave(123, 456);
```

**Parameters:**
- `$approverId` (int) - ID user yang akan approve
- `$applicantId` (int) - ID user yang mengajukan leave

**Returns:** `bool` - True jika dapat approve

### Dropdown Methods

#### `getHeadOfDepartmentForDropdown($deptCategoryId)`
Mengambil data HOD dalam format untuk dropdown.

```php
$dropdown = ApprovalLeaveServices::getHeadOfDepartmentForDropdown(1);
```

**Parameters:**
- `$deptCategoryId` (int) - ID kategori department

**Returns:** `array` - Array dengan format:
```php
[
    [
        'value' => 'hod@company.com',
        'text' => 'John Doe (hod@company.com)',
        'id' => 123,
        'email' => 'hod@company.com',
        'name' => 'John Doe'
    ]
]
```

#### `getHeadOfDepartmentForDropdownForCurrentUser()`
Mengambil data HOD untuk dropdown untuk user saat ini.

```php
$dropdown = ApprovalLeaveServices::getHeadOfDepartmentForDropdownForCurrentUser();
```

**Returns:** `array` - Array dengan format dropdown

### Hierarchy Methods

#### `getApprovalHierarchy($userId)`
Mengambil hierarchy approval untuk user tertentu.

```php
$hierarchy = ApprovalLeaveServices::getApprovalHierarchy(123);
```

**Parameters:**
- `$userId` (int) - ID user

**Returns:** `array` - Array dengan format:
```php
[
    'direct_hod' => User, // User model HOD
    'department' => 'IT Department',
    'department_id' => 1
]
```

#### `getApprovalHierarchyForCurrentUser()`
Mengambil hierarchy approval untuk user saat ini.

```php
$hierarchy = ApprovalLeaveServices::getApprovalHierarchyForCurrentUser();
```

**Returns:** `array` - Array hierarchy approval

### Statistics Methods

#### `getApprovalStatistics($deptCategoryId)`
Mengambil statistik approval untuk department.

```php
$stats = ApprovalLeaveServices::getApprovalStatistics(1);
```

**Parameters:**
- `$deptCategoryId` (int) - ID kategori department

**Returns:** `array` - Array dengan format:
```php
[
    'total_hods' => 2,
    'active_hods' => 2,
    'department_id' => 1,
    'hods' => [
        [
            'id' => 123,
            'name' => 'John Doe',
            'email' => 'john@company.com',
            'active' => 1
        ]
    ]
]
```

#### `getAllDepartmentsWithHODs()`
Mengambil semua department dengan HOD-nya.

```php
$departments = ApprovalLeaveServices::getAllDepartmentsWithHODs();
```

**Returns:** `array` - Array dengan format:
```php
[
    [
        'department_id' => 1,
        'department_name' => 'IT Department',
        'hods' => [
            [
                'id' => 123,
                'name' => 'John Doe',
                'email' => 'john@company.com'
            ]
        ]
    ]
]
```

## Usage Examples

### 1. Basic HOD Retrieval
```php
// Get HOD for current user's department
$hods = ApprovalLeaveServices::getHeadOfDepartmentForCurrentUser();

// Get HOD for specific department
$hods = ApprovalLeaveServices::getHeadOfDepartment(1);

// Get HOD for specific user
$hods = ApprovalLeaveServices::getHeadOfDepartmentByUserId(123);
```

### 2. Validation
```php
// Check if user is HOD
$isHod = ApprovalLeaveServices::isHeadOfDepartment(123);

// Check if current user is HOD
$isCurrentUserHod = ApprovalLeaveServices::isCurrentUserHeadOfDepartment();

// Check if user can approve leave
$canApprove = ApprovalLeaveServices::canApproveLeave(123, 456);
```

### 3. Dropdown Integration
```php
// Get HOD data for dropdown
$dropdown = ApprovalLeaveServices::getHeadOfDepartmentForDropdownForCurrentUser();

// Use in Blade template
@foreach($dropdown as $hod)
    <option value="{{ $hod['value'] }}">{{ $hod['text'] }}</option>
@endforeach
```

### 4. Hierarchy Management
```php
// Get approval hierarchy
$hierarchy = ApprovalLeaveServices::getApprovalHierarchyForCurrentUser();

// Access HOD information
$directHod = $hierarchy['direct_hod'];
$department = $hierarchy['department'];
```

### 5. Statistics
```php
// Get approval statistics
$stats = ApprovalLeaveServices::getApprovalStatisticsForCurrentUser();

// Access statistics
$totalHods = $stats['total_hods'];
$activeHods = $stats['active_hods'];
```

## Controller Integration

### In LeaveController
```php
use App\Services\ApprovalLeaveServices;

class LeaveController extends Controller
{
    public function createLeave()
    {
        // Get HOD for current user
        $headDept = ApprovalLeaveServices::getHeadOfDepartmentForCurrentUser();
        
        // Get HOD for dropdown
        $hodDropdown = ApprovalLeaveServices::getHeadOfDepartmentForDropdownForCurrentUser();
        
        return view('leave.form', compact('headDept', 'hodDropdown'));
    }
}
```

### In Blade Template
```php
<!-- HOD Dropdown -->
<select name="head_of_department" class="form-control" required>
    <option value="">Select HOD</option>
    @foreach($hodDropdown as $hod)
        <option value="{{ $hod['value'] }}">{{ $hod['text'] }}</option>
    @endforeach
</select>
```

## Error Handling

Service ini menangani error dengan cara:

1. **User Not Found** - Mengembalikan empty collection atau false
2. **Invalid Department** - Mengembalikan empty collection
3. **No HOD Found** - Mengembalikan empty collection
4. **Permission Denied** - Mengembalikan false untuk validation methods

## Performance Considerations

1. **Database Queries** - Service menggunakan Eloquent untuk efisiensi
2. **Caching** - Tidak ada caching built-in, bisa ditambahkan jika diperlukan
3. **N+1 Queries** - Service menghindari N+1 queries dengan menggunakan proper joins

## Dependencies

- `App\User` - User model
- `Illuminate\Support\Facades\DB` - Database facade
- `Illuminate\Support\Facades\Auth` - Auth facade (untuk auth()->user())

## Database Requirements

Service ini memerlukan tabel:
- `users` - Dengan kolom: `id`, `first_name`, `last_name`, `email`, `active`, `hd`, `dept_category_id`
- `dept_category` - Dengan kolom: `id`, `name`

## Security Considerations

1. **Authentication** - Service menggunakan `auth()->user()` untuk current user
2. **Authorization** - Service memvalidasi permission untuk approval
3. **Data Validation** - Service memvalidasi input parameters
4. **SQL Injection** - Service menggunakan Eloquent untuk mencegah SQL injection

