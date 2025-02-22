<?php

/**
 * Created by Md. Jahangir Alam Rohan.
 * User: Md. Jahangir Alam Rohan.
 * Date: 25-Jun-2024
 * Time: 03.01 PM
 */

if (!function_exists('rolesConvert')) {
    function rolesConvert($data = null) {
        switch ($data) {
            case 'Manager':
                return 'Manager';
            case 'Zonal Sales Executive':
                return 'ZSE';
            case 'Territory Sales Executive':
                return 'TSE';
            default:
                return null; // or handle as needed
        }
    }
}

if (!function_exists('rolesConvertShort')) {
    function rolesConvertShort($data = null) {
        switch ($data) {
            case 'Manager':
                return 'manager';
            case 'Zonal Sales Executive':
                return 'zse';
            case 'Territory Sales Executive':
                return 'tse';
            default:
                return null; // or handle as needed
        }
    }
}

if (!function_exists('underRole')) {
    function underRole($data = null) {
        switch ($data) {
            case 'Manager':
                return 'Zonal Sales Executive';
            case 'Zonal Sales Executive':
                return 'Territory Sales Executive';
            case 'Territory Sales Executive':
                return 'Customer';
            default:
                return null; // or handle as needed
        }
    }
}
