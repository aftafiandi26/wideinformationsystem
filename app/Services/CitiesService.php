<?php

namespace App\Services;

class CitiesService
{
    /**
     * Get cities data from JavaScript file
     *
     * @param string $id
     * @return string
     */
    public static function getCitiesDataFromJs($id)
    {
        $data = asset('response/js/hometown/' . $id . '.js');

        try {
            $response = file_get_contents($data);
            $response = json_decode($response, true);
            $return = $response;
        } catch (\Exception $error) {
            $return = null;
        }

        return $return;
    }

    /**
     * Get cities data from JavaScript file with full URL
     *
     * @param string $id
     * @return string
     */
    public static function getCitiesDataUrl($id)
    {
        $data =  asset('response/js/hometown/' . $id . '.js');

        try {
            $response = file_get_contents($data);
            $response = json_decode($response, true);
            $return = $response;
        } catch (\Exception $error) {
            $return = null;
        }

        return $return;
    }

    /**
     * Get cities data from JavaScript file with base URL
     *
     * @param string $id
     * @return string
     */
    public static function getCitiesDataPath($id)
    {
        $data =  asset('response/js/hometown/' . $id . '.js');
        
        try {
            $response = file_get_contents($data);
            $response = json_decode($response, true);
            $return = $response;
        } catch (\Exception $error) {
            $return = null;
        }

        return $return;
    }
}