<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Result;
use App\Models\Student;
use App\Repositories\Fields\PaperRepository;
use App\Repositories\Fields\PermissionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TwT\SSO\Api;

class LoginController extends Controller
{
    var $sso = null;
    protected $paperRepository, $permissionRepository;

    public function __construct(
        PaperRepository $paperRepository,
        PermissionRepository $permissionRepository
    ){
        $this->paperRepository = $paperRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public static function login()
    {
        $sso = self::getSSO();

        //这里是为了实现单点登陆后返回需要登陆的页面的功能,比如我要进入某个帖子,这个帖子需要登陆,
        //所以你来到了单点登陆页面,登陆完成后你可以继续浏览之前的帖子,而不用从主站重新开始
        //$from = $_GET['from'] ?? urlencode("/");
        //这里需要声明一个需要返回的页面,单点登陆会跳转到另一个网站(登陆页面),而这个页面被所有项目共用,
        //在那个页面用户点击登陆后,之所以会返回这个项目,而不是其他的项目,就是这个$link的作用
        //这个$link会在用户点击登陆成功后访问,会携带一个token,这个token用于获得登录用户的信息

//        $link = 'http://127.0.0.1:8000/api/ssoLogin';
        $link = 'https://theory.twt.edu.cn/api/ssoLogin';

        //getLoginUrl 会返回一个跳转到单点登陆页面连接的url,直接用header跳过去,就是单点登陆页面了
        $login_url = $sso->getLoginUrl($link);

        header("Location:" . $login_url);
        exit;
    }

    //https://login.twt.edu.cn/sso/login?app_id=44&time=1600024669&source=Imh0dHBzOlwvXC90aGVvcnkudHd0LmVkdS5jblwvYXBpXC9zc29Mb2dpbiI&sign=bcaa4cfd4075d31f7f5ab897348833b6bb9795d1

    /**
     * 返回登录信息
     * @param Request $request
     * @return JsonResponse
     */
    public function loginStatus(Request $request)
    {
        if ($request->session()->has('data')) {
            if ($request->session()->get('data')['type'] == 'student') {
                $data = $request->session()->get('data');
                $papers = Paper::TestsIntegrate($data['id'],$data['grade'],$data['college_code']);
                return Result::SuccessReturnData(1, ['stu_info' => $data, 'papers' => $papers]);
            } else {
                return Result::ErrorReturn(1, 'illigal user');
            }
        } else {
            return Result::ErrorReturn(1, 'student not logged in');
        }
    }

    /**
     * 登出
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->session()->forget('data');
        $request->session()->flush();
        return Result::SuccessReturnMessage(1, 'logout successful');
    }

    /**
     * 初始化
     * @return Api
     */
    public static function getSSO()
    {
        //使用单点登陆,需要提供key和id
        //这个是天外天管理系统生成管理的,每个项目会分配一个
        $app_key = "uaRO1iPlXW2uVS3dTA9E";
        $app_id = "44";
//        $app_id = "10";
//        $app_key = "9X8AyGyU689p3PEuKrkl";
        return new Api($app_id, $app_key);
    }

    /**
     * 若数据库无这个用户就增加这个用户
     * @param $token
     * @return void
     */
    public static function addNewUser($token)
    {
        $sso = self::getSSO();
        $userinfo = $sso->fetchUserInfo($token);
        Student::AddNewStudent($userinfo);
    }

    /**
     * 将登录后的信息存储到session
     * @param $token
     * @return bool
     */
    public function storage($token)
    {
        //这个token就是返回来的token,用于获得登录用户信息
        $sso = self::getSSO();

        //fetchUserInfo用来得到用户信息,用token
        $userinfo = $sso->fetchUserInfo($token);

        if ($userinfo != null && $userinfo->status == 1) {
            $result = $userinfo->result;
            $ui = $result->user_info;
            $data['type'] = 'student';
            $data['id'] = $result->id;
            $data['user_number'] = $result->user_number;
            $data['twt_name'] = $result->twt_name;
            $data['real_name'] = $ui->username;
            $data['college'] = $result->college;
            $data['college_code'] = $result->college_code;
            $data['token'] = $token;
            $data['class_id'] = $ui->class_id;
            $data['grade'] = $ui->grade;
            $data['permission'] = $this->permissionRepository->GetPermissionCode($data['id']);
            session([
                'data' => $data,
            ]);
            session()->save();

            DB::table('login_records')->insert([
                'twt_id'=>$result->id,
                'token'=>$token,
                'created_at'=>date('Y-m-d H:i:s')
            ]);
            return true;
        } else {
            return false;
        }
    }

    public function ssoLogin(Request $request)
    {
        $token = $request->get('token');
        $this->paperRepository->PaperStatusVerify();
        if ($this->storage($token)) {
            self::addNewUser($token);
            header("Location: https://theory.twt.edu.cn");
            exit();
        } else {
            return response()->json([
                'status' => "error",
                'message' => "token错误"
            ]);
        }
    }

    /**
     * 测试用快速登录
     * @param Request $request
     * @return JsonResponse
     */
    public function TmpLogin(Request $request){
        if ($request->session()->has('data') and $request->session()->get('data')['type'] == 'student'){
            $data = $request->session()->get('data');
            $papers = Paper::TestsIntegrate($data['id'],$data['grade'],$data['college_code']);
            session([
                'papers' => $papers
            ]);
        }else{
            $stu_id = $request->get('stu_id');
            $data=Student::where('stu_id',$stu_id)->first();
            $data->id = $data->twt_id;
            $data->permission = 1;
            $data->type='student';
            $papers = Paper::TestsIntegrate($data['id'],$data['grade'],$data['college_code']);
            session([
                'data' => $data,
                'papers' => $papers
            ]);
        }
        session()->save();

        return Result::SuccessReturnMessage(1,'success');
    }

}
