<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{

    protected $fillable=['password','updated_at','token'];

    /**
     * 生成token
     * @param $password
     * @return string
     */
    public static function GenerateToken($password){
        return md5($password.time());
    }

    /**
     * 检查token
     * @param $token
     * @return mixed
     */
    public static function CheckToken($token){
        return College::where('token',$token)->count();
    }

    /**
     * 确认用户信息并登录
     * @param $userName
     * @param $password
     * @return string
     */
    public static function auth($userName,$password){
        if (
            College::where('name',$userName)
            ->where('password',md5($password))
            ->exists()
        ){
            $user = College::where('name',$userName)
                ->select('id','name','college_code')
                ->first();
            $user->token=self::GenerateToken($password);
            $user->save();
            $user -> type = 'college';
            unset($user['updated_at']);
            session(['data'=>$user]);
            session()->save();
            return $user;
        }else{
            return 'error';
        }
    }

    /**
     * 更改密码检查
     * @param $name
     * @param $old
     * @return mixed
     */
    public static function PRCheck($name,$old){
        return College::where('name',$name)
            ->where('password',$old)
            ->count();
    }

    /**
     * 更改密码用户信息获取
     * @param $name
     * @param $old
     * @return mixed
     */
    public static function PRUserGet($name,$old){
        return College::where('name',$name)
            ->where('password',$old)
            ->first();
    }

    /**
     * 获取学院键值对
     * @return array
     */
    public static function NameCode(){
        $keyVal=self::select('name','college_code')
            ->get();
        $colleges=array();
        foreach ($keyVal as $k){
            $colleges[$k['name']]=$k['college_code'];
        }
        return $colleges;
    }
}
