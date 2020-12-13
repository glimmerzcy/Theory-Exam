<?php

namespace App\Admin\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	use HasDateTimeFormatter;

    /**
     * 答案转换算法（数字=>字母）
     * @param $num
     * @return string
     */
    public static function AnswerNumToStr($num)
    {
        $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        $res = null;
        $e = 5;
        while ($num > 0) {
            if ($num < pow(2, $e)) {
                $e--;
            } else {
                $num -= pow(2, $e);
                $res .= $letters[$e];
                $e--;
            }
        }
        return strrev($res);
    }

    /**
     * 答案转换算法（字母=>数字）
     * @param $answer
     * @return int
     */
    public static function AnswerStrToNum($answer)
    {
        $ans = 0;
        for ($j = 0; $j < strlen($answer); $j++) {
            switch (substr($answer, $j, 1)) {
                case "A":
                    $ans += 1;
                    break;
                case "B":
                    $ans += 2;
                    break;
                case "C":
                    $ans += 4;
                    break;
                case "D":
                    $ans += 8;
                    break;
                case "E":
                    $ans += 16;
                    break;
                case "F":
                    $ans += 32;
                    break;
            }
        }
        return $ans;
    }
}
