<?php
namespace App\Libraries\Helplib;
use Log;

class HelpLibServices extends BaseService {
	public function __construct() {
		$this -> config = $this -> loadConfig();
		$this -> uri = $this -> config['url'];
		self::$helpLib = BaseService::init();
	}

	protected function loadConfig() {
		return config("services.osspush");
	}

	/**
	 * 获取浏览器分析对象
	 * @return {Class} AnalysisBrowser
	 */
	public function getAnalysisBrowser() {
		return new AnalysisBrowser();
	}

	/**
	 * 获取错误信息对象
	 * @return {Class} AppConfig
	 */
	public function getAppConfig() {
		return new AppConfig();
	}

	/**
	 * 校验文本是否网址
	 */
	public function checkTextIsUrl($text) {
		return preg_match('#^(http|https|ftp|ftps)://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?$#ui', $text);
	}

	public function getUrlData($url) {
		$exceptionDn = null;//$this -> getDomainNameInfo($url);
		if (!empty($exceptionDn)) {
			$res = $exceptionDn;
		} else {
			$html = new Htmldom($url);
			$res = array("notes" => "", "url_path" => $url, "url_img" => "", "url_title" => "");
			//常规的信息处理
			$doms = explode(",", 'title,meta[name=description],img');
			foreach ($doms as $k => $v) {
				foreach ($html->find($v) as $element) {
					if ($k == 0) {
						$res['notes'] = !empty($element -> plaintext) ? $this -> getText($element -> plaintext) : "";
						$res['url_title'] = !empty($element -> plaintext) ? $this -> getText($element -> plaintext) : "";
						break;
					}
					if ($k == 1) {
						$res['notes'] = !empty($element -> content) ? $this -> getText($element -> content) : "";
						break;
					}
					if ($k == 2) {
						$res['url_img'] = $this -> checkImgUrl($url, $element -> src);
						break;
					}
				}
			}
		}
		return $res;
	}

	/**
	 * 获取不同域名的信息
	 */
	private function getDomainNameInfo($url) {
		$html = new Htmldom($url);
		$res = array("notes" => "", "url_path" => $url, "url_img" => "", "url_title" => "");
		$domainName = "http://www.cnblogs.com";
		//获取博客园数据
		if (preg_match("/(" . preg_replace("/[\/]/i", "\\/", $domainName) . ")/iu", $url)) {
			$doms = explode(";", "title;#topics .postBody;img.img_avatar");
			Log::info("抓取博客园:" . json_encode($doms));
			//给定初始值
			//$res['url_img'] = "";
			$res['notes'] = "博客园";
			$res['url_title'] = "博客园";
			foreach ($doms as $k => $v) {
				foreach ($html->find($v) as $element) {
					if ($k == 0) {
						$res['notes'] = !empty($element -> plaintext) ? $this -> getText($element -> plaintext) : "";
						$res['url_title'] = !empty($element -> plaintext) ? $this -> getText($element -> plaintext) : "";
						break;
					}
					if ($k == 1) {
						$res['notes'] = !empty($element -> plaintext) ? $this -> getText($element -> plaintext) : "";
						break;
					}
					if ($k == 2) {
						//$res['url_img'] = $this -> checkImgUrl($url, $domainName . $element -> src);
						//break;
						$res['url_img'][] = "1111";
					}
					if ($k == 2 || $k == 3) {
						$res['url_img'][] = $element -> src;
					}
				}
			}
			return $res;
		}
		$domainName = "https://mp.weixin.qq.com";
		//获取博客园数据
		if (preg_match("/(" . preg_replace("/[\/]/i", "\\/", $domainName) . ")/iu", $url)) {
			$doms = explode(";", 'title;script[nonce=1956463050];#img-content img');
			//给定初始值
			$res['notes'] = "微信分享";
			$res['url_title'] = "微信分享";
			foreach ($doms as $k => $v) {
				foreach ($html->find($v) as $element) {
					if ($k == 0) {
						$res['notes'] = !empty($element -> plaintext) ? $this -> getText($element -> plaintext) : "";
						$res['url_title'] = !empty($element -> plaintext) ? $this -> getText($element -> plaintext) : "";
						break;
					}
					if ($k == 1) {
						$res['notes'] = !empty($element -> plaintext) ? $this -> getText($element -> plaintext) : "";
						break;
					}
					if ($k == 2 && !empty($element -> src)) {
						$res['url_img'] = $this -> checkImgUrl($url, $element -> src);
						break;
					}
				}
			}
			return $res;
		}
		return null;
	}

