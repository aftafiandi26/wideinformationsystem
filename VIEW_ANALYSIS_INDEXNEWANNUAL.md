# Analisis View indexNewAnnual.blade.php

## Overview
Analisis mendalam terhadap logika perhitungan yang digunakan di view `indexNewAnnual.blade.php` untuk menampilkan balance cuti tahunan.

## Struktur Perhitungan

### 1. **Kondisi Forfeit Case**

#### **Jika `forfeitcase === 1` (Tidak ada forfeited):**
```php
<?php if (auth::user()->forfeitcase === 1): ?>
    {{ $user->initial_annual - $annual->transactionAnnual }}
```

**Formula:** `initial_annual - annual_taken`

#### **Jika `forfeitcase !== 1` (Ada forfeited):**
```php
<?php else: ?>
    {{ $user->initial_annual - $annual->transactionAnnual - $bla }}
```

**Formula:** `initial_annual - annual_taken - forfeited_amount`

### 2. **Breakdown Perhitungan (Detail dalam Parentheses)**

#### **Untuk Forfeit Case = 1:**

**Permanent Employees:**
```php
<?php if (auth::user()->emp_status === "Permanent"): ?>
    ( <span style="color: green;">{{ $totalAnnualPermanent1 }}</span> +
    {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnualPermanent1 }} )
```

**Contract Employees:**
```php
<?php else: ?>
    ( <span style="color: green;">{{ $totalAnnual }}</span> +
    {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnual }} )
```

#### **Untuk Forfeit Case ≠ 1:**

**Permanent Employees:**
```php
<?php if (auth::user()->emp_status === "Permanent"): ?>
    ( <span style="color: green;">{{ $renewPermanet }}</span> +
    {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnualPermanent1 }} )
```

**Contract Employees:**
```php
<?php else: ?>
    ( <span style="color: green;">{{ $renewContract }}</span> +
    {{ $user->initial_annual - $annual->transactionAnnual - $totalAnnual }} )
```

## Analisis Logika

### **Struktur Kondisi:**

```
1. Check forfeitcase
   ├── forfeitcase === 1 (No forfeited)
   │   ├── Basic calculation: initial_annual - annual_taken
   │   └── Breakdown by employment status
   │       ├── Permanent: (totalAnnualPermanent1 + remainder)
   │       └── Contract: (totalAnnual + remainder)
   └── forfeitcase !== 1 (Has forfeited)
       ├── Adjusted calculation: initial_annual - annual_taken - forfeited
       └── Breakdown by employment status
           ├── Permanent: (renewPermanet + remainder)
           └── Contract: (renewContract + remainder)
```

### **Variabel yang Digunakan:**

| Variable | Description | Source |
|----------|-------------|---------|
| `$user->initial_annual` | Initial annual entitlement | User model |
| `$annual->transactionAnnual` | Annual leave taken | Controller calculation |
| `$bla` | Forfeited amount | Controller calculation |
| `$totalAnnualPermanent1` | Permanent annual balance | Controller calculation |
| `$totalAnnual` | Contract annual balance | Controller calculation |
| `$renewPermanet` | Renewed permanent balance | Controller calculation |
| `$renewContract` | Renewed contract balance | Controller calculation |

## Mapping dengan LeaveBalanceService

### **Current View Logic vs Service Logic:**

#### **1. Basic Annual Balance:**
**View:** `$user->initial_annual - $annual->transactionAnnual`
**Service:** `calculateAnnualLeaveBalance()` → `total_annual` atau `total_annual_permanent`

#### **2. Forfeited Adjustment:**
**View:** `$user->initial_annual - $annual->transactionAnnual - $bla`
**Service:** `calculateFinalBalance()` → `renew_permanent` atau `renew_contract`

#### **3. Breakdown Display:**
**View:** Shows green value + remainder
**Service:** Can provide same breakdown through service methods

## Identifikasi Masalah

### **1. Inconsistency:**
- View menggunakan logika manual yang berbeda dengan service
- Perhitungan tidak konsisten antara view dan controller
- Variabel naming tidak konsisten (`$bla`, `$renewPermanet`)

### **2. Complexity:**
- Logika yang kompleks di view (seharusnya di controller)
- Multiple nested conditions
- Hard to maintain dan debug

### **3. Duplication:**
- Perhitungan yang sama dilakukan di view dan controller
- Logic duplication antara view dan service

