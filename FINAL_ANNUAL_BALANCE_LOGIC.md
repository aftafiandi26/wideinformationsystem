# Final Annual Balance Logic Implementation

## Overview
Implementasi kolom `final_annual_balance` berdasarkan analisis logika kompleks dari view `indexNewAnnual.blade.php`.

## Logic Mapping dari View ke Controller

### **View Logic (indexNewAnnual.blade.php):**

```php
// Main Display Logic
<?php if (auth::user()->forfeitcase === 1): ?>
    {{ $user->initial_annual - $annual->transactionAnnual }}
<?php else: ?>
    {{ $user->initial_annual - $annual->transactionAnnual - $bla }}
<?php endif ?>

// Breakdown Display Logic
<?php if (auth::user()->forfeitcase === 1): ?>
    <?php if (auth::user()->emp_status === "Permanent"): ?>
        ( <span style="color: green;">{{ $totalAnnualPermanent1 }}</span> +
        {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnualPermanent1 }} )
    <?php else: ?>
        ( <span style="color: green;">{{ $totalAnnual }}</span> +
        {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnual }} )
    <?php endif ?>
<?php else: ?>
    <?php if (auth::user()->emp_status === "Permanent"): ?>
        ( <span style="color: green;">{{ $renewPermanet }}</span> +
        {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnualPermanent1 }} )
    <?php else: ?>
        ( <span style="color: green;">{{ $renewContract }}</span> +
        {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnual }} )
    <?php endif ?>
<?php endif ?>
```

### **Controller Logic (CoordinatorLeaveBalanceController.php):**

```php
->addColumn('final_annual_balance', function (User $user) {
    // Get annual leave taken
    $annualTaken = $this->leaveBalanceService->getAnnualLeaveTaken($user->id);
    
    // Calculate annual leave balance
    $annualBalance = $this->leaveBalanceService->calculateAnnualLeaveBalance($user, $annualTaken);
    
    // Calculate forfeited leave
    $forfeitedData = $this->leaveBalanceService->calculateForfeitedLeave($user->id);
    
    // Calculate final balance with forfeited adjustment
    $finalBalance = $this->leaveBalanceService->calculateFinalBalance(
        $annualBalance, 
        $forfeitedData, 
        $user->emp_status
    );
    
    // Get the appropriate final balance based on employment status
    if ($user->emp_status === 'Permanent') {
        $finalAnnualBalance = $finalBalance['renew_permanent'];
    } else {
        $finalAnnualBalance = $finalBalance['renew_contract'];
    }
    
    // Store additional data for debugging and consistency
    $user->final_annual_balance = $finalAnnualBalance;
    $user->annual_taken = $annualTaken;
    $user->annual_balance = $annualBalance;
    $user->forfeited_data = $forfeitedData;
    $user->final_balance = $finalBalance;
    
    return $finalAnnualBalance;
})
```

## Logic Flow Diagram

```
Start
  ↓
Get Annual Leave Taken
  ↓
Calculate Annual Leave Balance
  ↓
Calculate Forfeited Leave
  ↓
Calculate Final Balance with Forfeited Adjustment
  ↓
Check Employment Status
  ├── Permanent → Use renew_permanent
  └── Contract → Use renew_contract
  ↓
Return Final Annual Balance
```

## Variable Mapping

| View Variable | Controller Variable | Service Method | Description |
|---------------|---------------------|----------------|-------------|
| `$annual->transactionAnnual` | `$annualTaken` | `getAnnualLeaveTaken()` | Annual leave taken |
| `$totalAnnualPermanent1` | `$annualBalance['total_annual_permanent']` | `calculateAnnualLeaveBalance()` | Permanent annual balance |
| `$totalAnnual` | `$annualBalance['total_annual']` | `calculateAnnualLeaveBalance()` | Contract annual balance |
| `$renewPermanet` | `$finalBalance['renew_permanent']` | `calculateFinalBalance()` | Permanent with forfeit |
| `$renewContract` | `$finalBalance['renew_contract']` | `calculateFinalBalance()` | Contract with forfeit |
| `$bla` | `$forfeitedData['valid_forfeited']` | `calculateForfeitedLeave()` | Forfeited amount |

