<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    protected $fillable=[
        'twt_id','stu_id','name','real_name','profession',
        'academic','class','college_code','grade','gender',
        'email','token','stu_id'
    ];

    public function papers(){
        return $this->belongsToMany(
            'App\Paper',
            'paper_student',
            'twt_id',
            'paper_id'
        );
    }

    /**
     * 通过twt_id查询学生信息
     * @param $twt_id
     * @return mixed
     */
    public static function SelByTwtId($twt_id){
        return Student::where('twt_id',$twt_id)->first();
    }

    /**
     * 通过twt_id获取学生的年级
     * @param $twt_id
     * @return mixed
     */
    public static function GetGrade($twt_id){
        $data = self::SelByTwtId($twt_id);
        $grade = null;
        if(isset($data['grade'])){
            $grade = $data['grade'];
        }
        return $grade;
    }

    /**
     * 学生信息补全
     * @param $record
     */
    public static function ExportLink($record){
        $twt_rec = Student::where('twt_id',$record->twt_id)->first();
        $record->stu_id = $twt_rec->stu_id;
        $record->name = $twt_rec->real_name;
    }

    /**
     * 排名筛选后记录信息补全
     * @param $records
     * @return mixed
     */
    public static function RecordLink($records){
        foreach ($records as $record){
            $stu = self::SelByTwtId($record['twt_id']);
            $record['real_name'] = $stu['real_name'];
            $record['stu_id'] = self::StuIdProtect($stu['stu_id']);
        }
        return $records;
    }

    /**
     * 学号3到6位替换为*
     * @param $stu_id
     * @return string|string[]
     */
    public static function StuIdProtect($stu_id){
        return substr_replace($stu_id,'****',3,4);
    }

    /**
     * 通过姓名学号匹配twt_id
     * @param $students
     * @return mixed
     */
    public static function Match($students){
        foreach ($students as $student){
            $stu_id = $student->stu_id;
            $name = $student->name;
            $twt_id = Student::where('real_name',$name)
                ->where('stu_id',$stu_id)
                ->first()
                ->twt_id;
            $student->twt_id = $twt_id;
        }
        return $students;
    }

    /**
     * 获取终端真实ip
     * @return array|false|mixed|string
     */
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

    public static function AddNewStudent($userinfo){
        $result = $userinfo->result;
        $ui=$result->user_info;
        $id = $result->id;
        $arr['stu_id'] = $result->user_number;
        $arr['name'] = $result->twt_name;
        $arr['real_name']=$ui->username;
        $arr['profession'] = $result->major;
        $arr['academic'] = $result->college;
        $arr['college_code'] = $result->college_code;
        $arr['grade'] = $result->user_info->grade;
        $arr['gender'] = $result->user_info->gender;
        $arr['class'] = $ui -> class_id;
        $arr['province'] = $ui->province;
        $arr['last_school'] = $ui->last_school;
        Student::updateOrInsert(['twt_id'=>$id],$arr);
    }

    /**
     * 后端防代刷功能（UA和IP判断设备）
     * 同一台设备不同账号限时10分钟
     * @param $twt_id
     * @param $userAgent
     * @return bool
     */
    public static function ReplaceCheck($twt_id,$userAgent){
        $last=Score::where('user_agent',$userAgent)
            ->where('ip',Student::getIP())
            ->first();
        $updated_at = strtotime($last->updated_at);
        $t_id = $last->twt_id;
        $j = false;
        if ($t_id != $twt_id){
            if (time()-$updated_at<86400){
                $j = true;
            }
        }
        return $j;
    }

    /**
     * 代刷记录
     * @param $twt_id
     * @param $paper_id
     * @param $ip
     * @param $userAgent
     */
    public static function CheatRecord($twt_id,$paper_id,$ip,$userAgent){
        DB::table('cheat')->insert(
            [
                'twt_id'=>$twt_id,
                'paper_id'=>$paper_id,
                'ip'=>$ip,
                'user_agent'=>$userAgent
            ]
        );
    }

    /**
     * 通过ip获取地址
     * @param $ip
     * @return array
     */
    public static function GetLoc($ip){
        $tmp=file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$ip);
        $data = array();
        foreach (json_decode($tmp)->data as $key=>$value){
            if ($key == 'country' || $key == 'region' ||
                $key == 'city' || $key == 'isp'){
                $data[$key] = $value;
            }
        }
        $data['ip']=$ip;
        return $data;
    }

    /**
     * @param $twt_id
     * @return mixed
     */
    public static function GetCCByTWTId($twt_id){
        return Student::where('twt_id',$twt_id)->first()['college_code'];
    }

    public static function StuIdsTransToTwTIds($stu_ids){
        $twt_ids = Student::whereIn('stu_id',$stu_ids)
            ->select('twt_id')->get();
        $ts = array();
        foreach ($twt_ids as $twt_id){
            array_push($ts,$twt_id->twt_id);
        }
        return $ts;
    }
}
