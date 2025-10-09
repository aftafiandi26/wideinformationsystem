# EtcLeaveServices Documentation

## Overview
`EtcLeaveServices` adalah service yang menyediakan semua kategori cuti dengan value yang dapat dikustomisasi. Service ini menangani berbagai jenis cuti seperti sakit, pernikahan, kelahiran anak, dll.

## Features

### 1. Leave Categories Management
- **Sick**: Cuti sakit (tidak ada batas hari, memerlukan surat dokter, dibayar)
- **Wedding (3 days)**: Cuti pernikahan (maksimal 3 hari, tidak perlu surat dokter, dibayar)
- **Birth of child (2 days)**: Cuti kelahiran anak (maksimal 2 hari, tidak perlu surat dokter, dibayar)
- **Unpaid**: Cuti tanpa gaji (tidak ada batas hari, tidak perlu surat dokter, tidak dibayar)
- **Son circumcision/baptism (2 days)**: Cuti sunat/baptis anak (maksimal 2 hari, tidak perlu surat dokter, dibayar)
- **Death of family (3 days)**: Cuti kematian keluarga (maksimal 3 hari, tidak perlu surat dokter, dibayar)
- **Death of family in law (2 days)**: Cuti kematian keluarga mertua (maksimal 2 hari, tidak perlu surat dokter, dibayar)
- **Maternity (3 Month)**: Cuti melahirkan (maksimal 90 hari, memerlukan surat dokter, dibayar)
- **Others**: Cuti lainnya (tidak ada batas hari, tidak perlu surat dokter, dibayar)

### 2. API Endpoints

#### Get All Leave Categories
```
GET /outsource/leave/categories
```
**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Sick",
            "value": "sick",
            "description": "Sick leave for medical reasons",
            "max_days": null,
            "requires_medical_cert": true,
            "is_paid": true
        }
    ],
    "total": 9
}
```

#### Get Leave Category by ID
```
GET /outsource/leave/categories/{id}
```
**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Sick",
        "value": "sick",
        "description": "Sick leave for medical reasons",
        "max_days": null,
        "requires_medical_cert": true,
        "is_paid": true
    }
}
```

#### Validate Leave Request
```
POST /outsource/leave/validate
```
**Request Body:**
```json
{
    "category_id": 1,
    "requested_days": 5
}
```
**Response:**
```json
{
    "valid": true,
    "message": "Leave request is valid",
    "category": {
        "id": 1,
        "name": "Sick",
        "value": "sick"
    },
    "requires_medical_cert": true,
    "is_paid": true
}
```

#### Get Leave Requirements
```
GET /outsource/leave/requirements/{id}
```
**Response:**
```json
{
    "success": true,
    "data": {
        "requires_medical_cert": true,
        "is_paid": true,
        "max_days": null,
        "description": "Sick leave for medical reasons"
    }
}
```

### 3. Service Methods

#### Static Methods
```php
// Get all leave categories
EtcLeaveServices::getAllLeaveCategories()

// Get category by ID
EtcLeaveServices::getLeaveCategoryById($id)

// Get category by value
EtcLeaveServices::getLeaveCategoryByValue($value)

// Get categories as options for dropdown
EtcLeaveServices::getLeaveCategoriesOptions()

// Get categories as JSON
EtcLeaveServices::getLeaveCategoriesJson()

// Validate leave request
EtcLeaveServices::validateLeaveRequest($categoryId, $requestedDays)

// Get leave requirements
EtcLeaveServices::getLeaveRequirements($categoryId)

// Get paid leave categories only
EtcLeaveServices::getPaidLeaveCategories()

// Get unpaid leave categories only
EtcLeaveServices::getUnpaidLeaveCategories()

// Get categories that require medical certificate
EtcLeaveServices::getMedicalCertRequiredCategories()

// Get categories with max days limit
EtcLeaveServices::getLimitedDaysCategories()

// Get categories without max days limit
EtcLeaveServices::getUnlimitedDaysCategories()
```

### 4. JavaScript Integration

#### Include the service
```html
<script src="/assets/js/leave/etc-leave-service.js"></script>
```

#### Basic Usage
```javascript
// Get all categories
const categories = window.etcLeaveService.getCategories();

// Get category by ID
const category = window.etcLeaveService.getCategoryById(1);

// Validate leave request
const validation = await window.etcLeaveService.validateLeaveRequest(1, 5);

// Populate dropdown
window.etcLeaveService.populateDropdown(document.getElementById('leave_category'));

// Show category info
window.etcLeaveService.showCategoryInfo(1, document.getElementById('category-info'));
```

#### Form Integration
```html
<select id="leave_category" name="leave_category">
    <!-- Options will be populated automatically -->
</select>

<div id="category-info">
    <!-- Category information will be displayed here -->
</div>

<input type="number" id="requested_days" name="requested_days" min="1">
```

