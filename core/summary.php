<?php
/*----------------------------------------------------------
	Summary
----------------------------------------------------------*/
function Nudalytics_summary() {
	global $nudalytics_start;

	global $RegExp_adminpage;
	global $tableName;
	global $wpdb;
	Nudalytics_chkOption();
	$identify_host = Nudalytics_SQLRegExp('/identify/crawler_host.dat');
	$identify_ip = Nudalytics_SQLRegExp('/identify/crawler_ip.dat', 'NOT');
	$identify_ipfc = Nudalytics_SQLRegExp('/identify/crawler_ip.dat');
	$identify_ua = Nudalytics_SQLRegExp('/identify/crawler_ua.dat');
	$terms = get_option('Nudalytics_terms');
	
	$recentstr = '';
	$edaystr = '';
	
	$statData = $wpdb->get_results("
		SELECT DATE(Time) Date, COUNT(DISTINCT IP) IP, COUNT(URL) PV, AVG(Process) Process 
		FROM $tableName 
		WHERE Host NOT REGEXP $identify_host[1] 
		AND $identify_ip[1] 
		AND UserAgent NOT REGEXP $identify_ua[1] 
		AND CurrentUser IS NULL 
		GROUP BY Date DESC
		LIMIT 0, $terms;
	");
	$crawler = $wpdb->get_results("
		SELECT DATE(Time) Date, COUNT(DISTINCT IP) IP, COUNT(URL) PV 
		FROM $tableName 
		WHERE (Host REGEXP $identify_host[1] 
		OR $identify_ipfc[1] 
		OR UserAgent REGEXP $identify_ua[1]) 
		GROUP BY Date DESC
		LIMIT 0, $terms;
	");
	$chooseData = array(
		get_option('Nudalytics_main_indicator'),
		get_option('Nudalytics_sub_indicator'),
		get_option('Nudalytics_main_indicator1'),
		get_option('Nudalytics_sub_indicator1')
	);
	$displayData = array();
	$c = count($chooseData);
	for($i = 0; $i < $c; $i++) {
		switch($chooseData[$i]) {
			case "Unique user":
				for($j = 0; $j < $terms; $j++)
					$displayData[$i][] = $statData[$j]->IP;
				break;
			case "Page view":
				for($j = 0; $j < $terms; $j++)
					$displayData[$i][] = $statData[$j]->PV;
				break;
			case "Processing time":
				for($j = 0; $j < $terms; $j++)
					$displayData[$i][] = $statData[$j]->Process;
				break;
			case "Crawler visit":
				for($j = 0; $j < $terms; $j++)
					$displayData[$i][] = $crawler[$j]->PV;
				break;
			case "All Unique IP":
				for($j = 0; $j < $terms; $j++)
					$displayData[$i][] = $statData[$j]->IP + $crawler[$j]->IP;
				break;
			case "Page view (+Crawler visit)":
				for($j = 0; $j < $terms; $j++)
					$displayData[$i][] = $statData[$j]->PV + $crawler[$j]->PV;
				break;
			default:
				$displayData[$i] = null;
		}
	}
	
	for($i = 0; $i < $terms; $i++)
		$recentstr .= '<ul class="nt">'.
			'<li class="w15 tc">'.$statData[$i]->Date.'</li>'.
			'<li class="w15 tc">'.$statData[$i]->IP.'</li>'.
			'<li class="w20 tc">'.$statData[$i]->PV.'</li>'.
			'<li class="w30 tc">'.$statData[$i]->Process.'</li>'.
			'<li class="w15 tc">'.$crawler[$i]->PV.'</li>'.
			'</ul>';

	//counting for each days
	$edStatData = $wpdb->get_results("
		SELECT TO_DAYS(Time)%7 eday, COUNT(DISTINCT IP) IP, COUNT(URL) PV, AVG(Process) Process 
		FROM $tableName 
		WHERE TO_DAYS(Time) > TO_DAYS(current_date)-$terms 
		AND Host NOT REGEXP $identify_host[1] 
		AND $identify_ip[1] 
		AND UserAgent NOT REGEXP $identify_ua[1] 
		AND URL NOT REGEXP $RegExp_adminpage 
		GROUP BY eday;
	");
	$edCrawler = $wpdb->get_results("
		SELECT TO_DAYS(Time)%7 eday, COUNT(URL) PV, COUNT(DISTINCT IP) IP FROM $tableName 
		WHERE TO_DAYS(Time) > TO_DAYS(current_date)-$terms 
		AND (Host REGEXP $identify_host[1] 
		OR $identify_ipfc[1] 
		OR UserAgent REGEXP $identify_ua[1]) 
		AND URL NOT REGEXP $RegExp_adminpage 
		GROUP BY eday;
	");
	$dayOfTheWeek = array("MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN");
	for($i = 0; $i <= 6; $i++)
		$edaystr .= '<ul class="nt">'.
			'<li class="w15 tc">'.$dayOfTheWeek[$i].'</li>'.
			'<li class="w15 tc">'.$edStatData[$i]->IP.'</li>'.
			'<li class="w20 tc">'.$edStatData[$i]->PV.'</li>'.
			'<li class="w30 tc">'.$edStatData[$i]->Process.'</li>'.
			'<li class="w15 tc">'.$edCrawler[$i]->PV.'</li>'.
			'</ul>';

	//counting for each hours
	$ehStatData = $wpdb->get_results("
		SELECT DATE_FORMAT(Time, '%H') ehour, COUNT(DISTINCT IP) IP, COUNT(URL) PV, AVG(Process) Process 
		FROM $tableName 
		WHERE TO_DAYS(Time) > TO_DAYS(current_date)-$terms 
		AND Host NOT REGEXP $identify_host[1] 
		AND $identify_ip[1] 
		AND UserAgent NOT REGEXP $identify_ua[1] 
		AND URL NOT REGEXP $RegExp_adminpage 
		GROUP BY ehour;
	");
	$ehCrawler = $wpdb->get_results("
		SELECT DATE_FORMAT(Time, '%H') ehour, COUNT(URL) PV FROM $tableName 
		WHERE TO_DAYS(Time) > TO_DAYS(current_date)-$terms 
		AND (Host REGEXP $identify_host[1] 
		OR $identify_ipfc[1] 
		OR UserAgent REGEXP $identify_ua[1]) 
		GROUP BY ehour;
	");
	for($i = 0; $i < 24; $i++)
		$ehourstr .= '<ul class="nt">'.
			'<li class="w15 tc">'.$ehStatData[$i]->ehour.'</li>'.
			'<li class="w15 tc">'.$ehStatData[$i]->IP.'</li>'.
			'<li class="w20 tc">'.$ehStatData[$i]->PV.'</li>'.
			'<li class="w30 tc">'.$ehStatData[$i]->Process.'</li>'.
			'<li class="w15 tc">'.$ehCrawler[$i]->PV.'</li>'.
			'</ul>';

?>
<div class="wrap">
<h2><?php _e("Summary", "Nudalytics") ?></h2>
<form method="POST">
<?php
	if($_POST['save'] == true) {
		update_option('Nudalytics_main_indicator', $_POST['main_indicator']);
		update_option('Nudalytics_sub_indicator', $_POST['sub_indicator']);
		update_option('Nudalytics_main_indicator1', $_POST['main_indicator1']);
		update_option('Nudalytics_sub_indicator1', $_POST['sub_indicator1']);
		print "<h3>".__('Changed indicator.', 'Nudalytics')."</h3>";
	}
?>
<p><?php _e("Select Indicator", "Nudalytics") ?></p>
<p><span style="color: #00f;"><?php _e("BLUE", "Nudalytics"); ?></span>
	<select name="main_indicator">
		<option value="Unique user"><?php _e("Unique user", "Nudalytics") ?></option>
		<option value="Page view"<?php if(get_option('Nudalytics_main_indicator')=="Page view") echo " selected"; ?>><?php _e("Page view", "Nudalytics") ?></option>
		<option value="Processing time"<?php if(get_option('Nudalytics_main_indicator')=="Processing time") echo " selected"; ?>><?php _e("Processing time", "Nudalytics") ?></option>
		<option value="Crawler visit"<?php if(get_option('Nudalytics_main_indicator')=="Crawler visit") echo " selected"; ?>><?php _e("Crawler visit", "Nudalytics") ?></option>
		<option value="All Unique IP"<?php if(get_option('Nudalytics_main_indicator')=="All Unique IP") echo " selected"; ?>><?php _e("All Unique IP", "Nudalytics") ?></option>
		<option value="Page view (+Crawler visit)"<?php if(get_option('Nudalytics_main_indicator')=="Page view (+Crawler visit)") echo " selected"; ?>><?php _e("Page view (+Crawler visit)", "Nudalytics") ?></option>
	</select>
	<?php _e("per", "Nudalytics"); ?>
	<select name="sub_indicator">
		<option value="none">(<?php _e("none", "Nudalytics") ?>)</option>
		<option value="Unique user"<?php if(get_option('Nudalytics_sub_indicator')=="Unique user") echo " selected"; ?>><?php _e("Unique user", "Nudalytics") ?></option>
		<option value="Page view"<?php if(get_option('Nudalytics_sub_indicator')=="Page view") echo " selected"; ?>><?php _e("Page view", "Nudalytics") ?></option>
		<option value="Processing time"<?php if(get_option('Nudalytics_sub_indicator')=="Processing time") echo " selected"; ?>><?php _e("Processing time", "Nudalytics") ?></option>
		<option value="Crawler visit"<?php if(get_option('Nudalytics_sub_indicator')=="Crawler visit") echo " selected"; ?>><?php _e("Crawler visit", "Nudalytics") ?></option>
		<option value="All Unique IP"<?php if(get_option('Nudalytics_sub_indicator')=="All Unique IP") echo " selected"; ?>><?php _e("All Unique IP", "Nudalytics") ?></option>
		<option value="Page view (+Crawler visit)"<?php if(get_option('Nudalytics_sub_indicator')=="Page view (+Crawler visit)") echo " selected"; ?>><?php _e("Page view (+Crawler visit)", "Nudalytics") ?></option>
	</select>
</p>

<p><span style="color: red;"><?php _e("RED", "Nudalytics"); ?></span>
	<select name="main_indicator1">
		<option value="none">(<?php _e("none", "Nudalytics") ?>)</option>
		<option value="Unique user"<?php if(get_option('Nudalytics_main_indicator1')=="Unique user") echo " selected"; ?>><?php _e("Unique user", "Nudalytics") ?></option>
		<option value="Page view"<?php if(get_option('Nudalytics_main_indicator1')=="Page view") echo " selected"; ?>><?php _e("Page view", "Nudalytics") ?></option>
		<option value="Processing time"<?php if(get_option('Nudalytics_main_indicator1')=="Processing time") echo " selected"; ?>><?php _e("Processing time", "Nudalytics") ?></option>
		<option value="Crawler visit"<?php if(get_option('Nudalytics_main_indicator1')=="Crawler visit") echo " selected"; ?>><?php _e("Crawler visit", "Nudalytics") ?></option>
		<option value="All Unique IP"<?php if(get_option('Nudalytics_main_indicator1')=="All Unique IP") echo " selected"; ?>><?php _e("All Unique IP", "Nudalytics") ?></option>
		<option value="Page view (+Crawler visit)"<?php if(get_option('Nudalytics_main_indicator1')=="Page view (+Crawler visit)") echo " selected"; ?>><?php _e("Page view (+Crawler visit)", "Nudalytics") ?></option>
	</select>
	<?php _e("per", "Nudalytics"); ?>
	<select name="sub_indicator1">
		<option value="none">(<?php _e("none", "Nudalytics") ?>)</option>
		<option value="Unique user"<?php if(get_option('Nudalytics_sub_indicator1')=="Unique user") echo " selected"; ?>><?php _e("Unique user", "Nudalytics") ?></option>
		<option value="Page view"<?php if(get_option('Nudalytics_sub_indicator1')=="Page view") echo " selected"; ?>><?php _e("Page view", "Nudalytics") ?></option>
		<option value="Processing time"<?php if(get_option('Nudalytics_sub_indicator1')=="Processing time") echo " selected"; ?>><?php _e("Processing time", "Nudalytics") ?></option>
		<option value="Crawler visit"<?php if(get_option('Nudalytics_sub_indicator1')=="Crawler visit") echo " selected"; ?>><?php _e("Crawler visit", "Nudalytics") ?></option>
		<option value="All Unique IP"<?php if(get_option('Nudalytics_sub_indicator1')=="All Unique IP") echo " selected"; ?>><?php _e("All Unique IP", "Nudalytics") ?></option>
		<option value="Page view (+Crawler visit)"<?php if(get_option('Nudalytics_sub_indicator1')=="Page view (+Crawler visit)") echo " selected"; ?>><?php _e("Page view (+Crawler visit)", "Nudalytics") ?></option>
	</select>
	<input name="save" class="button-primary" type="submit" value="<?php _e("Display", "Nudalytics") ?>" />
</p>
</form>

<div class="graph">
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
var data = google.visualization.arrayToDataTable([
<?php
/*
 * (現時点)使用するget_optionの値は以下4点
 * これ、変えたいね
 * 1-1. Nudalytics_main_indicator
 * 1-2. Nudalytics_sub_indicator
 * 2-1. Nudalytics_main_indicator1
 * 2-2. Nudalytics_sub_indicator1
 * なお、個々が設定値のない場合を'none'と評価
 */
	echo "['Date', '".get_option('Nudalytics_main_indicator');
	if(get_option('Nudalytics_sub_indicator') == 'none')
		echo "'";
	else
		echo " / ".get_option('Nudalytics_sub_indicator')." (*100)'";
		
	if(get_option('Nudalytics_main_indicator1') == 'none')
		echo "]";
	else {
		echo ", '".get_option('Nudalytics_main_indicator1');
		if(get_option('Nudalytics_sub_indicator1') == 'none')
			echo "']";
		else
			echo " / ".get_option('Nudalytics_sub_indicator1')." (*100)']";
	}
	
	//インジケータ1つ
	if(get_option('Nudalytics_main_indicator1') == 'none') {
		for($i = get_option('Nudalytics_terms')-1; $i >= 0; $i--) {
			if(!isset($displayData[1][$i]))
				$displayData[1][$i] = 100;
			echo ",['".$UU[$i]->Date."', ".($displayData[0][$i]/$displayData[1][$i]*100)."]";
		}
	}
	//インジケータ2つのとき
	else {
		for($i = get_option('Nudalytics_terms')-1; $i >= 0; $i--) {
			if(!isset($displayData[1][$i]))
				$displayData[1][$i] = 100;
			if(!isset($displayData[3][$i]))
				$displayData[3][$i] = 100;
			echo ",['".$UU[$i]->Date."', ".($displayData[0][$i]/$displayData[1][$i]*100).", ".($displayData[2][$i]/$displayData[3][$i]*100)."]";
		}
	}

?>
]);

        var options = {
          title: 'Statistics',
          hAxis: {title: 'Date',  titleTextStyle: {color: '#000'}}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

    <div id="chart_div" style="width: 1000px; height: 500px;"></div>

</div>

<h3><?php _e('Recent result', 'Nudalytics'); ?></h3>
<ul class="nt h">
	<li class="w15"><?php _e('Date', 'Nudalytics'); ?></li>
	<li class="w15"><?php _e('UU', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('PV(Direct / Referred / Organic)', 'Nudalytics'); ?></li>
	<li class="w30"><?php _e('AVG Processing time [ms]', 'Nudalytics'); ?></li>
	<li class="w15"><?php _e('Crawler visit', 'Nudalytics'); ?></li>
</ul>
<?php
	echo $recentstr;
?>

<h3><?php _e('Each day of the week', 'Nudalytics'); ?></h3>
<p><?php _e('Aggregate for a week, it can be displayed correctly.', 'Nudalytics') ?></p>
<ul class="nt h">
	<li class="w15"><?php _e('Day', 'Nudalytics'); ?></li>
	<li class="w15"><?php _e('UU', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('PV', 'Nudalytics'); ?></li>
	<li class="w30"><?php _e('AVG Processing time [ms]', 'Nudalytics'); ?></li>
	<li class="w15"><?php _e('Crawler visit', 'Nudalytics'); ?></li>
</ul>
<?php
	echo $edaystr;
?>

<h3><?php _e('Each o\'clock', 'Nudalytics'); ?></h3>
<ul class="nt h">
	<li class="w15"><?php _e('Hour', 'Nudalytics'); ?></li>
	<li class="w15"><?php _e('UU', 'Nudalytics'); ?></li>
	<li class="w20"><?php _e('PV', 'Nudalytics'); ?></li>
	<li class="w30"><?php _e('AVG Processing time [ms]', 'Nudalytics'); ?></li>
	<li class="w15"><?php _e('Crawler visit', 'Nudalytics'); ?></li>
</ul>
<?php
	echo $ehourstr;
	$nudalytics_total = (microtime(true)-$nudalytics_start)*1000;
?>
</div>
<span class="measure">time to display: <?php echo $nudalytics_total; ?>[ms]</span>
<?php
}
?>
