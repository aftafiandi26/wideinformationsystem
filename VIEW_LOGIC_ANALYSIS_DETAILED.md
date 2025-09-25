# Analisis Logika View indexNewAnnual.blade.php (Detailed)

## Overview
Analisis mendalam terhadap logika perhitungan yang kompleks di view `indexNewAnnual.blade.php` untuk memahami alur perhitungan balance cuti tahunan.

## Struktur Logika Perhitungan

### 1. **Main Display Logic (Baris 102-107)**

```php
<?php if (auth::user()->forfeitcase === 1): ?>
    {{ $user->initial_annual - $annual->transactionAnnual }}
<?php else: ?>
    {{ $user->initial_annual - $annual->transactionAnnual - $bla }}
<?php endif ?>
```

**Analisis:**
- **Condition**: `forfeitcase === 1` (User tidak memiliki forfeit case)
- **Formula 1**: `initial_annual - annual_taken` (Basic calculation)
- **Formula 2**: `initial_annual - annual_taken - forfeited_amount` (With forfeited adjustment)

### 2. **Breakdown Display Logic (Baris 108-124)**

#### **Jika `forfeitcase === 1` (No Forfeit):**

**Permanent Employees (Baris 109-111):**
```php
<?php if (auth::user()->emp_status === "Permanent"): ?>
    ( <span style="color: green;">{{ $totalAnnualPermanent1 }}</span> +
    {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnualPermanent1 }} )
```

**Contract Employees (Baris 112-115):**
```php
<?php else: ?>
    ( <span style="color: green;">{{ $totalAnnual }}</span> +
    {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnual }} )
```

#### **Jika `forfeitcase !== 1` (Has Forfeit):**

**Permanent Employees (Baris 117-119):**
```php
<?php if (auth::user()->emp_status === "Permanent"): ?>
    ( <span style="color: green;">{{ $renewPermanet }}</span> +
    {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnualPermanent1 }} )
```

**Contract Employees (Baris 120-123):**
```php
<?php else: ?>
    ( <span style="color: green;">{{ $renewContract }}</span> +
    {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnual }} )
```

## Analisis Variabel yang Digunakan

### **Core Variables:**
| Variable | Description | Source | Usage |
|----------|-------------|---------|-------|
| `$user->initial_annual` | Initial annual entitlement | User model | Base calculation |
| `$annual->transactionAnnual` | Annual leave taken | Controller | Subtract from initial |
| `$bla` | Forfeited amount | Controller | Forfeit adjustment |
| `$totalAnnualPermanent1` | Permanent annual balance | Controller | Permanent display |
| `$totalAnnual` | Contract annual balance | Controller | Contract display |
| `$renewPermanet` | Renewed permanent balance | Controller | Permanent with forfeit |
| `$renewContract` | Renewed contract balance | Controller | Contract with forfeit |

### **Condition Variables:**
| Variable | Description | Values | Logic |
|----------|-------------|---------|-------|
| `auth::user()->forfeitcase` | Forfeit case status | 1 = No forfeit, 0 = Has forfeit | Determines calculation method |
| `auth::user()->emp_status` | Employment status | "Permanent", "Contract" | Determines which balance to use |

## Flow Diagram

```
Start
  ↓
Check forfeitcase
  ├── forfeitcase === 1 (No forfeit)
  │   ├── Display: initial_annual - annual_taken
  │   └── Breakdown by employment status
  │       ├── Permanent: (totalAnnualPermanent1 + remainder)
  │       └── Contract: (totalAnnual + remainder)
  └── forfeitcase !== 1 (Has forfeit)
      ├── Display: initial_annual - annual_taken - forfeited
      └── Breakdown by employment status
          ├── Permanent: (renewPermanet + remainder)
          └── Contract: (renewContract + remainder)
```

## Perhitungan Breakdown

### **Formula Breakdown:**

#### **1. Main Display:**
- **No Forfeit**: `initial_annual - annual_taken`
- **With Forfeit**: `initial_annual - annual_taken - forfeited`

#### **2. Green Value (Available Balance):**
- **No Forfeit + Permanent**: `totalAnnualPermanent1`
- **No Forfeit + Contract**: `totalAnnual`
- **With Forfeit + Permanent**: `renewPermanet`
- **With Forfeit + Contract**: `renewContract`

#### **3. Remainder Calculation:**
- **Permanent**: `initial_annual - annual_taken - totalAnnualPermanent1`
- **Contract**: `initial_annual - annual_taken - totalAnnual`

## Identifikasi Masalah

### **1. Logic Complexity:**
- **Nested Conditions**: 4 level nested if statements
- **Code Duplication**: Similar calculations repeated
- **Hard to Debug**: Complex logic in view

### **2. Inconsistency Issues:**
- **Variable Naming**: `$bla`, `$renewPermanet` (typo)
- **Calculation Logic**: Different formulas for same purpose
- **Data Source**: Mix of controller data and direct calculations

### **3. Maintainability Issues:**
- **View Logic**: Business logic in view layer
- **Testing Difficulty**: Hard to unit test view logic
- **Code Duplication**: Repeated calculation patterns

