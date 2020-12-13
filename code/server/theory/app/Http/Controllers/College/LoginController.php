<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Result;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * 登录数据存session
     * @param $userName
     * @param $password
     * @return JsonResponse
     */
    public function LoginStorage($userName, $password)
    {
        $user = College::auth($userName, $password);
        if ($user == 'error') {
            return Result::ErrorReturn(1, 'wrong username or password');
        } else {
            return Result::SuccessReturnData(1, $user);
        }
    }

    /**
     * 登录
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $userName = $request->userName;
        $password = $request->passWord;
        if ($request->session()->has('data')) {
            $data = $request->session()->get('data');
            $token = $data->token;
            if (College::CheckToken($token) != 0) {
                return Result::SuccessReturnMessage(1, 'already logged in');
            } else {
                return $this->LoginStorage($userName, $password);
            }
        } else {
            return $this->LoginStorage($userName, $password);
        }
    }

    /**
     * 登录状态返回
     * @param Request $request
     * @return JsonResponse
     */
    public function loginStatus(Request $request)
    {
        if ($request->session()->has('data')) {
            if ($request->session()->get('data')['type'] == 'college') {
                $user = $request->session()->get('data');
                return Result::SuccessReturnData(1, $user);
            } else {
                return Result::ErrorReturn(1, 'illigal user');
            }
        } else {
            return Result::ErrorReturn(1, 'not logged in');
        }
    }

    /**
     * 登出
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return Result::SuccessReturnMessage(1, 'logout succeed');
    }

    /**
     * 改密码
     * @param Request $request
     * @return JsonResponse
     */
    public function PasswordReset(Request $request)
    {
        if ($request->session()->get('data')['type'] == 'college') {
            $name = $request->session()->get('data')['name'];
            $old = md5($request->old_password);
            $new = md5($request->new_password);
            $count = College::PRCheck($name, $old);
            if ($count == 1) {
                $data = College::PRUserGet($name, $old);
                $data->password = $new;
                $data->save();
                return Result::SuccessReturnMessage(1, 'password reset successful');
            } else {
                return Result::ErrorReturn(1, 'illegal user');
            }
        } else {
            return Result::ErrorReturn(1, 'illegal user');
        }
    }

    /**
     * 测试用快速登录（上线删除）
     * @param Request $request
     * @return JsonResponse
     */
    public function TmpLogin(Request $request)
    {
        $college_code = $request->get('college_code');
        $user = College::where('college_code', $college_code)
            ->select('id', 'name', 'college_code', 'token')
            ->first();
        $user->token = md5($college_code);
        $user->save();
        unset($user->updated_at);
        $user->type = 'college';
        session(['data' => $user]);
        session()->save();
        return Result::SuccessReturnData(1, $user);
    }
}
