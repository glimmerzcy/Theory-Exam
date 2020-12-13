<?php


namespace App\Supports;


class Student
{

    public static function UACheck($userAgent)
    {
        $res = true;
        if (strpos($userAgent, 'Android')) {
            if (!(strpos($userAgent, 'Version') and strpos($userAgent, 'Browser') == false)) {
                $res = false;
            }
        } elseif (strpos($userAgent, 'iPhone')) {
            if (!(strpos($userAgent, 'Safari') == false and strpos($userAgent, 'NetType') == false)) {
                $res = false;
            }
        }
        return $res;
    }

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

    public static function getIP(){
        $onlineIP='';
        if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
            $onlineIP=getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
            $onlineIP=getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_REAL_IP')&&strcasecmp(getenv('HTTP_X_REAL_IP'),'unknown')){
            $onlineIP=getenv('HTTP_X_REAL_IP');
        } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
            $onlineIP=getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
            $onlineIP=$_SERVER['REMOTE_ADDR'];
        }
        return $onlineIP;
    }

}