### 5. Customization

#### Adding New Categories
Edit `app/Services/EtcLeaveServices.php` and add new category to `getAllLeaveCategories()` method:

```php
[
    'id' => 10,
    'name' => 'Custom Leave',
    'value' => 'custom',
    'description' => 'Custom leave description',
    'max_days' => 5,
    'requires_medical_cert' => false,
    'is_paid' => true
]
```

#### Modifying Existing Categories
Update the category array in `getAllLeaveCategories()` method:

```php
[
    'id' => 1,
    'name' => 'Sick',
    'value' => 'sick',
    'description' => 'Sick leave for medical reasons',
    'max_days' => 30, // Add max days limit
    'requires_medical_cert' => true,
    'is_paid' => true
]
```

### 6. Database Integration

#### Using with Database
```php
// In controller
public function etcLeave()
{
    // Get categories from service
    $leaveCategories = EtcLeaveServices::getAllLeaveCategories();
    
    // Get user's leave balance
    $finalBalance = $this->getUserLeaveBalance();
    
    return view('leave.outsources.leave.formLeave.etc', compact('leaveCategories', 'finalBalance'));
}
```

#### Form Processing
```php
public function storeLeave(Request $request)
{
    // Validate using service
    $categoryId = $request->input('leave_category_id');
    $requestedDays = $request->input('requested_days');
    
    $validation = EtcLeaveServices::validateLeaveRequest($categoryId, $requestedDays);
    
    if (!$validation['valid']) {
        return redirect()->back()->withErrors(['leave_category_id' => $validation['message']]);
    }
    
    // Process leave application
    // ...
}
```

### 7. Frontend Integration

#### Blade Template
```blade
<select name="leave_category_id" id="leave_category" class="form-control">
    <option value="">Select Leave Category</option>
    @foreach($leaveCategories as $category)
        <option value="{{ $category['id'] }}" 
                data-max-days="{{ $category['max_days'] }}"
                data-requires-cert="{{ $category['requires_medical_cert'] ? 'true' : 'false' }}"
                data-is-paid="{{ $category['is_paid'] ? 'true' : 'false' }}">
            {{ $category['name'] }}
        </option>
    @endforeach
</select>
```

#### JavaScript Validation
```javascript
$('#leave_category').on('change', function() {
    const categoryId = $(this).val();
    const category = window.etcLeaveService.getCategoryById(categoryId);
    
    if (category) {
        // Show category info
        window.etcLeaveService.showCategoryInfo(categoryId, document.getElementById('category-info'));
        
        // Set max days limit
        const maxDays = category.max_days;
        if (maxDays) {
            $('#requested_days').attr('max', maxDays);
        }
        
        // Show medical cert requirement
        if (category.requires_medical_cert) {
            $('#medical_cert_required').show();
        } else {
            $('#medical_cert_required').hide();
        }
    }
});
```

### 8. Error Handling

#### Service Level
```php
try {
    $category = EtcLeaveServices::getLeaveCategoryById($id);
    if (!$category) {
        throw new \Exception('Category not found');
    }
} catch (\Exception $e) {
    Log::error('Error getting category: ' . $e->getMessage());
    return null;
}
```

#### JavaScript Level
```javascript
try {
    const validation = await window.etcLeaveService.validateLeaveRequest(categoryId, days);
    if (!validation.valid) {
        showError(validation.message);
    }
} catch (error) {
    console.error('Validation error:', error);
    showError('Error validating leave request');
}
```

### 9. Testing

#### Unit Tests
```php
public function testGetAllLeaveCategories()
{
    $categories = EtcLeaveServices::getAllLeaveCategories();
    $this->assertIsArray($categories);
    $this->assertCount(9, $categories);
}

public function testValidateLeaveRequest()
{
    $validation = EtcLeaveServices::validateLeaveRequest(1, 5);
    $this->assertTrue($validation['valid']);
}
```

#### Integration Tests
```php
public function testGetLeaveCategoriesApi()
{
    $response = $this->get('/outsource/leave/categories');
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
}
```

### 10. Performance Considerations

#### Caching
```php
// Cache categories for better performance
$categories = Cache::remember('leave_categories', 3600, function() {
    return EtcLeaveServices::getAllLeaveCategories();
});
```

#### Database Optimization
```php
// Use database for dynamic categories
public static function getAllLeaveCategories()
{
    return Leave_Category::select('id', 'name', 'value', 'description', 'max_days', 'requires_medical_cert', 'is_paid')
        ->get()
        ->toArray();
}
```

## Conclusion

`EtcLeaveServices` menyediakan solusi lengkap untuk manajemen kategori cuti dengan fleksibilitas tinggi. Service ini dapat dikustomisasi sesuai kebutuhan dan terintegrasi dengan baik dengan frontend dan backend aplikasi.

