<?php

namespace App;

class LongestCommonSequence
{
    public static function find(array $arr1, array $arr2): array
    {
        $m = count($arr1);
        $n = count($arr2);

        $L = [];
        for ($i = 0; $i <= $m; $i++) {
            for ($j = 0; $j <= $n; $j++) {
                if ($i == 0 || $j == 0)
                    $L[$i][$j] = 0;
                elseif ($arr1[$i - 1] == $arr2[$j - 1])
                    $L[$i][$j] = $L[$i - 1][$j - 1] + 1;
                else
                    $L[$i][$j] = max($L[$i - 1][$j], $L[$i][$j - 1]);
            }
        }

        $index = $L[$m][$n];
        $lcs = [];

        $i = $m;
        $j = $n;
        while ($i > 0 && $j > 0) {
            if ($arr1[$i - 1] == $arr2[$j - 1]) {
                $lcs[--$index] = $arr1[$i - 1];
                $i--;
                $j--;
            } elseif ($L[$i - 1][$j] > $L[$i][$j - 1])
                $i--;
            else
                $j--;
        }

        return $lcs;
    }
}