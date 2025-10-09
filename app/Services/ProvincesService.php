<?php

namespace App\Services;

class ProvincesService
{
    /**
     * Get all Indonesian provinces data
     *
     * @return array
     */
    public static function getAllProvinces()
    {
        return [
            ['id' => '11', 'name' => 'ACEH', 'region' => 'Sumatera'],
            ['id' => '12', 'name' => 'SUMATERA UTARA', 'region' => 'Sumatera'],
            ['id' => '13', 'name' => 'SUMATERA BARAT', 'region' => 'Sumatera'],
            ['id' => '14', 'name' => 'RIAU', 'region' => 'Sumatera'],
            ['id' => '15', 'name' => 'JAMBI', 'region' => 'Sumatera'],
            ['id' => '16', 'name' => 'SUMATERA SELATAN', 'region' => 'Sumatera'],
            ['id' => '17', 'name' => 'BENGKULU', 'region' => 'Sumatera'],
            ['id' => '18', 'name' => 'LAMPUNG', 'region' => 'Sumatera'],
            ['id' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG', 'region' => 'Sumatera'],
            ['id' => '21', 'name' => 'KEPULAUAN RIAU', 'region' => 'Sumatera'],
            ['id' => '31', 'name' => 'DKI JAKARTA', 'region' => 'Jawa'],
            ['id' => '32', 'name' => 'JAWA BARAT', 'region' => 'Jawa'],
            ['id' => '33', 'name' => 'JAWA TENGAH', 'region' => 'Jawa'],
            ['id' => '34', 'name' => 'DI YOGYAKARTA', 'region' => 'Jawa'],
            ['id' => '35', 'name' => 'JAWA TIMUR', 'region' => 'Jawa'],
            ['id' => '36', 'name' => 'BANTEN', 'region' => 'Jawa'],
            ['id' => '51', 'name' => 'BALI', 'region' => 'Nusa Tenggara'],
            ['id' => '52', 'name' => 'NUSA TENGGARA BARAT', 'region' => 'Nusa Tenggara'],
            ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR', 'region' => 'Nusa Tenggara'],
            ['id' => '61', 'name' => 'KALIMANTAN BARAT', 'region' => 'Kalimantan'],
            ['id' => '62', 'name' => 'KALIMANTAN TENGAH', 'region' => 'Kalimantan'],
            ['id' => '63', 'name' => 'KALIMANTAN SELATAN', 'region' => 'Kalimantan'],
            ['id' => '64', 'name' => 'KALIMANTAN TIMUR', 'region' => 'Kalimantan'],
            ['id' => '65', 'name' => 'KALIMANTAN UTARA', 'region' => 'Kalimantan'],
            ['id' => '71', 'name' => 'SULAWESI UTARA', 'region' => 'Sulawesi'],
            ['id' => '72', 'name' => 'SULAWESI TENGAH', 'region' => 'Sulawesi'],
            ['id' => '73', 'name' => 'SULAWESI SELATAN', 'region' => 'Sulawesi'],
            ['id' => '74', 'name' => 'SULAWESI TENGGARA', 'region' => 'Sulawesi'],
            ['id' => '75', 'name' => 'GORONTALO', 'region' => 'Sulawesi'],
            ['id' => '76', 'name' => 'SULAWESI BARAT', 'region' => 'Sulawesi'],
            ['id' => '81', 'name' => 'MALUKU', 'region' => 'Maluku'],
            ['id' => '82', 'name' => 'MALUKU UTARA', 'region' => 'Maluku'],
            ['id' => '91', 'name' => 'PAPUA BARAT', 'region' => 'Papua'],
            ['id' => '94', 'name' => 'PAPUA', 'region' => 'Papua']
        ];
    }

    /**
     * Get provinces grouped by region
     *
     * @return array
     */
    public static function getProvincesByRegion()
    {
        $provinces = self::getAllProvinces();
        $grouped = [];

        foreach ($provinces as $province) {
            $region = $province['region'];
            if (!isset($grouped[$region])) {
                $grouped[$region] = [];
            }
            $grouped[$region][] = $province;
        }

        return $grouped;
    }

    /**
     * Get provinces as JSON for JavaScript
     *
     * @return string
     */
    public static function getProvincesJson()
    {
        return json_encode(self::getAllProvinces());
    }

    /**
     * Get provinces grouped by region as JSON for JavaScript
     *
     * @return string
     */
    public static function getProvincesByRegionJson()
    {
        return json_encode(self::getProvincesByRegion());
    }

    /**
     * Get province by ID
     *
     * @param string $id
     * @return array|null
     */
    public static function getProvinceById($id)
    {
        $provinces = self::getAllProvinces();
        
        foreach ($provinces as $province) {
            if ($province['id'] === $id) {
                return $province;
            }
        }
        
        return null;
    }

    /**
     * Get province by name
     *
     * @param string $name
     * @return array|null
     */
    public static function getProvinceByName($name)
    {
        $provinces = self::getAllProvinces();
        
        foreach ($provinces as $province) {
            if ($province['name'] === $name) {
                return $province;
            }
        }
        
        return null;
    }

    /**
     * Get provinces by region
     *
     * @param string $region
     * @return array
     */
    public static function getProvincesByRegionName($region)
    {
        $provinces = self::getAllProvinces();
        $filtered = [];

        foreach ($provinces as $province) {
            if ($province['region'] === $region) {
                $filtered[] = $province;
            }
        }

        return $filtered;
    }

    /**
     * Get all regions
     *
     * @return array
     */
    public static function getAllRegions()
    {
        $provinces = self::getAllProvinces();
        $regions = [];

        foreach ($provinces as $province) {
            if (!in_array($province['region'], $regions)) {
                $regions[] = $province['region'];
            }
        }

        return $regions;
    }

    /**
     * Get total provinces count
     *
     * @return int
     */
    public static function getTotalProvinces()
    {
        return count(self::getAllProvinces());
    }

    /**
     * Search provinces by name
     *
     * @param string $query
     * @return array
     */
    public static function searchProvinces($query)
    {
        $provinces = self::getAllProvinces();
        $results = [];

        foreach ($provinces as $province) {
            if (stripos($province['name'], $query) !== false) {
                $results[] = $province;
            }
        }

        return $results;
    }
}
