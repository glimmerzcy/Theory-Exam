<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Score extends Model
{
    protected $fillable = [
        'twt_id', 'paper_id', 'time', 'score', 'started_at',
        'record', 'created_at', 'updated_at', 'ip', 'tstps',
        'user_agent'
    ];

    /**
     * 最早通过初始数据获得
     * @param $paper_id
     * @return mixed
     */
    public static function earlier($paper_id)
    {
        date_default_timezone_set('PRC');
        return Score::where('paper_id', $paper_id)
            ->where('score', '>=', 60)
            ->whereNotNull('updated_at')
            ->orderBy('updated_at')
            ->select('twt_id', 'score')
            ->limit(30)
            ->get();
    }

    /**
     * 一次通过最高记录获取
     * @param $paper_id
     * @return mixed
     */
    public static function higher($paper_id)
    {
        return Score::where('paper_id', $paper_id)
            ->where('time', 1)
            ->whereNotNull('updated_at')
            ->where('score', '>=', 60)
            ->orderBy('score', 'desc')
            ->orderBy('updated_at')
            ->select('twt_id', 'score')
            ->limit(30)
            ->get();
    }

    /**
     * 排名集合
     * @param $paper_id
     * @return mixed
     */
    public static function allRank($paper_id)
    {
        if (Cache::has('ranks')) {
            $records = json_decode(Cache::get('ranks'));
        } else {
            $records['rankByTime'] = Student::RecordLink(self::earlier($paper_id));
            $records['rankByScore'] = Student::RecordLink(self::higher($paper_id));
            Cache::add('ranks', json_encode($records), 60);
        }
        return $records;
    }

    /**
     * 考试次数
     * @param $paper_id
     * @param $twt_id
     * @return mixed
     */
    public static function TestedTime($paper_id, $twt_id)
    {
        return Score::where('paper_id', $paper_id)
            ->where('twt_id', $twt_id)
            ->first()
            ->time;
    }

    /**
     * 考试开始数据记录
     * @param $twt_id
     * @param $paper_id
     * @return mixed
     */
    public static function record($twt_id, $paper_id)
    {
        Score::updateOrCreate(
            ['twt_id' => '' . $twt_id, 'paper_id' => '' . $paper_id],
            ['started_at' => date('Y-m-d H:i:s')]
        );
    }

    /**
     * 获取最近一次考试记录
     * @param $twt_id
     * @param $paper_id
     * @return bool
     */
    public static function check($twt_id, $paper_id)
    {
        return Score::where('twt_id', '' . $twt_id)
            ->where('paper_id', '' . $paper_id)
            ->first();
    }

    /**
     * 记录
     * @param $twt_id
     * @param $paper_id
     * @param $time
     * @param $started_at
     * @param $record
     * @param $score
     * @param $user_agent
     */
    public static function HistoryRecord($twt_id, $paper_id, $time,$started_at, $record, $score, $user_agent){
        $data = array();
        $data['twt_id'] = $twt_id;
        $data['paper_id'] = $paper_id;
        $data['time'] = $time;
        $data['started_at'] = $started_at;
        $data['record'] = json_encode($record);
        $data['score'] = $score;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['ip'] = Student::getIP();
        $data['user_agent'] = $user_agent;
        DB::table('histories')->insert($data);
    }

    /**
     * 指定考试所有成绩获取
     * @param $paper_id
     */
    public static function ScoresExport($paper_id){
        $datas = Score::where("paper_id",$paper_id)->select("twt_id","score")->get();
        $twt_ids = array();
        foreach ($datas as $data){
            array_push($twt_ids,$data->twt_id);
        }
        $stu_infos = Student::whereIn("twt_id",$twt_ids)->select("twt_id","stu_id","real_name","academic")->get();
        $stuinfos = array();
        foreach ($stu_infos as $info){
            $stuinfos[''.$info->twt_id] = $info;
        }
        foreach ($datas as $data){
            $twt_id = $data->twt_id;
            if (isset($stuinfos[$twt_id])){
                $info = $stuinfos[$twt_id];
                $data->stu_id = $info->stu_id;
                $data->real_name = $info->real_name;
                $data->academic = $info->academic;
            }
        }
        return $datas;
    }
}
