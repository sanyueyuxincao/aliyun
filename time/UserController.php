<?php

namespace App\Http\Controllers\v1_0\Person;


use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected $users;
    public function __construct(Users $users)
    {
        parent::__construct();
        $this->users = $users;
    }

    public function register(Request $request)
    {
        $rules = $this->registRules();
        $messages = $this->messages();
        $this->validate($request,$rules, $messages);
        //验证通过后做其他处理
        //1.检查该邮箱是否注册过
        $username = $request->input('username','');
        $user_info = $this->users->where('username',$username)->get();
        if($user_info->isEmpty()){
            $data = $request->all();
            $data['token'] = md5(uniqid('user_').time());
            $data['password'] = md5(trim($data['password']));
            $res = $this->users->create($data);

            //发送邮件 todo

            return $this->apiResponse($res);
        }else{
            return $this->apiResponse([],20002);
        }

    }

    public function login(Request $request)
    {
        $rules = $this->loginRules();
        $messages = $this->messages();
        $this->validate($request,$rules, $messages);
        $username = $request->input('username');
        $password = md5($request->input('password'));
        $user_info = $this->users->where(function ($query) use ($username){
            return $query->where('username',$username)
                ->orWhere('telephone',$username);

        })
            ->where('password',$password)
            ->get();
        return $this->apiResponse($user_info);
    }

    public function registRules()
    {
        return [
            'username' => 'required|email|unique:web_users,username',
            'password' => 'required|min:6|max:20|alpha_dash',
        ];
    }

    public function loginRules()
    {
        return [
            'username' => 'required',
            'password' => 'required|min:6|max:20|alpha_dash',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '用户名称必须',
            'username.email' => '请填写有效邮箱',
            'username.unique' => '该邮箱已经注册过了',
            'password.required' => '密码必须填写',
            'password.min' => '密码最小6个字符',
            'password.max' => '密码最多20个字符',
            'password.alpha_dash' => '用户仅允许字母、数字、破折号（-）以及底线（_）',
        ];
    }

}