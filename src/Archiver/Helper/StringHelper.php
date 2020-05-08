<?php

namespace Archiver\Helper;

/**
 * Class StringHelper.
 */
class StringHelper
{
    public static function squash(string $string): string
    {
        return strtr(
            $string,
            [
                'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'Ae',
                'Ç' => 'C',
                'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
                'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
                'Ð' => 'Dj',
                'Ñ' => 'N',
                'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
                'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
                'Ý' => 'Y',
                'Þ' => 'B',
                'ß' => 'Ss',
                'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
                'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
                'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
                'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
                'ù' => 'u', 'ú' => 'u', 'û' => 'u',
                'ý' => 'y',
                'þ' => 'b',
                'ÿ' => 'y',
                'Š' => 'S', 'š' => 's', 'ś' => 's',
                'Ž' => 'Z', 'ž' => 'z',
                'ƒ' => 'f',
            ]
        );
    }

    public static function toAscii(string $string): string
    {
        if (empty($string)) {
            return $string;
        }

        return mb_convert_encoding(self::squash($string), 'ascii');
    }

    public static function strRev(string $string): string
    {
        $result = '';
        for ($i = mb_strlen($string); $i >= 0; --$i) {
            $result .= mb_substr($string, $i, 1);
        }

        return $result;
    }

    public static function toBaseName(string $path): string
    {
        return basename($path, PATHINFO_BASENAME);
    }
}
