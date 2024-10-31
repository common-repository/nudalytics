<?php
/*
Plugin Name: Nudalytics
Plugin URI: https://nimbusnoize.com/works/nudalytics
Description: Analyzer for WordPress, can filt IP address, host name, and User Agent widely.
Version: 2.0.2
Author: AJI Dreamtaste
Author URI: https://nimbusnoize.com
License: GNU General Public License

This plugin is being provided under GPL.
About GPL: http://www.gnu.org/licenses/gpl.html
Copyright (C) 2012 AJI Dreamtaste (まぼろしのあじ). All rights reserved.
*/

$nudalytics_start = microtime(true);	//当プラグインの処理を計測
$RegExp_adminpage = "'(wp-admin|wp-content)'";
$tableName = $wpdb->prefix.'Nudalytics';
define(NudalyticsURL, dirname(__FILE__));

if(get_option('Nudalytics_numlog') != null)
	$nudalytics_setting = get_option('Nudalytics_numlog');
else
	$nudalytics_setting = '50';



include(dirname(__FILE__).'/core/identify.php');
include(dirname(__FILE__).'/core/inflow.php');		//(submenu)Realtime, Referrer, Search query, Movement in Admin, Crawlers
include(dirname(__FILE__).'/core/setting.php');		//(submenu)Setting
include(dirname(__FILE__).'/core/summary.php');		//(main)Summary

add_action('admin_menu', 'Nudalytics_menu');
add_action('shutdown', 'Nudalytics_analyze');
add_action('admin_head','Nudalytics_css');

function Nudalytics_chkOption() {
	//graph in summary
	if(get_option('Nudalytics_main_indicator') == null) {
		update_option('Nudalytics_main_indicator', 'Unique user');
		$flg = true;
	}
	if(get_option('Nudalytics_main_indicator1') == null) {
		update_option('Nudalytics_main_indicator1', 'Page view');
		$flg = true;
	}
	if(get_option('Nudalytics_sub_indicator') == null) {
		update_option('Nudalytics_sub_indicator', 'none');
		$flg = true;
	}
	if(get_option('Nudalytics_sub_indicator1') == null) {
		update_option('Nudalytics_sub_indicator1', 'none');
		$flg = true;
	}

	//setting
	if(get_option('Nudalytics_terms') == null || (int)get_option('Nudalytics_terms') < 5) {
		update_option('Nudalytics_terms', 30);
		$flg = true;
	}
	if(get_option('Nudalytics_numlog') == null || (int)get_option('Nudalytics_numlog') < 10) {
		update_option('Nudalytics_numlog', 50);
		$flg = true;
	}
	if(get_option('Nudalytics_retention') == null || (int)get_option('Nudalytics_retention') < 5) {
		update_option('Nudalytics_retention', 30);
		$flg = true;
	}
	if(get_option('Nudalytics_roles_realtime') == null) {
		update_option('Nudalytics_roles_realtime', '8');
		$flg = true;
	}
	if(get_option('Nudalytics_roles_referrer') == null) {
		update_option('Nudalytics_roles_referrer', '8');
		$flg = true;
	}
	if(get_option('Nudalytics_roles_searchQuery') == null) {
		update_option('Nudalytics_roles_searchQuery', '8');
		$flg = true;
	}
	if(get_option('Nudalytics_roles_crawlers') == null) {
		update_option('Nudalytics_roles_crawlers', '8');
		$flg = true;
	}
	if(get_option('Nudalytics_roles_movementInAdmin') == null) {
		update_option('Nudalytics_roles_movementInAdmin', '8');
		$flg = true;
	}
	if(get_option('Nudalytics_roles_searchLogs') == null) {
		update_option('Nudalytics_roles_searchLogs', '8');
		$flg = true;
	}
	
	if(isset($flg))
		echo '<span class="r">Automatically set properties that weren\'t set or were smaller than each limit value.</span>';
}

function Nudalytics_css(){
	echo '<link rel="stylesheet" type="text/css" href="'.plugin_dir_url(__FILE__).'/core/style.css">';
}

function Nudalytics_menu() {
	global $wpdb;
	global $tableName;
	$level = array(
		get_option('Nudalytics_roles_realtime'),
		get_option('Nudalytics_roles_referrer'),
		get_option('Nudalytics_roles_searchQuery'),
		get_option('Nudalytics_roles_crawlers'),
		get_option('Nudalytics_roles_movementInAdmin'),
		get_option('Nudalytics_roles_searchLogs'),
	);

	$count = count($wpdb->get_col("DESCRIBE $tableName;"));
	$SQLColumn = '';
	foreach($wpdb->get_col("DESCRIBE $tableName;") as $row)
		$SQLColumn .= "$row ";
	if($wpdb->get_var('SHOW TABLES') != $tableName)
		Nudalytics_SQLTableCreate();
	if(
		!preg_match('/CurrentUser/', $SQLColumn)
		|| preg_match('/(OS|Browser|Crawler|Search)/', $SQLColumn)
	)
		Nudalytics_change();
	
	add_menu_page('Nudalytics', 'Nudalytics', 0, 'Nudalytics', 'Nudalytics_summary', plugin_dir_url(__FILE__).'/icon.png');
	add_submenu_page('Nudalytics', __('Summary', 'Nudalytics'), __('Summary', 'Nudalytics'), 0, 'Nudalytics', 'Nudalytics_summary');
	add_submenu_page('Nudalytics', __('Realtime', 'Nudalytics'), __('Realtime', 'Nudalytics'), $level[0], 'Nudalytics_realtime', 'Nudalytics_realtime');
	add_submenu_page('Nudalytics', __('Referrer', 'Nudalytics'), __('Referrer', 'Nudalytics'), $level[1], 'Nudalytics_referrer', 'Nudalytics_referrer');
	add_submenu_page('Nudalytics', __('Search query', 'Nudalytics'), __('Search query', 'Nudalytics'), $level[2], 'Nudalytics_searchquery', 'Nudalytics_searchquery');
	add_submenu_page('Nudalytics', __('Crawlers', 'Nudalytics'), __('Crawlers', 'Nudalytics'), $level[3], 'Nudalytics_crawlers', 'Nudalytics_crawlers');
	add_submenu_page('Nudalytics', __('Movement in Admin', 'Nudalytics'), __('Movement in Admin', 'Nudalytics'), $level[4], 'Nudalytics_adminpage', 'Nudalytics_adminpage');
	add_submenu_page('Nudalytics', __('Search logs', 'Nudalytics'), __('Search logs', 'Nudalytics'), $level[5], 'Nudalytics_searchLogs', 'Nudalytics_searchLogs');
	add_submenu_page('Nudalytics', __('Settings', 'Nudalytics'), __('Settings', 'Nudalytics'), 8, 'Nudalytics_setting', 'Nudalytics_setting');
}

