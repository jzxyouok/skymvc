<?php
/**
*函数库核心文件
*/
/*全局变量定义*/
function G($var){
	return $GLOBALS[$var];
}
 

/*运营时间*/
function running_time(){
	return round(microtime(true)-B_TIME,4);
}
/*内存占用*/
function memory_use(){
	return intval(memory_get_usage()/1024);
}
/*输出测试数据*/
function skyTest(){
	 
	echo "服务器：".$_SERVER['SERVER_SOFTWARE']."<br>";
	 
	echo "php模式：".php_sapi_name()." <br>";
	echo "SQL执行时间：".G('query_time')."<br>";
	echo "程序运营时间：".running_time()."<br>";
	echo "程序内存使用：PID.".getmypid().",占用内存：".memory_use()."KB<br>";
	echo "SQL执行次数:".G('skysqlnum')."<br>";	
	echo "SQl执行语句：".G('skysqlrun')."<br>";
}
/**系统日志**/
function skyLog($file,$content){
	$file=ROOT_PATH."temp/log/".$file;
	$fp=fopen($file,"a+");
	fwrite($fp,"\r\n---".date("Y-m-d H:i:s")."----\r\n".$content."\r\n");
	fclose($fp);	
}

/*
构造表格前缀 函数
*/
function table($tb)
{
	return TABLE_PRE.$tb;
}

/*
*字符串处理函数库
*/
function nrexplode($str){
	$str=str_replace("\r\n","\n",$str);
	return explode("\n",$str); 
}

function texplode($str){
	$t_d=explode(" ",$str);
	if($t_d){
		foreach($t_d as $v){
			if(!empty($v)){
				$data[]=$v;
			}
		}
	}
	return $data;
}

//获取小数点
function numdot($str){
	$s=explode(".",$str);
	if(isset($s[1])){
		return $s[1];
	}else{
		return $s[0];
	}
}
function sql($value){
	return newaddslashes($value);
}

function ustrip_tags($str){	
	$arr=array("&amp;","&nbsp;","&ldquo;","&rdquo;","&hellip;");
	$str=str_replace($arr,"",$str);
	$str=strip_tags($str);
	return $str;
}
function get($k,$format="",$len=0){
        if(isset($_GET[$k])){
				$_GET[$k]=get_format($_GET[$k],$format,$len);
				         
                return $_GET[$k];                
        }elseif($format=='i'){
			$_GET[$k]=0;
			return 0;
		}
}
function post($k,$format="",$len=0){
        if(isset($_POST[$k])){         
                $_POST[$k]=get_format($_POST[$k],$format,$len);
				return $_POST[$k];                
        }elseif($format=='i'){
			$_POST[$k]=0;
			return 0;
		}
}

function get_post($k,$format="",$len=0){
        if(isset($_GET[$k])){         
                return get_format($_GET[$k],$format,$len);                
        }
        if(isset($_POST[$k])){         
                return get_format($_POST[$k],$format,$len);                
        }
		if($format=='i'){
			return $_GET[$k]=0;
		}
}

function get_format($str,$format='',$len=0){
        if(!$format) return $str;

        if(is_array($str)){
                foreach($str as $k=>$v){
						$str[$k]=get_format($v,$format,$len);
                }
                return $str;
        }else{
                return str_format($str,$format,$len);
        }
         
}

function str_format($str,$format='',$len=0){
        if(!$format) return $str;
        $str=trim($str);
        $arr=str_split($format);
        foreach($arr as $html){
                switch($html){
					case "i":
							if($str>2147483647){
								$str=round($str,$len);
							}else{
								$str=intval($str);
							}
							break;
					case "f":
								$str=floatval($str);
							break;
					case "h":
							$str=htmlspecialchars($str);
							break;
					case "s":
							$str=strip_tags($str);
							break;
					case "x":
							$str=nRemoveXSS($str);
							break;
					case "r":
							$str=round($str,$len);
							break;
					case "a"://返回全部
							break;
                }
        }
        return $str;
}

