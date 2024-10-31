<?php
/*----------------------------------------------------------
	Settings
----------------------------------------------------------*/
function Nudalytics_setting() {
	Nudalytics_chkOption();
?>
<div class="wrap">
<h2><?php _e('Settings', 'Nudalytics') ?></h2>

<?php
	if(isset($_POST['save'])) {
		if(is_numeric($_POST['terms']) && is_numeric($_POST['numlog']) && is_numeric($_POST['retention'])) {
			update_option('Nudalytics_roles_realtime', $_POST[roles_realtime]);
			update_option('Nudalytics_roles_referrer', $_POST[roles_referrer]);
			update_option('Nudalytics_roles_searchQuery', $_POST[roles_searchQuery]);
			update_option('Nudalytics_roles_crawlers', $_POST[roles_crawlers]);
			update_option('Nudalytics_roles_movementInAdmin', $_POST[roles_movementInAdmin]);
			update_option('Nudalytics_roles_searchLogs', $_POST[roles_searchLogs]);
			
			update_option('Nudalytics_terms', $_POST[terms]);
			update_option('Nudalytics_numlog', $_POST[numlog]);
			update_option('Nudalytics_retention', $_POST[retention]);
			print "<h3>".__('Completely saved.', 'Nudalytics')."</h3>";
		}
		else
			print "<h3 style='color: red;'>".__('ERROR: exists some forms that is enpty, or not number.', 'Nudalytics')."</h3>";
	}
?>

<div class="metabox-holder">

<form method="POST">
<div class="postbox">
	<div class="handlediv">
	</div>
	<h3 class="hndle"><span><?php _e('Management of logs', 'Nudalytics') ?></span></h3>
	<div class="inside">
		<p><label><?php _e('How terms display in summary graphs(min_value: 5)', 'Nudalytics') ?>: <input name="terms" type="text" value="<?php echo get_option('Nudalytics_terms'); ?>" /><?php _e("days", 'Nudalytics') ?></label></p>
		<p><label><?php _e('Number of Logs in Realtime(min_value: 10)', 'Nudalytics') ?>: <input name="numlog" type="text" value="<?php echo get_option('Nudalytics_numlog'); ?>" /><?php _e("lines", "Nudalytics") ?></label><br /><span style="color: #d00;"><?php _e("CAUTION! 1000 and more values may strain your server.", "Nudalytics"); ?></span></p>
		<p><label><?php _e('Retention Periods of Data(min_value: 5)', 'Nudalytics') ?>: <input name="retention" type="text" value="<?php echo get_option('Nudalytics_retention'); ?>" /><?php _e("days", "Nudalytics") ?></label></p>
		<p><input name="save" class="button-primary" type="submit" value="<?php _e('Save', 'Nudalytics') ?>" /></label></p>
	</div>
</div>
<div class="postbox">
	<div class="handlediv">
	</div>
	<h3 class="hndle"><span><?php _e('Change submenu levels for displaying', 'Nudalytics') ?></span></h3>
	<div class="inside">
		<p><label>
			<?php _e('Realtime', 'Nudalytics') ?>: 
			<select name="roles_realtime">
				<option value="8" <?php if(get_option('Nudalytics_roles_realtime') == '8') echo 'selected'; ?>><?php _e("Administrator", 'Nudalytics') ?></option>
				<option value="5" <?php if(get_option('Nudalytics_roles_realtime') == '5') echo 'selected'; ?>><?php _e("Editor", 'Nudalytics') ?></option>
				<option value="2" <?php if(get_option('Nudalytics_roles_realtime') == '2') echo 'selected'; ?>><?php _e("Author", 'Nudalytics') ?></option>
				<option value="1" <?php if(get_option('Nudalytics_roles_realtime') == '1') echo 'selected'; ?>><?php _e("Contributor", 'Nudalytics') ?></option>
				<option value="0" <?php if(get_option('Nudalytics_roles_realtime') == '0') echo 'selected'; ?>><?php _e("Subscriber(all users)", 'Nudalytics') ?></option>
				<option value="11" <?php if(get_option('Nudalytics_roles_realtime') == '11') echo 'selected'; ?>><?php _e("(don't display)", 'Nudalytics') ?></option>
			</select>
		</label></p>
		<p><label>
			<?php _e('Referrer', 'Nudalytics') ?>: 
			<select name="roles_referrer">
				<option value="8" <?php if(get_option('Nudalytics_roles_referrer') == '8') echo 'selected'; ?>><?php _e("Administrator", 'Nudalytics') ?></option>
				<option value="5" <?php if(get_option('Nudalytics_roles_referrer') == '5') echo 'selected'; ?>><?php _e("Editor", 'Nudalytics') ?></option>
				<option value="2" <?php if(get_option('Nudalytics_roles_referrer') == '2') echo 'selected'; ?>><?php _e("Author", 'Nudalytics') ?></option>
				<option value="1" <?php if(get_option('Nudalytics_roles_referrer') == '1') echo 'selected'; ?>><?php _e("Contributor", 'Nudalytics') ?></option>
				<option value="0" <?php if(get_option('Nudalytics_roles_referrer') == '0') echo 'selected'; ?>><?php _e("Subscriber(all users)", 'Nudalytics') ?></option>
				<option value="11" <?php if(get_option('Nudalytics_roles_referrer') == '11') echo 'selected'; ?>><?php _e("(don't display)", 'Nudalytics') ?></option>
			</select>
		</label></p>
		<p><label>
			<?php _e('Search query', 'Nudalytics') ?>: 
			<select name="roles_searchQuery">
				<option value="8" <?php if(get_option('Nudalytics_roles_searchQuery') == '8') echo 'selected'; ?>><?php _e("Administrator", 'Nudalytics') ?></option>
				<option value="5" <?php if(get_option('Nudalytics_roles_searchQuery') == '5') echo 'selected'; ?>><?php _e("Editor", 'Nudalytics') ?></option>
				<option value="2" <?php if(get_option('Nudalytics_roles_searchQuery') == '2') echo 'selected'; ?>><?php _e("Author", 'Nudalytics') ?></option>
				<option value="1" <?php if(get_option('Nudalytics_roles_searchQuery') == '1') echo 'selected'; ?>><?php _e("Contributor", 'Nudalytics') ?></option>
				<option value="0" <?php if(get_option('Nudalytics_roles_searchQuery') == '0') echo 'selected'; ?>><?php _e("Subscriber(all users)", 'Nudalytics') ?></option>
				<option value="11" <?php if(get_option('Nudalytics_roles_searchQuery') == '11') echo 'selected'; ?>><?php _e("(don't display)", 'Nudalytics') ?></option>
			</select>
		</label></p>
		<p><label>
			<?php _e('Crawlers', 'Nudalytics') ?>: 
			<select name="roles_crawlers">
				<option value="8" <?php if(get_option('Nudalytics_roles_crawlers') == '8') echo 'selected'; ?>><?php _e("Administrator", 'Nudalytics') ?></option>
				<option value="5" <?php if(get_option('Nudalytics_roles_crawlers') == '5') echo 'selected'; ?>><?php _e("Editor", 'Nudalytics') ?></option>
				<option value="2" <?php if(get_option('Nudalytics_roles_crawlers') == '2') echo 'selected'; ?>><?php _e("Author", 'Nudalytics') ?></option>
				<option value="1" <?php if(get_option('Nudalytics_roles_crawlers') == '1') echo 'selected'; ?>><?php _e("Contributor", 'Nudalytics') ?></option>
				<option value="0" <?php if(get_option('Nudalytics_roles_crawlers') == '0') echo 'selected'; ?>><?php _e("Subscriber(all users)", 'Nudalytics') ?></option>
				<option value="11" <?php if(get_option('Nudalytics_roles_crawlers') == '11') echo 'selected'; ?>><?php _e("(don't display)", 'Nudalytics') ?></option>
			</select>
		</label></p>
		<p><label>
			<?php _e('Movement in Admin', 'Nudalytics') ?>: 
			<select name="roles_movementInAdmin">
				<option value="8" <?php if(get_option('Nudalytics_roles_movementInAdmin') == '8') echo 'selected'; ?>><?php _e("Administrator", 'Nudalytics') ?></option>
				<option value="5" <?php if(get_option('Nudalytics_roles_movementInAdmin') == '5') echo 'selected'; ?>><?php _e("Editor", 'Nudalytics') ?></option>
				<option value="2" <?php if(get_option('Nudalytics_roles_movementInAdmin') == '2') echo 'selected'; ?>><?php _e("Author", 'Nudalytics') ?></option>
				<option value="1" <?php if(get_option('Nudalytics_roles_movementInAdmin') == '1') echo 'selected'; ?>><?php _e("Contributor", 'Nudalytics') ?></option>
				<option value="0" <?php if(get_option('Nudalytics_roles_movementInAdmin') == '0') echo 'selected'; ?>><?php _e("Subscriber(all users)", 'Nudalytics') ?></option>
				<option value="11" <?php if(get_option('Nudalytics_roles_movementInAdmin') == '11') echo 'selected'; ?>><?php _e("(don't display)", 'Nudalytics') ?></option>
			</select>
		</label></p>
		<p><label>
			<?php _e('Search logs', 'Nudalytics') ?>: 
			<select name="roles_searchLogs">
				<option value="8" <?php if(get_option('Nudalytics_roles_searchLogs') == '8') echo 'selected'; ?>><?php _e("Administrator", 'Nudalytics') ?></option>
				<option value="5" <?php if(get_option('Nudalytics_roles_searchLogs') == '5') echo 'selected'; ?>><?php _e("Editor", 'Nudalytics') ?></option>
				<option value="2" <?php if(get_option('Nudalytics_roles_searchLogs') == '2') echo 'selected'; ?>><?php _e("Author", 'Nudalytics') ?></option>
				<option value="1" <?php if(get_option('Nudalytics_roles_searchLogs') == '1') echo 'selected'; ?>><?php _e("Contributor", 'Nudalytics') ?></option>
				<option value="0" <?php if(get_option('Nudalytics_roles_searchLogs') == '0') echo 'selected'; ?>><?php _e("Subscriber(all users)", 'Nudalytics') ?></option>
				<option value="11" <?php if(get_option('Nudalytics_roles_searchLogs') == '11') echo 'selected'; ?>><?php _e("(don't display)", 'Nudalytics') ?></option>
			</select>
		</label></p>
		<p><input name="save" class="button-primary" type="submit" value="<?php _e('Save', 'Nudalytics') ?>" /></label></p>
	</div>
</div>

</form>

</div>

<hr />
<div class="postbox-container">
<h3><?php _e("About the Plugin", 'Nudalytics') ?></h3>
<p>Author: AJI Dreamtaste(まぼろしのあじ)</p>
<p>Website: <a href="https://nimbusnoize.com" target="_blank">nimbus noize</a></p>
<p>Google+: <a href="https://plus.google.com/113124531461711903938?rel=author" target="_blank">AJI Dreamtaste(まぼろしのあじ) - Google+</a></p>

<h3><?php _e("Contact", 'Nudalytics') ?></h3>
<p><a href="https://nimbusnoize.com/works/nudalytics" target="_blank">Nudalytics | nimbus noize</a></p>
<p><a href="http://wordpress.org/extend/plugins/nudalytics/" target="_blank">Nudalytics ≪ WordPress Plugins</a></p>
<p><?php _e("I'll respond your voices only in those comment forms.", 'Nudalytics') ?><br /></p>
</div>

<script type="text/javascript">
/* <![CDATA[ */
var commonL10n = {"warnDelete":"\u9078\u629e\u3057\u305f\u9805\u76ee\u3092\u5b8c\u5168\u306b\u524a\u9664\u3057\u3088\u3046\u3068\u3057\u3066\u3044\u307e\u3059\u3002\n\u4e2d\u6b62\u3059\u308b\u306b\u306f '\u30ad\u30e3\u30f3\u30bb\u30eb' \u3092\u3001\u524a\u9664\u3059\u308b\u306b\u306f 'OK' \u3092\u30af\u30ea\u30c3\u30af\u3057\u3066\u304f\u3060\u3055\u3044\u3002"};var wpAjax = {"noPerm":"\u64cd\u4f5c\u3092\u5b9f\u884c\u3059\u308b\u6a29\u9650\u304c\u3042\u308a\u307e\u305b\u3093\u3002","broken":"\u4e0d\u660e\u306a\u30a8\u30e9\u30fc\u304c\u767a\u751f\u3057\u307e\u3057\u305f\u3002"};var quicktagsL10n = {"wordLookup":"\u8abf\u3079\u308b\u5358\u8a9e\u3092\u5165\u529b:","dictionaryLookup":"\u8f9e\u66f8\u3092\u53c2\u7167","lookup":"\u691c\u7d22","closeAllOpenTags":"\u958b\u3044\u3066\u3044\u308b\u3059\u3079\u3066\u306e\u30bf\u30b0\u3092\u9589\u3058\u308b","closeTags":"\u30bf\u30b0\u3092\u9589\u3058\u308b","enterURL":"URL \u3092\u5165\u529b\u3057\u3066\u304f\u3060\u3055\u3044","enterImageURL":"\u753b\u50cf\u306e URL \u3092\u5165\u529b\u3057\u3066\u304f\u3060\u3055\u3044","enterImageDescription":"\u753b\u50cf\u306e\u8aac\u660e\u3092\u5165\u529b\u3057\u3066\u304f\u3060\u3055\u3044","fullscreen":"\u30d5\u30eb\u30b9\u30af\u30ea\u30fc\u30f3","toggleFullscreen":"\u30d5\u30eb\u30b9\u30af\u30ea\u30fc\u30f3"};var adminCommentsL10n = {"hotkeys_highlight_first":"","hotkeys_highlight_last":"","replyApprove":"\u627f\u8a8d\u3068\u8fd4\u4fe1","reply":"\u8fd4\u4fe1"};var thickboxL10n = {"next":"\u6b21\u3078 >","prev":"< \u524d\u3078","image":"\u753b\u50cf","of":"\/","close":"\u9589\u3058\u308b","noiframes":"\u3053\u306e\u6a5f\u80fd\u3067\u306f iframe \u304c\u5fc5\u8981\u3067\u3059\u3002\u73fe\u5728 iframe \u3092\u7121\u52b9\u5316\u3057\u3066\u3044\u308b\u304b\u3001\u5bfe\u5fdc\u3057\u3066\u3044\u306a\u3044\u30d6\u30e9\u30a6\u30b6\u30fc\u3092\u4f7f\u3063\u3066\u3044\u308b\u3088\u3046\u3067\u3059\u3002","loadingAnimation":"http:\/\/nimbusnoize.com\/wp-includes\/js\/thickbox\/loadingAnimation.gif","closeImage":"http:\/\/nimbusnoize.com\/wp-includes\/js\/thickbox\/tb-close.png"};var plugininstallL10n = {"plugin_information":"\u30d7\u30e9\u30b0\u30a4\u30f3\u60c5\u5831:","ays":"\u672c\u5f53\u306b\u3053\u306e\u30d7\u30e9\u30b0\u30a4\u30f3\u3092\u30a4\u30f3\u30b9\u30c8\u30fc\u30eb\u3057\u3066\u3082\u3044\u3044\u3067\u3059\u304b ?"};/* ]]> */
</script>
<script type="text/javascript" src="http://nimbusnoize.com/wp-admin/load-scripts.php?c=1&amp;load=admin-bar,hoverIntent,common,jquery-color,wp-ajax-response,wp-lists,quicktags,jquery-query,admin-comments,jquery-ui-core,jquery-ui-widget,jquery-ui-mouse,jquery-ui-sortable,postbox,dashboard,thickbox,plugin-install,media-upload&amp;ver=4c39b6e71d488a2f460711d94887fe08"></script>
<div class="clear"></div>
</div>
<?php
}

?>
