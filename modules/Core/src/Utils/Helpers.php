<?php

namespace Modules\Core\Utils;

class Helpers
{
    /**
     * @param  int  $id
     * @param  string  $prefix
     * @param  string|null  $type
     * @return string
     *
     * @example Helpers::generateSerialNumber(1, 'RC', 'TR') // RC-TR-AAA-001
     * @example Helpers::generateSerialNumber(1, 'RC', 'F') // RC-F-AAA-001
     */
    public static function generateSerialNumber(int $id, string $prefix = 'RC', ?string $type = 'TR'): string
    {
        $start = 703; // 0 = A, 703 = AAA, adjust accordingly
        $letters_value = $start + (ceil($id / 999) - 1);
        $numbers = ($id % 999 === 0) ? 999 : $id % 999;

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $letters = '';

        // while there are still positive integers to divide
        while ($letters_value) {
            $current = $letters_value % $base - 1; // We use -1 because we want to start at 0 index
            $letters = $characters[$current].$letters;
            $letters_value = floor($letters_value / $base);
        }

        if ($type == null) {
            return $prefix.'-'.$letters.'-'.sprintf('%03d', $numbers);
        }

        return $prefix.'-'.$type.'-'.$letters.'-'.sprintf('%03d', $numbers);
    }
}
