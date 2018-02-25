<?php
namespace App\Helper;

use Overtrue\Pinyin\Pinyin;
use Carbon\Carbon;

class Helper {

	function getCnToPinyin($str) {
		$py = new Pinyin();
		return $py -> convert($str);
	}

	/**
	 * 获取指定日期的公历农历描述
	 */
	function getDateToGLNL($date) {
		if (empty($date)) {
			$date = Carbon::now();
		}
		$week = array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
		return array($date, $week[$date -> dayOfWeek], $this -> _CnDateofDateStr($date), $this -> _SolarTerm($date));
	}
	/**
	 * 获取指定日期的二十四节气
	 */
	function SolarTerm($date) {
		if (empty($date)) {
			$date = Carbon::now();
		}
		return $this -> _SolarTerm($date);
	}
	/**
	 * 计算日期是当前年份中的第多少天
	 */
	private function _DaysNumberofDate($date) {
		return $date -> dayOfYear + 1;
	}

	/**
	 * 获取中文农历显示
	 */
	private function _CnDateofDateStr($date) {
		if ($this -> _CnMonthofDate($date) == "零月")
			return "　请调整您的计算机日期!";
		else
			return $this -> _CnYearofDate($date) . " " . $this -> _CnMonthofDate($date) . $this -> _CnDayofDate($date);
	}

	/**
	 * 获取农历中文
	 */
	private function _CnDayofDate($date) {
		$CnDayStr = array("零", "初一", "初二", "初三", "初四", "初五", "初六", "初七", "初八", "初九", "初十", "十一", "十二", "十三", "十四", "十五", "十六", "十七", "十八", "十九", "二十", "廿一", "廿二", "廿三", "廿四", "廿五", "廿六", "廿七", "廿八", "廿九", "三十");
		$Day = (abs($this -> _CnDateofDate($date))) % 100;
		if ("初一" == $CnDayStr[$Day]) {
			return $this -> _CnMonthofDate($date);
		} else {
			$st = $this -> _SolarTerm($date);
			if ($st != "") {
				return $CnDayStr[$Day] . " " . $st;
			} else {
				return $CnDayStr[$Day];
			}

		}
	}

	/**
	 * 获取年农历描述
	 */
	private function _CnYearofDate($date) {
		$YYYY = $date -> year;
		$MM = $date -> month;
		$CnMM = intval(abs($this -> _CnDateofDate($date)) / 100);
		if ($YYYY < 100)
			$YYYY += 1900;
		if ($CnMM > $MM)
			$YYYY--;
		$YYYY -= 1864;
		return $this -> _CnEra($YYYY) . "年";
	}

	/**
	 * 获取天之地干
	 */
	private function _CnEra($year) {
		$Tiangan = array("甲", "乙", "丙", "丁", "戊", "己", "庚", "辛", "壬", "癸");
		$Dizhi = array("子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥");
		return $Tiangan[$year % 10] . $Dizhi[$year % 12];
	}

	private function _CnMonthofDate($date) {
		$CnMonthStr = array("零", "正", "二", "三", "四", "五", "六", "七", "八", "九", "十", "冬", "腊");
		$Month = intval($this -> _CnDateofDate($date) / 100);
		if ($Month < 0) {
			return "闰" . $CnMonthStr[-$Month] . "月";
		} else {
			return $CnMonthStr[$Month] . "月";
		}
	}

