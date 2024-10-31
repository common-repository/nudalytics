<?php
/*----------------------------------------------------------
	Realtime
----------------------------------------------------------*/
function Nudalytics_realtime() {
	global $nudalytics_start;

	global $nudalytics_setting;
	global $RegExp_adminpage;
	global $tableName;
	global $wpdb;
	Nudalytics_chkOption();
	$identify_host = Nudalytics_SQLRegExp('/identify/crawler_host.dat');
	$identify_ip = Nudalytics_SQLRegExp('/identify/crawler_ip.dat', 'NOT');
	$identify_ua = Nudalytics_SQLRegExp('/identify/crawler_ua.dat');
?>
<div class="wrap">
<h2><?php _e('Realtime', 'Nudalytics'); ?></h2>
<ul class="nt h">
	<li class="w10"><?php _e('Date', 'Nudalytics'); ?></li>
	<li class="w25"><?php _e('IP address / Host / UA', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('URL', 'Nudalytics'); ?></li>
	<li class="w5"><?php _e('OS', 'Nudalytics'); ?></li>
	<li class="w5"><?php _e('Browser', 'Nudalytics'); ?></li>
	<li class="w30"><?php _e('Referrer', 'Nudalytics'); ?></li>
</ul>
<?php
	$list = $wpdb->get_results("
		SELECT * FROM $tableName 
		WHERE Host NOT REGEXP $identify_host[1] 
		AND $identify_ip[1] 
		AND UserAgent NOT REGEXP $identify_ua[1] 
		AND CurrentUser IS NULL 
		ORDER BY Time DESC 
		LIMIT 0, $nudalytics_setting;
	");

	foreach($list as $data) {
		$os = Nudalytics_identifier($data->UserAgent, '/identify/os.dat');
		$browser = Nudalytics_identifier($data->UserAgent, '/identify/browser.dat');

		$urlstr = explode('/', $data->Referrer);
		if($urlstr[2] == $_SERVER['SERVER_NAME'])
			echo '<ul class="nt">'.
				'<li class="w10 tc">'.$data->Time.'</li>'.
				'<li class="w25 fs-s">'.$data->IP.'<br />'.$data->Host.'<br /><span class="itl">'.$data->UserAgent.'</span></li>'.
				'<li class="w20">'.urldecode($data->URL).'</li>'.
				'<li class="w5 fs-s">'.$os.'</li>'.
				'<li class="w5 fs-s">'.$browser.'</li>'.
				'<li class="w30 g">'.urldecode($data->Referrer).'</li>'.
				'</ul>';
		elseif(preg_match('/http:\/\/[0-9a-z]*\.[0-9a-z]*\/?$/', $urlstr[3]))
			echo '<ul class="nt">'.
				'<li class="w10 tc">'.$data->Time.'</li>'.
				'<li class="w25 fs-s">'.$data->IP.'<br />'.$data->Host.'<br /><span class="itl">'.$data->UserAgent.'</span></li>'.
				'<li class="w20">'.urldecode($data->URL).'</li>'.
				'<li class="w5 fs-s">'.$os.'</li>'.
				'<li class="w5 fs-s">'.$browser.'</li>'.
				'<li class="w30 r">'.urldecode($data->Referrer).'</li>'.
				'</ul>';
		else
			echo '<ul class="nt">'.
				'<li class="w10 tc">'.$data->Time.'</li>'.
				'<li class="w25 fs-s">'.$data->IP.'<br />'.$data->Host.'<br /><span class="itl">'.$data->UserAgent.'</span></li>'.
				'<li class="w20">'.urldecode($data->URL).'</li>'.
				'<li class="w5 fs-s">'.$os.'</li>'.
				'<li class="w5 fs-s">'.$browser.'</li>'.
				'<li class="w30">'.urldecode($data->Referrer).'</li>'.
				'</ul>';
	}
?>
</div>
<span class="measure">time to display: <?php echo (microtime(true)-$nudalytics_start)*1000; ?>[ms]</span>
<?php
}

/*----------------------------------------------------------
	Referrer
----------------------------------------------------------*/
function Nudalytics_referrer() {
	global $nudalytics_start;

	global $nudalytics_setting;
	global $RegExp_adminpage;
	global $tableName;
	global $wpdb;
	Nudalytics_chkOption();
?>
<div class="wrap">
<h2><?php _e('Referrer', 'Nudalytics'); ?></h2>
<ul class="nt h">
	<li class="w10"><?php _e('Date', 'Nudalytics'); ?></li>
	<li class="w25"><?php _e('IP address / Host / UA', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('URL', 'Nudalytics'); ?></li>
	<li class="w5"><?php _e('OS', 'Nudalytics'); ?></li>
	<li class="w5"><?php _e('Browser', 'Nudalytics'); ?></li>
	<li class="w30"><?php _e('Referrer', 'Nudalytics'); ?></li>
</ul>
<?php
	$list = $wpdb->get_results("
		SELECT * FROM $tableName 
		WHERE CurrentUser IS NULL 
		AND Referrer IS NOT NULL 
		AND URL NOT REGEXP $RegExp_adminpage 
		ORDER BY Time DESC 
		LIMIT 0, $nudalytics_setting;
	");
	
	foreach($list as $data) {
		$os = Nudalytics_identifier($data->UserAgent, '/identify/os.dat');
		$browser = Nudalytics_identifier($data->UserAgent, '/identify/browser.dat');

		$urlstr = explode('/', $data->Referrer);
		if($urlstr[2] == $_SERVER['SERVER_NAME'])
			echo '<ul class="nt">'.
				'<li class="w10 tc">'.$data->Time.'</li>'.
				'<li class="w25 fs-s">'.$data->IP.'<br />'.$data->Host.'<br /><span class="itl">'.$data->UserAgent.'</span></li>'.
				'<li class="w20">'.urldecode($data->URL).'</li>'.
				'<li class="w5 fs-s">'.$os.'</li>'.
				'<li class="w5 fs-s">'.$browser.'</li>'.
				'<li class="w30 g">'.urldecode($data->Referrer).'</li>'.
				'</ul>';
		elseif(preg_match('/http:\/\/[0-9a-z]*\.[0-9a-z]*\/?$/', $urlstr[3]))
			echo '<ul class="nt">'.
				'<li class="w10 tc">'.$data->Time.'</li>'.
				'<li class="w25 fs-s">'.$data->IP.'<br />'.$data->Host.'<br /><span class="itl">'.$data->UserAgent.'</span></li>'.
				'<li class="w20">'.urldecode($data->URL).'</li>'.
				'<li class="w5 fs-s">'.$os.'</li>'.
				'<li class="w5 fs-s">'.$browser.'</li>'.
				'<li class="w30 r">'.urldecode($data->Referrer).'</li>'.
				'</ul>';
		else
			echo '<ul class="nt">'.
				'<li class="w10 tc">'.$data->Time.'</li>'.
				'<li class="w25 fs-s">'.$data->IP.'<br />'.$data->Host.'<br /><span class="itl">'.$data->UserAgent.'</span></li>'.
				'<li class="w20">'.urldecode($data->URL).'</li>'.
				'<li class="w5 fs-s">'.$os.'</li>'.
				'<li class="w5 fs-s">'.$browser.'</li>'.
				'<li class="w30">'.urldecode($data->Referrer).'</li>'.
				'</ul>';
	}
?>
</div>
<span class="measure">time to display: <?php echo (microtime(true)-$nudalytics_start)*1000; ?>[ms]</span>

<?php
}

/*----------------------------------------------------------
	Search query
----------------------------------------------------------*/
function Nudalytics_searchquery() {
	global $nudalytics_start;

	global $nudalytics_setting;
	global $RegExp_adminpage;
	global $tableName;
	global $wpdb;
	$identify_searchengine = Nudalytics_SQLRegExp('/identify/searchquery.dat');
?>
<div class="wrap">
<h2>Search query</h2>
<ul class="nt h">
	<li class="w10"><?php _e('Date', 'Nudalytics'); ?></li>
	<li class="w25"><?php _e('IP address / Host / UA', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('URL', 'Nudalytics'); ?></li>
	<li class="w5"><?php _e('OS', 'Nudalytics'); ?></li>
	<li class="w5"><?php _e('Browser', 'Nudalytics'); ?></li>
	<li class="w30"><?php _e('Referrer', 'Nudalytics'); ?></li>
</ul>
<?php
	$list = $wpdb->get_results("
		SELECT * FROM $tableName 
		WHERE CurrentUser IS NULL 
		AND Referrer REGEXP $identify_searchengine[1] 
		ORDER BY Time DESC 
		LIMIT 0, $nudalytics_setting;
	");
	
	foreach($list as $data) {
		$search = Nudalytics_identifier($data->Referrer, '/identify/searchquery.dat');
		if(strlen($search[engine]) <= 0)
			continue;
		$os = Nudalytics_identifier($data->UserAgent, '/identify/os.dat');
		$browser = Nudalytics_identifier($data->UserAgent, '/identify/browser.dat');
		echo '<ul class="nt">'.
			'<li class="w10 tc">'.$data->Time.'</li>'.
			'<li class="w25 fs-s">'.$data->IP.'<br />'.$data->Host.'<br /><span class="itl">'.$data->UserAgent.'</span></li>'.
			'<li class="w20">'.urldecode($data->URL).'</li>'.
			'<li class="w5 fs-s">'.$os.'</li>'.
			'<li class="w5 fs-s">'.$browser.'</li>'.
			'<li class="w30"><span class="b">'.$search[engine].'</span>: '.$search[query].'</li>'.
			'</ul>';
	}
?>
</div>
<span class="measure">time to display: <?php echo (microtime(true)-$nudalytics_start)*1000; ?>[ms]</span>
<?php
}

/*----------------------------------------------------------
	Movement in Admin
----------------------------------------------------------*/
function Nudalytics_adminpage() {
	global $nudalytics_start;

	global $nudalytics_setting;
	global $RegExp_adminpage;
	global $tableName;
	global $wpdb;
	Nudalytics_chkOption();
?>
<div class="wrap">
<h2><?php _e('Movement in Admin', 'Nudalytics') ?></h2>
<ul class="nt h">
	<li class="w10"><?php _e('Date', 'Nudalytics'); ?></li>
	<li class="w25"><?php _e('IP address / Host / UA', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('User', 'Nudalytics'); ?></li>
	<li class="w40"><?php _e('URL', 'Nudalytics'); ?></li>
</ul>
<?php
	$list = $wpdb->get_results("
		SELECT * FROM $tableName 
		WHERE URL REGEXP $RegExp_adminpage 
		AND CurrentUser IS NOT NULL 
		ORDER BY Time DESC 
		LIMIT 0, $nudalytics_setting;
	");
	
	foreach($list as $data) {
		$prop = explode('?', $data->URL);
		
		$place = explode('/', $prop[0]);
		if($place[2] == 'index-exula.php')
			continue;
		elseif(strpos($data->URL, '/admin.php?') >= 1)
			$place[2] = 'Somewhere plugin\'s page';
		elseif($place[2] == '' || $place[2] == 'admin.php')
			$place[2] = 'Admin home';
		
		echo '<ul class="nt">'.
			'<li class="w10 tc">'.$data->Time.'</li>'.
			'<li class="w25 fs-s">'.$data->IP.'<br />'.$data->Host.'<br /><span class="itl">'.$data->UserAgent.'</span></li>'.
			'<li class="w20 tc">'.$data->CurrentUser.'</li>'.
			'<li class="w40 fw-b">';
		_e($place[2], 'Nudalytics');

		if($place[2] != 'index.exula.php' && $place[2] != 'Admin home') {
			$action = explode('&', $prop[1]);
			for($i = 0; $i < count($action); $i++)
				$action[$i] = explode('=', $action[$i]);
			
			if($action[0][0] != null)
				echo '<hr style="border: 1px solid #888; border-width: 1px 0 0 0;" />';
			
			for($n = 0; $n < count($action[$n]); $n++) {
				switch($action[$n][0]) {
					case 'action':
						_e('Action: ', 'Nudalytics');
						break;
					case 'file':
						_e('Editted file\'s pass: ', 'Nudalytics');
						break;
					case 'page':
						_e('Being stayed: ', 'Nudalytics');
						break;
					case 'paged':
						_e('Target of page: ', 'Nudalytics');
						break;
					case 'plugin':
						_e('Target of plugin: ', 'Nudalytics');
						break;
					case 'plugin_status':
						_e('Plugin\'s status is changed: ', 'Nudalytics');
						break;
					case 'post':
						_e('Post number: ', 'Nudalytics');
						break;
					case 'post_type':
						_e('The post type is: ', 'Nudalytics');
						break;
					case 'settings-updated':
						_e('Changed settings: ', 'Nudalytics');
						break;
					case 'theme':
						_e('Theme name: ', 'Nudalytics');
						break;
				}
				switch($action[$n][0]){
					case 'action':
					case 'file':
					case 'plugin_status':
					case 'settings-updated':
						echo '<span class="r">'.$action[$n][1].'</span><br />';
						break;
					case 'page':
						echo '<span class="brwn">'.$action[$n][1].'</span><br />';
						break;
					default:
						echo $action[$n][1].'<br />';
						break;
				}
			}
		}
		echo '</li></ul>';
	}
?>
</div>
<span class="measure">time to display: <?php echo (microtime(true)-$nudalytics_start)*1000; ?>[ms]</span>
<?php
}

/*----------------------------------------------------------
	Crawlers
----------------------------------------------------------*/
function Nudalytics_crawlers() {
	global $nudalytics_start;

	global $nudalytics_setting;
	global $RegExp_adminpage;
	global $tableName;
	global $wpdb;
	Nudalytics_chkOption();
	$identify_host = Nudalytics_SQLRegExp('/identify/crawler_host.dat');
	$identify_ip = Nudalytics_SQLRegExp('/identify/crawler_ip.dat');
	$identify_ua = Nudalytics_SQLRegExp('/identify/crawler_ua.dat');
?>
<div class="wrap">
<h2><?php _e('Crawlers', 'Nudalytics'); ?></h2>
<ul class="nt h">
	<li class="w20"><?php _e('Date', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('Crawler name', 'Nudalytics'); ?></li>
	<li class="w55"><?php _e('URL', 'Nudalytics'); ?></li>
</ul>
<?php
	$list = $wpdb->get_results("
		SELECT * FROM $tableName 
		WHERE Host REGEXP $identify_host[1] 
		OR $identify_ip[1] 
		OR UserAgent REGEXP $identify_ua[1] 
		ORDER BY Time DESC 
		LIMIT 0, $nudalytics_setting;
	");
	foreach($list as $data) {
		$crawler = Nudalytics_identifier($data->UserAgent, '/identify/crawler_ua.dat');
		echo '<ul class="nt">'.
			'<li class="w20 tc">'.$data->Time.'</li>'.
			'<li class="w20">'.$crawler.'</li>'.
			'<li class="w55">'.urldecode($data->URL).'</li>'.
			'</ul>';
	}
?>
</div>
<span class="measure">time to display: <?php echo (microtime(true)-$nudalytics_start)*1000; ?>[ms]</span>
<?php
}

/*----------------------------------------------------------
	Search logs
----------------------------------------------------------*/
function Nudalytics_searchLogs() {
	global $nudalytics_start;

	global $nudalytics_setting;
	global $RegExp_adminpage;
	global $tableName;
	global $wpdb;
	Nudalytics_chkOption();
	$identify_host = Nudalytics_SQLRegExp('/identify/crawler_host.dat');
	$identify_ip = Nudalytics_SQLRegExp('/identify/crawler_ip.dat');
	$identify_ua = Nudalytics_SQLRegExp('/identify/crawler_ua.dat');
?>
<div class="wrap">
<h2><?php _e('Search Logs', 'Nudalytics'); ?></h2>
<form method="POST">
	<p>Select target of the plugin's column
		<select name="target">
			<option value="*">(all)</option>
			<option value="Host">Host name</option>
			<option value="IP">IP address</option>
			<option value="Referrer">Referrer</option>
			<option value="URL">URL</option>
			<option value="UserAgent">UserAgent</option>
			<option value="CurrentUser">Users in WordPress</option>
			<option value="Time">Time</option>
		</select>
	</p>
	<p>Search words<input name="searchQuery" type="text" value="" /></p>
	<p><input type="submit" value="GO!" /></p>
</form>

<?php
	if(isset($_POST[target])) {
		$columns = array(
			'Host',
			'IP',
			'Referrer',
			'URL',
			'UserAgent',
			'CurrentUser',
			'Time'
		);
		$cc = count($columns);
		$query = preg_replace('/^[\s　]+/u', '', $_POST[searchQuery]);
		$query = preg_replace('/[\s　]+$/u', '', $query);
		
		//a∧b(alternative plan)
		if($_POST[target] == '*') {
			$tq = "REGEXP '(".preg_replace('/[\s　]+/u', '|', $query).")'";
			$query = '';
			for($i = 0; $i < $cc; $i++)
				$query .= "$columns[$i] $tq OR ";
			$query = preg_replace('/OR $/', '', $query);
		}
		else
			$query = "$_POST[target] REGEXP '(".preg_replace('/[\s　]+/u', '|', $query).")'";
		
		$result = $wpdb->get_results("
			SELECT * FROM $tableName 
			WHERE $query 
			ORDER BY Time DESC;
		");
		if(count($result) <= 0) {
			echo "<p>No one matched your query <span class=\"fw-b\">\"$_POST[searchQuery]\".</span></p></div>";
			exit;
		}
?>
<ul class="nt h">
	<li class="w10"><?php _e('Date', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('IP address / Host / UA', 'Nudalytics'); ?></li>
	<li class="w10"><?php _e('User', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('URL', 'Nudalytics'); ?></li>
	<li class="w35"><?php _e('Referrer', 'Nudalytics'); ?></li>
</ul>
<?php
		foreach($result as $data) {
			switch($_POST[target]) {
				case 'Host':
				case 'IP':
				case 'Referrer':
				case 'URL':
				case 'UserAgent':
				case 'CurrentUser':
					$data->$_POST[target] = preg_replace("/$_POST[searchQuery]+/iu", "<span class=\"hl\">$_POST[searchQuery]</span>", $data->$_POST[target]);
					break;
				default:
					for($i = 0; $i < $cc; $i++)
						$data->$columns[$i] = preg_replace("/$_POST[searchQuery]+/iu", "<span class=\"hl\">$_POST[searchQuery]</span>", $data->$columns[$i]);
			}
?>
<ul class="nt">
	<li class="w10 tc"><?php echo $data->Time; ?></li>
	<li class="w20 fs-s"><?php echo "$data->IP<br />$data->Host<br /><span class=\"itl\">$data->UserAgent</span>"; ?></li>
	<li class="w10"><?php echo $data->CurrentUser; ?></li>
	<li class="w20"><?php echo $data->URL; ?></li>
	<li class="w35"><?php echo $data->Referrer; ?></li>
</ul>
<?php
		}
	}
?>
</div>
<span class="measure">time to display: <?php echo (microtime(true)-$nudalytics_start)*1000; ?>[ms]</span>
<?php
}
?>
