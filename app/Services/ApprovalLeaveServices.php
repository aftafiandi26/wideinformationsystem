<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApprovalLeaveServices
{
    /**
     * Get head of department for approval
     *
     * @param int $deptCategoryId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getHeadOfDepartment($deptCategoryId)
    {
        return User::where('active', 1)
            ->where('hd', 1)
            ->where('dept_category_id', $deptCategoryId)
            ->get();
    }

    public static function getProducer($deptCategoryId)
    {
        return User::where('active', 1)
            ->where('producer', 1)
            ->where('dept_category_id', $deptCategoryId)
            ->get();
    }

    public static function getSupervisor($deptCategoryId)
    {
        return User::where('active', 1)
            ->where('spv', 1)
            ->where('dept_category_id', $deptCategoryId)
            ->get();
    }

    public static function getCoordinator($deptCategoryId)
    {
        return User::where('active', 1)
            ->where('koor', 1)
            ->where('dept_category_id', $deptCategoryId)
            ->get();
    }

    public static function getITCoordinator($deptCategoryId)
    {
        return User::where('active', 1)
            ->where('koor', 1)
            ->where('dept_category_id', $deptCategoryId)
            ->get();
    }

    public static function getGM()
    {
        return User::where('active', 1)
            ->where('gm', 1)
            ->get();
    }

    /**
     * Determine approval rules for leave application based on department and user role
     *
     * @param string $emailCoor Coordinator email
     * @param string $emailSPV Supervisor email  
     * @param string $emailPM Project Manager email
     * @param string $emailProducer Producer email
     * @return array Approval configuration
     */
    public function ruleFormLeave($emailCoor, $emailSPV, $emailPM, $emailProducer)
    {
        // Initialize approval variables
        $email_koor = null;
        $email_spv = null;
        $email_pm = null;
        $email_producer = null;

        // Approval status flags
        $ap_gm = 0;
        $ap_pipeline = 0;
        $ap_spv = 0;
        $ap_koor = 0;
        $ap_pm = 0;
        $ap_producer = 0;
        $ap_hd = 0;
        $ap_infinite = 0;

        // Approval dates
        $date_ap_gm = null;
        $date_ap_pipeline = null;
        $date_ap_spv = null;
        $date_ap_koor = null;
        $date_ap_pm = null;
        $date_producer = null;
        $date_ap_hd = null;
        $date_ap_infinite = null;

        $currentDate = date("Y-m-d");

        // Helper function to set approval status and date
        $setApprovalStatus = function ($status, $date = null) use ($currentDate) {
            return $date ?: $currentDate;
        };

        // ========================================
        // DEPARTMENT-BASED APPROVAL RULES
        // ========================================

        // Department 1: IT Department
        if (Auth::user()->dept_category_id === 1) {
            if (Auth::user()->hd === 1) {
                // HOD -> GM (mike) -> Ver_hr -> HRD ->
                // HOD -> FA Manager -> GM (ghea) -> Ver_hr -> HRD
                // HOD -> FA Manager -> Ver_hr -> HRD

                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                // $ap_producer = 1;
                // $date_producer  = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                $email_pm = $emailPM;
                $email_producer = $emailProducer;
            } else {
                // Employee -> HOD -> Ver_HR -> HRD
                if (Auth::user()->stat_officer === 0) {
                    $ap_koor = 1;
                    $date_ap_koor = date("Y-m-d");
                }

                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                if (Auth::user()->stat_officer === 1) {
                    $email_koor = $emailCoor;
                }
                $email_pm = $emailPM;
            }
        }
        // Department 2: Finance Department
        elseif (Auth::user()->dept_category_id === 2) {
            if (Auth::user()->hd === 1) {
                // HOD -> Sow Kim -> Ver_HR -> HRD -> GM (mike)
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                // $ap_producer = 1;
                // $date_producer  = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                $email_producer = $emailProducer;

                // temp for assist GM
                if (Auth::user()->assistGM === 1) {
                    $ap_pipeline = 1;
                    $date_ap_pipeline = date("Y-m-d");
                    $ap_spv = 1;
                    $date_ap_spv = date("Y-m-d");
                    $ap_koor = 1;
                    $date_ap_koor = date("Y-m-d");
                    $ap_pm = 1;
                    $date_ap_pm = date("Y-m-d");
                    $ap_producer = 1;
                    $date_producer = date("Y-m-d");
                    $ap_hd = 1;
                    $date_ap_hd = date("Y-m-d");
                    $email_producer = $emailProducer;
                }
            } else {
                // Employee -> HOD -> Ver_HR -> HRD
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                $email_pm = $emailPM;
            }
        }
        // Department 3: HRD Department
        elseif (Auth::user()->dept_category_id === 3) {
            if (Auth::user()->hd === 1) {
                // HOD -> Ver_HR -> HRD -> GM (mike)
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                // $ap_producer = 1;
                // $date_producer  = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                $email_pm = $emailPM;
                $email_producer = $emailProducer;
            } else {
                // Employee -> Ver_HR -> HRD
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                $email_pm = 'hr.verification@infinitestudios.id';
                // $email_pm = $emailPM;
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
            }
        }
        // Department 4: Operational Department
        elseif (Auth::user()->dept_category_id === 4) {
            $ap_pipeline = 1;
            $date_ap_pipeline = date("Y-m-d");
            $ap_spv = 1;
            $date_ap_spv = date("Y-m-d");
            $ap_koor = 1;
            $date_ap_koor = date("Y-m-d");
            $ap_pm = 1;
            $date_ap_pm = date("Y-m-d");
            $ap_producer = 1;
            $date_producer = date("Y-m-d");
            // $ap_gm = 1;
            // $date_ap_gm = date("Y-m-d");
            $email_pm = $emailPM;
            $email_producer = $emailProducer;
        }
        // Department 5: Facility Department
        elseif (Auth::user()->dept_category_id === 5) {
            if (Auth::user()->hd === 1) {
                // HOD -> John Reedel -> Ver_HR -> HRD -> GM (Mike)
                // HOD -> FA Manager -> GM Ghea -> Ver_HR -> HRD -> GM (Mike)
                // HOD -> FA Manager -> Ver_HR -> HRD
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 0;
                $date_producer = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                // -------------------
                $ap_infinite = 1;
                $date_ap_infinite = date('Y-m-d');
                $email_pm = $emailPM;
                $email_producer = $emailProducer;
            } else {
                // Employee -> HOD -> Ver_HR -> HRD
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                $email_pm = $emailPM;
            }
        }
        // Department 6: Production Department
        elseif (Auth::user()->dept_category_id === 6) {
            if (Auth::user()->hd === 1) {
                // HOD -> Choonmeng -> Ver_HR -> HRD -> GM (Mike)
                // HOD -> HRD
                $ap_pipeline = 0;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 0;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                // $ap_producer = 1;
                // $date_producer  = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                // -----------------------
                $ap_infinite = 1;
                $date_ap_infinite = date('Y-m-d');
                $email_pm = $emailPM;
                $email_producer = $emailProducer;

                if (Auth::user()->gm === 1) {
                    $ap_pipeline = 1;
                    $date_ap_pipeline = date("Y-m-d");
                    $ap_spv = 1;
                    $date_ap_spv = date("Y-m-d");
                    $ap_koor = 1;
                    $date_ap_koor = date("Y-m-d");
                    $ap_pm = 1;
                    $date_ap_pm = date("Y-m-d");
                    $ap_producer = 1;
                    $date_producer = date("Y-m-d");
                    $ap_hd = 1;
                    $date_ap_hd = date("Y-m-d");
                    // -----------------------
                    $ap_infinite = 1;
                    $date_ap_infinite = date('Y-m-d');
                    $email_pm = 'hr.verification@infinitestudios.id';
                }
            } else {
                if (Auth::user()->level_hrd != '0') {
                    if (Auth::user()->level_hrd === 'Junior Pipeline') {
                        // Employee -> Senior Pipeline -> HOD -> Ver_HR -> HRD
                        $ap_pipeline = 0;
                        $date_ap_pipeline = null;
                        $ap_spv = 0;
                        $date_ap_spv = null;
                        $ap_koor = 1;
                        $date_ap_koor = date("Y-m-d");
                        $ap_pm = 1;
                        $date_ap_pm = date("Y-m-d");
                        $ap_producer = 1;
                        $date_producer = date("Y-m-d");
                        $ap_gm = 1;
                        $date_ap_gm = date("Y-m-d");
                        $email_koor = $emailCoor;
                    } elseif (Auth::user()->level_hrd === 'Junior Technical') {
                        // Employee -> Senior Pipeline -> HOD -> Ver_HR -> HRD
                        $ap_pipeline = 0;
                        $date_ap_pipeline = null;
                        $ap_spv = 0;
                        $date_ap_spv = null;
                        $ap_koor = 1;
                        $date_ap_koor = date("Y-m-d");
                        $ap_pm = 1;
                        $date_ap_pm = date("Y-m-d");
                        $ap_producer = 1;
                        $date_producer = date("Y-m-d");
                        $ap_gm = 1;
                        $date_ap_gm = date("Y-m-d");
                        $email_koor = $emailCoor;
                    } elseif (Auth::user()->level_hrd === 'Senior Pipeline') {
                        $ap_pipeline = 0;
                        $date_ap_pipeline = date("Y-m-d");
                        $ap_spv = 1;
                        $date_ap_spv = date("Y-m-d");
                        $ap_koor = 1;
                        $date_ap_koor = date("Y-m-d");
                        $ap_pm = 1;
                        $date_ap_pm = date("Y-m-d");
                        $ap_producer = 1;
                        $date_producer = date("Y-m-d");
                        $ap_gm = 1;
                        $date_ap_gm = date("Y-m-d");
                        $email_pm = $emailPM;

                        if (Auth::user()->position = "Head Of Technology") {
                            $ap_pipeline = 1;
                            $date_ap_pipeline = date("Y-m-d");
                            $ap_spv = 1;
                            $date_ap_spv = date("Y-m-d");
                            $ap_koor = 1;
                            $date_ap_koor = date("Y-m-d");
                            $ap_pm = 1;
                            $date_ap_pm = date("Y-m-d");
                            $ap_producer = 1;
                            $date_producer = date("Y-m-d");
                            $ap_hd = 1;
                            $date_ap_hd = date("Y-m-d");
                            $ap_gm = 0;
                            $date_ap_gm = null;
                            $email_pm = $emailPM;
                        }
                    } elseif (Auth::user()->level_hrd === 'Senior Technical') {
                        $ap_pipeline = 0;
                        $date_ap_pipeline = date("Y-m-d");
                        $ap_spv = 1;
                        $date_ap_spv = date("Y-m-d");
                        $ap_koor = 1;
                        $date_ap_koor = date("Y-m-d");
                        $ap_pm = 1;
                        $date_ap_pm = date("Y-m-d");
                        $ap_producer = 1;
                        $date_producer = date("Y-m-d");
                        $ap_gm = 1;
                        $date_ap_gm = date("Y-m-d");
                        $email_pm = $emailPM;
                    }
                } else {
                    if (Auth::user()->producer === 1) {
                        $ap_pipeline = 0;
                        $date_ap_pipeline = date("Y-m-d");
                        $ap_spv = 1;
                        $date_ap_spv = date("Y-m-d");
                        $ap_koor = 1;
                        $date_ap_koor = date("Y-m-d");
                        $ap_pm = 1;
                        $date_ap_pm = date("Y-m-d");
                        $ap_producer = 1;
                        $date_producer = date("Y-m-d");
                        $ap_gm = 1;
                        $date_ap_gm = date("Y-m-d");
                        $email_pm = $emailPM;
                    } elseif (Auth::user()->pm === 1) {
                        $ap_pipeline = 0;
                        $date_ap_pipeline = date("Y-m-d");
                        $ap_spv = 1;
                        $date_ap_spv = date("Y-m-d");
                        $ap_koor = 1;
                        $date_ap_koor = date("Y-m-d");
                        $ap_pm = 1;
                        $date_ap_pm = date("Y-m-d");
                        $ap_producer = 1;
                        $date_producer = date("Y-m-d");
                        $ap_gm = 1;
                        $date_ap_gm = date("Y-m-d");
                        $email_pm = $emailPM;
                    } elseif (Auth::user()->koor === 1) {
                        if (Auth::user()->lineGM === 1) {
                            $ap_pipeline = 0;
                            $date_ap_pipeline = date("Y-m-d");
                            $ap_spv = 1;
                            $date_ap_spv = date("Y-m-d");
                            $ap_pm = 1;
                            $date_ap_pm = date("Y-m-d");
                            $ap_koor = 1;
                            $date_ap_koor = date("Y-m-d");
                            $ap_gm = 1;
                            $date_ap_gm = date("Y-m-d");
                            $email_pm = $emailPM;
                            $email_producer = $emailProducer;
                        } else {
                            $ap_pipeline = 0;
                            $date_ap_pipeline = date("Y-m-d");
                            $ap_spv = 1;
                            $date_ap_spv = date("Y-m-d");
                            $ap_koor = 1;
                            $date_ap_koor = date("Y-m-d");
                            $ap_gm = 1;
                            $date_ap_gm = date("Y-m-d");
                            $email_pm = $emailPM;
                            $email_producer = $emailProducer;
                        }
                    } elseif (Auth::user()->spv === 1) {
                        $ap_pipeline = 0;
                        $date_ap_pipeline = date("Y-m-d");
                        $ap_spv = 1;
                        $date_ap_spv = date("Y-m-d");
                        $ap_koor = 1;
                        $date_ap_koor = date("Y-m-d");
                        $ap_gm = 1;
                        $date_ap_gm = date("Y-m-d");
                        $email_producer = $emailProducer;
                        $email_pm = $emailPM;
                    } else {
                        $ap_pipeline = 0;
                        $date_ap_pipeline = date("Y-m-d");
                        $ap_gm = 1;
                        $date_ap_gm = date("Y-m-d");
                        $email_koor = $emailCoor;
                        $email_spv = $emailSPV;
                        $email_pm = $emailPM;
                    }
                }
            }
        }
        // Department 7: Production LS Department
        elseif (Auth::user()->dept_category_id === 7) {
            if (Auth::user()->hd === 1) {
                // HOD -> Ver_HR -> HRD -> GM (mike)
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                $email_pm = $emailPM;
                $email_producer = $emailProducer;
            } else {
                // Employee -> HOD-> Ver_HR -> HRD
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                $email_pm = $emailPM;
            }
        }
        // Department 8: General Department
        elseif (Auth::user()->dept_category_id === 8) {
            if (Auth::user()->hd === 1) {
                // HOD -> Ver_HR -> HRD
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                // $ap_producer = 1;
                // $date_producer  = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                $email_pm = $emailPM;
                $email_producer = $emailProducer;
            } else {
                // Employee -> HOD -> Ver_HR -> HRD
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                $email_pm = $emailPM;
            }
        }
        // Department 9: Management Department
        elseif (Auth::user()->dept_category_id === 9) {
            $ap_pipeline = 1;
            $date_ap_pipeline = date("Y-m-d");
            $ap_spv = 1;
            $date_ap_spv = date("Y-m-d");
            $ap_koor = 1;
            $date_ap_koor = date("Y-m-d");
            $ap_pm = 1;
            $date_ap_pm = date("Y-m-d");
            $ap_producer = 1;
            $date_producer = date("Y-m-d");
            $ap_hd = 1;
            $date_ap_hd = date("Y-m-d");
            $ap_gm = 1;
            $date_ap_gm = date("Y-m-d");
        }
        // Department 10: Unknown Department
        elseif (Auth::user()->dept_category_id === 10) {
            if (Auth::user()->hd === 1) {

                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                // $ap_producer = 1;
                // $date_producer  = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                $email_pm = $emailPM;
                $email_producer = $emailProducer;
            } else {
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                $email_pm = $emailPM;
            }
        }
        // Department 11: Unknown Department
        elseif (Auth::user()->dept_category_id === 11) {
            if (Auth::user()->hd === 1) {

                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                // $ap_producer = 1;
                // $date_producer  = date("Y-m-d");
                $ap_hd = 1;
                $date_ap_hd = date("Y-m-d");
                $email_pm = $emailPM;
                $email_producer = $emailProducer;
            } else {
                $ap_koor = 1;
                $date_ap_koor = date("Y-m-d");
                $ap_pipeline = 1;
                $date_ap_pipeline = date("Y-m-d");
                $ap_spv = 1;
                $date_ap_spv = date("Y-m-d");
                $ap_pm = 1;
                $date_ap_pm = date("Y-m-d");
                $ap_producer = 1;
                $date_producer = date("Y-m-d");
                $ap_gm = 1;
                $date_ap_gm = date("Y-m-d");
                $email_pm = $emailPM;
            }
        }


        // Return approval configuration
        return [
            // Email recipients
            "email_koor" => $email_koor,
            "email_spv" => $email_spv,
            "email_pm" => $email_pm,
            "email_producer" => $email_producer,

            // Approval status flags
            "ap_gm" => $ap_gm,
            "ap_pipeline" => $ap_pipeline,
            "ap_spv" => $ap_spv,
            "ap_koor" => $ap_koor,
            "ap_pm" => $ap_pm,
            "ap_producer" => $ap_producer,
            "ap_hd" => $ap_hd,
            "ap_infinite" => $ap_infinite,

            // Approval dates
            "date_ap_gm" => $date_ap_gm,
            "date_ap_pipeline" => $date_ap_pipeline,
            "date_ap_spv" => $date_ap_spv,
            "date_ap_koor" => $date_ap_koor,
            "date_ap_pm" => $date_ap_pm,
            "date_producer" => $date_producer,
            "date_ap_hd" => $date_ap_hd,
            "date_ap_infinite" => $date_ap_infinite
        ];
    }

    public function ruleOutsourceLeave($emailCoor, $emailSPV, $emailPM, $emailProducer)
    {
        if ($emailPM) {
            $pm = User::where('username', $emailPM)->first();
            $emailPM = $pm->email;
        }
        $emailCoor = null;
        $emailSPV = null;
        $emailProducer = null;

        if (Auth::user()->dept_category_id === 2) {

            if (Auth::user()->hd === 0) {
                $ver_hr = 0;
                $ap_gm = 1;
                $ap_coor = 1;
                $ap_spv = 1;
                $ap_pm = 1;
                $ap_producer = 1;
                $ap_hd = 0;
                $ap_infinite = 1;
                $ap_pipeline = 1;

                $date_ap_gm = date("Y-m-d");
                $date_ap_pipeline = date("Y-m-d");
                $date_ap_coor = date("Y-m-d");
                $date_ap_spv = date("Y-m-d");
                $date_ap_pm = date("Y-m-d");
                $date_producer = date("Y-m-d");
                $date_ap_infinite = date("Y-m-d");

                $email_pm = $emailPM;
            }

        }
        $return = [
            'ver_hr' => $ver_hr,
            'ap_gm' => $ap_gm,
            'ap_coor' => $ap_coor,
            'ap_spv' => $ap_spv,
            'ap_pm' => $ap_pm,
            'ap_producer' => $ap_producer,
            'ap_hd' => $ap_hd,
            'ap_infinite' => $ap_infinite,
            'ap_pipeline' => $ap_pipeline,
            'date_ap_gm' => $date_ap_gm,
            'date_ap_pipeline' => $date_ap_pipeline,
            'date_ap_coor' => $date_ap_coor,
            'date_ap_spv' => $date_ap_spv,
            'date_ap_pm' => $date_ap_pm,
            'date_producer' => $date_producer,
            'date_ap_infinite' => $date_ap_infinite,
            'email_pm' => $email_pm,
            'email_coor' => $emailCoor,
            'email_spv' => $emailSPV,
            'email_producer' => $emailProducer,
        ];

        return $return;
    }
}