<?php

namespace App\Helpers;

class DateHelper
{
    public static function convertToDays($text)
    {
        // Nilai konversi kasar
        $yearInDays = 365;
        $monthInDays = 30;

        $days = 0;

        // Ambil angka dari string (bisa fleksibel urutannya)
        if (preg_match('/(\d+)\s*Tahun/', $text, $match)) {
            $days += ((int) $match[1]) * $yearInDays;
        }

        if (preg_match('/(\d+)\s*Bulan/', $text, $match)) {
            $days += ((int) $match[1]) * $monthInDays;
        }

        if (preg_match('/(\d+)\s*Hari/', $text, $match)) {
            $days += (int) $match[1];
        }

        return $days;
    }
}
