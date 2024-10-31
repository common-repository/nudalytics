=== Plugin Name ===
Contributors: AJI Dreamtaste
Donate link: http://nimbusnoize.com/works
Tags: analytics, analyze, spam, filting, filter, admin, administrator
Stable tag: 2.0.1
License: GNU General Public License
License URI: http://www.gnu.org/copyleft/lesser.html

Analyzer for WordPress, can filter IP address, host name, and User Agent widely. Also can check movement in your admin page.

== Description ==
This plugin can record visitor's information and make those values as statistics.
When record, you can change how to deal those through edit dat files in "Nudalytics/filter" or "Nudalytics/identiry" directory.
Also can display movement in your admin page for multi-user WP site and security.

CAUTION!!

Please backup your .dat file.

Make a empty row in "/filter/ip.dat". If not, almost IP address is the target to be filtered.

About detail of the plugin, please see Other Notes.

== Installation ==
1. Upload decompressed "Nudalytics.zip" to "wp-content/plugins/" directory.
2. Then upload, "menu -> plugins" in WP Admin page, activate Nudalytics.

== Menu and check ==
1. Summary
	make recent result of statistics as graphs in realtime processing, and display that per day or each it in a week.

2. Realtime
	make a list from visitor's information and visited URL at real. In referrer field, it divide below each.
	BLUE: visit from search engine (display search query)
	GREEN: visitor's movement.
	RED: maybe referrer spam.

3. Referrer
	make a list of referrel users.

4. Search query
	make a list of organic users(refering searchquery.dat).

5. Crawlers
	make a list of crawlers(refering crawler_*.dat).

6. Movement in Admin
	It displays movements in your admin page.
	however, generally displaying IP address is fixed, you can check your WP site is cracked.

7. Search logs
	Using your query and specified columns, make a list from logs.

8. Settings
	You can change settings "How terms display in summary graphs", "Number of Logs in Realtime", and "Retention Periods of Data".

== How to use ==
You can operate graph in Summary and items in Setting.

- Summary -
You can choose following indicators,
	1. (none)
		don't use indicator（except main indicator of BLUE)
		
	2. Unique user
		(except crawler) value of unique users
		
	3. Page view
		(except crawler) value of page views
		
	4. Processing time
		(except crawler) processing time your site spend
		
	5. Crawler visit
		value of crawler's visit
		
	6. All unique IP
		all unique IP includes crawler
		
	7. Page view (+Crawler visit)
		all page views includes crawler
		
If you make some indicators directly in graph, you need to set "(none)" in the sub indicator.
Setting indicator both main and sub indicator, you can see new value which calculated *100 as [percentage].

- Settings -
Those means as text of each items.

	[Management of logs]
	1. How terms display in summary graphs [day]
		You choose how long in graph, stcastics is displayed in. (from now to ~ days before)
		
	2. Number of Logs in Realtime [line]
		You choose how many logs display in Realtime item. If you set the value is 1000, it spends about 1 sec to display.
		
	3. Retention Periods of Data [day]
		You choose how long those data is holded. If they are over to your set value, they are deleted automatically.
		
	
	[Submenu for displaying]
	You can change whether each menu are displayed except "Summary" and "Settings"


== About Determination ==
Discrimination of datas is dealed through dat files in "Nudalytics/filter" and "Nudalytics/identify" directory.
In files of "Nudalytics/filter" directory, they exists to refuse targeted records.
Conversely, In files of "Nudalytics/identify" directory, they are to give datas to identify targeted records.
They formats "StatPress" writing for that user.
I Explain how to write dat files below.

Nudalytics/filter
	ip.dat:
		specify single IP address
		[ex]192.168.0.1
		if specify multi IP address, you must insert "-" between that
		[ex]192.168.0.1-192.168.255.255

	host.dat
		write all or a part of host name
		[ex]hogehoge.hogehoge
		
	useragent.dat
		write all or a part of User Agent
		[ex]Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)
				//User Agent of IE6 cannot be recorded
		    MSIE
		    	//In case of this、you refuse records all of Trident rendering engine
	referer.dat
		write all or a part of URL as referrer

Nudalytics/identify --- use "|" in order to present datas to identify
	browser.dat:
		browser name|all or a part of User Agent

	crawler_ip.dat:
		crawler name|IP address
		about how to write IP address, it sames as ip.dat

	crawler_ua.dat:
		crawler name|all or a part of User Agent

	os.dat:
		OS name|all or a part of User Agent

	searchquery.dat:
		Search engine name|Search engine's URL|string of Search query ([ex]if "q=hogehoge", you must set "q")

== Changelog ==
= 2.0.2 =
* Fixed redundant code.
* Added the function that's to convert specific characters during recoding.

= 2.0.1 =
* Fixed it doesn't work to change columns.
* Added a optimization function. This works at this plugin's table in MySQL.

= 2.0.0 =
* Changed columns to use in MySQL, and ways to process.
* Newly add the function of recording names of logging in WordPress.
* Add "Search logs" menu. It can display logs through your queries.

= 1.3.4 =
* Displayed UA.
* Added initial values in all properties in this plugin.
* Editted the design a little.

= 1.3.3 =
* Changed HTML table layout.
* Fixed SQL query which are unmodified.

= 1.3.2 =
* Fixed the code of making this SQL table.

= 1.3.1 =
* Simplificated that source codes just a little.
* Separated "Inflow" as "Referrer" and "SearchQuery" pages.
* Fixed a timing of deleting records. And if not settings, it's to hold those for 30 days.
* Fixed pages that displays no logs in case of not settings. And if not settings, it's to display 50 logs.

= 1.3.0 =
* At "Movement in Admin", arranged displaying items. You may be able to confirm users' action easily.

= 1.2.1 =
* Fixed a problem that IP address to filter doesn't be processed correctly.

= 1.2.0 =
* Added "crawler_host.dat" to identify the crawler by hostname.

= 1.1.1 =
* Displayed host name in IP address to each menu.

= 1.1.0 =
* Added "Inflow" to menu.
* Fixed decision of page transition in your domain in "Realtime" menu.

= 1.0.0 =
* Changed the timing of starting to analyzing.
* Changed duration and bounce percentage to measurement of processing time

= 0.2.1 =
* Added and fixed indicators displaying in graph.

= 0.2.0 =
* Changed graph's design.
* Identified using another color when the referrer's URL includes your domain.

= 0.1.2 =
* Fixed overflowing outbreaks when IP addresses are decided.

= 0.1.1 =
* Changed the timing of starting to analyzing.
* Fixed culculated value in "Summary" menu.

= 0.1.0 =
* Publiced as prototype.
