<?php

namespace App\Services;

class HolidayService
{
    /**
     * Get static holidays data for Indonesia
     *
     * @param int $year
     * @return array
     */
    public static function getStaticHolidays($year)
    {
        $holidays = array();
        
        // Fixed holidays (same date every year)
        $fixedHolidays = array(
            array('date' => '01-01', 'name' => 'Tahun Baru'),
            array('date' => '05-01', 'name' => 'Hari Buruh Internasional'),
            array('date' => '08-17', 'name' => 'Hari Kemerdekaan RI'),
            array('date' => '12-25', 'name' => 'Hari Natal')
        );
        
        // Add fixed holidays
        foreach ($fixedHolidays as $holiday) {
            $holidays[] = array(
                'date' => $holiday['date'],
                'name' => $holiday['name'],
                'fullDate' => $year . '-' . $holiday['date']
            );
        }
        
        // Islamic holidays (approximate dates - may vary by 1-2 days)
        $islamicHolidays = self::getIslamicHolidays($year);
        $holidays = array_merge($holidays, $islamicHolidays);
        
        return $holidays;
    }
    
    /**
     * Get Islamic holidays for specific year
     *
     * @param int $year
     * @return array
     */
    public static function getIslamicHolidays($year)
    {
        $holidays = array();
        
        // These dates are approximate and may vary
        $islamicDates = array(
            2024 => array(
                // Fixed holidays
                array('date' => '01-01', 'name' => 'Tahun Baru Masehi'),
                array('date' => '05-01', 'name' => 'Hari Buruh Internasional'),
                array('date' => '08-17', 'name' => 'Hari Proklamasi Kemerdekaan RI'),
                array('date' => '12-25', 'name' => 'Hari Raya Natal'),
                
                // Islamic holidays
                array('date' => '03-11', 'name' => 'Hari Raya Idul Fitri 1445 H'),
                array('date' => '03-12', 'name' => 'Hari Raya Idul Fitri 1445 H'),
                array('date' => '04-10', 'name' => 'Wafat Isa Al Masih'),
                array('date' => '05-20', 'name' => 'Kenaikan Isa Al Masih'),
                array('date' => '06-16', 'name' => 'Hari Raya Idul Adha 1445 H'),
                array('date' => '07-07', 'name' => 'Tahun Baru Islam 1446 H'),
                array('date' => '09-15', 'name' => 'Maulid Nabi Muhammad SAW'),
                
                // Other national holidays
                array('date' => '02-10', 'name' => 'Tahun Baru Imlek 2575 Kongzili'),
                array('date' => '03-11', 'name' => 'Hari Raya Nyepi'),
                array('date' => '05-23', 'name' => 'Hari Raya Waisak 2568'),
                array('date' => '06-01', 'name' => 'Hari Lahirnya Pancasila'),
                
                // Hindu holidays (approximate dates for 2024)
                array('date' => '02-08', 'name' => 'Hari Saraswati'),
                array('date' => '04-22', 'name' => 'Penampahan Galungan'),
                array('date' => '04-23', 'name' => 'Hari Raya Galungan'),
                array('date' => '04-24', 'name' => 'Umanis Galungan'),
                array('date' => '05-03', 'name' => 'Hari Raya Kuningan'),
                array('date' => '09-06', 'name' => 'Hari Saraswati'),
                array('date' => '11-18', 'name' => 'Penampahan Galungan'),
                array('date' => '11-19', 'name' => 'Hari Raya Galungan'),
                array('date' => '11-20', 'name' => 'Umanis Galungan'),
                array('date' => '11-29', 'name' => 'Hari Raya Kuningan')
            ),
            2025 => array(
                // Fixed holidays
                array('date' => '01-01', 'name' => 'Tahun Baru Masehi'),
                array('date' => '05-01', 'name' => 'Hari Buruh Internasional'),
                array('date' => '08-17', 'name' => 'Hari Proklamasi Kemerdekaan RI'),
                array('date' => '12-25', 'name' => 'Hari Raya Natal'),
                
                // Islamic holidays
                array('date' => '01-27', 'name' => 'Isra Mikraj Nabi Muhammad SAW'),
                array('date' => '03-31', 'name' => 'Hari Raya Idul Fitri 1446 H'),
                array('date' => '04-01', 'name' => 'Hari Raya Idul Fitri 1446 H'),
                array('date' => '04-18', 'name' => 'Wafat Isa Al Masih'),
                array('date' => '05-29', 'name' => 'Kenaikan Isa Al Masih'),
                array('date' => '06-07', 'name' => 'Hari Raya Idul Adha 1446 H'),
                array('date' => '06-27', 'name' => 'Tahun Baru Islam 1447 H'),
                array('date' => '09-05', 'name' => 'Maulid Nabi Muhammad SAW'),
                
                // Other national holidays
                array('date' => '01-29', 'name' => 'Tahun Baru Imlek 2576 Kongzili'),
                array('date' => '03-29', 'name' => 'Hari Raya Nyepi'),
                array('date' => '05-12', 'name' => 'Hari Raya Waisak 2569'),
                array('date' => '06-01', 'name' => 'Hari Lahirnya Pancasila'),                
             
            ),
            2026 => array(
                // Fixed holidays
                array('date' => '01-01', 'name' => 'Tahun Baru Masehi'),
                array('date' => '05-01', 'name' => 'Hari Buruh Internasional'),
                array('date' => '08-17', 'name' => 'Hari Proklamasi Kemerdekaan RI'),
                array('date' => '12-25', 'name' => 'Hari Raya Natal'),
                
                // Islamic holidays
                array('date' => '03-21', 'name' => 'Hari Raya Idul Fitri 1447 H'),
                array('date' => '03-22', 'name' => 'Hari Raya Idul Fitri 1447 H'),
                array('date' => '04-03', 'name' => 'Wafat Isa Al Masih'),
                array('date' => '05-14', 'name' => 'Kenaikan Isa Al Masih'),
                array('date' => '05-26', 'name' => 'Hari Raya Idul Adha 1447 H'),
                array('date' => '06-16', 'name' => 'Tahun Baru Islam 1448 H'),
                array('date' => '09-04', 'name' => 'Maulid Nabi Muhammad SAW'),
                
                // Other national holidays
                array('date' => '01-29', 'name' => 'Tahun Baru Imlek 2577 Kongzili'),
                array('date' => '03-20', 'name' => 'Hari Raya Nyepi'),
                array('date' => '05-12', 'name' => 'Hari Raya Waisak 2570'),
                array('date' => '06-01', 'name' => 'Hari Lahirnya Pancasila'),                
              
            )
        );
        
        if (isset($islamicDates[$year])) {
            foreach ($islamicDates[$year] as $holiday) {
                $holidays[] = array(
                    'date' => $holiday['date'],
                    'name' => $holiday['name'],
                    'fullDate' => $year . '-' . $holiday['date']
                );
            }
        }
        
        return $holidays;
    }
    
