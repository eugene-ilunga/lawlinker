<?php

namespace App\Services;

class RdcPhoneFormatter
{
    public static function normalizeForFreshPay(string $phone): string
    {
        $normalized = preg_replace('/\D+/', '', trim($phone));

        if (str_starts_with($normalized, '243') && strlen($normalized) >= 12) {
            $normalized = '0'.substr($normalized, -9);
        }

        if (strlen($normalized) === 9 && str_starts_with($normalized, '8')) {
            return '0'.$normalized;
        }

        if (strlen($normalized) === 10 && str_starts_with($normalized, '08')) {
            return $normalized;
        }

        return $normalized;
    }
}
