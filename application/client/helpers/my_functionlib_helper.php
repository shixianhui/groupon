<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * print ajax error
 *
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('getBaseUrl')) {
	function getBaseUrl($isHtml = false, $htmlUrl = '', $unHtmlUrl = '', $client_index = '') {
		$url = '';
		if ($isHtml) {
		    $url = $htmlUrl;
		} else {
		    $url = $client_index;
    	    $url .= $client_index?'/':'';
    	    $url .= $unHtmlUrl;
		}
		
		return $url;
	}
}

// ------------------------------------------------------------------------
/**
 * 获取字的宽度
 *
 *
 */
if ( ! function_exists('getStrWidth')) {
	function getStrWidth($str) {
		$len = mb_strlen($str);
		$letterCount = 0.0;
		for ($i = 0; $i < $len; $i++) {
		    if (strlen(mb_substr($str, $i, 1)) == 3) {
		        $letterCount += 1.0;
		    } else {
		        $letterCount += 0.5;
		    }
		}
		
		return $letterCount;
	}
}

// ------------------------------------------------------------------------

/**
 * Intercept length of the string
 *
 *
 * @access	public
 * @param	string
 * @param	int
 * @param	string
 * @return	string
 */
if ( ! function_exists('my_substr')) {
function my_substr($string, $length, $dot = '...', $charset = 'utf-8') {
		if(strlen($string) <= $length) return $string;	
		$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);	
		$strcut = '';		
		if(strtolower($charset) == 'utf-8') {
			$n = $tn = $noc = 0;			
			while($n < strlen($string)) {
				$t = ord($string[$n]);	
				// 特别要注意这部分，utf-8是1--6位不定长表示的，这里就是如何
				// 判断utf-8是1位2位还是3位还是4、5、6位,这对其他语言的编程也有用处
				// 具体可以查看rfc3629或rfc2279
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1; $n++; $noc++;
				} else if(194 <= $t && $t <= 223) {
					$tn = 2; $n += 2; $noc += 2;
				} elseif(224 <= $t && $t < 239) {
					$tn = 3; $n += 3; $noc += 2;
				} elseif(240 <= $t && $t <= 247) {
					$tn = 4; $n += 4; $noc += 2;
				} elseif(248 <= $t && $t <= 251) {
					$tn = 5; $n += 5; $noc += 2;
				} elseif($t == 252 || $t == 253) {
					$tn = 6; $n += 6; $noc += 2;
				} else {
					$n++;
				}
	
				if($noc >= $length) {
					break;
				}
			}
			
			if($noc > $length) $n -= $tn;
			
			$strcut = substr($string, 0, $n);
		} else {
			for($i = 0; $i < $length; $i++) {
				$strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
			}
		}
		//$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
		return strlen($string) > strlen($strcut)? $strcut . $dot:$strcut;
    }
}

// ------------------------------------------------------------------------

/**
 * Display html
 *
 */
if ( ! function_exists('html')) {
    function html($str) {
    	return html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
    }
}

// ------------------------------------------------------------------------

/**
 * Html filter
 *
 */
if ( ! function_exists('unhtml')) {
    function unhtml($str) {
    	return htmlentities($str, ENT_NOQUOTES, 'UTF-8');
    }
}

// ------------------------------------------------------------------------

/**
 * clear string
 *
 */
if ( ! function_exists('clearstring')) {
    function clearstring($str) {
    	return str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $str);
    }
}


// ------------------------------------------------------------------------

/**
 * 生成密码
 *
 *
 * @access	public
 * @param int 
 * @return	string
 */
if ( ! function_exists('getRandNum')) {
	function getRandNum($len) {
	    $str = '0123456789';
		$maxLen = strlen($str)-1;
		$randStr = '';
		for ($i = 0; $i < $len; $i++) {
		    $randStr .= substr($str, rand(0, $maxLen), 1);
		}
		
		return $randStr;
	}
}

// ------------------------------------------------------------------------

/**
 * 生成密码
 *
 *
 * @access	public
 * @param int 
 * @return	string
 */
if ( ! function_exists('getRandPass')) {
	function getRandPass($len) {
	    $str = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$maxLen = strlen($str)-1;
		$randStr = '';
		for ($i = 0; $i < $len; $i++) {
		    $randStr .= substr($str, rand(0, $maxLen), 1);
		}
		
		return $randStr;
	}
}

// --------------------------------获取时间格式----------------------------------------

/**
 * clear string
 *
 */