function get_post_Html($arr){
	if(!empty($arr)){
		return is_array($arr) ? array_map('get_post_Html', $arr) : htmlspecialchars(trim($arr));
	}
}

function arrRemoveXss($arr){
	if(!empty($arr)){
		return is_array($arr) ? array_map('arrRemoveXss', $arr) : nRemoveXSS(trim($arr));
	}
}

function nRemoveXSS($str){
	$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link',  'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound','base','alert');
	$ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	$ra = array_merge($ra1, $ra2);
	$x_r="(".implode("|",$ra).")";
	//过滤script 不允许<>
	$str=preg_replace("/<script([^>]*)>/iUs","&ltscript\\1&gt",$str);
	$str=str_replace("</script>","&lt/script&gt",$str);
	//过滤a 允许<>
	while(@preg_match("/<([^>]*){$x_r}([^>]*)>/iUs",$str)){
			$str=preg_replace("/<([^>]*){$x_r}([^>]*)>/iUs","<\\1xss_\\3>",$str);
	}
	return $str;
}


/**
* 将数组元素格式化成类似 '1','2','3' 的字符串
* @return STRING 字串 否则为 NULL
*/
function _implode($array) {
	if(!empty($array)) {
		return "'".implode("','", is_array($array) ? $array : array($array))."'";
	} else {
		return '';
	}
}


/**
* 递归方式的对变量中的特殊字符进行转义
*
* @access  public
* @param   mix     $value
*
* @return  mix
*/
function addslashes_deep($value)
{
	if (empty($value))
	{
		return $value;
	}
	else
	{
		return is_array($value) ? array_map('addslashes_deep', $value) : addslashes(trim($value));
	}
}


function stripslashes_deep($value)
{
	if (empty($value))
	{
		return $value;
	}
	else
	{
		return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes(trim($value));
	}
}
function newaddslashes($value){		 
		if(empty($value)){
			return $value;
		}else{
			if(is_array($value)){
				 return array_map('newaddslashes', $value);
			}else{				
				$value=stripslashes($value);
				$value=str_replace("\'","'",$value);  
				$value=str_replace('\"','"',$value);
				$value=addslashes(trim($value));				
				return $value;	
			}
		}	 
}