function Nudalytics_SQLTableCreate() {
	global $wpdb;
	global $tableName;
	$wpdb->query("
		CREATE TABLE $tableName(
		Time CHAR(19),
		IP CHAR(15),
		Host VARCHAR(100),
		UserAgent VARCHAR(250),
		CurrentUser VARCHAR(100),
		URL VARCHAR(250),
		Referrer VARCHAR(250),
		Process INT(10)
	);");
	
	/*
	1.3.4未満で生成していたテーブル内容
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		Time char(19),
		Process char(10),
		IP char(15),
		URL varchar(250),
		Host varchar(100),
		UserAgent varchar(250),
		OS varchar(18),
		Browser varchar(32),
		Crawler varchar(32),
		Referrer varchar(250),
		SearchQuery varchar(80),
		SearchEngine varchar(20),
		UNIQUE KEY id (id)
	それらの変更についてはNudalytics_change関数に記述
	*/

	
	/*$wpdb->query("
		ALTER TABLE $tableName 
		ADD FULLTEXT text(URL, Host, UserAgent, CurrentUser, Referrer);
	");
	*/

	$wpdb->query("OPTIMIZE TABLE $tablename;");
	include(ABSPATH . 'wp-admin/includes/upgrade.php');
}

function Nudalytics_change() {
	global $tableName;
	global $wpdb;
	$wpdb->query("
		ALTER TABLE $tableName
		DROP COLUMN ID,
		DROP COLUMN OS,
		DROP COLUMN Browser,
		DROP COLUMN Crawler,
		DROP COLUMN SearchQuery,
		DROP COLUMN SearchEngine
	;");
	$wpdb->query("
		ALTER TABLE $tableName 
		ADD CurrentUser VARCHAR(100) 
		AFTER UserAgent;
	");
	$wpdb->query("
		UPDATE $tableName 
		SET Referrer = NULL 
		WHERE Referrer = ' '
		OR Referrer = '';
	");
	$wpdb->query("
		UPDATE $tableName 
		SET CurrentUser = NULL 
		WHERE CurrentUser = ' ' 
		OR CurrentUser = '';
	");
	/*
	$wpdb->query("
		ALTER TABLE $tableName 
		ADD FULLTEXT text(URL, Host, UserAgent, CurrentUser, Referrer);
	");
	*/
	echo '<span class="r">SQL table in \'Nudalytics\' was renewed.</span>';
}

function Nudalytics_analyze() {
	global $nudalytics_start;

	global $current_user;
	global $tableName;
	global $wpdb;
	get_currentuserinfo();
	
	$time = current_time('mysql');
	$ip = ipCheck($_SERVER['REMOTE_ADDR']);
	$host = hostCheck(gethostbyaddr($_SERVER['REMOTE_ADDR']));
	$ua = addslashes(strip_tags(uaCheck($_SERVER['HTTP_USER_AGENT'])));
	if(strlen($current_user->user_login) > 0)
		$user = "'".$current_user->user_login."'";
	else
		$user = 'NULL';
	$url = urldecode(strtolower($_SERVER['REQUEST_URI']));
	$referrer = referrerCheck(htmlentities($_SERVER['HTTP_REFERER']));
	
	$tilt = 0;
	if(preg_match('/(wp-cron.php|xmlrpc|ajax)/', $url))
		$tilt++;
	
	if($tilt > 0)
		$url = null;
	
	if($ip!=null && $host!=null && $ua!=null && $url!=null) {
		$nudalytics_total = (microtime(true)-$nudalytics_start)*1000;
		
		//use columns (Time, IP, HOST, UserAgent, User, URL, Referrer)
		$wpdb->query("INSERT INTO $tableName(
			Time,
			IP,
			HOST,
			UserAgent,
			CurrentUser,
			URL,
			Referrer,
			Process
		) VALUES(
			'$time',
			'$ip',
			'$host',
			'$ua',
			$user,
			'$url',
			$referrer,
			$nudalytics_total
		);");
		
		//auto deletion
		if(get_option('Nudalytics_retention')!=null)
			$wpdb->query("DELETE FROM $tableName WHERE Time < FROM_UNIXTIME(UNIX_TIMESTAMP()-86400*".(get_option('Nudalytics_retention')).");");
		else
			$wpdb->query("DELETE FROM $tableName WHERE Time < FROM_UNIXTIME(UNIX_TIMESTAMP()-86400*30);");
	}
}
?>
