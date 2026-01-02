<?php

use App\ValueObjects\FormatRupiah;

if (! function_exists('formatRupiah')) {
    /**
     * Format a number as Indonesian Rupiah currency
     *
     * @param  float|int  $value  The value to format
     * @param  bool  $with_prefix  Whether to include the "Rp" prefix
     * @return string The formatted currency string
     */
    function formatRupiah(float|int|string $value): string
    {
        // return $value;
        return FormatRupiah::fromInt((float) $value)->format();
    }
}

if (! function_exists('unformatRupiah')) {
    /**
     * Convert a formatted Rupiah string back to a numeric value
     *
     * @param  string  $formatted  The formatted Rupiah string (e.g., "Rp 1.000,00")
     * @return float The numeric value
     */
    function unformatRupiah(string $formatted): float
    {
        return FormatRupiah::fromString($formatted)->toInt();
    }
}
