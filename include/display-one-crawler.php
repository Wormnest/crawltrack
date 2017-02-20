<?php
//----------------------------------------------------------------------
//  CrawlTrack
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Updating: Jacob Boerema
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// This script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: display-one-crawler.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$listpage = array();
$nbvisits = array();
$lastdate1 = array();
$address = array();
$info = array();
$agent = array();
$ip = array();
$uagent = array();
$table = array();
$crawlencode = urlencode($settings->crawler);

if ($settings->period >= 1000) {
	$cachename = "permanent-" . $settings->navig . "-" . $settings->siteid . "-" . $crawlencode."-" . $settings->displayorder."-" . $settings->displayrows . "-".$settings->language . "-".$settings->displayall . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($settings->period >= 100 && $settings->period < 200) //previous month
{
	$cachename = "permanent-month" . $settings->navig . "-" . $settings->siteid . "-" . $crawlencode."-" . $settings->displayorder."-" . $settings->displayrows . "-".$settings->language . "-".$settings->displayall . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($settings->period >= 200 && $settings->period < 300) //previous year
{
	$cachename = "permanent-year" . $settings->navig . "-" . $settings->siteid . "-" . $crawlencode."-" . $settings->displayorder."-" . $settings->displayrows . "-".$settings->language . "-".$settings->displayall . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->displayorder.$settings->displayrows . $crawlencode . $settings->displayall . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language;
}

//start the caching
cache($cachename);

//case change of name of MSN Bot
if( urldecode($settings->crawler)=='MSN Bot - Bingbot' || urldecode($settings->crawler)=='MSN Bot' || urldecode($settings->crawler)=='Bingbot') {
	$crawlertolookfor= "AND crawlt_crawler.crawler_name IN ('MSN Bot','Bingbot')";
	$settings->crawler='MSN Bot - Bingbot';
}
else {
	$crawlertolookfor="AND crawlt_crawler.crawler_name='" . crawlt_sql_quote($db->connexion, $settings->crawler) . "'";
}

//date for the mysql query
if ($settings->period >= 10) {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "'";
}

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");

//mysql query
//query to count the number of crawler per page and to list the page viewed and to count the number of visits per page and to have the date of last visit for each pages
$sqlstats = "SELECT  url_page,   COUNT(id_visit) as maxvisites,
MAX(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), MIN(UNIX_TIMESTAMP(date)-($settings->timediff*3600)) 
FROM crawlt_visits 
INNER JOIN crawlt_crawler 
ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
INNER JOIN crawlt_pages
ON crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
WHERE $datetolookfor    
AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
$crawlertolookfor
GROUP BY crawlt_pages_id_page
ORDER BY maxvisites DESC
LIMIT 0, 200
";
$requetestats = db_query($sqlstats, $db->connexion);
$nbrresult = $requetestats->num_rows;
if ($nbrresult >= 1) {
	$onlyarchive = 0;
	while ($ligne = $requetestats->fetch_row()) {
		$nbvisits[$ligne[0]] = $ligne[1];
		$lastdatedisplay[$ligne[0]] = $ligne[2];
		$firstdatedisplay[$ligne[0]] = $ligne[3];
		$listpages[$ligne[0]] = $ligne[0];
	}
	//query to have the crawler data
	$sqlstats2 = "SELECT DISTINCT crawlt_crawler_id_crawler as robot, crawler_url, crawler_info, crawler_user_agent, crawler_ip, COUNT(DISTINCT id_visit), COUNT(DISTINCT crawlt_pages_id_page) 
    FROM crawlt_visits
    INNER JOIN crawlt_crawler 
    ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler 
    WHERE  $datetolookfor          
    AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
    $crawlertolookfor
    GROUP BY robot";
	$requetestats2 = db_query($sqlstats2, $db->connexion);
	$nbrtotvisits = 0;
	$nbrtotpages = 0;
	while ($ligne = $requetestats2->fetch_row()) {
		$nbrtotvisits = $nbrtotvisits + $ligne[5];
		$nbrtotpages = $nbrtotpages + $ligne[6];
		$address = $ligne[1];
		$info = $ligne[2];
		$agent = $ligne[3];
		$ip = $ligne[4];
		if (!empty($agent)) {
			$uagent[] = $agent;
		}
		if (!empty($ip)) {
			$uagent[] = $ip;
		}
	}
	// Don't close database here since mapgraph3 also needs the connection!

	//display--------------------------------------------------------------------------------------------------
	$settings->crawlerdisplay = htmlentities($settings->crawler);
	$addressdisplay = htmlentities($address);
	$infodisplay = htmlentities($info);
	echo "<br><br><div class=\"content2\"><br><hr>\n";
	//ua table
	echo "<div class='tableau' align='center' onmouseover=\"javascript:montre();\">\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr><th class='tableau1'>\n";
	echo "" . $language['user_agent_or_ip'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['Origin'] . "\n";
	echo "</th></tr>\n";
	$nbline = sizeof($uagent);
	$nb = 0;
	foreach ($uagent as $ua) {
		$uadisplay = htmlentities($ua);
		echo "<tr><td class='tableau3'>" . $uadisplay . "</td>\n";
		if ($nb == 0) {
			echo "<td class='tableau5' rowspan=" . $nbline . "><a href=\"$addressdisplay\">" . $infodisplay . "</a></td></tr>\n";
		} else {
			echo "</tr>\n";
		}
		$nb = 2;
	}
	echo "</table></div><br>\n";
	echo "</div>\n";
	//graph
	echo "<div align='center'>\n";
	echo "<img src=\"./graphs/page-graph.php?nbrpageview=$nbrtotpages&amp;nbrpagestotal=$nbrpagestotal&amp;crawltlang=$settings->language\" alt=\"graph\"  width=\"500\" height=\"200\"/>\n";
	echo "</div><br>\n";
	echo "<div class='tableau' align='center'>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
	echo "<tr><th class='tableau1'>\n";
	echo "" . $language['nbr_tot_visits'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1'>\n";
	echo "" . $language['nbr_tot_pages'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['pc-page-view'] . "\n";
	echo "</th></tr>\n";
	echo "<tr><td class='tableau3'>" . numbdisp($nbrtotvisits) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp($nbrtotpages) . "</td>\n";
	$pcvis = round(($nbrtotpages / $nbrpagestotal) * 100, 1);
	echo "<td class='tableau5'>" . $pcvis . "%</td></tr> \n";
	echo "</table></div><br>\n";

	if ($settings->period != 5) {
		//graph
		echo "<div class='graphvisits'>\n";
		//mapgraph
		include "include/mapgraph.php";
		echo "<img src=\"./graphs/visit-graph.php?crawltlang=$settings->language&period=$settings->period&navig=$settings->navig&graphname=$graphname\" USEMAP=\"#visit\" alt=\"graph\" width=\"700\" height=\"300\"  border=\"0\"/>\n";
		echo "</div>\n";
		echo "<div class='imprimgraph'>\n";
		echo "&nbsp;<br><br><br><br><br><br></div>\n";
	}
	//change text if more than x crawlers	and display limited (value of x can be change in function.php,,it's displaynumber)
	if ($nbrtotpages >= $settings->displayrows && $settings->displayall == 'no' && $settings->period != 5) {
		echo "<br><h2>";
		printf($language['100_visit_per-crawler'], $settings->displayrows);
		echo "<br>\n";
		$crawlencode = urlencode($settings->crawler);
		echo "<span class=\"smalltext\"><a href=\"index.php?navig=$settings->navig&period=$settings->period&site=$settings->siteid&crawler=$crawlencode&order=$settings->displayorder&displayall=yes&graphpos=$settings->graphpos\">" . $language['show_all'] . "</a></span></h2>";
	} else {
		echo "<h2>" . $language['visit_per-crawler'] . "</h2>\n";
	}
	echo "<div class='tableaularge' align='center'>\n";
	echo "<table   cellpadding='0px'; cellspacing='0' width='100%'>\n";
	if ($settings->displayorder == 3) {
		echo "<tr><th class='tableau1' colspan=\"2\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['page'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	} else {
		echo "<tr><th class='tableau1' colspan=\"2\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='order' value='" . $language['page'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	}
	if ($settings->displayorder == 2) {
		if ($settings->period != 5) {
			echo "<th class='tableau1' >\n";
		} else {
			echo "<th class='tableau2' >\n";
		}
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"2\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['nbr_visits'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	} else {
		if ($settings->period != 5) {
			echo "<th class='tableau1' >\n";
		} else {
			echo "<th class='tableau2' >\n";
		}
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"2\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='order' value='" . $language['nbr_visits'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	}
	if ($settings->period != 5) {
		if ($settings->displayorder == 4) {
			echo "<th class='tableau1'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='order' value=\"4\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
			echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
			echo "<input type='submit' class='orderselect' value='" . $language['first_date_visits'] . "'>\n";
			echo "</form>\n";
			echo "</th>\n";
			echo "</th>\n";
		} else {
			echo "<th class='tableau1'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='order' value=\"4\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
			echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
			echo "<input type='submit' class='order' value='" . $language['first_date_visits'] . "'>\n";
			echo "</form>\n";
			echo "</th>\n";
		}
		if ($settings->displayorder == 0) {
			echo "<th class='tableau1'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='order' value=\"0\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
			echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
			echo "<input type='submit' class='orderselect' value='" . $language['date_visits'] . "'>\n";
			echo "</form>\n";
			echo "</th>\n";
		} else {
			echo "<th class='tableau1'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='order' value=\"0\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
			echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
			echo "<input type='submit' class='order' value='" . $language['date_visits'] . "'>\n";
			echo "</form>\n";
			echo "</th>\n";
		}
		echo "<th class='tableau2'>\n";
		echo $language['deltatime'];
		echo "</th></tr>\n";
	}
	if ($settings->displayorder == 0) {
		arsort($lastdatedisplay);
		$sorttab = $lastdatedisplay;
	} elseif ($settings->displayorder == 2) {
		arsort($nbvisits);
		$sorttab = $nbvisits;
	} elseif ($settings->displayorder == 3) {
		asort($listpages);
		$sorttab = $listpages;
	} elseif ($settings->displayorder == 4) {
		arsort($firstdatedisplay);
		$sorttab = $firstdatedisplay;
	}
	//counter for alternate color lane
	$comptligne = 2;
	foreach ($sorttab as $key => $value) {
		if ($comptligne < ($settings->displayrows + 2) || $settings->displayall == 'yes') {
			//calculation of averagetime between visits
			$deltadate = $lastdatedisplay[$key] - $firstdatedisplay[$key];
			if ($deltadate == 0) {
				$deltatime = '?';
			} else {
				$deltatime = $deltadate / ($nbvisits[$key] - 1);
				$hour = floor($deltatime / 3600);
				if ($hour == 0) {
					$hourdisplay = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				} else {
					$hourdisplay = $hour . "hr ";
				}
				$reste = $deltatime % 3600;
				$minutes = floor($reste / 60);
				if (strlen($minutes) == 1) {
					if ($hour == 0 && $minutes == 0) {
						$minutesdisplay = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					} elseif ($hour == 0 && $minutes != 0) {
						$minutesdisplay = $minutes . "mn ";
					} elseif ($hour != 0 && $minutes != 0) {
						$minutesdisplay = "0" . $minutes . "mn ";
					}
				} else {
					$minutesdisplay = $minutes . "mn ";
				}
				$secondes = $reste % 60;
				if (strlen($secondes) == 1 && ($minutes != 0 || $hour != 0)) {
					$secondesdisplay = "0" . $secondes . "s";
				} else {
					$secondesdisplay = $secondes . "s";
				}
				$deltatime = $hourdisplay . $minutesdisplay . $secondesdisplay;
			}
			$page1display = crawltcutkeyword($key, '40', $settings->useutf8);
			$page1encode = urlencode($key);
			//to avoid problem if the url is enter in the database with http://
			if (!preg_match('#^http://#i', $urlsite[$settings->siteid])) {
				$urlpage = "http://" . $urlsite[$settings->siteid] . $key;
			} else {
				$urlpage = $urlsite[$settings->siteid] . $key;
			}
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 40) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=4&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $page1encode . "&amp;graphpos=" . $settings->graphpos . "' rel='nofollow'>" . $page1display . "</a></td>\n";
				echo "<td class='tableau6' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				if ($settings->period != 5) {
					echo "<td class='tableau3' width='60px'>" . numbdisp($nbvisits[$key]) . "</td> \n";
					echo "<td class='tableau3'>" . date("d/m/Y", $firstdatedisplay[$key]) . "<br>" . date("G:i", $firstdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau3'>" . date("d/m/Y", $lastdatedisplay[$key]) . "<br>" . date("G:i", $lastdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau5'>" . $deltatime . "</td></tr>\n";
				} else {
					echo "<td class='tableau5' width='60px'>" . numbdisp($nbvisits[$key]) . "</td> \n";
					echo "</tr> \n";
				}
			} else {
				echo "<tr><td class='tableau30g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 40) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=4&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $page1encode . "&amp;graphpos=" . $settings->graphpos . "' rel='nofollow'>" . $page1display . "</a></td>\n";
				echo "<td class='tableau60' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				if ($settings->period != 5) {
					echo "<td class='tableau30' width='60px'>" . numbdisp($nbvisits[$key]) . "</td> \n";
					echo "<td class='tableau30'>" . date("d/m/Y", $firstdatedisplay[$key]) . "<br>" . date("G:i", $firstdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau30'>" . date("d/m/Y", $lastdatedisplay[$key]) . "<br>" . date("G:i", $lastdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau50'>" . $deltatime . "</td></tr>\n";
				} else {
					echo "<td class='tableau50' width='60px'>" . numbdisp($nbvisits[$key]) . "</td> \n";
					echo "</tr> \n";
				}
			}
			if ($keywordcut == 1) {
				if ($settings->period == 0 || $settings->period >= 1000) {
					$step = 25;
				} else {
					$step = 30;
				}
				echo "<div id=\"smenu" . ($comptligne + 40) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (900 + (($comptligne - 3) * $step)) . "px; left:5px; background:#fff;\">\n";
				echo "&nbsp;" . crawltcuturl($key, '92', $settings->useutf8) . "&nbsp;\n";
				echo "</div>\n";
			}
		}
		$comptligne++;
	}
	echo "</table>\n";
	echo "<br>\n";
} else {
	$sqlstats2 = "SELECT * FROM crawlt_crawler
	WHERE crawlt_crawler.crawler_name='" . crawlt_sql_quote($db->connexion, $settings->crawler) . "'
	ORDER BY crawler_name ASC";
	$requetestats2 = db_query($sqlstats2, $db->connexion);
	$db->close(); // Close database
	$nbrresult2 = $requetestats2->num_rows;
	if ($nbrresult2 == 0) {
		exit('<h1>Hacking attempt !!!!</h1>');
	}
	while ($ligne = $requetestats2->fetch_object()) {
		$address = $ligne->crawler_url;
		$info = $ligne->crawler_info;
		$agent = $ligne->crawler_user_agent;
		$uagent[] = $agent;
	}
	$settings->crawlerdisplay = htmlentities($settings->crawler);
	$addressdisplay = htmlentities($address);
	$infodisplay = htmlentities($info);
	echo "<div class=\"content2\"><br><hr>\n";
	//ua table
	echo "<div class='tableau' align='center'>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr><th class='tableau1'>\n";
	echo "" . $language['user_agent'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['Origin'] . "\n";
	echo "</th></tr>\n";
	$uagent = array_unique($uagent);
	$nbline = sizeof($uagent);
	$nb = 0;
	foreach ($uagent as $ua) {
		$uadisplay = htmlentities($ua);
		echo "<tr><td class='tableau3'>" . $uadisplay . "</td>\n";
		if ($nb == 0) {
			echo "<td class='tableau5' rowspan=" . $nbline . "><a href=\"$addressdisplay\">" . $infodisplay . "</a></td></tr>\n";
		} else {
			echo "</tr>\n";
		}
		$nb = 2;
	}
	echo "</table></div><br>\n";
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
$db->close(); // Close database
?>