if ( ! function_exists('getFormatTime')) {
    function getFormatTime($minute = 0) {
    	$retStr = '';
    	if ($minute > 0) {
    	    $day = floor($minute/(60*24));
    	    $hour = floor(($minute - $day*(60*24))/60);
    	    $min = floor(($minute - $day*(60*24) - $hour*60));
    	    
    	    if ($day) {
    	        $retStr .= $day.'天';
    	    }
    	    if ($hour) {
    	        $retStr .= $hour.'小时';
    	    }
    	    if ($min) {
    	        $retStr .= $min.'分钟';
    	    }
    	}
    	
    	return $retStr;
    }
}


// --------------------------------中间星号----------------------------------------

/**
 * clear string
 *
 */
if ( ! function_exists('createMiddleBit')) {
    function createMiddleBit($wangwang = NULL) {
    	$retStr = "";
    	$len = mb_strlen($wangwang);
    	if ($len > 6) {
    	    $retStr = mb_substr($wangwang, 0, 1).'***'.mb_substr($wangwang, $len - 3, 3);
    	} else {
    	    if ($len > 4) {
    	        $retStr = mb_substr($wangwang, 0, 1).'***'.mb_substr($wangwang, $len - 2, 2);
    	    } else {
    	        $retStr = mb_substr($wangwang, 0, 1).'***'.mb_substr($wangwang, $len - 1, 1);
    	    }
    	}
    	
    	return $retStr;
    }
}

// ------------------------------------------------------------------------

/**
 * 获取IP地址
 *
 *
 * @access	public
 * @param int 
 * @return	string
 */
if ( ! function_exists('getUserIPAddress')) {
	function getUserIPAddress($time = 5) {
		$cip = '';
	    if(!empty($_SERVER["HTTP_CLIENT_IP"])) {
		    $cip = $_SERVER["HTTP_CLIENT_IP"];
	    } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		    $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (!empty($_SERVER["REMOTE_ADDR"])){
		    $cip = $_SERVER["REMOTE_ADDR"];
		}
		if (!$cip) {
		    return array('', '');
		}
	    //初始化
		$ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, "http://www.ip138.com/ips138.asp?ip={$cip}");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.yhd.com");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $time);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		//释放curl句柄
		curl_close($ch);
		$output = mb_convert_encoding($output, 'utf-8', 'gbk');
		header("Content-type: text/html; charset=utf-8");
		if (!$output) {
		    return array('', '');
		}
		preg_match("/(本站主数据：)+.*(参考数据一：)+/", $output, $matches);
	    if (!$matches || !$matches[0]) {
			return array('', '');
		}
		$address = preg_replace(array('/本站主数据：/', '/参考数据一：/', '/(<\/li>|<li>)/'), array('', '', ''), $matches[0]);
		
		return array($cip, $address);
	}
}

// ------------------------------------------------------------------------

/**
 * 获取中文字符的长度
 *
 *
 * @access	public
 * @param int 
 * @return	string
 */
if ( ! function_exists('getChineseLen')) {
	function getChineseLen($str = '') {
		$mb_len = mb_strlen($str);
	    $mb_count = 0;
	    for ($i = 0; $i < $mb_len; $i++) {
	    	$tmp_str = mb_substr($str, $i, 1);
	    	if (preg_match("/[\x7f-\xff]/", $tmp_str)) {
	    		$mb_count++;
	    	}
	    }
	    
	    return floor(($mb_count/$mb_len)*100);
	}
}

// --------------------------------替换内容图片的路径－相对路径换成绝对路径----------------------------------------

/**
 * clear string
 *
 */
if ( ! function_exists('filter_content')) {
    function filter_content($content = '', $url = '') {
        preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU", $content, $matches);
        if($matches){
            $img_url_arr = $matches[2];
            $img_url_arr = array_unique($img_url_arr);
            if ($img_url_arr) {
                foreach($img_url_arr as $val){
                    if (!preg_match('/^http/', $val)) {
                        $content = str_replace($val, $url.$val, $content);
                    }
                }
            }
        }

        return $content;
    }
}

/**
 * get order number
 *
 *
 * @access	public
 * @param int
 * @return	string
 */
if ( ! function_exists('getOrderNumber')) {
    function getOrderNumber($len) {
        $str = '0123456789';
        $maxLen = strlen($str)-1;
        $randStr = '';
        for ($i = 0; $i < $len; $i++) {
            $randStr .= substr($str, rand(0, $maxLen), 1);
        }

        return date('ymdhi', time()).$randStr;
    }
}

function logs($data = null){
    $file  = 'logs/log.txt';
    $content = var_export($data,true)."\r\n";
    file_put_contents($file,$content,FILE_APPEND);

    return true;
}
/* End of file my_functionlib_helper.php */
/* Location: ./application/admin/helpers/my_functionlib_helper.php */