## Rekomendasi Perbaikan

### **1. Refactor View Logic:**
```php
<!-- Instead of complex PHP logic in view -->
<td>{{ $finalAnnualBalance }}</td>
<td>
    ( <span style="color: green;">{{ $availableBalance }}</span> + 
    {{ $remainingBalance }} )
</td>
```

### **2. Controller Preparation:**
```php
// In controller, prepare all needed values
$viewData = [
    'finalAnnualBalance' => $finalBalance['renew_permanent'] ?? $finalBalance['renew_contract'],
    'availableBalance' => $availableBalance,
    'remainingBalance' => $remainingBalance,
    'forfeitcase' => $user->forfeitcase,
    'emp_status' => $user->emp_status
];
```

### **3. Service Integration:**
```php
// Use service methods consistently
$annualBalance = $this->leaveBalanceService->calculateAnnualLeaveBalance($user, $annualTaken);
$forfeitedData = $this->leaveBalanceService->calculateForfeitedLeave($userId);
$finalBalance = $this->leaveBalanceService->calculateFinalBalance($annualBalance, $forfeitedData, $user->emp_status);
```

## Implementasi yang Disarankan

### **1. Controller Method:**
```php
public function indexNewApply()
{
    $userId = Auth::user()->id;
    $user = User::find($userId);
    
    // Get all balance data using service
    $annualTaken = $this->leaveBalanceService->getAnnualLeaveTaken($userId);
    $annualBalance = $this->leaveBalanceService->calculateAnnualLeaveBalance($user, $annualTaken);
    $exdoBalance = $this->leaveBalanceService->calculateExdoBalance($userId);
    $forfeitedData = $this->leaveBalanceService->calculateForfeitedLeave($userId);
    $finalBalance = $this->leaveBalanceService->calculateFinalBalance($annualBalance, $forfeitedData, $user->emp_status);
    
    // Prepare view data
    $viewData = [
        'user' => $user,
        'annual' => (object)['transactionAnnual' => $annualTaken],
        'finalAnnualBalance' => $user->emp_status === 'Permanent' 
            ? $finalBalance['renew_permanent'] 
            : $finalBalance['renew_contract'],
        'availableBalance' => $user->emp_status === 'Permanent'
            ? $annualBalance['total_annual_permanent']
            : $annualBalance['total_annual'],
        'remainingBalance' => $user->initial_annual - $annualTaken - $finalBalance['renew_permanent'],
        'forfeitcase' => $user->forfeitcase,
        'emp_status' => $user->emp_status,
        // ... other data
    ];
    
    return view('leave.NewAnnual.indexNewAnnual', $viewData);
}
```

### **2. Simplified View:**
```php
<td>Total Annual Leave <sup>(until EOC)</sup></td>
<td>
    <span>{{ $finalAnnualBalance }}</span>
    @if($forfeitcase === 1)
        ( <span style="color: green;">{{ $availableBalance }}</span> + {{ $remainingBalance }} )
    @else
        ( <span style="color: green;">{{ $finalAnnualBalance }}</span> + {{ $remainingBalance }} )
    @endif
</td>
```

## Benefits of Refactoring

### **1. Consistency:**
- ✅ Semua perhitungan menggunakan service yang sama
- ✅ Value yang konsisten di seluruh aplikasi
- ✅ Logic terpusat di satu tempat

### **2. Maintainability:**
- ✅ View lebih clean dan mudah dibaca
- ✅ Logic bisnis di controller, bukan di view
- ✅ Mudah untuk testing dan debugging

### **3. Performance:**
- ✅ Perhitungan dilakukan sekali di controller
- ✅ View hanya menampilkan data yang sudah dihitung
- ✅ Mengurangi kompleksitas di frontend

### **4. Reliability:**
- ✅ Menggunakan service yang sudah teruji
- ✅ Error handling yang konsisten
- ✅ Validasi yang proper

## Conclusion

View `indexNewAnnual.blade.php` memiliki logika perhitungan yang kompleks dan tidak konsisten dengan service yang sudah direfactor. Perlu dilakukan refactoring untuk:

1. **Memindahkan logic ke controller**
2. **Menggunakan LeaveBalanceService secara konsisten**
3. **Menyederhanakan view**
4. **Memastikan consistency dengan controller lain**

Hasilnya akan menjadi sistem yang lebih maintainable, reliable, dan konsisten.
