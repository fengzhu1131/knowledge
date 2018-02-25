<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Validator;
use Log;
use Request;
use Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
   // protected $redirectTo = '/';
	protected $username = 'username';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	//$this->auth = $auth;
       // $this->registrar = $registrar;
    	
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255',
            'password' => 'required|min:6',
        ]);
    }
    ///提交登录验证
     public function postLogin()
     {
     	if (Request::isMethod('post')) {
			$p = Request::all();
			//$pw = md5($p['password']);
			$pw = $p['password'];
			if($this->checkUser($p['username'])){
				if (Auth::attempt(array('username'=>$p['username'], 'password'=>$pw))) {
	            	//return Redirect::to($p['selSystem']);
					//return Redirect::intended();
					//return Redirect::to('oper/jxIndex');
					return Redirect::to('oper');
		        } else {
		            return Redirect::to('oper/login')
		                ->withErrors(array('message'=>'用户名或密码不正确!'))
						 ->withInput();
		        }
			}else {
		            return Redirect::to('oper/login')
		                ->withErrors(array('message'=>'没有登录后台的权限!'))
						 ->withInput();
		        }						
		}  	         
     }
	 /**
	  * 校验固定的用户才能进行登录
	  */
	 private function checkUser($username){
	 	//$uList = ['18612246863','18658153900','18657971573','17717033737','17317914626','17717369797'];
	 	$uList = ['18612246863','18658153900','18657971573','17717369797','13681806334','13183838214','17717083965','15221565492'];
	 	//$uList = ['18612246863','18658153900','18657971573','17717369797','13681806334'];
	 	$uList = explode(",", env('ADMIN_USERNAME'));
		$isCheck = false;
		foreach ($uList as $key => $value) {
			if($value == $username){
				$isCheck = true;
				break;
			}
		}
		return $isCheck;
	 }
	  // 登出
     public function getLogout()
     {
          Auth::logout();
          return Redirect::to('oper/login');
     }
 
}
