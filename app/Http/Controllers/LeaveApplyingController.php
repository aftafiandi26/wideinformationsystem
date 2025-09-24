<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Initial_Leave;
use App\Leave;
use App\Forfeited;
use App\ForfeitedCounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\Datatables\Facades\Datatables;

class LeaveApplyingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active']);
    }

    public function indexNewApply()
    {
        // Hitung sisa hak cuti untuk user yang sedang login
        $leaveBalance = $this->calculateLeaveBalance();
        
        // Hitung sisa hak Exdo untuk user yang sedang login
        $exdoBalance = $this->calculateExdoBalance();

        return view('leave.NewAnnual.LeaveApplying.index', compact('leaveBalance', 'exdoBalance'));
    }

    /**
     * Menghitung sisa hak cuti untuk user
     * Untuk karyawan kontrak: dari join_date sampai end_date
     * Untuk karyawan tetap: dari join_date sampai akhir tahun ini
     */
    private function calculateLeaveBalance($userId = null)
    {
        // Gunakan user yang sedang login jika tidak ada user ID yang diberikan
        $userId = $userId ?: Auth::id();
        $user = User::find($userId);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Data Not Found',
                'balance' => 0
            ];
        }

        try {
            // Validasi data user
            if (!$user->join_date || !$user->initial_annual) {
                return [
                    'success' => false,
                    'message' => 'User data incomplete (missing join_date or initial_annual)',
                    'balance' => 0
                ];
            }
            
            $joinDate = Carbon::parse($user->join_date);
            $nowDate = Carbon::now();
            
            // Validasi tanggal
            if ($joinDate->gt($nowDate)) {
                return [
                    'success' => false,
                    'message' => 'Join date cannot be in the future',
                    'balance' => 0
                ];
            }
            
            // Gunakan initial_annual dari database sebagai total hak cuti
            $entitledDays = (float) $user->initial_annual;
            
            // Ambil total cuti yang sudah diambil (hanya yang sudah disetujui)
            $takenDays = (float) Leave::where('user_id', $userId)
                ->where('leave_category_id', 1) // Kategori cuti tahunan
                ->where('formstat', 1) // Status disetujui
                ->sum('total_day');
            
            // Hitung sisa saldo (total hak cuti - yang sudah diambil)
            $remainingBalance = max(0.0, $entitledDays - $takenDays);
            
            // Hitung annual_available berdasarkan bulan yang sudah berjalan sampai sekarang
            // Menggunakan diffInMonths untuk akurasi yang lebih baik
            $totalMonthsNow = (float) $joinDate->diffInMonths($nowDate);   
            
            $totalAnnualAvailable = max(0.0, $totalMonthsNow);
            $annualAvailable = max(0.0, $totalAnnualAvailable - $takenDays);
            
            return [
                'total_annual' => $entitledDays,
                'annual_taken' => $takenDays,
                'annual_balance' => $remainingBalance,
                'total_annual_available' => $totalAnnualAvailable,
                'annual_available' => $annualAvailable,
                'totalMonthsNow' => $totalMonthsNow,
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error calculating leave balance: ' . $e->getMessage(),
                'balance' => 0
            ];
        }
    }

    /**
     * Ambil saldo cuti untuk user yang sedang login
     */
    public function getMyLeaveBalance()
    {
        return response()->json($this->calculateLeaveBalance());
    }

    /**
     * Ambil saldo cuti untuk user tertentu (untuk admin/HR)
     */
    public function getUserLeaveBalance($userId)
    {
        return response()->json($this->calculateLeaveBalance($userId));
    }

    /**
     * Menghitung sisa hak Exdo (Extra Day Off) untuk user
     */
    private function calculateExdoBalance($userId = null)
    {
        // Gunakan user yang sedang login jika tidak ada user ID yang diberikan
        $userId = $userId ?: Auth::id();
        $user = User::find($userId);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Data Not Found',
                'balance' => 0
            ];
        }

        try {
            // Ambil semua Exdo yang dimiliki user
            $exdoRecords = Initial_Leave::where('user_id', $userId)
                ->where('leave_category_id', 2) // Kategori Exdo
                ->where('initial', '>', 0)
                ->get();
            
            $totalExdo = $exdoRecords->sum('initial');
            
            // Hitung Exdo yang sudah expired
            $currentDate = Carbon::now();
            $expiredExdo = 0;
            $validExdo = 0;
            
            foreach ($exdoRecords as $record) {
                if ($record->expired && Carbon::parse($record->expired)->lt($currentDate)) {
                    $expiredExdo += $record->initial;
                } else {
                    $validExdo += $record->initial;
                }
            }
            
            // Ambil total Exdo yang sudah diambil (hanya yang sudah disetujui)
            $takenExdo = (float) Leave::where('user_id', $userId)
                ->where('leave_category_id', 2) // Kategori Exdo
                ->where('formstat', 1) // Status disetujui
                ->sum('total_day');
            
            // Hitung sisa Exdo yang masih valid
            $remainingExdo = max(0.0, $validExdo - $takenExdo);
            
            // Hitung Exdo yang akan expired dalam 30 hari ke depan
            $expiringSoon = 0;
            $expiringDate = Carbon::now()->addDays(30);
            
            foreach ($exdoRecords as $record) {
                if ($record->expired && 
                    Carbon::parse($record->expired)->between($currentDate, $expiringDate)) {
                    $expiringSoon += $record->initial;
                }
            }

            $expiredExdo = $expiredExdo - $takenExdo;

            if ($expiredExdo < 0) { 
                $expiredExdo = 0;
            }
            
            return [
                'total_exdo' => $totalExdo,
                'valid_exdo' => $validExdo,
                'expired_exdo' => $expiredExdo,
                'taken_exdo' => $takenExdo,
                'remaining_exdo' => $remainingExdo,
                'expiring_soon' => $expiringSoon,
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error calculating Exdo balance: ' . $e->getMessage(),
                'balance' => 0
            ];
        }
    }

    /**
     * Ambil saldo Exdo untuk user yang sedang login
     */
    public function getMyExdoBalance()
    {
        return response()->json($this->calculateExdoBalance());
    }

    /**
     * Ambil saldo Exdo untuk user tertentu (untuk admin/HR)
     */
    public function getUserExdoBalance($userId)
    {
        return response()->json($this->calculateExdoBalance($userId));
    }

    public function getExdoList()
    {
        $query = Initial_Leave::where('user_id', auth()->user()->id)
            ->where('leave_category_id', 2)
            ->where('initial', '>', 0)
            ->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('expired', function (Initial_Leave $initial) {
                return $initial->expired ? Carbon::parse($initial->expired)->format('Y-m-d') : '-';
            })
            ->addColumn('limit', function (Initial_Leave $initial) {
                if ($initial->expired) {
                    $expiredDate = Carbon::parse($initial->expired);
                    $now = Carbon::now();
                    
                    // Tentukan apakah sudah expired atau belum
                    if ($expiredDate->lt($now)) {
                        return '<span class="text-danger">Expired ' . $expiredDate->diffForHumans() . '</span>';
                    } else {
                        return '<span class="text-warning">Expires ' . $expiredDate->diffForHumans() . '</span>';
                    }
                }
                return '<span class="text-muted">No limit</span>';
            })
            ->addColumn('note', function (Initial_Leave $initial) {
                return $initial->note ?: '-';
            })
            ->rawColumns(['limit']) // Allow HTML in limit column
            ->make(true);
    }

}
