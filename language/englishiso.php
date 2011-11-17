<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.2
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// Translation: Peter Bowey (http://www.pbcomp.com.au)
//----------------------------------------------------------------------
// file: english.php
//----------------------------------------------------------------------
//  Last update: 17/11/2011
//----------------------------------------------------------------------
$language= array();
//installation
$language['install']="Installation";
$language['welcome_install'] ="Welcome on CrawlTrack, installation is just three easy steps.";
$language['menu_install_1']="1) Enter database connection information.";
$language['menu_install_2']="2) Set up the websites.";
$language['menu_install_3']="3) Administrator account set-up.";
$language['go_install']="Install";
$language['step1_install'] ="Please enter the database connection information. Once the form is validated, the tables will be created.";
$language['step1_install_login_mysql']="User MySQL";
$language['step1_install_password_mysql']="Password MySQL";
$language['step1_install_host_mysql']="Host MySQL";
$language['step1_install_database_mysql']="Database MySQL";
$language['step1_install_ok'] ="Connection files OK.";
$language['step1_install_ok2'] ="Tables creation OK.";
$language['step1_install_no_ok'] ="Information is missing to create the tables and the files, please check the information and retry.";
$language['step1_install_no_ok2'] ="The files haven't been created, check if the folder is CHMOD 777.";
$language['step1_install_no_ok3'] ="A problem appeared during table creation, try again.";
$language['back_to_form'] ="Back to the form";
$language['retry'] ="Try again";
$language['step2_install_no_ok']="Connection to the database is not possible, please check the connection information.";
$language['step3_install_no_ok']="Database selection is failing, please, check the connection information.";
$language['step4_install']="Go";
//site creation
//modified in 1.5.0
$language['set_up_site']="Please enter the website name (to identify it in WebCrawler) and domain (ie, www.mydomain.com) below."; 
$language['site_name']="Website name:";
//modified in 2.0.0
$language['site_no_ok']="You have to enter a website name and domain.";
$language['site_ok']="The website has been add to the database.";
$language['new_site']="Add a new website";
//tag creation
$language['tag']="Tag to insert in your pages";
//modified in 2.3.0
$language['create_tag']="<p><b>How to use the CrawlTrack tag:</b><br><ul id=\"listtag\">
<li>The CrawlTrack tag is a php file, you have to insert it in a .php page.</li>
<li>The CrawlTrack tags have to be between &#60;?php and ?&#62 tags, if there is no such tags on your page, you have to add them before and after the CrawlTrack tag.</li>
<li>If your site is not using .php pages, see documentation on www.crawltrack.net.</li>
<li>For the best anti-hacking protection, have the CrawlTrack tag as the first thing in your page just after &#60;?php.</li>
<li>If you are using any sort of content management script (discussion board, blog, gallery, CMS etc.), have a look on www.crawltrack.net/doccms.php to find the best place to insert the tag.</li>
<li>The CrawlTrack tag will give absolutly no visible output on your pages (even in the source code).</li>
<li>If you would like to support CrawlTrack and see the logo with a link to www.crawltrack.net, you will find logo models below that you can put at any place on your pages.</li>
<li>For any other questions, see the documentation on www.crawltrack.net or use the support forum on the same site.</li></ul></p><br>" ;
$language['site_name2']="Choose a website";
//modified in 1.5.0
$language['local_tag']="Standard tag for a site hosted on the same server as Crawltrack.";
$language['non_local_tag']="Tag to be used if the site is hosted on a different server than Crawltrack (you also need to have the fsockopen and fputs functions activated).";
//login set_up
$language['admin_creation']="Administrator account set-up";
$language['admin_setup']="Please enter the administrator login and password below.";
$language['user_creation']="User account set-up";
$language['user_setup']="Please enter the user login and password below.";
$language['user_site_creation']="Single-website user account set-up";
$language['user_site_setup']="Please enter the single-website user login and password below.";
$language['admin_rights']="Administrator has access to all website stats and set-up";
$language['login']="Login";
$language['password']="Password";
$language['valid_password']="Enter the password again.";
$language['login_no_ok']="Information is missing or the passwords are different, please check the fields and try again.";
$language['login_ok']="Account is set-up.";
$language['login_no_ok2']="A problem appeared during account set-up, try again.";
$language['login_user']="Create a user account";
$language['login_user_what']="User has access to all website stats";
$language['login_user_site']="Create a single-website user account";
$language['login_user_site_what']="Single-website user account has access to a specific website's stats";
//modified in 1.5.0
$language['login_finish']="Installation is now finished. Don't forget to put the tag (available on tools page <img src=\"./images/wrench.png\" width=\"16\" height=\"16\" border=\"0\" >) on your site pages.";
//access
$language['restrited_access']="Restricted access.";
$language['enter_login']="Please, enter your login and password below.";
//display
$language['crawler_name']="Crawlers";
$language['nbr_visits']="Visits";
$language['nbr_pages']="Pages viewed";
$language['date_visits']="Last visit";
$language['display_period']="Period displayed: ";
$language['today']="Day";
$language['days']="Week";
//modified in 1.5.0
$language['month']="Month";
$language['one_year']="Year";
$language['no_visit']="There were no visits.";
$language['page']="Pages";
//modified in 1.5.0
$language['admin']="Tools";
$language['nbr_tot_visits']="Total visits";
$language['nbr_tot_pages']="Total pages viewed";
$language['nbr_tot_crawlers']="Number of crawlers";
$language['visit_per-crawler']="Visits detail";
$language['100_visit_per-crawler']="Visits detail (display limited to %d lines).";
$language['user_agent']="User agent";
$language['Origin']="User";
$language['help']="Help";
//search
$language['search']="Search";
$language['search2']="Search...";
$language['search_crawler']="...for a crawler";
$language['search_user_agent']="...for a user-agent";
$language['search_page']="...for a page";
$language['search_user']="...for a crawler user";
$language['go_search']="Search";
$language['result_crawler']="Here are the crawlers you are looking for.";
$language['result_ua']="Here are the user-agents you are looking for.";
$language['result_page']="Here are the pages you are looking for.";
$language['result_user']="Here are the crawler users you are looking for.";
$language['result_user_crawler']="Here are the crawlers of that user.";
$language['result_user_1']="User:&nbsp;";
$language['result_crawler_1']="Search keyword:&nbsp;";
$language['no_answer']="There is no answer.";
$language['to_many_answer']="There is more than 100 answers (display limited to 100 lines).";
//admin
$language['user_create']="Create a new user account.";
$language['user_site_create']="Create a new single website user account.";
$language['new_site']="Add a website.";
$language['see_tag']="Create tags to insert on your website.";
$language['new_crawler']="Add a new crawler";
$language['crawler_creation']="Please complete the following form with the new crawler information."; 
$language['crawler_name2']="Crawler name:";
$language['crawler_user_agent']="User agent:";
$language['crawler_user']="Crawler user:";
$language['crawler_url']="User url (like this: http://www.example.com)";
$language['crawler_url2']="User url:";
$language['crawler_ip']="IP:";
$language['crawler_no_ok']="Information is missing, please check the form content and retry.";
$language['exist']="That crawler is already in the database";
$language['exist_data']="Here is the information concerning it in the database:";
$language['crawler_no_ok2']="A problem appeared during crawler creation, try again.";
$language['crawler_ok']="The crawler has been added to the database.";
$language['user_suppress']="Delete a user or a user-website account.";
$language['user_list']="List of users and user-websites logins";
$language['suppress_user']="Delete that account";
$language['user_suppress_validation']="Are you sure that you want to delete that account?";
$language['yes']="Yes";
$language['no']="No";
$language['user_suppress_ok']="The account has been successfully deleted.";
$language['user_suppress_no_ok']="A problem occured during account deletion, try again.";
$language['site_suppress']="Delete a website.";
$language['site_list']="Websites list";
$language['suppress_site']="Delete that website";
$language['site_suppress_validation']="Are you sure that you want to delete that website?";
$language['site_suppress_ok']="The website has been successfully deleted.";
$language['site_suppress_no_ok']="A problem occured during website deletion, try again.";
$language['crawler_suppress']="Delete a crawler.";
$language['crawler_list']="Crawler list";
$language['suppress_crawler']="Delete that crawler";
$language['crawler_suppress_validation']="Are you sure that you want to delete that crawler?";
$language['crawler_suppress_ok']="The crawler has been successfully deleted.";
$language['crawler_suppress_no_ok']="A problem occured during crawler deletion, try again.";
$language['crawler_test_creation']="Create a test crawler.";
$language['crawler_test_suppress']="Delete the test crawler.";
$language['crawler_test_text']="Once the test crawler is created, visit your site with the same computer and the browser used to create the crawler."; 
$language['crawler_test_text2']="If everything is OK, your visit will be displayed in CrawlTrack as a Test-Crawltrack crawler visit. Don't forget to delete the test crawler when you are done.";
$language['crawler_test_no_exist']="The test crawler didn't exist in the database.";
$language['exist_site']="That site is already in the database";
$language['exist_login']="That login is already in the database";
//1.2.0
$language['update_title']="Crawlers list update.";
$language['update_crawler']="Update the crawlers list.";
$language['list_up_to_date']="There is no updated list available.";
$language['update_ok']="Update successfull.";
$language['crawler_add']="crawlers have been added to the database";
$language['no_access']="Online update is not available.<br><br>To update, click on the link below to download the last crawlers list, upload the crawlerlist.php file to your CrawlTrack include folder and restart the update procedure.";
$language['no_access2']="Link with www.CrawlTrack.net failed, please try again later.";
$language['download_update']="If you have already uploaded the new crawlers list to your site, click on the button below to update your database.";
$language['download']="Download the crawlers list.";
$language['your_list']="The list you are using is:";
$language['crawltrack_list']="The list available on www.Crawltrack.net is:";
$language['no_update']="Do not update the crawlers list.";
$language['no_crawler_list']="The file crawlerlist.php didn't exist in your include folder.";
//1.3.0
$language['use_user_agent']="Crawler detection is made by user agent or by IP. Please enter one of the two.";
$language['user_agent_or_ip']="User agent or IP";
$language['crawler_ip']="IP:";
$language['table_mod_ok']="Crawlt_crawler table updated OK.";
$language['files_mod_ok']="Configconnect.php and crawltrack.php files updated OK.";
$language['update_crawltrack_ok']="CrawlTrack update is finish, you are now using version:";
$language['table_mod_no_ok']="Crawlt_crawler table update failed.";
$language['files_mod_no_ok']="A problem appeared during configconnect.php and crawltrack.php update.";
$language['update_crawltrack_no_ok']="A problem appeared during CrawlTrack update.";
$language['no_logo']="No logo.";
//modified in 1.5.0
$language['data_suppress_ok']="The information has been successfully archived.";
$language['data_suppress_no_ok']="A problem appeared during archiving, try again.";
$language['data_suppress_validation']="Are you sure you want to archive all &nbsp;";
$language['data_suppress']="Archive the oldest information on the visits table.";
$language['data_suppress2']="Archive all...";
$language['one_year_data']="information more than one year old.";
$language['six_months_data']="information more than six months old.";
$language['one_month_data']="information more than one month old.";
$language['oldest_data']="The oldest data dates from the &nbsp;";
$language['no_data']="There is no data in the visits table.";
//1.4.0
$language['time_set_up']="Time shift";
$language['server_time']="Server date and hour =";
$language['local_time']="Local date and hour=";
$language['time_difference']="Difference in hours between the server time and the local time=";
$language['time_server']="You are using the server time, would you like to use the local time to display the information?";
$language['time_local']="You are using the local time, would you like to use the server time to display the information?";
$language['decal_ok']="CrawlTrack is now using the local time; you can go back to server time at any time";
$language['nodecal_ok']="CrawlTrack is now using the server time; you can go back to local time at any time";
$language['need_javascript']="You have to activate javascript to use this function.";
//1.5.0 
$language['origin']="Source";
$language['crawler_ip_used']="IP used";
$language['crawler_country']="Country of origin";
$language['other']="Others";
$language['pc-page-view']="Percent of the site visited";
$language['pc-page-noview']="Percent of the site not visited";
$language['print']="Print";
$language['ip_suppress_ok']="The visits have been successfully deleted.";
$language['ip_suppress_no_ok']="A problem appeared during visits deletion, try again.";
$language['no_ip']="There is no IP record for that period.";
$language['ip_suppress_validation']="That IP has been used by different crawlers, so there is a doubt concerning the origin of these visits. Would you like to delete visits by that IP from the visits table?";
$language['ip_suppress_validation2']="Are you sure that you want to delete the visits coming from that IP?";
$language['ip_suppress_validation3']="If you want to forbid access to your site from that IP, add the following line to your .htaccess file at your site root:";
$language['ip_suppress']="Delete an IP";
$language['diff-day-before']="compare to the day before";
$language['daily-stats']="Daily statistics";
$language['top-crawler']="Most active crawler:";
$language['stat-access']="See details statistics";
$language['stat-crawltrack']="This information has been collected using:";
$language['nbr-pages-top-crawler']="he visits";
$language['of-site']="of the site";
$language['mail']="Receive a daily summary by Email.";
$language['set_up_mail']="If you want to received a daily summary of your statistics by Email, enter your Email address.";
$language['email-address']="Email address:";
$language['address_no_ok']="The address you entered is not correct.";
$language['set_up_mail2']="The daily summary mail is actually activated. Would you like to deactivate it ?";
$language['update']="The modification is done.";
$language['search_ip']="Track an IP address";
$language['ip']="IP address";
$language['maxmind']="The tracking has been done using GeoLite database, created by Maxmind, available at the following address:";
$language['ip_no_ok']="The IP address you enter is not correct.";
$language['public']="Allow public access to the statistics.";
$language['public-set-up2']="The access to your statistics is currently public, would you like to protect it by a password?";
$language['public-set-up']="The access to your statistics is protected by a password, would you like to make it public?";
$language['public2']="Only the tools page will remain protected";
$language['admin_protected']="The access to the tools page is protected.";
$language['no_data_to_suppress']="There is no data to archive in the requested period.";
$language['data_suppress3']="Information archiving will reduce the size of the database, but the corresponding data will not be
available for the statistics display. Only a summary table will be available (see Crawlers/Archives).
 It is best to archive information only if you really need to reduce the size of the database; the details will be permenantly lost.";
