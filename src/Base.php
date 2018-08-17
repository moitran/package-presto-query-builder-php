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
            return $this->removeSpecialCharsFromStr($str);
        }

        if (is_array($str)) {
            $rs = [];
            foreach ($str as $item) {
                $rsItem = $this->removeSpecialCharsFromStr($item);
                $rs[] = $rsItem;
            }

            return $rs;
        }

        return $str;
    }

    /**
     * @param $str
     *
     * @return bool|false|string
     */
    private function removeSpecialCharsFromStr($str)
    {
        $prefix = substr($str, 0, 1);
        $suffix = substr($str, -1);
        if ($prefix === '%') {
            $str = substr($str, 1);
        }

        if ($suffix === '%') {
            $str = substr($str, 0, -1);
        }

        $str = stripcslashes($str);

        $str = mb_ereg_replace('[\x00\x0A\x0D\x1A\x22\x25\x27\x5C\x5F]', '\\\0', $str);

        if ($prefix === '%') {
            $str = '%' . $str;
        }

        if ($suffix === '%') {
            $str = $str . "%";
        }

        return $str;
    }
}
