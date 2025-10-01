<?php

namespace App\Helpers;

class BeratBadan
{
    public static function decimalDayToHoursMinutes(float $decimalDay): string
    {
        $totalHours = $decimalDay * 24;
        $hours = floor($totalHours);
        $minutesDecimal = $totalHours - $hours;
        $minutes = round($minutesDecimal * 60);

        return sprintf('%02d.%02d', $hours, $minutes);
    }

    public static function convertToBB($text)
    {
        // Cek apakah $text bisa dianggap angka (float) valid
        // Gunakan is_numeric karena bisa handle angka dengan koma/pemisah
        if (!is_numeric(str_replace(',', '.', $text))) {
            // Bukan angka valid, return original data atau string kosong sesuai kebutuhan
            return $text; // atau return ''; kalau mau kosong
        }

        // Jika ada koma, ganti dengan titik untuk casting float
        if (strpos($text, ',') !== false) {
            $decimalDay = floatval(str_replace(',', '.', $text));
        } else {
            $decimalDay = floatval($text);
        }

        return self::decimalDayToHoursMinutes($decimalDay);
    }
}