$language['archive']="Archives";
$language['month2']="Month";
$language['top_visits']="Top 3 in number of visits";
$language['top_pages']="Top 3 in number of pages viewed";
$language['no-archive']="There is no archived data.";
$language['use-archive']="Since part of the data has been archived, these values are not complete.";
$language['url_update']="Update the site data";
$language['set_up_url']="Complete the following table with the site's domain like: www.example.com (without http:// at the beginning and / at the end)."; 
$language['site_url']="Site domain:";
//1.6.0
$language['page_cache']="Page cached at: ";
//1.7.0
$language['step1_install_no_ok4']="A problem appeared during IP table filling, this could happen on some hosts as this table is more than 78,000 rows. You can either try again or continue without that table. If you continue, you will not display the crawler's country of origin. See the 'Troubleshooting' page in the documention at www.crawltrack.net to fill manually that table.";
$language['show_all']="Show all lines";
$language['from']="from";
$language['to']="to";
$language['firstweekday-title']="Choice of the first day of the week";
$language['firstweekday-set-up2']="The first day of the week is set for Monday, would you like to change to Sunday?";
$language['firstweekday-set-up']="The first day of the week is set for Sunday, would you like to change to Monday?";
$language['01']="January";
$language['02']="February";
$language['03']="March";
$language['04']="April";
$language['05']="May";
$language['06']="June";
$language['07']="July";
$language['08']="August";
$language['09']="September";
$language['10']="October";
$language['11']="November";
$language['12']="December";
$language['day0']="Monday";
$language['day1']="Tuesday";
$language['day2']="Wednesday";
$language['day3']="Thursday";
$language['day4']="Friday";
$language['day5']="Saturday";
$language['day6']="Sunday";
//2.0.0
$language['ask']="Ask";
$language['google']="Google";
$language['msn']="Bing";  //change for 3.1.1
$language['yahoo']="Yahoo";
$language['delicious']="Del.icio.us";
$language['index']="Indexation";
$language['keyword']="Keywords";
$language['entry-page']="Entry page";
$language['searchengine']="Search engines";
$language['social-bookmark']="Social bookmarks";
$language['tag']="Tags";
$language['nbr_tot_bookmark']="Bookmarks";
$language['nbr_tot_link']="Backlinks";
$language['nbr_tot_pages_index']="Indexed pages";
$language['nbr_visits_crawler']="Number of crawler visits";
$language['nbr_tot_visit_seo']="Visitors sent to the site";
$language['100_lines']="Display limited to %d lines.";
$language['8days']="Last 8 days";
$language['close']="Close";
$language['date']="Date";
$language['modif_site']="Modify the name or domain of a site.";
$language['site_url2']="Domain";
$language['modif_site2']="Modify this site's information.";
$language['no-info-day-before']="No information for the previous day";
$language['data_human_suppress_ok']="The information has been successfully deleted.";
$language['data_human_suppress_no_ok']="A problem appeared during information deletion, try again.";
$language['data_human_suppress_validation']="Are you sure you want to delete all &nbsp;";
$language['data_human_suppress']="Delete the oldest information in the human visits table (keywords and entry pages).";
$language['data_human_suppress2']="Delete...";
$language['one_year_human_data']="information more than one year old";
$language['six_months_human_data']="information more than six months old";
$language['one_month_human_data']="information more than one month old";
$language['data_human_suppress3']="Deleting information will reduce the size of the database, but the information will not be
available for the statistics display. It is best to delete the information only if you really need to reduce the size of the database; information is permenantly lost.";
$language['no_data_human_to_suppress']="There is no data in the human visits table.";
$language['choose_language']="Choose your language.";
//2.1.0
$language['since_beginning']="Everything";
//2.2.0
$language['admin_database']="See the database size";
$language['table_name']="Table name";
$language['nbr_of_data']="Number of records";
$language['table_size']="Table size";
$language['database_size']="Database size";
$language['total']="Total:";
$language['mailsubject']="CrawlTrack daily summary";
$language['yesterday']="Yesterday";
$language['beginmonth']="Since the beginning of the month";
$language['evolution']="Change compare to";
$language['lastthreemonths']="3 last monthes";
$language['set_up_mail3']="You are currently using the following address:";
$language['set_up_mail4']="Add an address";
$language['set_up_mail5']="Enter the new Email address.";
$language['set_up_mail6']="Delete one or more Email address";
$language['set_up_mail7']="Delete the selected address";
$language['chmod_no_ok']="The crawltrack.php file update has failed, CHMOD 777 your CrawlTrack folder and restart the update. FOr security reasons, don't forget to go back to CHMOD 711 at the end of the update.";
$language['display_parameters']="Display parameters";
$language['ordertype']="Order:";
$language['orderbydate']="by date";
$language['orderbypagesview']="by number of pages viewed";
$language['orderbyvisites']="by number of visits";
$language['orderbyname']="in alphabetic order";
$language['numberrowdisplay']="Number of rows displayed:";
//2.2.1
$language['french']="French";
$language['english']="English";
$language['german']="German";
$language['spanish']="Spanish";
$language['turkish']="Turkish";
$language['dutch']="Dutch";
//2.3.0
$language['hacking']="Attacks";
$language['hacking2']="Hacking attempts";
$language['hacking3']="Code injection";
$language['hacking4']="SQL injection";
$language['no_hacking']="CrawlTrack detected no attempts";
$language['attack_detail']="Attacks details";
$language['attack']="Parameters used for code injection attempts";
$language['attack_sql']="Parameters used for sql injection attempts";
$language['bad_site']="File/script the hacker attempted to inject";
$language['bad_sql']="SQL query the hacker attempted to inject";
$language['bad_url']="Url requested";
$language['hacker']="Attackers";
$language['date_hacking']="Time";
$language['unknown']="Unknown";
$language['danger']="You could be at risk if you run one of these scripts";
$language['attack_number_display']="Attacks details (display limited to %d attackers).";
$language['update_attack']="Update the attacks list.";
$language['no_update_attack']="Do not update the attacks list.";
$language['update_title_attack']="Attacks list update.";
$language['attack_type']="Type of attack";
$language['parameter']="Parameter";
$language['script']="Script";
$language['attack_add']="attacks have been added to the database";
$language['no_access_attack']="Online update is not available.<br><br>To update, click on the link below to download the last attacks list, upload the attacklist.php file in your CrawlTrack include folder and restart the update procedure.";
$language['download_update_attack']="If you have already uploaded the new attacks list to your site, click on the button below to update your database.";
$language['download_attack']="Download the attacks list.";
$language['no_attack_list']="The file attacklist.php didn't exist in your include folder.";
$language['change_password']="Change your password";
$language['old_password']="Current password";
$language['new_password']="New password";
$language['valid_new_password']="Enter the new password again.";
$language['goodsite_update']="Update trust sites list";
$language['goodsite_list']="Trusted sites";
$language['goodsite_list2']="A link to these sites included in a URL will not count as an attack";
$language['goodsite_list3']="Actual list of trusted sites";
$language['suppress_goodsite']="Delete that site from the list.";
$language['goodsite_suppress_validation']="Are you sure that you want to delete that site?";
$language['good_site']="Trusted site";
$language['goodsite_suppress_ok']="The site has been successfully deleted.";
$language['goodsite_suppress_no_ok']="A problem appeared during the site deletion, try again.";
$language['list_empty']="There are no trusted sites yet";
$language['add_goodsite']="Add a new trusted site to the list";
$language['goodsite_no_ok']="You have to enter a website url.";
$language['attack-blocked']="All these attacks have been blocked by CrawlTrack as requested";
$language['attack-no-blocked']="Be careful your CrawlTrack is not set-up to block attacks (see tools page)";
$language['attack_parameters']="Hacking protection parameters";
$language['attack_action']="Action when an attack is detected";
$language['attack_block']="Record it and block it";
$language['attack_no_block']="Just record it";
$language['attack_block_alert']="Before blocking attacks (important for your site's safety), have a look on the documentation (on www.crawltrack.net) to 
make sure that blocking attacks will not cause a problem with your normal visitors.";
$language['crawltrack-backlink']="CrawlTrack is free, if you like it and want to share it, why don't put a backlink on your page?<br><br>If you chose
the 'nologo' option for the tracking tag, you can use these alternate graphics (one version for a php page and other for html) to place anywhere on your site.";
$language['session_id_parameters']="Session id treatment";
$language['remove_session_id']="Remove session id from page urls";
$language['session_id_alert']="Removing the session id from page urls will avoid having multiple entries on the pages table if you use scripts with a session id in the url.";
$language['session_id_used']="Session ids used";
//3.0.0
$language['webmaster_dashboard']="Webmaster dashboard";
$language['summary']="All sites summary";
$language['charge']="Server load";
$language['unidentified']="Unidentified";
$language['display_period2']="Period choice";
$language['visitors']="Visitors";
$language['unique_visitors']="Uniques visitors";
$language['visits']="Visits";
$language['nbr_tot_visits2']="Total visits";
$language['nbr_tot_visits3']="Total";
$language['referer']="Referer";
$language['website']="Web site & other search engines";
$language['website2']="sites";
$language['website3']="Web sites";
$language['country']="country";
$language['direct']="Direct arrival";
$language['average_pages']="Pages view per visit";
$language['stats_visitors']="Visitors statistiques";
$language['count_in_stats']="Don't count your own visits in the followings sites:";
$language['stats_visitors_other_domain']="If one of the sites is host on an other server; you have to copy the file crawltsetcookie.php (you will find it in the Crawltrack php folder) at the root of the site before to click on sur OK in order to have your choice taken in account.";
$language['main_crawlers']="Main crawlers";
$language['magnifier']="Do a search in the CrawlTrack database";
$language['refresh']="Empty cache and recalculate datas";
$language['wrench']="Go to your Crawltrack administration page";
$language['printer']="Print the actual page";
$language['information']="Documentation on www.crawltrack.net";
$language['help']="About CrawlTrack";
$language['cross']="Logout";
$language['home']="Go back to home page";
$language['badreferer']="Are you sure you want to put this area in the list of referer spammers? Once it is added to this list, the visits in this area will no longer be taken into account by CrawlTrack.";
$language['spamreferer']="Set this field in the list of spammers";
$language['badreferer_update']="Update the list of sites for referer spammers";
$language['add_badreferer']="Add a site referer spammer to the list";
$language['listbadreferer_empty']="There is no site of referer spamming";
$language['badreferer_list']="Sites of referer spammers";
$language['badreferer_list2']="Visits from these sites will not be taken into account by CrawlTrack.";
$language['badreferer_list3']="Current list of sites for referer spammers";
$language['badreferer_site']="Website of referer spammers";

