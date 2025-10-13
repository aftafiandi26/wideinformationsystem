<?php

namespace App\Services;

class StatusFormServices
{
	public static function StatusForm($stat)
    {
        $status = '--';

        if ($stat->ap_koor === 0) {
            $status = 'Pending Coordinator';
        } else {
            if ($stat->ap_spv === 0) {
                $status = 'Pending SPV';
            } else {
                if ($stat->ap_pm === 0) {
                    $status = 'Pending PM';
                } else {
                    if ($stat->ap_producer === 0) {
                        $status = 'Pending Producer';
                    } else {
                        if ($stat->ap_hd === 0) {
                            $status = 'Pending Head Of Department';
                        } else {
                            if ($stat->ver_hr === 0) {
                                $status = 'Pending HR';
                            } else {
                                if ($stat->ap_hrd === 0) {
                                    $status = 'Pending HR Manager';
                                } else {
                                    $status = 'Complete';
                                }
                            }
                        }
                    }
                }
            }
        }

        return $status;
    }
}