    /**
     * Get all holidays for a specific year (fixed + Islamic)
     *
     * @param int $year
     * @return array
     */
    public static function getAllHolidays($year)
    {
        return self::getStaticHolidays($year);
    }
    
    /**
     * Check if a specific date is a holiday
     *
     * @param string $date (format: Y-m-d)
     * @return bool
     */
    public static function isHoliday($date)
    {
        $year = date('Y', strtotime($date));
        $monthDay = date('m-d', strtotime($date));
        
        $holidays = self::getAllHolidays($year);
        
        foreach ($holidays as $holiday) {
            if ($holiday['date'] === $monthDay) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get holiday information for a specific date
     *
     * @param string $date (format: Y-m-d)
     * @return array|null
     */
    public static function getHolidayInfo($date)
    {
        $year = date('Y', strtotime($date));
        $monthDay = date('m-d', strtotime($date));
        
        $holidays = self::getAllHolidays($year);
        
        foreach ($holidays as $holiday) {
            if ($holiday['date'] === $monthDay) {
                return $holiday;
            }
        }
        
        return null;
    }
    
    /**
     * Get holidays within a date range
     *
     * @param string $startDate (format: Y-m-d)
     * @param string $endDate (format: Y-m-d)
     * @return array
     */
    public static function getHolidaysInRange($startDate, $endDate)
    {
        $holidays = array();
        $currentDate = $startDate;
        
        while ($currentDate <= $endDate) {
            if (self::isHoliday($currentDate)) {
                $holidayInfo = self::getHolidayInfo($currentDate);
                if ($holidayInfo) {
                    $holidays[] = array(
                        'date' => date('Y-m-d', strtotime($currentDate)),
                        'name' => $holidayInfo['name'],
                        'displayDate' => date('d/m/Y', strtotime($currentDate))
                    );
                }
            }
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        
        return $holidays;
    }
    
    /**
     * Get holidays as JSON for JavaScript
     *
     * @param int $year
     * @return string
     */
    public static function getHolidaysJson($year)
    {
        $holidays = self::getAllHolidays($year);
        return json_encode($holidays);
    }
    
    /**
     * Get total number of holidays in a year
     *
     * @param int $year
     * @return int
     */
    public static function getTotalHolidays($year)
    {
        return count(self::getAllHolidays($year));
    }
    
    /**
     * Get holidays by month
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function getHolidaysByMonth($year, $month)
    {
        $allHolidays = self::getAllHolidays($year);
        $monthHolidays = array();
        
        foreach ($allHolidays as $holiday) {
            $holidayMonth = (int) substr($holiday['date'], 0, 2);
            if ($holidayMonth === $month) {
                $monthHolidays[] = $holiday;
            }
        }
        
        return $monthHolidays;
    }
}
