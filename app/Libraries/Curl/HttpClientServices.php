<?php
namespace App\Libraries\Curl;
use Log;

class HttpClientServices extends BaseService {
	public function __construct() {
		$this -> config = $this -> loadConfig();
		$this -> uri = $this -> config['url'];
		self::$httpClient = BaseService::init();
	}

	protected function loadConfig() {
		return config("services.osspush");
	}
	/**
	 * 获取团队活动量统计数据
	 * @param $data 待提交数据
	 * @param $sip server ip
	 * @return mixed
	 */
	public function getJXTeamStaticJSONList($data, $sip) {
		return $this -> httpBuilder('', $data, "POST", $sip . 'team_statistics/query_team_report');
	}
	/**
	 * 发送短信通知
	 * @param $data 待提交数据
	 * @param $sip server ip
	 * @return mixed
	 */
	public function postMessageToPhone($data, $sip) {
		return $this -> httpBuilder('', $data, "POST", $sip . 'sendmsg');
	}
	/**
	 * 获取统计直接界面显示
	 * @param $data 待提交数据
	 * @param $sip server ip
	 * @return mixed
	 */
	public function getJXStaticJSONList($data, $sip) {
		return $this -> httpBuilder('', $data, "GET", $sip . '/web/grjson');
	}

	/**
	 * 获取记信Lite的分享数据
	 * @param $data 待提交数据
	 * @param $sip server ip
	 * @return mixed
	 */
	public function getJXLiteJSONList($data, $sip) {
		return $this -> callJXLiteInterface($data, $sip, "Share/GetShareRecord");
	}

	/**
	 * 微信登录接口信息处理
	 * @param $data 待提交数据
	 * @param $sip server ip
	 */
	public function getJXLiteWxLogin($data, $sip) {
		return $this -> callJXLiteInterface($data, $sip, "User/WxLogin");
	}

	/**
	 * 获取记信Lite的分享数据
	 * @param $data 待提交数据
	 * @param $sip server ip
	 * @return mixed
	 */
	public function updateJXLite($data, $sip) {
		return $this -> callJXLiteInterface($data, $sip, "Share/IncReviewCount");
	}

	/**
	 * 获取记信统计接口数据
	 * @param $data 待提交数据
	 * @return mixed
	 */
	public function getJxStaticOfInterface($data) {
		return $this -> httpBuilder('', $data, "GET", env('JX_STATIC_URL'));
	}
	/**
	 * 获取记信运营原数据接口
	 * @param $data 待提交数据
	 * @return mixed
	 */
	public function getJxOldOfInterface($data,$face) {
		return $this -> httpBuilder('', $data, "GET", env('YY_INTERFACE_URL').$face);
	}
	/**
	 * 调用记信lite接口获取数据
	 * @param $data 待提交数据
	 * @param $sip server ip
	 * @param $face 接口名称
	 * @param $method 调用方法,默认为post提交
	 * @return mixed
	 */
	private function callJXLiteInterface($data, $sip, $face, $method = "POST") {
		return $this -> httpBuilder('', $data, $method, $sip . $face);
	}

	/**
	 * 推送版本更新通知
	 * @param $data 调用推送推送通知给app，更新版本
	 * @return mixed
	 */
	public function pushVersionMessage($data) {
		return $this -> httpBuilder('/OSSPushMessage.php', $data, "POST");
	}

	/**
	 * 推送版本更新通知
	 * @param $data 调用推送推送通知给app，更新版本
	 * @return mixed
	 */
	public function getAmrFile($url, $data) {
		return $this -> httpBuilder("", $data, "GET", $url);
	}

	/**
	 * 推送版本更新通知
	 * @param $data 调用推送推送通知给app，更新版本
	 * @return mixed
	 */
	public function getBookFile($url, $data) {
		return $this -> httpBuilder("", $data, "GET", $url);
	}

	/**
	 * 调用函数
	 * @param $data 调用推送推送通知给app，更新版本
	 * @return mixed
	 */
	public function getMethod($url, $data, $method = "GET") {
		return $this -> httpBuilder("", $data, $method, $url);
	}

}
