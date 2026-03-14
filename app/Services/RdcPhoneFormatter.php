<?php

namespace App\Services;

class RdcPhoneFormatter
{
    public static function normalizeForFreshPay(string $phone): string
    {
        $normalized = preg_replace('/\D+/', '', trim($phone));

        if (str_starts_with($normalized, '243') && strlen($normalized) === 12) {
            $normalized = substr($normalized, 3);
        }

        if (str_starts_with($normalized, '0')) {
            return substr($normalized, 1);
        }

        return $normalized;
    }
}
