<?php
//check strings in .dat files.
function ipCheck($ip) {
	$bls = file(NudalyticsURL.'/filter/ip.dat');
	foreach($bls as $bl => $data) {
		if(strpos($data, "-")>=1) {
			$fip = explode("-", $data);
			$s = explode(".", $fip[0]);
			$e = explode(".", $fip[1]);
			$ipart = explode(".", $ip);
			$start = sprintf("%03d%03d%03d%03d", $s[0], $s[1], $s[2], $s[3]);
			$end = sprintf("%03d%03d%03d%03d", $e[0], $e[1], $e[2], $e[3]);
			$ipstr = sprintf("%03d%03d%03d%03d", $ipart[0], $ipart[1], $ipart[2], $ipart[3]);
			
			if(floatval($ipstr)>=floatval($start) && floatval($ipstr)<=floatval($end))
				return null;
		}
		else {
			if(strpos($ip, trim($data))>=1)
				return null;
		}
	}
	return $ip;
}

function hostCheck($host) {
	$bls = file(NudalyticsURL.'/filter/host.dat');
	foreach($bls as $bl => $data) {
		if(strpos($host, $data)>=1)
			return null;
	}
	return htmlentities($host, ENT_QUOTES);
}

function referrerCheck($referrer) {
	$bls = file(NudalyticsURL.'/filter/referrer.dat');
	foreach($bls as $bl => $data) {
		if(strpos($referrer, $data)>=1)
			return null;
	}
	if(strlen($referrer) == 0)
		return 'NULL';
	return "'".$referrer."'";
}

function uaCheck($ua) {
	$bls = file(NudalyticsURL.'/filter/useragent.dat');
	foreach($bls as $bl => $data) {
		if(strpos($ua, $data)>=1)
			return null;
	}
	return htmlentities($ua, ENT_QUOTES);
}


//as a valiable name "d", output string "'(d1|d2|...|dn)'".
//if IP address like "xxx.xxx.xxx.xxx",
//output string "(INET_ATON(IP) BETWEEN INET_ATON('xxx.xxx.xxx.xxx') AND INET_ATON('yyy.yyy.yyy.yyy'))".
function Nudalytics_SQLRegExp($fn, $interquery = null) {
	$str = array(
		'',
		'',
		'',
	);
	$dl = file(NudalyticsURL.$fn);
	foreach($dl as $d) {
		$d = preg_replace('/[\n\t]*[\s]$/', '', $d);
		if(preg_match('/^#/', $d) || strlen($d) <= 0)
			continue;
		
		$data = explode('|', $d);
		$count = count($data);
		for($i = 0; $i < $count; $i++) {
			//for IP address
			if(preg_match('/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/', $data[$i])) {
				if(preg_match('/[-]{1}/', $data[$i])) {
					$regex = explode('-', $data[$i]);
					$str[$i] .= "INET_ATON(IP) $interquery BETWEEN INET_ATON('$regex[0]') AND INET_ATON('$regex[1]')";
				}
				else
					$str[$i] .= "INET_ATON(IP) $interquery LIKE INET_ATON('$data[$i]')";

				$str[$i] .= " OR ";
			}
			//others
			else {
				$data[$i] = preg_quote($data[$i], '/');
				if(preg_match('/[,]*/', $data[$i])) {
					$regex = explode(',', $data[$i]);
					$c = count($regex);
					for($j = 0; $j < $c; $j++)
						$str[$i] .= $regex[$j].'|';
				}
				else
					$str[$i] .= $data[$i].'|';
			}
		}
	}
	$count = count($str);
	for($i = 0; $i < $count; $i++) {
		if(preg_match('/(\ OR\ )$/', $str[$i]))
			$str[$i] = '('.substr($str[$i], 0, strlen($str[$i])-4).')';
		else
			$str[$i] = '\'('.substr($str[$i], 0, strlen($str[$i])-1).')\'';
	}
	return $str;
}

//output a string from the string for searching.
function Nudalytics_identifier($search, $fn) {
	$dl = file(NudalyticsURL.$fn);
	foreach($dl as $d) {
		$d = preg_replace('/[\n\t]*[\s]$/', '', $d);
		if(preg_match('/^#/', $d) || strlen($d) <= 0)
			continue;
		
		$data = explode('|', $d);
		$data[count($data)-1] = explode(',', $data[count($data)-1]);

		//for IP address
		if(preg_match('/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/', $data[count($data)-1][0])) {
			foreach($data[count($data)-1] as $regex) {
				if(strpos($regex, '-')>=1) {
					$scope = explode('-', $regex);
					$s = explode('.', $scope[0]);
					$e = explode('.', $scope[1]);
					$ippart = explode('.', $search);
					$start = sprintf("%03d%03d%03d%03d", $s[0], $s[1], $s[2], $s[3]);
					$end = sprintf("%03d%03d%03d%03d", $e[0], $e[1], $e[2], $e[3]);
					$ipstr = sprintf("%03d%03d%03d%03d", $ippart[0], $ippart[1], $ippart[2], $ippart[3]);
				
					if(floatval($ipstr)>=floatval($start) && floatval($ipstr)<=floatval($end))
						return htmlspecialchars($data[0]);
				}
				else
					if(strpos($search, trim($data))>=1)
						return htmlspecialchars($data[0]);
			}
		}
		//extract the search query(3 values)
		elseif(count($data) == 3) {
			$data[1] = preg_quote($data[1], '/');
			if(preg_match('/'.$data[1].'/', $search)) {
				$ltr = strstr($search, $data[2][0].'=');
				$ltr1 = explode('&', $ltr);
				$ltr2 = explode('=', $ltr1[0]);
				return array(
					'query' => urldecode(htmlspecialchars($ltr2[1])),
					'engine' => $data[0],
				);
			}
		}
		else {
			foreach($data[count($data)-1] as $regex) {
				$regex = preg_quote($regex, '/');
				if(preg_match('/'.$regex.'/', $search))
					return htmlspecialchars($data[0]);
			}
		}
	}
	return null;
}