//截取字符串函数
function cutstr($string, $length, $dot = ' ...') {
	if(strlen($string) <= $length) {
		return $string;
	}
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	$strcut = '';
	$n = $tn = $noc = 0;
	while($n < strlen($string)) {	
		$t = ord($string[$n]);
		if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
			$tn = 1; $n++; $noc++;
		} elseif(194 <= $t && $t <= 223) {
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
	if($noc > $length) {
		$n -= $tn;
	}
	$strcut = substr($string, 0, $n);		
	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
}


/**
@字符转换函数
*/
function iconvstr($from,$to,$str)
{
	if(empty($str))
	{
		return false;
	}
	if(is_array($str))
	{
		foreach($str as $key=>$val)
		{
		$string[$key]=iconvstr($from,$to,$val);
		}		
	}else
	{
		$string=iconv($from,$to,$str);
	}
	
	return $string;
}

//移除链接
function removelink($c)
{
	return preg_replace("/<a.*>(.*)<\/a>/iU","\\1",$c);
}

/*加密函数*/
function umd5($str)
{
	return md5(md5($str));	
}

function array_lastnum($arr){
	if(!is_array($arr)) return intval($arr);
	if($arr){
		$len=count($arr)-1;
		for($i=$len;$i>=0;$i--){
			if($arr[$i]>0){			 
				return $arr[$i];
			}
		}
	}else{
		return 0;
	}
}


function getImgs($content,$i=9999){
	preg_match_all("/<img[^>]*src[^=]*=[^\"']*[\"'](.*)[\"'][^>]*>/iUs",$content,$imgs);
	if($i!=9999){
		return $imgs[1][$i];
	}else{
		return $imgs[1];
	}
}

function replaceImgs($content){
	preg_match_all("/<img[^>]*src[^=]*=[^\"']*[\"'](.*)[\"'][^>]*>/iUs",$content,$imgs);
	if(is_array($imgs[1])){
		foreach($imgs[1] as $img){
			$content=str_replace($img,images_site($img),$content);
		}
	}
	return $content;
}

/*保持中文格式 转化*/
function zh_json_encode($data){
		$data=array_urlencode($data);
		$data=json_encode($data);
		$data=array_urldecode($data);
		return $data;
}

function array_urlencode($data){
		if(is_array($data)){
			foreach($data as $k=>$v){
				$data[$k]=array_urlencode($v);
			}
			return $data;
		}else{
			return urlencode($data);
		}
}

function array_urldecode($data){
		if(is_array($data)){
			foreach($data as $k=>$v){
				$data[$k]=array_urldecode($v);
			}
			return $data;
		}else{
			return urldecode($data);
		}
}
/**
*@json 处理jsonp和json
**/
function sky_json_encode($data){
	if(get_post('jsonp')){
		return get_post('callback','h')."(".json_encode($data).")";
	}else{
		return json_encode($data);
	}
}

function sky_json_decode($data){
	return json_decode($data,true);
}

/**
*@简易字符串转js
*/
function strToJs($content){
	$content=str_replace(array("\r","\n"),"",$content);
	$content=str_replace("'","\'",$content);
	return "document.write('$content');";
}
 

/**
* 获得用户的真实IP地址
*/
function ip(){
	return realip();
}
function realip() {
	$onlineip = '';
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	return $onlineip;
}




/*获取远程内容*/
function curl_get_contents($url,$timeout=30,$referer="http://www.qq.com"){
	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_URL, $url);
	 curl_setopt($ch, CURLOPT_HEADER, 0);
	 curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (iPhone; CPU iPhone OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/9B176 MicroMessenger/4.3.2");
	 curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	 curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	 curl_setopt($ch, CURLOPT_REFERER,$referer); //伪造来路页面 防止被禁止
	 $content= curl_exec($ch);
	 curl_close($ch);
	 return $content;
} 

/*远程post*/
function curl_post($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $ret = curl_exec($ch);

    curl_close($ch);
    return $ret;
}

function curl_post_json($url, $json)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json); 
    //curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json))
);
    $ret = curl_exec($ch);

    curl_close($ch);
    return $ret;
}

/**
*判断是否来自搜索引擎
*/
function is_robot() { 
	$kw_spiders = 'Bot|Crawl|Spider|spider|Slurp|slurp|sohu-search|Lycos|lycos|robozilla|baidu|Baidu|google|Google|soso|Soso|YodaoBot|Sogou|sogou|Adsbot|Mediapartners|Msn|msn|scooter|FAST|ia_archiver|Ia_archiver'; 
	if(preg_match("/($kw_spiders)/i", $_SERVER['HTTP_USER_AGENT'],$matches)) 
	{ 
		return true;
	} else { 
		return false;
	}
}

/**
* 验证输入的邮件地址是否合法
*
* @access  public
* @param   string      $email      需要验证的邮件地址
*
* @return bool
*/
function is_email($user_email)
{
	$user_email=trim($user_email);
	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
	{
		if (preg_match($chars, $user_email))
		{
			return true;
		}
		else
		{
			return false;
		}
	}else
	{
		return false;
	}
}


/*判断是否手机号码*/
function is_tel($tel){
	if(preg_match("/1[34587]{1}\d{9}$/",$tel)){
		return true;
	}
	return false;
}


