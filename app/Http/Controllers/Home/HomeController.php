<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\BaseController;
use App\Models\User\AcWxUser;
use Redirect;
use Weixin;
use Share;
use View;
use Log;


/**
 * todo controller example
 * Class NotesController
 * @package App\Http\Controllers\Home
 */
class HomeController extends BaseController {
	public function getIndex() {
		//return Redirect::to('jx/index.html');
		return view('index');
	}

	public function getDown() {
		$type = request('type', '0');
		return view('down',['type'=>$type,'is_weixin'=>Share::is_weixin()]);
	}
	public function getDownLoad() {
		$type = request('type', '0');
		return view('download',['type'=>$type]);
	}
	public function getLiteDown(){
		if($this->checkmobile()){
			//移动端，直接跳转到下载界面
			return redirect("http://a.app.qq.com/o/simple.jsp?pkgname=duoduo.app");
		}else{
			//非移动端，跳转到下载显示页面
			$type = request('type', '0');
			return view('litedown',['type'=>$type]);
		}
	}
	public function getAcList() {
		$params = request() -> input();
		Log::info('台账系统微信授权获取参数为:' . json_encode($params));
		$url = "http://www.mdg-app.com/aw/cp?userid=";
		$errUrl = "http://www.mdg-app.com/ac/error";
		$code = request('code');
		if (!empty($code)) {
			$wxuser = Weixin::getAuthorizeToken($code);
			Log::info('微信授权用户信息为:' . json_encode($wxuser));
			if (!empty($wxuser)) {
				//做用户校验
				$acwxUser = $this -> checkExitUser($wxuser);
				if (!empty($acwxUser)) {
					Log::info('台账微信获取用户信息:' . json_encode($acwxUser));
					$url .= $acwxUser['id'];
				} else {
					$url = $errUrl . "?msg=更新微信信息失败&code=1002";
				}
			} else {
				$url = $errUrl . "?msg=获取微信授权失败&code=1003";
			}
		} else {
			$url = $errUrl . "?msg=无效的授权路径&code=1004";
		}
		//$url = 'http://www.mdg-app.com/ac/list?' . implode("&", $ps);
		Log::info('获取参数为:' . json_encode($params) . ";重新跳转路径 :" . $url);
		return Redirect::to($url) -> withInput() -> send();
	}

	//校验微信用户是否已经授权存在
	private function checkExitUser($user) {
		$acwxUser = new AcWxUser();
		$openid = $user['openid'];
		/*if (!empty($user['unionid'])) {
			$openid = $user['unionid'];
		}*/
		$result = $acwxUser::where('openid', $openid) -> first();
		Log::info('台账系统微信用户,openid:' . $openid . ";结果为:" . json_encode($result));

		if (!empty($result)) {//更新数据
			$acwxUser = $result;
			//$wxUser -> save($data);
		} else {//插入数据
			$data['openid'] = $user['openid'];
			$data['name'] = $user['nickname'];
			$data['realname'] = $user['nickname'];
			$data['sex'] = $user['sex'];
			$data['headimgurl'] = $user['headimgurl'];
			$data['city'] = $user['city'];
			$data['province'] = $user['province'];
			$data['country'] = $user['country'];
			$acwxUser -> create($data);
		}
		return $acwxUser;
	}
//私有方法

	/**
	 * 根据php的$_SERVER['HTTP_USER_AGENT'] 中各种浏览器访问时所包含各个浏览器特定的字符串来判断是属于PC还是移动端
	 * @return  bool
	 */
	private function checkmobile() {
		Log::info("浏览器信息".json_encode($_SERVER));
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
			return true;
		}
		// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset($_SERVER['HTTP_VIA'])) {
			// 找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		global $_G;
		$mobile = array();
		//各个触控浏览器中$_SERVER['HTTP_USER_AGENT']所包含的字符串数组
		static $touchbrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini', 'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung', 'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser', 'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource', 'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone', 'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop', 'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
		//window手机浏览器数组【猜的】
		static $mobilebrowser_list = array('windows phone');
		//wap浏览器中$_SERVER['HTTP_USER_AGENT']所包含的字符串数组
		static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom', 'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh', 'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');
		$pad_list = array('pad', 'gt-p1000');
		$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if ($this -> dstrpos($useragent, $pad_list)) {
			return false;
		}
		if (($v = $this -> dstrpos($useragent, $mobilebrowser_list, true))) {
			$_G['mobile'] = $v;
			return '1';
		}
		if (($v = $this -> dstrpos($useragent, $touchbrowser_list, true))) {
			$_G['mobile'] = $v;
			return '2';
		}
		if (($v = $this -> dstrpos($useragent, $wmlbrowser_list))) {
			$_G['mobile'] = $v;
			return '3';
			//wml版
		}
		$brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
		if ($this -> dstrpos($useragent, $brower))
			return false;
		$_G['mobile'] = 'unknown';
		//对于未知类型的浏览器，通过$_GET['mobile']参数来决定是否是手机浏览器
		if (isset($_G['mobiletpl'][$_GET['mobile']])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 判断$arr中元素字符串是否有出现在$string中
	 * @param  $string     $_SERVER['HTTP_USER_AGENT']
	 * @param  $arr          各中浏览器$_SERVER['HTTP_USER_AGENT']中必定会包含的字符串
	 * @param  $returnvalue 返回浏览器名称还是返回布尔值，true为返回浏览器名称，false为返回布尔值【默认】
	 * @author           discuz3x
	 * @lastmodify    2014-04-09
	 */
	private function dstrpos($string, $arr, $returnvalue = false) {
		if (empty($string))
			return false;
		foreach ((array)$arr as $v) {
			if (strpos($string, $v) !== false) {
				$return = $returnvalue ? $v : true;
				return $return;
			}
		}
		return false;
	}

}
