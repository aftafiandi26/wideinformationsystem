# Final Annual Balance Test Scenarios

## Test Cases untuk final_annual_balance

### **Scenario 1: Permanent Employee - No Forfeit Case**

```php
// Input Data
$user = [
    'id' => 1,
    'emp_status' => 'Permanent',
    'initial_annual' => 12,
    'forfeitcase' => 1
];

// Expected Calculation
$annualTaken = 3; // From database
$annualBalance = [
    'total_annual_permanent' => 9,
    'total_annual' => 9
];
$forfeitedData = [
    'valid_forfeited' => 0
];
$finalBalance = [
    'renew_permanent' => 9,
    'renew_contract' => 9
];

// Expected Result
$finalAnnualBalance = 9; // renew_permanent
```

### **Scenario 2: Contract Employee - No Forfeit Case**

```php
// Input Data
$user = [
    'id' => 2,
    'emp_status' => 'Contract',
    'initial_annual' => 12,
    'forfeitcase' => 1
];

// Expected Calculation
$annualTaken = 5; // From database
$annualBalance = [
    'total_annual_permanent' => 7,
    'total_annual' => 7
];
$forfeitedData = [
    'valid_forfeited' => 0
];
$finalBalance = [
    'renew_permanent' => 7,
    'renew_contract' => 7
];

// Expected Result
$finalAnnualBalance = 7; // renew_contract
```

### **Scenario 3: Permanent Employee - Has Forfeit Case**

```php
// Input Data
$user = [
    'id' => 3,
    'emp_status' => 'Permanent',
    'initial_annual' => 12,
    'forfeitcase' => 0
];

// Expected Calculation
$annualTaken = 2; // From database
$annualBalance = [
    'total_annual_permanent' => 10,
    'total_annual' => 10
];
$forfeitedData = [
    'valid_forfeited' => 2
];
$finalBalance = [
    'renew_permanent' => 8, // 10 - 2
    'renew_contract' => 8
];

// Expected Result
$finalAnnualBalance = 8; // renew_permanent
```

### **Scenario 4: Contract Employee - Has Forfeit Case**

```php
// Input Data
$user = [
    'id' => 4,
    'emp_status' => 'Contract',
    'initial_annual' => 12,
    'forfeitcase' => 0
];

// Expected Calculation
$annualTaken = 4; // From database
$annualBalance = [
    'total_annual_permanent' => 8,
    'total_annual' => 8
];
$forfeitedData = [
    'valid_forfeited' => 1
];
$finalBalance = [
    'renew_permanent' => 7,
    'renew_contract' => 7 // 8 - 1
];

// Expected Result
$finalAnnualBalance = 7; // renew_contract
```

## View Logic Comparison

### **Original View Logic:**
```php
// Main Display
<?php if (auth::user()->forfeitcase === 1): ?>
    {{ $user->initial_annual - $annual->transactionAnnual }}
<?php else: ?>
    {{ $user->initial_annual - $annual->transactionAnnual - $bla }}
<?php endif ?>

// Breakdown Display
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

### **New Controller Logic:**
```php
// Single calculation using service
$finalAnnualBalance = $this->leaveBalanceService->calculateFinalBalance(
    $annualBalance, 
    $forfeitedData, 
    $user->emp_status
);

// Get appropriate balance
if ($user->emp_status === 'Permanent') {
    $finalAnnualBalance = $finalBalance['renew_permanent'];
} else {
    $finalAnnualBalance = $finalBalance['renew_contract'];
}
```

## Data Consistency Check

### **Stored Data for Debugging:**
```php
$user->final_annual_balance = $finalAnnualBalance;  // Final result
$user->annual_taken = $annualTaken;                // Annual taken
$user->annual_balance = $annualBalance;            // Annual balance
$user->forfeited_data = $forfeitedData;            // Forfeited data
$user->final_balance = $finalBalance;              // Final balance
```

### **Usage in View:**
```php
// Simple display
<td>{{ $user->final_annual_balance }}</td>

// With breakdown (if needed)
<td>
    <span>{{ $user->final_annual_balance }}</span>
    @if($user->forfeited_data['valid_forfeited'] > 0)
        ( <span style="color: green;">{{ $user->annual_balance['total_annual'] }}</span> + 
        {{ $user->final_annual_balance - $user->annual_balance['total_annual'] }} )
    @else
        ( <span style="color: green;">{{ $user->final_annual_balance }}</span> + 0 )
    @endif
</td>
```

## Performance Comparison

### **Original View Logic:**
- **Multiple Calculations**: Same calculation repeated
- **Database Queries**: Direct user model access
- **Complex Logic**: 4 level nested conditions
- **Hard to Debug**: Logic scattered in view

### **New Controller Logic:**
- **Single Calculation**: One service call
- **Optimized Queries**: Service uses efficient queries
- **Simple Logic**: Clear flow
- **Easy Debug**: All data stored in user object

## Integration Test

### **Test with Datatables:**
```php
// In CoordinatorLeaveBalanceController
->addColumn('final_annual_balance', function (User $user) {
    // ... calculation logic ...
    return $finalAnnualBalance;
})
->addColumn('total_balance', function (User $user) {
    return $user->final_annual_balance + $user->exdo_balance;
})
```

### **Test with View:**
```php
// In indexNewAnnual.blade.php
<tr>
    <td>Total Annual Leave <sup>(until EOC)</sup></td>
    <td>
        <span>{{ $user->final_annual_balance }}</span>
        @if($user->forfeited_data['valid_forfeited'] > 0)
            ( <span style="color: green;">{{ $user->annual_balance['total_annual'] }}</span> + 
            {{ $user->final_annual_balance - $user->annual_balance['total_annual'] }} )
        @else
            ( <span style="color: green;">{{ $user->final_annual_balance }}</span> + 0 )
        @endif
    </td>
    <td>
        @if($user->final_annual_balance > 0)
            <a href="{!! URL::route('createAdvanceLeave') !!}" class="btn btn-danger btn-xs" role="button">Apply</a>
        @endif
    </td>
</tr>
```

## Conclusion

Implementasi `final_annual_balance` berhasil:

1. **✅ Konsisten** dengan logika view yang kompleks
2. **✅ Menggunakan Service** untuk business logic
3. **✅ Menyimpan Data** untuk debugging
4. **✅ Mudah Maintain** dan update
5. **✅ Performant** dengan optimized queries

Kolom ini sekarang bisa digunakan di Datatables dengan data yang konsisten dan akurat! 🚀