/*是否来自手机*/
function is_mobile(){ 
 
    // returns true if one of the specified mobile browsers is detected 
 
    $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|"; 
    $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";     
    $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|"; 
    $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220"; 
    $regex_match.=")/i";
	         
    return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])); 
}
/****加解密*****/
if(!defined("JIAMI_MIYAO")){
	define("JIAMI_MIYAO","我要保密");
}
/*加密*/
function jiami($str){
	$code=md5(JIAMI_MIYAO);
	$a=0;
	for($i=0;$i<6;$i++){
		$a+=ord($code{$i});
	}
	$n= $a%1258;
	$str=gzcompress($str,9);
	$str=base64_encode($str);
	preg_match_all("/./iUs",$str,$s);
	$nstr="";
	foreach($s[0] as $t){
		$t1=ord($t);
		if($t1+$n>12580){
			$nstr.=$t;
		}else{
			$nstr.= chr($t1+$n);
		}
	}
	return base64_encode(urlencode($nstr));
}
/******解密******/
function jiemi($str){
	
	$code=md5(JIAMI_MIYAO);
	$a=0;
	for($i=0;$i<6;$i++){
		$a+=ord($code{$i});
	}
	$n= $a%1258;
	$str=urldecode(base64_decode($str));
	preg_match_all("/./iUs",$str,$s);
	$nstr="";
	foreach($s[0] as $t){
		$t1=ord($t);
		if($t1>12580){
			$nstr.=$t;
		}else{
			$nstr.= chr($t1-$n);
		}
	}
	$nstr=base64_decode($nstr);
	$nstr=@gzuncompress($nstr);
	return $nstr;
} 


/*剩余时间*/
function lefttime($endtime,$format="还剩#天#小时#分#秒",$ec=0){
	$t=$endtime-time();
	$a=explode("#",$format);
	if($t<=0){
		 $str="已结束"; 
	}elseif($t<60){
		$str= $a[0].$t.$a[4];
	}elseif($t<3600){
		$str= $a[0].intval($t/60).$a[3].($t-intval($t/60)*60).$a[4];
	}elseif($t<86400){//小时
		$str= $a[0].intval($t/3600).$a[2].($t-intval($t/3600)*3600).$a[3];
	}else{
		$str=$a[0].intval($t/86400).$a[1].intval(($t-intval($t/86400)*86400)/3600).$a[2];
	}
	if($ec){
		echo $str;
	}else{
		return $str;
	}
}

//格式化时间 多久前的
function timeago($dateline)
{
	$t=time()-intval($dateline);
	if($t<60)
	{
		return $t."秒前";
	}elseif($t<3600)
	{
		return ceil($t/60)."分前";
	}elseif($t<7200)
	{
		return "1小时".ceil(($t-3600)/60)."分前";
	}elseif($t<86400)//一天
	{
		return ceil($t/3600)."小时前";
	}elseif($t<604800)//一周
	{
		return ceil($t/86400)."天前";
	}else
	{
		return date("Y年m月d日",$dateline);
	}
}


/*计算开业时间函数*/
function opentype($starthour,$startminute,$endhour,$endminute)
{
	$opentype='doing';
	$h=intval(date("H"));
	$m=intval(date("i"));
	if($starthour>$endhour)
	{
		if(($endhour<$h && $starthour>$h) or (($endhour==$h && $endminute<$m) && ($starthour==$h && $startminute>$m)))
		{
			if(($starthour-$h)>(($starthour-$endhour)/2))
			{
				$opentype='done';
			}else
			{
				
				$opentype='will';
			}
		}elseif($endhour)
		{
			$opentype='doing';
		}
		
	}else
	{
		if($h<$starthour or ($h==$starthour && $m<$startminute))
		{
			$opentype='will';//未开时
		}elseif($h>$endhour or($h==$endhour && $m>$endminute))
		{
			$opentype='done';//一结束
		}else
		{
			$opentype='doing';
		}
	}
	return $opentype;
}


//星期几
function getweek($w="")
{
	if(date("N"))
	{
		return $w.date("N");
	}
	if(date("w")==0)
	{
		return $w."7";
	}else
	{
		return $w.intval(date("w"));
	}
}

function week_list($week=0){
	$data=array(
		'week1'=>'星期一',
		'week2'=>'星期二',
		'week3'=>'星期三',
		'week4'=>'星期四',
		'week5'=>'星期五',
		'week6'=>'星期六',
		'week7'=>'星期日',
	);
	if($week){
		return $data[$week];
	}else{
		return $data;
	}
}
 function echoweek($time){
	 $w=date("w",$time);
	 if($w==0) $w=7;
	 return week_list("week".$w);
	  
 }

?>