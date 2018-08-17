<?php

namespace MoiTran\PrestoQueryBuilder;

/**
 * Class Base
 * @package MoiTran\PrestoQueryBuilder
 */
abstract class Base
{
    use Where;

    /**
     * @param $str
     *
     * @return array|false|string
     */
    public function removeSpecialChars($str)
    {
        if (is_numeric($str)) {
            return $str;
        }

        if (is_string($str)) {
            $str = stripcslashes($str);

            return mb_ereg_replace('[\x00\x0A\x0D\x1A\x22\x25\x27\x5C\x5F]', '\\\0', $str);
        }

        if (is_array($str)) {
            $rs = [];
            foreach ($str as $item) {
                $rsItem = stripcslashes($item);
                $rsItem = mb_ereg_replace('[\x00\x0A\x0D\x1A\x22\x25\x27\x5C\x5F]', '\\\0', $rsItem);
                $rs[] = $rsItem;
            }

            return $rs;
        }

        return $str;
    }
}