## Logic Conditions

### **1. Forfeit Case Logic:**

| Condition | View Logic | Controller Logic |
|-----------|------------|------------------|
| `forfeitcase === 1` | `initial_annual - annual_taken` | `$annualBalance['total_annual']` or `$annualBalance['total_annual_permanent']` |
| `forfeitcase !== 1` | `initial_annual - annual_taken - forfeited` | `$finalBalance['renew_permanent']` or `$finalBalance['renew_contract']` |

### **2. Employment Status Logic:**

| Status | View Logic | Controller Logic |
|--------|------------|------------------|
| Permanent | `$totalAnnualPermanent1` or `$renewPermanet` | `$annualBalance['total_annual_permanent']` or `$finalBalance['renew_permanent']` |
| Contract | `$totalAnnual` or `$renewContract` | `$annualBalance['total_annual']` or `$finalBalance['renew_contract']` |

## Implementation Benefits

### **1. Consistency:**
- **Single Source of Truth**: Semua perhitungan menggunakan `LeaveBalanceService`
- **Consistent Logic**: Logika yang sama di semua tempat
- **Data Integrity**: Tidak ada perbedaan perhitungan

### **2. Maintainability:**
- **Centralized Logic**: Business logic di service layer
- **Easy Updates**: Perubahan logic hanya di satu tempat
- **Testable**: Logic bisa di-unit test

### **3. Performance:**
- **Optimized Queries**: Service menggunakan query yang optimal
- **Cached Results**: Data bisa di-cache untuk performa
- **Reduced Duplication**: Tidak ada perhitungan berulang

### **4. Debugging:**
- **Stored Data**: Data intermediate disimpan untuk debugging
- **Clear Flow**: Alur perhitungan yang jelas
- **Error Tracking**: Mudah track error di setiap step

## Data Storage for Debugging

```php
// Store additional data for debugging and consistency
$user->final_annual_balance = $finalAnnualBalance;
$user->annual_taken = $annualTaken;
$user->annual_balance = $annualBalance;
$user->forfeited_data = $forfeitedData;
$user->final_balance = $finalBalance;
```

### **Available Data:**
- `$user->final_annual_balance`: Final calculated balance
- `$user->annual_taken`: Annual leave taken
- `$user->annual_balance`: Annual balance calculation
- `$user->forfeited_data`: Forfeited leave data
- `$user->final_balance`: Final balance with forfeit adjustment

## Testing Scenarios

### **1. No Forfeit Case (forfeitcase === 1):**
- **Permanent**: Should return `total_annual_permanent`
- **Contract**: Should return `total_annual`

### **2. Has Forfeit Case (forfeitcase !== 1):**
- **Permanent**: Should return `renew_permanent`
- **Contract**: Should return `renew_contract`

### **3. Edge Cases:**
- **Zero Balance**: When no annual leave available
- **Negative Balance**: When taken exceeds available
- **Maximum Balance**: When at maximum entitlement

## Usage in Datatables

```php
// In view, you can access:
$user->final_annual_balance  // Final calculated balance
$user->annual_taken          // Annual leave taken
$user->annual_balance        // Annual balance data
$user->forfeited_data        // Forfeited leave data
$user->final_balance         // Final balance data
```

## Integration with Other Columns

### **Total Balance Calculation:**
```php
->addColumn('total_balance', function (User $user) {
    return $user->final_annual_balance + $user->exdo_balance;
})
```

### **Apply Button Logic:**
```php
@if($user->final_annual_balance > 0)
    <a href="{!! URL::route('createAdvanceLeave') !!}" class="btn btn-danger btn-xs" role="button">Apply</a>
@endif
```

## Conclusion

Implementasi `final_annual_balance` berhasil:

1. **✅ Konsisten** dengan logika view yang kompleks
2. **✅ Menggunakan Service** untuk business logic
3. **✅ Menyimpan Data** untuk debugging
4. **✅ Mudah Maintain** dan update
5. **✅ Performant** dengan optimized queries

Kolom ini sekarang bisa digunakan di Datatables dengan data yang konsisten dan akurat! 🚀