## Mapping dengan LeaveBalanceService

### **Current View Logic vs Service Logic:**

| View Logic | Service Equivalent | Status |
|------------|-------------------|--------|
| `$user->initial_annual - $annual->transactionAnnual` | `calculateAnnualLeaveBalance()` → `total_annual` | ✅ Match |
| `$user->initial_annual - $annual->transactionAnnual - $bla` | `calculateFinalBalance()` → `renew_permanent/contract` | ✅ Match |
| `$totalAnnualPermanent1` | `calculateAnnualLeaveBalance()` → `total_annual_permanent` | ✅ Match |
| `$totalAnnual` | `calculateAnnualLeaveBalance()` → `total_annual` | ✅ Match |
| `$renewPermanet` | `calculateFinalBalance()` → `renew_permanent` | ✅ Match |
| `$renewContract` | `calculateFinalBalance()` → `renew_contract` | ✅ Match |

## Button Logic Analysis

### **Apply Button Conditions (Baris 125-135):**

```php
<?php if (auth::user()->emp_status === "Contract"): ?>
    @if ($user->initial_annual - $annual->transactionAnnual > 0)
        <a href="{!! URL::route('createAdvanceLeave') !!}" class="btn btn-danger btn-xs" role="button">Apply</a>
    @endif
<?php endif ?>
<?php if (auth::user()->emp_status === "Permanent"): ?>
    @if ($user->initial_annual - $annual->transactionAnnual > 0)
        <a href="{!! URL::route('createAdvanceLeave') !!}" class="btn btn-danger btn-xs" role="button">Apply</a>
    @endif
<?php endif ?>
```

**Analisis:**
- **Condition**: `initial_annual - annual_taken > 0`
- **Logic**: Show Apply button if user has remaining annual leave
- **Issue**: Same condition for both Permanent and Contract
- **Simplification**: Could be `@if($remainingBalance > 0)`

## Rekomendasi Perbaikan

### **1. Controller Refactoring:**
```php
// Calculate all values in controller
$viewData = [
    'mainDisplay' => $forfeitcase === 1 
        ? $user->initial_annual - $annualTaken
        : $user->initial_annual - $annualTaken - $forfeitedAmount,
    
    'greenValue' => $forfeitcase === 1
        ? ($empStatus === 'Permanent' ? $totalAnnualPermanent1 : $totalAnnual)
        : ($empStatus === 'Permanent' ? $renewPermanent : $renewContract),
    
    'remainder' => $user->initial_annual - $annualTaken - $greenValue,
    
    'showApplyButton' => $remainingBalance > 0
];
```

### **2. View Simplification:**
```php
<td>
    <span>{{ $mainDisplay }}</span>
    ( <span style="color: green;">{{ $greenValue }}</span> + {{ $remainder }} )
</td>
<td>
    @if($showApplyButton)
        <a href="{!! URL::route('createAdvanceLeave') !!}" class="btn btn-danger btn-xs" role="button">Apply</a>
    @endif
</td>
```

### **3. Service Integration:**
```php
// Use service methods consistently
$annualBalance = $this->leaveBalanceService->calculateAnnualLeaveBalance($user, $annualTaken);
$forfeitedData = $this->leaveBalanceService->calculateForfeitedLeave($userId);
$finalBalance = $this->leaveBalanceService->calculateFinalBalance($annualBalance, $forfeitedData, $user->emp_status);
```

## Testing Scenarios

### **1. Forfeit Case = 1 (No Forfeit):**
- **Permanent**: Should show `totalAnnualPermanent1` in green
- **Contract**: Should show `totalAnnual` in green
- **Display**: `initial_annual - annual_taken`

### **2. Forfeit Case ≠ 1 (Has Forfeit):**
- **Permanent**: Should show `renewPermanent` in green
- **Contract**: Should show `renewContract` in green
- **Display**: `initial_annual - annual_taken - forfeited`

### **3. Button Logic:**
- **Show Button**: When `initial_annual - annual_taken > 0`
- **Hide Button**: When no remaining balance
- **Same Logic**: For both Permanent and Contract

## Performance Considerations

### **Current Issues:**
- **Multiple Calculations**: Same calculation repeated in view
- **Database Queries**: Direct user model access in view
- **Complex Logic**: Nested conditions impact performance

### **Optimization:**
- **Pre-calculate**: All values in controller
- **Single Query**: Use service methods
- **Cache Results**: Store calculated values
- **Simplify Logic**: Reduce nested conditions

## Conclusion

View `indexNewAnnual.blade.php` memiliki logika yang kompleks dengan:

1. **4 Level Nested Conditions** - Hard to maintain
2. **Code Duplication** - Repeated calculations
3. **Business Logic in View** - Should be in controller
4. **Inconsistent Data Source** - Mix of direct calculations and controller data

**Rekomendasi:**
1. **Move Logic to Controller** - Pre-calculate all values
2. **Use Service Consistently** - Leverage LeaveBalanceService
3. **Simplify View** - Only display pre-calculated values
4. **Add Validation** - Ensure data consistency

Hasilnya akan menjadi sistem yang lebih maintainable, testable, dan konsisten.
