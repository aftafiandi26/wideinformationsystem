<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class HolidayController extends Controller
{
    /**
     * Get holiday data for a specific year
     *
     * @param int $year
     * @return JsonResponse
     */
    public function getHolidaysByYear($year)
    {

        try {
            $filePath = public_path("response/js/holiday/{$year}.js");

            if (!File::exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => "Holiday data for year {$year} not found"
                ], 404);
            }

            $content = File::get($filePath);

            // Parse the JavaScript array content
            $holidays = $this->parseHolidayData($content);

            return response()->json([
                'success' => true,
                'data' => $holidays,
                'year' => $year,
                'total' => count($holidays)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving holiday data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if a specific date is a holiday
     *
     * @param string $date (format: Y-m-d)
     * @return JsonResponse
     */
    public function checkHoliday($date)
    {
        try {
            $year = date('Y', strtotime($date));
            $holidays = $this->getHolidaysForYear($year);

            $isHoliday = false;
            $holidayInfo = null;

            foreach ($holidays as $holiday) {
                if ($holiday['holiday_date'] === $date) {
                    $isHoliday = true;
                    $holidayInfo = $holiday;
                    break;
                }
            }

            return response()->json([
                'success' => true,
                'date' => $date,
                'isHoliday' => $isHoliday,
                'holidayInfo' => $holidayInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking holiday: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get holidays within a date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return JsonResponse
     */
    public function getHolidaysInRange($startDate, $endDate)
    {
        try {
            $startYear = date('Y', strtotime($startDate));
            $endYear = date('Y', strtotime($endDate));
            $holidays = [];

            // Get holidays for all years in range
            for ($year = $startYear; $year <= $endYear; $year++) {
                $yearHolidays = $this->getHolidaysForYear($year);
                foreach ($yearHolidays as $holiday) {
                    if ($holiday['holiday_date'] >= $startDate && $holiday['holiday_date'] <= $endDate) {
                        $holidays[] = $holiday;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $holidays,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'total' => count($holidays)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving holidays in range: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get holidays by month
     *
     * @param int $year
     * @param int $month
     * @return JsonResponse
     */
    public function getHolidaysByMonth($year, $month)
    {
        try {
            $holidays = $this->getHolidaysForYear($year);
            $monthHolidays = [];

            foreach ($holidays as $holiday) {
                $holidayMonth = (int) date('n', strtotime($holiday['holiday_date']));
                if ($holidayMonth === $month) {
                    $monthHolidays[] = $holiday;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $monthHolidays,
                'year' => $year,
                'month' => $month,
                'total' => count($monthHolidays)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving holidays by month: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total number of holidays in a year
     *
     * @param int $year
     * @return JsonResponse
     */
    public function getTotalHolidays($year)
    {
        try {
            $holidays = $this->getHolidaysForYear($year);

            return response()->json([
                'success' => true,
                'year' => $year,
                'total' => count($holidays)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting total holidays: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available years for holiday data
     *
     * @return JsonResponse
     */
    public function getAvailableYears()
    {
        try {
            $holidayDir = public_path('response/js/holiday');
            $files = File::files($holidayDir);
            $years = [];

            foreach ($files as $file) {
                $filename = $file->getFilename();
                if (preg_match('/^(\d{4})\.js$/', $filename, $matches)) {
                    $years[] = (int) $matches[1];
                }
            }

            sort($years);

            return response()->json([
                'success' => true,
                'data' => $years
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting available years: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get holidays for a specific year (internal method)
     *
     * @param int $year
     * @return array
     */
    private function getHolidaysForYear($year)
    {
        $filePath = public_path("response/js/holiday/{$year}.js");

        if (!File::exists($filePath)) {
            return [];
        }

        $content = File::get($filePath);
        return $this->parseHolidayData($content);
    }

    /**
     * Parse holiday data from JavaScript file content
     *
     * @param string $content
     * @return array
     */
    private function parseHolidayData($content)
    {
        try {
            // Remove any trailing semicolon
            $content = trim($content);
            if (substr($content, -1) === ';') {
                $content = substr($content, 0, -1);
            }

            // Convert JavaScript object syntax to JSON
            // Step 1: Add quotes around object keys
            $content = preg_replace('/(holiday_date|holiday_name|is_national_holiday):/', '"$1":', $content);

            // Step 2: Remove trailing commas before closing brackets/braces
            $content = preg_replace('/,(\s*[}\]])/', '$1', $content);

            // Step 3: Parse as JSON
            $decoded = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }
            return $decoded ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