	private function _CnDateofDate($date) {
		$CnData = array(0x16, 0x2a, 0xda, 0x00, 0x83, 0x49, 0xb6, 0x05, 0x0e, 0x64, 0xbb, 0x00, 0x19, 0xb2, 0x5b, 0x00, 0x87, 0x6a, 0x57, 0x04, 0x12, 0x75, 0x2b, 0x00, 0x1d, 0xb6, 0x95, 0x00, 0x8a, 0xad, 0x55, 0x02, 0x15, 0x55, 0xaa, 0x00, 0x82, 0x55, 0x6c, 0x07, 0x0d, 0xc9, 0x76, 0x00, 0x17, 0x64, 0xb7, 0x00, 0x86, 0xe4, 0xae, 0x05, 0x11, 0xea, 0x56, 0x00, 0x1b, 0x6d, 0x2a, 0x00, 0x88, 0x5a, 0xaa, 0x04, 0x14, 0xad, 0x55, 0x00, 0x81, 0xaa, 0xd5, 0x09, 0x0b, 0x52, 0xea, 0x00, 0x16, 0xa9, 0x6d, 0x00, 0x84, 0xa9, 0x5d, 0x06, 0x0f, 0xd4, 0xae, 0x00, 0x1a, 0xea, 0x4d, 0x00, 0x87, 0xba, 0x55, 0x04);
		$CnMonth = array();
		$CnMonthDays = array();
		$CnBeginDay;
		$LeapMonth;
		$Bytes = array();
		$I;
		$CnMonthData;
		$DaysCount;
		$CnDaysCount;
		$ResultMonth;
		$ResultDay;
		$yyyy = $date -> year;
		$mm = $date -> month;
		$dd = $date -> day;
		if ($yyyy < 100)
			$yyyy += 1900;
		if (($yyyy < 1997) || ($yyyy > 2020)) {
			return 0;
		}
		$Bytes[0] = $CnData[($yyyy - 1997) * 4];
		$Bytes[1] = $CnData[($yyyy - 1997) * 4 + 1];
		$Bytes[2] = $CnData[($yyyy - 1997) * 4 + 2];
		$Bytes[3] = $CnData[($yyyy - 1997) * 4 + 3];
		if (($Bytes[0] & 0x80) != 0) {
			$CnMonth[0] = 12;
		} else {
			$CnMonth[0] = 11;
		}
		$CnBeginDay = ($Bytes[0] & 0x7f);
		$CnMonthData = $Bytes[1];
		$CnMonthData = $CnMonthData<<8;
		$CnMonthData = $CnMonthData | $Bytes[2];
		$LeapMonth = $Bytes[3];
		for ($I = 15; $I >= 0; $I--) {
			$CnMonthDays[15 - $I] = 29;
			if (((1<<$I) & $CnMonthData) != 0) {
				$CnMonthDays[15 - $I]++;
			}
			if ($CnMonth[15 - $I] == $LeapMonth) {
				$CnMonth[15 - $I + 1] = -$LeapMonth;
			} else {
				if ($CnMonth[15 - $I] < 0) {
					$CnMonth[15 - $I + 1] = -$CnMonth[15 - $I] + 1;
				} else {
					$CnMonth[15 - $I + 1] = $CnMonth[15 - $I] + 1;
				}
				if ($CnMonth[15 - $I + 1] > 12) {
					$CnMonth[15 - $I + 1] = 1;
				}
			}
		}
		$DaysCount = $this -> _DaysNumberofDate($date) - 1;
		if ($DaysCount <= ($CnMonthDays[0] - $CnBeginDay)) {
			if (($yyyy > 1901) && ($this -> _CnDateofDate(Carbon::create($yyyy - 1, 12, 31)) < 0)) {
				$ResultMonth = -$CnMonth[0];
			} else {
				$ResultMonth = $CnMonth[0];
			}
			$ResultDay = $CnBeginDay + $DaysCount;
		} else {
			$CnDaysCount = $CnMonthDays[0] - $CnBeginDay;
			$I = 1;
			while (($CnDaysCount < $DaysCount) && ($CnDaysCount + $CnMonthDays[$I] < $DaysCount)) {
				$CnDaysCount += $CnMonthDays[$I];
				$I++;
			}
			$ResultMonth = $CnMonth[$I];
			$ResultDay = $DaysCount - $CnDaysCount;
		}
		if ($ResultMonth > 0) {
			return $ResultMonth * 100 + $ResultDay;
		} else {
			return $ResultMonth * 100 - $ResultDay;
		}
	}

	private function _SolarTerm($date) {
		$SolarTermStr = array("小寒", "大寒", "立春", "雨水", "惊蛰", "春分", "清明", "谷雨", "立夏", "小满", "芒种", "夏至", "小暑", "大暑", "立秋", "处暑", "白露", "秋分", "寒露", "霜降", "立冬", "小雪", "大雪", "冬至");
		$DifferenceInMonth = array(1272060, 1275495, 1281180, 1289445, 1299225, 1310355, 1321560, 1333035, 1342770, 1350855, 1356420, 1359045, 1358580, 1355055, 1348695, 1340040, 1329630, 1318455, 1306935, 1297380, 1286865, 1277730, 1274550, 1271556);
		$DifferenceInYear = 31556926;
		$BeginTime = Carbon::create(1901, 1, 1, 0, 0, 0);
		$BeginTime = $BeginTime -> timestamp(947120460000 / 1000);
		for (; $date -> year < $BeginTime -> year; ) {
			$BeginTime = $BeginTime -> timestamp($BeginTime -> timestamp - $DifferenceInYear);
		}
		for (; $date -> year > $BeginTime -> year; ) {
			$BeginTime = $BeginTime -> timestamp($BeginTime -> timestamp + $DifferenceInYear);
		}
		for ($M = 0; $date -> month > $BeginTime -> month; $M++) {
			$BeginTime = $BeginTime -> timestamp($BeginTime -> timestamp + $DifferenceInMonth[$M]);
		}
		if ($date -> day > $BeginTime -> day) {
			$BeginTime = $BeginTime -> timestamp($BeginTime -> timestamp + $DifferenceInMonth[$M]);
			$M++;
		}
		if ($date -> day > $BeginTime -> day) {
			$BeginTime = $BeginTime -> timestamp($BeginTime -> timestamp + $DifferenceInMonth[$M]);
			$M == 23 ? $M = 0 : $M++;
		}
		$JQ = "";
		if ($date -> day == $BeginTime -> day) {
			$JQ = $SolarTermStr[$M];
		}
		return $JQ;
	}

}
?>