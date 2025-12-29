<?php

namespace App\Services;

use App\User;

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

    public static function StatusEmail($stat)
    {
        $status = Null;
        $verify = User::select('email')->where('hr', true)->where('active', true)->first();
        $hr = User::select('email')->where('hrd', true)->first();

        if ($stat->ap_koor === 0) {
            $status = $stat->email_koor;
        } else {
            if ($stat->ap_spv === 0) {
                $status = $stat->email_spv;
            } else {
                if ($stat->ap_pm === 0) {
                    $status = $stat->email_pm;
                } else {
                    if ($stat->ap_producer === 0) {
                        $status = $stat->email_producer;
                    } else {
                        if ($stat->ap_hd === 0) {
                            $status = $stat->email_pm;
                        } else {
                            if ($stat->ver_hr === 0) {
                                $status = $verify->email;
                            } else {
                                if ($stat->ap_hrd === 0) {
                                    $status = $hr->email;
                                } else {
                                    $status = Null;
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