$language['goodreferer']="Are you sure you want to put this field in the list of sites which have a link to your site? Once it is added to this list, the visits in this area will be taken into account by CrawlTrack without further control.";
$language['goodreferer2']="Set this field in the list of sites which have a link to your site";

$language['goodreferer_update']="Update the list of sites which have a link to your site";
$language['add_goodreferer']="Add a website link to your site from the list";
$language['listgoodreferer_empty']="There is no site that has a link to your site";
$language['goodreferer_list']="Sites that have a link to your site";
$language['goodreferer_list2']="Visits from these sites will be taken into account by CrawlTrack without further control.";
$language['goodreferer_list3']="Current list of sites which have a link to your site";
$language['goodreferer_site']="Site with a link to your site";

$language['download']="Downloads";
$language['file']="File";
$language['download_period']="Over the period";
$language['download_link']="Count downloads";
$language['download_link2']="<b>For your downloads are counted by CrawlTrack:</b><br><br>
-The downloadable files must be hosted on one of the sites followed by CrawlTrack.<br>
-the download link (for a file whose location is http://www.example.com/folder/file.zip) must be of the form :";
$language['download_link3']="http://www.example.com/folder/file.zip";
$language['download_link4']="That's it, no further manipulation is necessary.";
$language['error']="Errors 404";
$language['number']="Number";
$language['outer-referer']="External Links";
$language['inner-referer']="Internal Links";
$language['error-attack']="Including hacking attempts";
$language['total_hacking']="Number of attacks";
$language['error_hacking']="Attacks that have given a 404";
$language['error_page']="Requested url";
$language['crawler_error']="Details of 404 errors from robots";
$language['direct_error']="Details of 404 errors due to a direct arrival";
$language['extern_error']="Details of 404 errors due to an external link to the site";
$language['intern_error']="Details of 404 errors due to an internal link to the site";
$language['error_referer']="Original url";
$language['404_no_in_graph']="These attacks are not taken into account for the number of Ip, the graph and table detail.";
$language['404_no_in_graph2']="The attacks resulted in a 404 do not appear on the graph.";
$language['exalead']="Exalead";
$language['connect']="You are identified";
$language['connect_you']="Login";
$language['notcheck']="Unaudited link, click on 'Check links' to start the verification.";
$language['checklink']="Check links";
$language['linkok']="Link validation";
$language['first_date_visits']="First visit";
$language['next_visits']="Next visit";
$language['data_suppress']="Reduce the size of the database."; //modified in 3.0.0
$language['data_suppress2']="Remove ";
$language['other_bot']="all visits except the following robots; Ask Jeeves / Teoma, Exabot, Googlebot, MSN Bot and Slurp Inktomi (Yahoo)";
$language['one_year_data']="all the old robot visits over a year";
$language['six_months_data']="all the old robot visits over six months";
$language['five_months_data']="all the old robot visits over five months";
$language['four_months_data']="all the old robot visits over four months";
$language['three_months_data']="all the old robot visits over three months";
$language['two_months_data']="all visits of robot-old more than two months";
$language['one_month_data']="all visits of robot-old more than one month";
$language['one_year_data_human']="all the old visitors from more than one year";
$language['six_months_data_human']="all the old visitors over six months";
$language['five_months_data_human']="all the old visitors over five months";
$language['four_months_data_human']="all the old visitors over four months";
$language['three_months_data_human']="all the old visitors over three months";
$language['two_months_data_human']="all the old visitors over two months";
$language['one_month_data_human']="all the old visitors over one month";
$language['attack_data']="all data concerning the attempts of piracy";
$language['oldest_data']="The oldest of &nbsp;";
$language['no_data']="There is no data in the table of hits.";
$language['no_data_to_suppress']="There is no data to be deleted for the requested period.";
$language['data_suppress3']="Warning! Deletion of data reduces the size of the database, but in part against the data is irretrievably lost.";
$language['data_suppress_ok']="The data was deleted successfully.";
$language['data_suppress_no_ok']="A problem occurred when deleting data, try the procedure again.";
$language['data_suppress_validation']="Are you sure you want to delete all &nbsp;";
$language['deltatime']="Frequency of visit";
$language['nbr_tot_visit_seo']="Origin visits";
$language['url_parameters']="Parameters in the url";
$language['remove_parameter']="Remove url parameters";
$language['remove_parameter_alert']="Remove url parameters, will prevent page table growing excessively, for against any type of url: www.example.com/index.php?article=225 will be recorded in the form www.example.com / index.php giving less detail on the pages visited.";
$language['bookmark']="Use this address for this page to your Favorites";
$language['evolution']="Trend number of unique visitors";
$language['perday']="per day";
$language['shortterm']="Last 7 days:";
$language['longterm']="30 days:";
$language['bounce_rate']="Bounce Rate";
$language['visit_summary']="Cumulative visits on all sites";
$language['data']="Data";
$language['index']="Index";
$language['sponsorship']="They support CrawlTrack:";
//3.1.0
$language['browser']="Browsers";
$language['visitor-browser']="Browsers used by visitors";
$language['hits-per-hour']="Hits per hour";
$language['russian']="Russian";
//3.1.2
$language['besponsor']="Use CrawlTrack to present your products and services to thousands of webmasters.";
$language['ad-on-crawltrack']="<a href=\"http://translate.google.fr/translate?u=http%3A%2F%2Fwww.ad42.com%2Fzone.aspx%3Fidz%3D6690%26ida%3D-1&sl=fr&tl=en&hl=fr&ie=UTF-8\" target=\"_blank\">Why not use CrawlTrack to present your products and services to thousands of webmasters?</a>";
//3.2.0
$language['baidu']="Baidu";
$language['googleposition']="Position<br>in Google";
$language['position']="Actual position";
$language['positiononemonth']="Position one month ago";
$language['positiontwomonth']="Position two months ago";
$language['positionthreemonth']="Position three months ago";
$language['googledetail']="Details of position in Google and hits generated";
//3.2.3
$language['bulgarian']="Bulgarian";
//3.2.8
$language['italian']="Italian";
$language['two_year_data']="all the old robot visits over two years";
$language['two_year_data_human']="all the old visitors from more than two years";
//3.3.1
$language['googleimage']="Google-Images";
//3.3.2
$language['yandex']="Yandex";
$language['aol']="Aol";
$language['no_visitors_stats']="Count only crawlers";
$language['no_visitors_stats2']="Don't count any visit";
$language['no_cookie']="You have to set up your browser to accept cookies";
//country code
$country = array(

    "ad" => "Andorra",
    "ae" => "United Arab Emirates",
    "af" => "Afghanistan",
    "ag" => "Antigua and Barbuda",
    "ai" => "Anguilla",
    "al" => "Albania",
    "am" => "Armenia",
    "an" => "Netherlands Antilles",
    "ao" => "Angola",
    "ap" => "Asia/Pacific Region",    
    "aq" => "Antarctica",
    "ar" => "Argentina",
    "as" => "American Samoa",
    "at" => "Austria",
    "au" => "Australia",
    "aw" => "Aruba",
    "az" => "Azerbaijan",
    "ba" => "Bosnia and Herzegovina",
    "bb" => "Barbados",
    "bd" => "Bangladesh",
    "be" => "Belgium",
    "bf" => "Burkina Faso",
    "bg" => "Bulgaria",
    "bh" => "Bahrain",
    "bi" => "Burundi",
    "bj" => "Benin",
    "bm" => "Bermuda",
    "bn" => "Bruneo",
    "bo" => "Bolivia",
    "br" => "Brazil",
    "bs" => "Bahamas",
    "bt" => "Bhutan",
    "bw" => "Botswana",
    "by" => "Belarus",
    "bz" => "Belize",
    "ca" => "Canada",
    "cd" => "The Democratic Republic of the Congo",
    "cf" => "Central African Republic",
    "cg" => "Congo",
    "ch" => "Switzerland",
    "ci" => "Cote D'Ivoire",
    "ck" => "Cook Islands",
    "cl" => "Chile",
    "cm" => "Cameroon",
    "cn" => "China",
    "co" => "Colombia",
    "cr" => "Costa Rica",
    "cs" => "Serbia and Montenegro",
    "cu" => "Cuba",
    "cv" => "Cape Verde",
    "cx" => "Christmas Island",
    "cy" => "Cyprus",
    "cz" => "Czech Republic",
    "de" => "Germany",
    "dj" => "Djibouti",
    "dk" => "Denmark",
    "dm" => "Dominica",
    "do" => "Dominican Republic",
    "dz" => "Algeria",
    "ec" => "Ecuador",
    "ee" => "Estonia",
    "eg" => "Egypt",
    "er" => "Eritrea",
    "es" => "Spain",
    "et" => "Ethiopia",
    "fi" => "Finland",
    "fj" => "Fiji",
    "fk" => "Falkland Islands (Malvinas)",
    "fm" => "Federated States of Micronesia ",
    "fo" => "Faroe Islands",
    "fr" => "France",
    "ga" => "Gabon",
    "gb" => "Great Britain",
    "gd" => "Grenada",
    "ge" => "Georgia",
    "gf" => "French Guyana",
    "gg" => "Guernesey",
    "gh" => "Ghana",
    "gi" => "Gibraltar",
    "gl" => "Greenland",
    "gm" => "Gambia",
    "gn" => "Guinea",
    "gp" => "Guadeloupe",
    "gq" => "Equatorial Guinea",
    "gr" => "Greece",
    "gs" => "South Georgia and the South Sandwich Islands",
    "gt" => "Guatemala",
    "gu" => "Guam",
    "gw" => "Guinea-Bissau",
    "gy" => "Guyana",
    "hk" => "Hong Kong",
    "hn" => "Honduras",
    "hr" => "Croatia",
    "ht" => "Haiti",
    "hu" => "Hungary",
    "id" => "Indonesia",
    "ie" => "Ireland",
    "il" => "Israel",
    "im" => "Isle of Man",
    "in" => "India",
    "io" => "British Indian Ocean Territory",
    "iq" => "Iraq",
    "ir" => "Iran",
    "is" => "Iceland",
    "it" => "Italy",
    "je" => "Jersey",
    "jm" => "Jamaica",
    "jo" => "Jordan",
    "jp" => "Japan",
    "ke" => "Kenya",
    "kg" => "Kyrgyzstan",
    "kh" => "Cambodia",
    "ki" => "Kiribati",
    "km" => "Comoros",
    "kn" => "Saint Kitts and Nevis",
    "kr" => "Republic of Korea",
    "kw" => "Kuwait",
    "ky" => "Cayman Islands",
    "kz" => "Kazakhstan",
    "la" => "Laos",
    "lb" => "Lebanon",
    "lc" => "Saint Lucia",
    "li" => "Liechtenstein",
    "lk" => "Sri Lanka",
    "lr" => "Liberia",
    "ls" => "Lesotho",
    "lt" => "Lithuania",
    "lu" => "Luxembourg",
    "lv" => "Latvia",
    "ly" => "Libya",
    "ma" => "Morocco",
    "mc" => "Monaco",
    "md" => "Moldova",
    "me" => "Montenegro",
    "mg" => "Madagascar",
    "mh" => "Marshall Islands",
    "mk" => "Macedonia",
    "ml" => "Mali",
    "mm" => "Myanmar",
    "mn" => "Mongolia",
    "mo" => "Macau",
    "mp" => "Northern Mariana Islands",
    "mq" => "Martinique",
    "mr" => "Mauritania",
    "ms" => "Montserrat",
    "mt" => "Malta",
    "mu" => "Mauritius",
    "mv" => "Maldives",
    "mw" => "Malawi",
    "mx" => "Mexico",
    "my" => "Malaysia",
    "mz" => "Mozambique",
    "na" => "Namibia",
    "nc" => "New Caledonia",
    "ne" => "Niger",
    "nf" => "Norfolk Island",
    "ng" => "Nigeria",
    "ni" => "Nicaragua",
    "nl" => "Netherlands",
    "no" => "Norway",
    "np" => "Nepal",
    "nr" => "Nauru",
    "nu" => "Niue",
    "nz" => "New Zealand",
    "om" => "Oman",
    "pa" => "Panama",
    "pe" => "Peru",
    "pf" => "French Polynesia",
    "pg" => "Papua New Guinea",
    "ph" => "Philippines",
    "pk" => "Pakistan",
    "pl" => "Poland",
    "pm" => "Saint Pierre et Miquelon",
    "pr" => "Puerto Rico",
    "ps" => "Palestinian territory",
    "pt" => "Portugal",
    "pw" => "Palau",
    "py" => "Paraguay",
    "qa" => "Qatar",
    "re" => "Reunion Island",
    "ro" => "Romania",
    "ru" => "Russian Federation",
    "rs" => "Russia",
    "rw" => "Rwanda",
    "sa" => "Saudi Arabia",
    "sb" => "Solomon Islands",
    "sc" => "Seychelles",
    "sd" => "Sudan",
    "se" => "Sweden",
    "sg" => "Singapore",
    "sh" => "Saint Helena",
    "si" => "Slovenia",
    "sj" => "Svalbard",
    "sk" => "Slovakia",
    "sl" => "Sierra Leone",
    "sm" => "San Marino",
    "sn" => "Senegal",
    "so" => "Somalia",
    "sr" => "Suriname",
    "st" => "Sao Tome and Principe",
    "sv" => "El Salvador",
    "sy" => "Syrian Arab Republic",
    "sz" => "Switzerland",
    "tc" => "Turks and Caicos Islands",
    "td" => "Chad",
    "tf" => "French Southern Territories",
    "tg" => "Togo",
    "th" => "Thailand",
    "tj" => "Tajikistan",
    "tk" => "Tokelau",
    "tl" => "Timor Leste",
    "tm" => "Turkmenistan",
    "tn" => "Tunisia",
    "to" => "Tonga",
    "tr" => "Turkey",
    "tt" => "Trinidad and Tobago",
    "tv" => "Tuvalu",
    "tw" => "Taiwan",
    "tz" => "Tanzania",
    "ua" => "Ukraine",
    "ug" => "Uganda",
    "us" => "United States",
    "uy" => "Uruguay",
    "uz" => "Uzbekistan",
    "va" => "Vatican City",
    "vc" => "Saint Vincent and the Grenadines",
    "ve" => "Venezuela",
    "vg" => "Virgin Islands, British",
    "vi" => "Virgin Islands, U.S.",
    "vn" => "Vietnam",
    "vu" => "Vanuatu",
"wf" => "Wallis et Futuna",
    "ws" => "Samoa",
    "ye" => "Yemen",
    "yt" => "Mayotte",
    "za" => "South Africa",
    "zm" => "Zambia",
    "zw" => "Zimbabwe",
    "xx" => "Unknown",
    "a2" => "Unknown",
    "eu" => "European Union",    
);

?>
