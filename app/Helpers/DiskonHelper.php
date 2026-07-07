<?php
// app/Helpers/DiskonHelper.php

if (!function_exists('hitung_biaya_jasa')) {
    /**
     * Hitung biaya jasa (service fee)
     * <= 10jt: 1%, > 10jt: 2%
     */
    function hitung_biaya_jasa($total_harga)
    {
        if ($total_harga <= 10000000) {
            return $total_harga * 0.01;
        } else {
            return $total_harga * 0.02;
        }
    }
}

if (!function_exists('hitung_diskon_voucher')) {
    /**
     * Hitung diskon voucher promo
     */
    function hitung_diskon_voucher($total_harga, $voucher_code)
    {
        $persen = 0;
        $voucher_code = strtoupper($voucher_code);

        switch ($voucher_code) {
            case 'PROMO2025':
                $persen = 10;
                break;
            case 'PROMO2026':
                $persen = 15;
                break;
            case 'AKHIRTAHUN':
                $persen = 25;
                break;
            default:
                $persen = 0;
                break;
        }

        return ($persen / 100) * $total_harga;
    }
}

if (!function_exists('hitung_free_mouse')) {
    /**
     * Hitung nominal hadiah free mouse
     * > 15jt: 150.000
     */
    function hitung_free_mouse($total_harga)
    {
        if ($total_harga > 15000000) {
            return 150000;
        }
        return 0;
    }
}

if (!function_exists('hitung_diskon')) {
    /**
     * Hitung diskon berdasarkan total pembelian (Existing logic)
     * @param int|float $total_harga
     * @return array array berisi persentase teks dan nominal diskon
     */
    function hitung_diskon($total_harga)
    {
        $persen = 0;

        if ($total_harga >= 50000000) {
            $persen = 20;
        } elseif ($total_harga >= 25000000) {
            $persen = 12;
        } elseif ($total_harga >= 15000000) {
            $persen = 7;
        } elseif ($total_harga >= 5000000) {
            $persen = 3;
        } else {
            $persen = 0;
        }

        $nominal_diskon = ($persen / 100) * $total_harga;

        return [
            'persen' => $persen,
            'nominal' => $nominal_diskon
        ];
    }
}