<?php
function convertNumberToWords($number) {
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'forty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        100000              => 'lakh',
        10000000            => 'crore',
    );

    if (!is_numeric($number)) {
        return false;
    }

    if ($number < 0) {
        return $negative . convertNumberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = floor($number / 100);
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convertNumberToWords($remainder);
            }
            break;
        case $number < 100000:
            $thousands  = floor($number / 1000);
            $remainder = $number % 1000;
            $string = convertNumberToWords($thousands) . ' ' . $dictionary[1000];
            if ($remainder) {
                $string .= $separator . convertNumberToWords($remainder);
            }
            break;
        case $number < 10000000:
            $lakhs  = floor($number / 100000);
            $remainder = $number % 100000;
            $string = convertNumberToWords($lakhs) . ' ' . $dictionary[100000];
            if ($remainder) {
                $string .= $separator . convertNumberToWords($remainder);
            }
            break;
        case $number < 1000000000:
            $crores  = floor($number / 10000000);
            $remainder = $number % 10000000;
            $string = convertNumberToWords($crores) . ' ' . $dictionary[10000000];
            if ($remainder) {
                $string .= $separator . convertNumberToWords($remainder);
            }
            break;
        default:
            return 'Number is too large.';
    }

    if ($fraction !== null && is_numeric($fraction)) {
        $string .= $decimal;
        foreach (str_split((string) $fraction) as $number) {
            $string .= $dictionary[$number] . ' ';
        }
    }

    return $string;
}

function isFileExists($image_path = '')
{
	if (is_file($image_path) && !empty ($image_path) && file_exists(FCPATH . $image_path)) {
		return base_url($image_path);
	} else {
		return base_url('uploads/user_avatar/default.jpg');
	}
}