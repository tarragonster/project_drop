<?php
function pre_print($data, $exit = true) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	if ($exit) {
		die("");
	}
}


function validate_email($str) {
	return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}

function validate_number($str) {
	return (!preg_match("/^([0-9]+)$/ix", $str)) ? FALSE : TRUE;
}

function validate_socialid($str) {
	return (!preg_match("/^([0-9a-zA-Z]+)$/ix", $str)) ? FALSE : TRUE;
}

function base_dir($path = ''){
	return config_item('base_dir') . $path;
}

function validate_username($str) {
	if (strlen($str) < 6)
		return false;
	return (!preg_match("/^[a-zA-Z_]([a-zA-Z0-9_\.]*)([a-zA-Z0-9_]+)$/ix", $str)) ? FALSE : TRUE;
}

function validate_identifier($str) {
	if (strlen($str) < 3)
		return false;
	return (!preg_match("/^[a-zA-Z_]([a-zA-Z0-9_\.]*)([a-zA-Z0-9_]+)$/ix", $str)) ? FALSE : TRUE;
}

function createThumbnailName($path) {
	$pos = strripos($path, '.');
	return substr($path, 0, $pos) . '-thumb' . substr($path, $pos);
}

if (!function_exists('showTime')) {
	function showTime($old_time, $time = '') {
		if($time == '')
			$time = time();
		$diff = $time - $old_time;
		if ($diff <= 1) {
			return 'one second ago';
		} else if($diff <= 5) {
			return $diff. ' seconds ago';
		} else if ($diff <= 60){
			return 5 * (int)($diff / 5) . ' seconds ago';
		} else if ($diff < 600) {
			return (int)($diff / 60) . ' minutes ago';
		} else if ($diff <= 3600) {
			return 5 * (int)($diff / 300) . ' minutes ago';
		} else if($diff < 86400) {
			return (int)($diff / 3600) . ' hours ago';
		} else if($diff < 172800) {
			return 'yesterday';
		} else if ($diff < 4322000) {
			return (int)($diff / 86400) . ' days ago';
		} else {
			return date('M. d, Y', $old_time);
		}
	}
}

function showAvgResponse($diff) {
	if ($diff < 0) {
		return "many time";
	} else if ($diff <= 60){
		return 'about sec';
	} else if ($diff < 600) {
		return "about " . (int)($diff / 60) . ' min';
	} else if ($diff <= 3600) {
		return "about " . 5 * (int)($diff / 300) . ' min';
	} else if($diff < 86400) {
		return "about " . (int)($diff / 3600) . ' hours';
	} else if($diff < 172800) {
		return "about 1 day";
	} else if ($diff < 4322000) {
		return "about " . (int)($diff / 86400) . ' days';
	} else {
		return "many time";
	}
}

function timeFormat($timestamp) {
	return date('M. d, Y H:i', $timestamp);
}


function getTypeMember($type) {
	switch ($type) {
		case 1: return 'Shop Online';
		case 2: return 'Store';
		case 3: return 'Big Onnline';
		case 10: return 'Members';
		default: 'Members';
	}
}

function getTrim($string) {
	$aa = explode(':', $string);
	if (count($aa) > 1) {
		return trim($aa[1], ' ');
	} else {
		return trim($string, ' ');
	}
}

function getMoney1($string) {
	$s = getTrim($string);
	$aa = explode(' ', $s);
	$num = '';
	foreach ($aa as $a) {
		if ($a != 'đ') {
			$num .= $a;
		} else {
			return $num;
		}
	}
	return $num;
}

function checkEndDot($string) {
	if (strlen($string) > 0) {
		if ($string[strlen($string) - 1] == '.') {
			return substr($string, 0, strlen($string) - 1);
		} else {
			return $string;
		}
	} else {
		return $string;
	}
}

function startsWith($haystack, $needle)
{
	return $needle === "" || strpos($haystack, $needle) === 0;
}
function endsWith($haystack, $needle)
{
	return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function getOrderStatus($payment_status) {
	switch($payment_status) {
		case -1: return 'Cancle';
		case 1: return 'In Process';
		case 2: return 'Wait Shipping';
		case 3: return 'Delivered';
		case 3: return 'Completed';
		default: return 'No defined';
	} 
}

function getParcelStatus($parcel_status) {
	switch($parcel_status) {
		case PARCEL_PROCESSING: return 'Processing';
		case PARCEL_PRE_TRANSIT: return 'Preparing Shipment';
		case PARCEL_IN_TRANSIT: return 'In Transit';
		case PARCEL_DELIVERED: return 'Complete';
		case PARCEL_CANCELLED: return 'Cancelled';
		default: return 'No defined';
	}
}

function getProductReportReason($reason_id) {
	switch ($reason_id) {
		case 1:
			return 'Item Not Allowed';
		case 2:
			return 'Non-VM Transaction';
		case 3:
			return 'Offensive Item';
		case 4:
			return 'Spam';
		default:
			return 'Harassment';
	}
}


function getShippingPrice($package) {
	return ($package == 1) ? 5.20 : ($package == 2 ? 11.30 : 3.29);
}

function stringtotime($cdate) {
	$aDate = explode('-', $cdate);
	if (count($aDate) != 3)
		return 0;
	return mktime(0, 0, 0, $aDate[1], $aDate[2], $aDate[0]);
}

function strptimestamp($date) {
	date_default_timezone_set('America/Los_Angeles');
// 	echo time();
	$dtime = DateTime::createFromFormat("m/d/Y H:i", $date);
	return $dtime->getTimestamp();
}

function getUsernameCanbe($content) {
	$pattern = '/(\s|^|:)@([a-zA-Z_]([a-zA-Z0-9_\.]*)([a-zA-Z0-9_]+))/';
	preg_match_all($pattern, $content, $matches, PREG_PATTERN_ORDER);
	if (count($matches) > 2)
		return $matches[2];
	else
		return null;
}