	/**
	 * 格式化获取文本信息
	 */
	private function getText($text) {
		return trim(preg_replace("/[\t\n\r\f\b]/i", "", mb_substr($text, 0, 200)));
	}

	/**
	 * 校验图片路径并修改成新的路径
	 */
	private function checkImgUrl($url, $src) {
		if (empty($src)) {
			return "";
		}
		if (preg_match("/(http|https|ftp|ftps)/iu", $src)) {
			return $src;
		}
		return $src;
	}

}

/**
 * 分析浏览器调用
 */
class AnalysisBrowser {
	private $HTTP_USER_AGENT;
	public function __construct() {
		$this -> HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * 获取浏览器类型
	 * @return string
	 */
	public function GetBrowser() {
		$sys = $_SERVER['HTTP_USER_AGENT'];
		//获取用户代理字符串
		if (stripos($sys, "Firefox/") > 0) {
			preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
			$exp[0] = "Firefox";
			$exp[1] = $b[1];
			//获取火狐浏览器的版本号
		} elseif (stripos($sys, "Maxthon") > 0) {
			preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
			$exp[0] = "傲游";
			$exp[1] = $aoyou[1];
		} elseif (stripos($sys, "MSIE") > 0) {
			preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
			$exp[0] = "IE";
			$exp[1] = $ie[1];
			//获取IE的版本号
		} elseif (stripos($sys, "OPR") > 0) {
			preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
			$exp[0] = "Opera";
			$exp[1] = $opera[1];
		} elseif (stripos($sys, "Edge") > 0) {
			//win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
			preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
			$exp[0] = "Edge";
			$exp[1] = $Edge[1];
		} elseif (stripos($sys, "Chrome") > 0) {
			preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
			$exp[0] = "Chrome";
			$exp[1] = $google[1];
			//获取google chrome的版本号
		} elseif (stripos($sys, 'rv:') > 0 && stripos($sys, 'Gecko') > 0) {
			preg_match("/rv:([\d\.]+)/", $sys, $IE);
			$exp[0] = "IE";
			$exp[1] = $IE[1];
		} else {
			$exp[0] = "未知浏览器";
			$exp[1] = "";
		}
		return $exp[0] . '(' . $exp[1] . ')';
	}

	/**
	 * 获取客户端操作系统信息包括win10
	 * @return array
	 * array('os' => '系统名称', 'osv' => '系统版本', 'osb' => '系统位数', 'dev' => '设备类型',
	 * 		 'nettype' => '网络情况', 'bw' => '浏览器名称', 'bwv' => '浏览器版本','kernel'=>'内核')
	 */
	function GetOSAndBrowser() {
		$agent = $this -> HTTP_USER_AGENT;
		/*print_r($_SERVER);
		 print_r('<br />');*/
		// $_SERVER['HTTP_USER_AGENT'];
		print_r('agent:');
		print_r($agent);
		print_r('<br/>');
		$rArr = array('os' => '未知系统', 'osv' => '', 'osb' => '', 'dev' => '', 'nettype' => '', 'bw' => '', 'bwv' => '', 'kernel' => '');
		//分析操作系统及版本
		if (preg_match('/win/i', $agent, $os) && strpos($agent, '95')) {
			$rArr['os'] = 'Windows 95';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/win 9x/i', $agent, $os) && strpos($agent, '4.90')) {
			$rArr['os'] = 'Windows ME';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/win/i', $agent, $os) && preg_match('/98/i', $agent)) {
			$rArr['os'] = 'Windows 98';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/win/i', $agent, $os) && preg_match('/nt 6.0/i', $agent)) {
			$rArr['os'] = 'Windows Vista';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/windows nt 6.1/i', $agent, $os)) {
			$rArr['os'] = 'Windows 7';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/win/i', $agent, $os) && preg_match('/nt 6.2/i', $agent)) {
			$rArr['os'] = 'Windows 8';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/win/i', $agent, $os) && preg_match('/nt 10.0/i', $agent)) {
			$rArr['os'] = 'Windows 10';
			$rArr['osv'] = $os[0];
			#添加win10判断
		} else if (preg_match('/win/i', $agent, $os) && preg_match('/nt 5.1/i', $agent)) {
			$rArr['os'] = 'Windows XP';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/win/i', $agent, $os) && preg_match('/nt 5/i', $agent)) {
			$rArr['os'] = 'Windows 2000';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/win/i', $agent, $os) && preg_match('/nt/i', $agent)) {
			$rArr['os'] = 'Windows NT';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/win/i', $agent, $os) && preg_match('/32/i', $agent)) {
			$rArr['os'] = 'Windows 32';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/linux/i', $agent, $os)) {
			$rArr['os'] = 'Linux';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/unix/i', $agent, $os)) {
			$rArr['os'] = 'Unix';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/sun/i', $agent, $os) && preg_match('/os/i', $agent)) {
			$rArr['os'] = 'SunOS';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/ibm/i', $agent, $os) && preg_match('/os/i', $agent)) {
			$rArr['os'] = 'IBM OS/2';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/Mac/i', $agent, $os) && preg_match('/PC/i', $agent)) {
			$rArr['os'] = 'Macintosh';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/PowerPC/i', $agent, $os)) {
			$rArr['os'] = 'PowerPC';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/AIX/i', $agent, $os)) {
			$rArr['os'] = 'AIX';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/HPUX/i', $agent, $os)) {
			$rArr['os'] = 'HPUX';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/NetBSD/i', $agent, $os)) {
			$rArr['os'] = 'NetBSD';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/BSD/i', $agent, $os)) {
			$rArr['os'] = 'BSD';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/OSF1/i', $agent, $os)) {
			$rArr['os'] = 'OSF1';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/IRIX/i', $agent, $os)) {
			$rArr['os'] = 'IRIX';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/FreeBSD/i', $agent, $os)) {
			$rArr['os'] = 'FreeBSD';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/teleport/i', $agent, $os)) {
			$rArr['os'] = 'teleport';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/flashget/i', $agent, $os)) {
			$rArr['os'] = 'flashget';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/webzip/i', $agent, $os)) {
			$rArr['os'] = 'webzip';
			$rArr['osv'] = $os[0];
		} else if (preg_match('/offline/i', $agent, $os)) {
			$rArr['os'] = 'offline';
			$rArr['osv'] = $os[0];
		}
		//版本位数
		if (preg_match('/WOW64|Win64/i', $agent, $os)) {
			$rArr['osb'] = '64 bit';
		}
		if (preg_match('/Win32/i', $agent, $os)) {
			$rArr['osb'] = '32 bit';
		}
		preg_match('/Web[kK]it[\/]{0,1}([\d.]+)/i', $agent, $webkit);

		preg_match('/(Android);?[\s\/]+([\d.]+)?/', $agent, $android);
		preg_match('/\(Macintosh\; Intel /', $agent, $osx);
		//osx = !!ua.match(/\(Macintosh\; Intel /),
		//preg_match('/NetType\/WIFI/', $agent, $wifi);
		preg_match('/(NetType)\/([\w|\s]+)/', $agent, $wifi);
		preg_match('/(iPad).*OS\s([\d_]+)/', $agent, $ipad);
		preg_match('/(iPod)(.*OS\s([\d_]+))?/', $agent, $ipod);
		print_r($ipod);
		print_r('<br />');
		!$ipad && preg_match('/(iPhone\sOS)\s([\d_]+)/', $agent, $iphone);
		print_r($iphone);
		print_r('<br />');
		preg_match('/(webOS|hpwOS)[\s\/]([\d.]+)/', $agent, $webos);

		//win = /Win\d{2}|Windows/.test(platform),
		preg_match('/Windows Phone ([\d.]+)/', $agent, $wp);
		$webos && preg_match('/TouchPad/', $agent, $touchpad);

		preg_match('/Kindle\/([\d.]+)/', $agent, $kindle);
		preg_match('/Silk\/([\d._]+)/', $agent, $silk);
		preg_match('/(BlackBerry).*Version\/([\d.]+)/', $agent, $blackberry);

		preg_match('/(BB10).*Version\/([\d.]+)/', $agent, $bb10);
		preg_match('/(RIM\sTablet\sOS)\s([\d.]+)/', $agent, $rimtabletos);
		preg_match('/PlayBook/', $agent, $playbook);
		preg_match('/Chrome\/([\d.]+)/', $agent, $chrome) || preg_match('/CriOS\/([\d.]+)/', $agent, $chrome);

		//国内常规浏览器使用统一的变量设置Standard browser
		preg_match('/(MicroMessenger|OPR|QQBrowser|MetaSr|Maxthon|BIDUBrowser|UBrowser|Navigator|CoolNovo|Wochacha)\/([\d\.]+)/i', $agent, $sbw);
		//非标准浏览器unStandard browser//这种浏览器只能识别浏览器，不能识别版本
		preg_match('/(LBBROWSER|qihu theworld|GiSoon)/i', $agent, $usbw);
		//特殊类型浏览器单独处理
		preg_match('/(BIDUBrowser)\s+([\d.]+)/i', $agent, $bd);
		/*preg_match('/LBBROWSER/i', $agent, $lb);
		 preg_match('/(qihu theworld)/i', $agent, $sjzc);
		 preg_match('/(GiSoon)/i', $agent, $fq);*/

		preg_match('/Firefox\/([\d.]+)/i', $agent, $firefox);
		preg_match('/\((?:Mobile|Tablet); rv:([\d.]+)\).*Firefox\/[\d.]+/i', $agent, $firefoxos);
		preg_match('/MSIE\s([\d.]+)/i', $agent, $ie) || preg_match('/Trident\/[\d](?=[^\?]+).*rv:([0-9.].)/i', $agent, $ie);

		!$chrome && preg_match('/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i', $agent, $webview);
		$webview || preg_match('/Version\/([\d.]+)([^S](Safari)|[^M]*(Mobile)[^S]*(Safari))/i', $agent, $safari);

		// Todo: clean this up with a better OS/browser seperation:
		// - discern (more) between multiple browsers on android
		// - decide if kindle fire in silk mode is android or not
		// - Firefox on Android doesn't specify the Android version
		// - possibly devide in os, device and browser hashes
		if ($webkit) {
			$rArr['bw'] = 'webkit';
			$rArr['bwv'] = $webkit[1];
		}
		//国内很多浏览器都是借用ie\firefox\chrome外套的，需要将这几种浏览器的类型判断放置在前面
		if ($android) {
			$rArr['os'] = 'android';
			$rArr['osv'] = $android[2];
		}
		//基础浏览器先进性判断
		if ($ie) {
			$rArr['bw'] = 'Internet Explorer';
			$rArr['bwv'] = $ie[1];
		}
		if ($firefox) {
			$rArr['bw'] = 'Firefox';
			$rArr['bwv'] = $firefox[1];
		}
		if ($safari) {
			$rArr['bw'] = 'Safari';
			$rArr['bwv'] = $safari[1];
		}
		if ($chrome) {
			$rArr['bw'] = 'Google Chrome';
			$rArr['bwv'] = $chrome[1];
		}
		//在微信浏览器中，会提交当前网络情况
		if ($wifi) {
			$rArr['nettype'] = str_replace(' Language', '', $wifi[2]);
		}
		//判断移动端设备
		if ($iphone && !$ipod) {
			$rArr['os'] = 'ios';
			$rArr['osv'] = str_replace('_', '.', $iphone[2]);
			$rArr['dev'] = 'iPhone';
		}
		if ($ipad) {
			$rArr['os'] = 'ios';
			$rArr['osv'] = str_replace('_', '.', $iphone[2]);
			$rArr['dev'] = 'ipad';
		}
		if ($ipod) {
			$rArr['os'] = 'ios';
			$rArr['osv'] = str_replace('_', '.', $iphone[2]);
			$rArr['dev'] = 'ipod';
		}
		if ($wp) {
			$rArr['bw'] = 'wp';
			$rArr['bwv'] = $wp[1];
		}
		if ($webos) {
			$rArr['os'] = 'Webos';
			$rArr['osv'] = $webos[2];
		}
		if ($touchpad) {
			$rArr['dev'] = 'touchpad';
		}
		if ($firefoxos) {
			$rArr['os'] = 'FirefoxOS';
			$rArr['osv'] = $firefoxos[1];
		}

		//标准浏览器
		if ($sbw) {
			$rArr['bw'] = $sbw[1];
			$rArr['bwv'] = $sbw[2];
		}
		//非标准浏览，只能识别浏览器类型，不能识别版本
		if ($usbw) {
			$rArr['bw'] = $usbw[0];
			$rArr['bwv'] = 'Unknown';
		}
		//特殊类型浏览器进行判断,特殊类型放在最下面进行判断
		if ($bd) {
			$rArr['bw'] = $bd[1];
			$rArr['bwv'] = $bd[2];
		}
		/*if ($weixin) {
		 $rArr['bw'] = 'WeiXin';
		 $rArr['bwv'] = $weixin[1];
		 }
		 if ($opera) {
		 $rArr['bw'] = 'Opera';
		 $rArr['bwv'] = $opera[1];
		 }
		 if ($qqb) {
		 $rArr['bw'] = $qqb[1];
		 $rArr['bwv'] = $qqb[2];
		 }
		 if ($lb) {
		 $rArr['bw'] = $lb[0];
		 $rArr['bwv'] = 'Unknown';
		 }
		 if ($sg) {
		 $rArr['bw'] = $sg[0];
		 $rArr['bwv'] = 'Unknown';
		 }
		 if ($aq) {
		 $rArr['bw'] = $aq[1];
		 $rArr['bwv'] = $aq[2];
		 }
		 if ($sjzc) {
		 $rArr['bw'] = $sjzc[0];
		 $rArr['bwv'] = 'Unknown';
		 }
		 if ($fq) {
		 $rArr['bw'] = $fq[0];
		 $rArr['bwv'] = 'Unknown';
		 }
		 if ($bd) {
		 $rArr['bw'] = $bd[1];
		 $rArr['bwv'] = $bd[2];
		 }
		 if ($uc) {
		 $rArr['bw'] = $uc[1];
		 $rArr['bwv'] = $uc[2];
		 }
		 if ($wj) {
		 $rArr['bw'] = $wj[1];
		 $rArr['bwv'] = $wj[2];
		 }
		 if ($fs) {
		 $rArr['bw'] = $fs[1];
		 $rArr['bwv'] = $fs[2];
		 }
		 if ($wcc) {
		 $rArr['bw'] = $wcc[1];
		 $rArr['bwv'] = $wcc[2];
		 }*/

		//增加内核浏览器标记
		$chrome ? $rArr['kernel'] = $chrome[0] : '';
		$ie ? $rArr['kernel'] = $ie[0] : '';
		$firefox ? $rArr['kernel'] = $firefox[0] : '';
		//国内尺长上常见的浏览器类型QQ浏览器、UC浏览器、Opera浏览器、欧鹏、Chrome、FireFox、Safari、

		/*if (iphone && !ipod) os.ios = os.iphone = true, os.version = iphone[2].replace(/_/g, '.')
		 if (ipad) os.ios = os.ipad = true, os.version = ipad[2].replace(/_/g, '.')
		 if (ipod) os.ios = os.ipod = true, os.version = ipod[3] ? ipod[3].replace(/_/g, '.') : null
		 if (wp) os.wp = true, os.version = wp[1]
		 if (webos) os.webos = true, os.version = webos[2]
		 if (touchpad) os.touchpad = true
		 if (blackberry) os.blackberry = true, os.version = blackberry[2]
		 if (bb10) os.bb10 = true, os.version = bb10[2]
		 if (rimtabletos) os.rimtabletos = true, os.version = rimtabletos[2]
		 if (playbook) browser.playbook = true
		 if (kindle) os.kindle = true, os.version = kindle[1]
		 if (silk) browser.silk = true, browser.version = silk[1]
		 if (!silk && os.android && ua.match(/Kindle Fire/)) browser.silk = true
		 if (chrome) browser.chrome = true, browser.version = chrome[1]
		 if (weixin) browser.weixin = true, browser.version = weixin[1]
		 if (firefox) browser.firefox = true, browser.version = firefox[1]
		 if (firefoxos) os.firefoxos = true, os.version = firefoxos[1]
		 if (ie) browser.ie = true, browser.version = ie[1]
		 if (safari && (osx || os.ios || win)) {
		 browser.safari = true
		 if (!os.ios) browser.version = safari[1]
		 }
		 if (webview) browser.webview = true

		 os.tablet = !!(ipad || playbook || (android && !ua.match(/Mobile/)) ||
		 (firefox && ua.match(/Tablet/)) || (ie && !ua.match(/Phone/) && ua.match(/Touch/)))
		 os.phone = !!(!os.tablet && !os.ipod && (android || iphone || webos || blackberry || bb10 ||
		 (chrome && ua.match(/Android/)) || (chrome && ua.match(/CriOS\/([\d.]+)/)) ||
		 (firefox && ua.match(/Mobile/)) || (ie && ua.match(/Touch/))))
		 os['browser'] = browser;*/

		return $rArr;
	}

	/**
	 * 是否安卓系统
	 */
	function isAndroid() {
		$uainfo = $this -> getUA();
		if (preg_match('/.*Opera.*/i', $uainfo)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 是否IOS系统
	 */
	function isIOS() {
		$uainfo = $this -> getUA();
		if (preg_match('/.*Opera.*/i', $uainfo)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 获取主机名
	 */
	function GetHost() {
		return $_SERVER['HTTP_HOST'];
	}

	/**
	 * 获取来源
	 */
	function GetReferer() {
		return $_SERVER['HTTP_REFERER'];
	}

	/**
	 * 函数名称: getPhoneNumber
	 * 函数功能: 取手机号
	 * 输入参数: none
	 * 函数返回值: 成功返回号码，失败返回false
	 * 其它说明: 说明
	 */
	function getPhoneNumber() {
		if (isset($_SERVER['HTTP_X_NETWORK_INFO'])) {
			$str1 = $_SERVER['HTTP_X_NETWORK_INFO'];
			$getstr1 = preg_replace('/(.*,)(11[d])(,.*)/i', "", $str1);
			return $getstr1;
		} elseif (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) {
			$getstr2 = $_SERVER['HTTP_X_UP_CALLING_LINE_ID'];
			return $getstr2;
		} elseif (isset($_SERVER['HTTP_X_UP_SUBNO'])) {
			$str3 = $_SERVER['HTTP_X_UP_SUBNO'];
			$getstr3 = preg_replace('/(.*)(11[d])(.*)/i', "", $str3);
			return $getstr3;
		} elseif (isset($_SERVER['DEVICEID'])) {
			return $_SERVER['DEVICEID'];
		} else {
			return false;
		}
	}

	/**
	 * 函数名称: getHttpHeader
	 * 函数功能: 取头信息
	 * 输入参数: none
	 * 函数返回值: 成功返回号码，失败返回false
	 * 其它说明: 说明
	 */
	function getHttpHeader() {
		$str = "";
		foreach ($_SERVER as $key => $val) {
			$gstr = str_replace("&", "&", $val);
			$str .= "$key -> " . $gstr . "rn";
		}
		return $str;
	}

	/**
	 * 函数名称: getUA
	 * 函数功能: 取UA
	 * 输入参数: none
	 * 函数返回值: 成功返回号码，失败返回false
	 * 其它说明: 说明
	 */
	function getUA() {
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			return $_SERVER['HTTP_USER_AGENT'];
		} else {
			return false;
		}
	}

	/**
	 * 函数名称: getPhoneType
	 * 函数功能: 取得手机类型
	 * 输入参数: none
	 * 函数返回值: 成功返回string，失败返回false
	 * 其它说明: 说明
	 */
	function getPhoneType() {
		$ua = $this -> getUA();
		if ($ua != false) {
			$str = explode(" ", $ua);
			return $str[0];
		} else {
			return false;
		}
	}

	/**
	 * 函数名称: isOpera
	 * 函数功能: 判断是否是opera
	 * 输入参数: none www.knowsky.com
	 * 函数返回值: 成功返回string，失败返回false
	 * 其它说明: 说明
	 */
	function isOpera() {
		$uainfo = $this -> getUA();
		if (preg_match('/.*Opera.*/i', $uainfo)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 函数名称: isM3gate
	 * 函数功能: 判断是否是m3gate
	 * 输入参数: none
	 * 函数返回值: 成功返回string，失败返回false
	 * 其它说明: 说明
	 */
	function isM3gate() {
		$uainfo = $this -> getUA();
		if (preg_match('/M3Gate/i', $uainfo)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 函数名称: getHttpAccept
	 * 函数功能: 取得HA
	 * 输入参数: none
	 * 函数返回值: 成功返回string，失败返回false
	 * 其它说明: 说明
	 */
	function getHttpAccept() {
		if (isset($_SERVER['HTTP_ACCEPT'])) {
			return $_SERVER['HTTP_ACCEPT'];
		} else {
			return false;
		}
	}

	/**
	 * 函数名称: getIP
	 * 函数功能: 取得手机IP
	 * 输入参数: none
	 * 函数返回值: 成功返回string
	 * 其它说明: 说明
	 */
	function getIP() {
		$ip = getenv('REMOTE_ADDR');
		$ip_ = getenv('HTTP_X_FORWARDED_FOR');
		if (($ip_ != "") && ($ip_ != "unknown")) {
			$ip = $ip_;
		}
		return $ip;
	}

	/**
	 * 判断是否移动端
	 * @return boole
	 */
	function isMobile() {
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
			return true;
		}
		// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset($_SERVER['HTTP_VIA'])) {
			// 找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		// 脑残法，判断手机发送的客户端标志,兼容性有待提高
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			}
		}
		// 协议法，因为有可能不准确，放到最后判断
		if (isset($_SERVER['HTTP_ACCEPT'])) {
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 获取手机位置
	 */
	function GetPosition() {
		//$ip = '202.98.96.68';
		$ip = getIP();
		$url = "http://api.map.baidu.com/location/ip?ak=aGlEjW0vegU01oARIpHIQSru&ip=$ip&coor=bd09ll";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		if (curl_errno($ch)) { echo 'CURL ERROR Code: ' . curl_errno($ch) . ', reason: ' . curl_error($ch);
		}
		curl_close($ch);
		$info = json_decode($output, true);
		return $info;
		/*print_r($info);
		 if ($info['status'] == "0") {
		 $lotx = $info['content']['point']['y'];
		 $loty = $info['content']['point']['x'];
		 $citytemp = $info['content']['address_detail']['city'];
		 $keywords = explode("市", $citytemp);
		 $city = $keywords[0];
		 } else {
		 $lotx = "34.2597";
		 $loty = "108.9471";
		 $city = "西安";
		 }*/
	}

}

/**
 * 配置文件，用于程序错误返回值
 */
class AppConfig {

	/**
	 * 编码分为3类：
	 * 1、10开头，为提交参数为空
	 * 2、20开头，为数据库查询为
	 * 3、30开头，为数据库操作失败
	 */
	private $QUN_RET_CODE_MSG = array(1000 => array("success" => 999, "msg" => "获取错误信息失败!"), 1001 => array("success" => 0, "msg" => "参数code没有传递!"), 1002 => array("success" => 0, "msg" => "userid为空!"), 1003 => array("success" => 0, "msg" => "类型为空!"), 1004 => array("success" => 0, "msg" => "id为空!"), 1005 => array("success" => 0, "msg" => "客户id为空!"), 1006 => array("success" => 0, "msg" => "待归档记录id为空!"), 1007 => array("success" => 0, "msg" => "客户ID为空!"), 1008 => array("success" => 0, "msg" => "获取客户数据失败!"), 1009 => array("success" => 0, "msg" => "用户名不能为空!"), 1010 => array("success" => 0, "msg" => "密码不能为空!"), 1011 => array("success" => 0, "msg" => "手机号码不能为空!"), 1012 => array("success" => 0, "msg" => "关键字不能为空!"), 1013 => array("success" => 0, "msg" => "缺少参数被保人who!"), 1014 => array("success" => 0, "msg" => "缺少参数boughtid!"), 1015 => array("success" => 0, "msg" => "沟通记录ID为空!"), 1016 => array("success" => 0, "msg" => "公司ID为空!"), 1017 => array("success" => 0, "msg" => "险种ID为空!"), 1018 => array("success" => 0, "msg" => "性别为空!"), 1019 => array("success" => 0, "msg" => "年龄为空!"), 1020 => array("success" => 0, "msg" => "缴费年限为空!"), 1021 => array("success" => 0, "msg" => "保费为空!"), 1022 => array("success" => 0, "msg" => "客户名为空!"), 1023 => array("success" => 0, "msg" => "计划书ID为空!"), 1024 => array("success" => 0, "msg" => "缺少参数myUserid!"), 1025 => array("success" => 0, "msg" => "待删除成员ID为空!"), 1026 => array("success" => 0, "msg" => "缺少参数invteid!"), 1027 => array("success" => 0, "msg" => "查询手机号为空!"), 1028 => array("success" => 0, "msg" => "picture_size为空!"), 1029 => array("success" => 0, "msg" => "status为空!"), 1030 => array("success" => 0, "msg" => "缺少参数notesid!"), 1031 => array("success" => 0, "msg" => "缺少参数recordid!"), 1032 => array("success" => 0, "msg" => "被邀请人id不能为空!"), 1033 => array("success" => 0, "msg" => "口令不能为空!"), 1034 => array("success" => 0, "msg" => "团队长ID不能为空!"), 1035 => array("success" => 0, "msg" => "申请ID不能为空!"), 1036 => array("success" => 0, "msg" => "动态ID不能为空!"), 1037 => array("success" => 0, "msg" => "评论ID不能为空!"), 1038 => array("success" => 0, "msg" => "配置信息不能为空!"), 1039 => array("success" => 0, "msg" => "用户名称不能为空!"), 1040 => array("success" => 0, "msg" => "邮箱地址不能为空!"), 1041 => array("success" => 0, "msg" => "邮箱地址格式不正确!"), 1042 => array("success" => 0, "msg" => "验证信息不正确!"), 1043 => array("success" => 0, "msg" => "自己不能加入自己的团队!"), 1044 => array("success" => 0, "msg" => "申请人手机号码不能为空!"), 1045 => array("success" => 0, "msg" => "推荐人手机号码不能为空!"), 1046 => array("success" => 0, "msg" => "token不能为空!"), 2000 => array("success" => 1, "msg" => "操作成功!"), 2001 => array("success" => 1, "msg" => "添加成功!"), 2002 => array("success" => 1, "msg" => "删除成功!"), 2003 => array("success" => 1, "msg" => "归档成功!"), 2004 => array("success" => 1, "msg" => "导入成功!"), 2005 => array("success" => 1, "msg" => "编辑成功!"), 2006 => array("success" => 1, "msg" => "登录成功!"), 2007 => array("success" => 1, "msg" => "注册成功!"), 2008 => array("success" => 1, "msg" => "用户存在!"), 2009 => array("success" => 0, "msg" => "用户不存在!"), 2010 => array("success" => 1, "msg" => "发送验证码成功!"), 2011 => array("success" => 0, "msg" => "未能计算出保费!"), 2012 => array("success" => 1, "msg" => "已成功发送邀请，请等待对方确认!"), 2013 => array("success" => 0, "msg" => "发送失败!"), 2014 => array("success" => 0, "msg" => "他已经是您的团长了!"), 2015 => array("success" => 0, "msg" => "他已经是您的下属了!"), 2016 => array("success" => 1, "msg" => "已成功发送邀请，请等待对方确认!"), 2017 => array("success" => 0, "msg" => "口令不存在，请确认输入是否正确!"), 2018 => array("success" => 1, "msg" => "校验通过!"), 2019 => array("success" => 1, "msg" => "已经发送过该团队加入申请，正在等待团队长确认！"), 2020 => array("success" => 0, "msg" => "团队长还未创建团队，请联系团队长重新创建团队！"), 2021 => array("success" => 0, "msg" => "待验证的信息不存在，请确认后再验证！"), 2022 => array("success" => 0, "msg" => "验证信息匹配不正确，请重新发送验证后再验证！"), 2023 => array("success" => 0, "msg" => "不能给自己的评论进行回复！"), 2024 => array("success" => 0, "code" => 2024, "msg" => "用户已经存在，不能使用该手机号码申请内测！"), 2025 => array("success" => 0, "msg" => "推荐人不存在！"), 2026 => array("success" => 0, "msg" => "您填写的推荐人已经超过了推荐限制！"), 2027 => array("success" => 0, "msg" => "您已经申请过，正在审核中...！"), 2028 => array("success" => 1, "msg" => "您的申请已经提交，请等待审核，审核结果我们会以短信方式通知您！"), 2029 => array("success" => 0, "msg" => "不能加入自己的团队成员的团队！"), 2030 => array("success" => 0, "msg" => "请先返回上一页提交申请信息！"), 2031 => array("success" => 0, "msg" => "没有获取到通知接收设备！"), 2032 => array("success" => 0, "msg" => "用户已经加入其他团队！"), 2033 => array("success" => 0, "msg" => "不需要发送消息！"), 2034 => array("success" => 1, "msg" => "用户没有加入团队！"), 3000 => array("success" => 0, "msg" => "操作失败!"), 3001 => array("success" => 0, "msg" => "归档失败!"), 3002 => array("success" => 0, "msg" => "用户名或密码错误!"), 3003 => array("success" => 0, "msg" => "注册失败,该用户已经存在!"), 3004 => array("success" => 0, "msg" => "更新用户信息失败!"), 3005 => array("success" => 0, "msg" => "重置密码失败!"), 3006 => array("success" => 0, "msg" => "计划书创建失败!"), 3007 => array("success" => 0, "msg" => "发送验证码失败!"), 3008 => array("success" => 0, "msg" => "申请失败!"));
	/**
	 * @description 获取返回信息
	 * @param $code 获取返回信息编码
	 * @param $msg 新制定的提交信息
	 */
	public function getRetMsg($code, $msg) {
		if (!isset($code)) {
			return $this -> $QUN_RET_CODE_MSG[1000];
		}
		if (isset($msg)) {
			/*$res=$this -> QUN_RET_CODE_MSG[$code];
			 $res['retmsg']=$msg;
			 return $res;*/
			//有数据库错误信息时打印日志
			Log::record(date('Y-m-d H:i:s') . ' ' . $msg, 'INFO', true);
		}
		return $this -> QUN_RET_CODE_MSG[$code];
	}

}
