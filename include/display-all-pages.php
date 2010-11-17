<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.6
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: display-all-pages.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$nbrcrawlerpage = array();
$nbvisits = array();
$lastdatedisplay = array();
$listpage = array();
$crawlencode = urlencode($crawler);
if ($period >= 1000) //previous days
{
	$cachename = "permanent-" . $navig . "-" . $site . "-" . $crawlencode . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($period >= 100 && $period < 200) //previous month
{
	$cachename = "permanent-month" . $navig . "-" . $site . "-" . $crawlencode . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($period >= 200 && $period < 300) //previous year
{
	$cachename = "permanent-year" . $navig . "-" . $site . "-" . $crawlencode . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $navig . $period . $site . $order . $crawlencode . $displayall . $firstdayweek . $localday . $graphpos . $crawltlang;
}
//start the caching
cache($cachename);
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");
//date for the mysql query
if ($period >= 10) {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "' 
    AND  date <'" . sql_quote($daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "'";
}
//query to count the number of crawler per page and to list the page viewed and to count the number of visits per page and to have the date of last visit for each pages
$sqlstats = "SELECT  crawlt_pages_id_page, COUNT(DISTINCT crawler_name),  COUNT(id_visit) AS nbrvisits,
MAX(UNIX_TIMESTAMP(date)-($times*3600)), MIN(UNIX_TIMESTAMP(date)-($times*3600))
FROM crawlt_visits
INNER JOIN crawlt_crawler
ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
WHERE $datetolookfor    
AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'    
GROUP BY crawlt_pages_id_page
ORDER BY nbrvisits DESC
LIMIT 0, 200
";
$requetestats = db_query($sqlstats, $connexion);
$nbrresult = mysql_num_rows($requetestats);
if ($nbrresult >= 1) {
	$onlyarchive = 0;
	while ($ligne = mysql_fetch_row($requetestats)) {
		$nbrcrawlerpage[$ligne[0]] = $ligne[1];
		$nbvisits[$ligne[0]] = $ligne[2];
		$lastdatedisplay[$ligne[0]] = $ligne[3];
		$firstdatedisplay[$ligne[0]] = $ligne[4];
		$listpageid[$ligne[0]] = $ligne[0];
	}
	mysql_free_result($requetestats);
	//query to get page url list
	$crawltlistpageid = implode("','", $listpageid);
	$sql = "SELECT  id_page, url_page
  FROM crawlt_pages
  WHERE id_page IN ('$crawltlistpageid')";
	$requete = db_query($sql, $connexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		while ($ligne = mysql_fetch_row($requete)) {
			$listpage[$ligne[0]] = $ligne[1];
		}
	}
	mysql_free_result($requete);
	$sqlstats2 = "SELECT COUNT(DISTINCT crawlt_pages_id_page), COUNT(DISTINCT crawler_name), COUNT(id_visit)
  FROM crawlt_visits
  INNER JOIN crawlt_crawler
  ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
  WHERE $datetolookfor         
  AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'";
	$requetestats2 = db_query($sqlstats2, $connexion);
	$ligne2 = mysql_fetch_row($requetestats2);
	$nbrtotpages = $ligne2[0];
	$nbrtotcrawlers = $ligne2[1];
	$nbrtotvisits = $ligne2[2];
	mysql_close($connexion);
	//display----------------------------------------------------------------------------------------------------
	echo "<div class=\"content2\"><br><hr>\n";
	echo "</div>\n";
	if ($graphpos == 0 && $period != 5) {
		//graph
		echo "<div align='center'>\n";
		echo "<a href=\"index.php?navig=$navig&amp;graphpos=1&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode\">\n";
		echo "<img src=\"./graphs/page-graph.php?nbrpageview=$nbrtotpages&amp;nbrpagestotal=$nbrpagestotal&amp;crawltlang=$crawltlang\" alt=\"graph\"  style=\"border:0; width:500px; height:200px\">\n";
		echo "</a>\n";
		echo "</div><br>\n";
	}
	echo "<div class='tableau' align='center' onmouseover=\"javascript:montre();\">\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
	echo "<tr><th class='tableau1'>\n";
	echo "" . $language['nbr_pages'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1'>\n";
	echo "" . $language['nbr_tot_visits'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['nbr_tot_crawlers'] . "\n";
	echo "</th></tr>\n";
	echo "<tr><td class='tableau3'>" . numbdisp($nbrtotpages) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp($nbrtotvisits) . "</td>\n";
	echo "<td class='tableau5'>" . numbdisp($nbrtotcrawlers) . "</td></tr>\n";
	echo "</table></div><br>\n";
	if ($period != 5) {
		//graph
		echo "<div class='graphvisits'>\n";
		//mapgraph
		include "include/mapgraph.php";
		echo "<img src=\"./graphs/visit-graph.php?crawltlang=$crawltlang&period=$period&navig=$navig&graphname=$graphname\" USEMAP=\"#visit\" alt=\"graph\" width=\"700\" height=\"300\"  border=\"0\"/>\n";
		echo "</div>\n";
		echo "<div class='imprimgraph'>\n";
		echo "&nbsp;<br><br><br><br><br><br></div>\n";
	}
	if ($graphpos == 1 && $period != 5) {
		//graph
		echo "<br><h2>" . $language['pc-page-view'] . "</h2>\n";
		echo "<div align='center'>\n";
		echo "<a href=\"index.php?navig=$navig&amp;graphpos=0&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode\">\n";
		echo "<img src=\"./graphs/page-graph.php?nbrpageview=$nbrtotpages&amp;nbrpagestotal=$nbrpagestotal[$site]&amp;crawltlang=$crawltlang\" alt=\"graph\"  width=\"500px\" height=\"175px\" style=\"border:0\"/>\n";
		echo "</a>\n";
		echo "</div>\n";
	}
	//change text if more than x crawlers	and display limited (value of x can be change in function.php,,it's displaynumber)
	if ($nbrtotpages >= $rowdisplay && $displayall == 'no' && $period != 5) {
		echo "<br><h2>";
		printf($language['100_visit_per-crawler'], $rowdisplay);
		echo "<br>\n";
		$crawlencode = urlencode($crawler);
		echo "<span class=\"smalltext\"><a href=\"index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&order=$order&displayall=yes&graphpos=$graphpos\">" . $language['show_all'] . "</a></span></h2>";
	} else {
		echo "<h2>" . $language['visit_per-crawler'] . "</h2>\n";
	}
	echo "<div class='tableaularge' align='center'>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	if ($order == 3) {
		echo "<tr><th class='tableau1' colspan=\"2\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['nbr_pages'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	} else {
		echo "<tr><th class='tableau1' colspan=\"2\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type='submit' class='order' value='" . $language['nbr_pages'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	}
	if ($order == 2) {
		echo "<th class='tableau1'>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"2\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['nbr_visits'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	} else {
		echo "<th class='tableau1'>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"2\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type='submit' class='order' value='" . $language['nbr_visits'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	}
	if ($order == 1) {
		if ($period == 5) {
			echo "<th class='tableau2' >\n";
		} else {
			echo "<th class='tableau1' >\n";
		}
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['crawler_name'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
		echo "</th>\n";
	} else {
		if ($period == 5) {
			echo "<th class='tableau2' >\n";
		} else {
			echo "<th class='tableau1' >\n";
		}
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type='submit' class='order' value='" . $language['crawler_name'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	}
	if ($period != 5) {
		if ($order == 4) {
			echo "<th class='tableau1'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='order' value=\"4\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
			echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type='submit' class='orderselect' value='" . $language['first_date_visits'] . "'>\n";
			echo "</form>\n";
			echo "</th>\n";
			echo "</th>\n";
		} else {
			echo "<th class='tableau1'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='order' value=\"4\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
			echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type='submit' class='order' value='" . $language['first_date_visits'] . "'>\n";
			echo "</form>\n";
			echo "</th>\n";
		}
		if ($order == 0) {
			echo "<th class='tableau1'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='order' value=\"0\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
			echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type='submit' class='orderselect' value='" . $language['date_visits'] . "'>\n";
			echo "</form>\n";
			echo "</th>\n";
		} else {
			echo "<th class='tableau1'>\n";
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='order' value=\"0\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
			echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type='submit' class='order' value='" . $language['date_visits'] . "'>\n";
			echo "</form>\n";
			echo "</th>\n";
		}
		echo "<th class='tableau2'>\n";
		echo $language['deltatime'];
		echo "</th></tr>\n";
	} else {
		echo "</tr>\n";
	}
	if ($order == 0) {
		arsort($lastdatedisplay);
		$sorttab = $lastdatedisplay;
	} elseif ($order == 1) {
		arsort($nbrcrawlerpage);
		$sorttab = $nbrcrawlerpage;
	} elseif ($order == 2) {
		arsort($nbvisits);
		$sorttab = $nbvisits;
	} elseif ($order == 3) {
		asort($listpage);
		$sorttab = $listpage;
	} elseif ($order == 4) {
		asort($firstdatedisplay);
		$sorttab = $firstdatedisplay;
	}
	//counter for alternate color lane
	$comptligne = 2;
	foreach ($sorttab as $key => $value) {
		if ($comptligne < ($rowdisplay + 2) || $displayall == 'yes') {
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
			$crawldisplay = crawltcutkeyword($listpage[$key], '40');
			$nbrpage = $nbrcrawlerpage[$key];
			$crawlencode = urlencode($listpage[$key]);
			//to avoid problem if the url is enter in the database with http://
			if (!preg_match('#^http://#i', $urlsite[$site])) {
				$urlpage = "http://" . $urlsite[$site] . $listpage[$key];
			} else {
				$urlpage = $urlsite[$site] . $listpage[$key];
			}
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 40) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=4&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $graphpos . "' rel='nofollow'>" . $crawldisplay . "</a></td>\n";
				echo "<td class='tableau6' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau3'>" . numbdisp($nbvisits[$key]) . "</td>\n";
				if ($period != 5) {
					echo "<td class='tableau3' width='60px'>" . numbdisp($nbrpage) . "</td> \n";
					echo "<td class='tableau3'>" . date("d/m/Y", $firstdatedisplay[$key]) . "<br>" . date("G:i", $firstdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau3'>" . date("d/m/Y", $lastdatedisplay[$key]) . "<br>" . date("G:i", $lastdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau5'>" . $deltatime . "</td></tr>\n";
				} else {
					echo "<td class='tableau5' width='60px'>" . numbdisp($nbrpage) . "</td> \n";
					echo "</tr> \n";
				}
			} else {
				echo "<tr><td class='tableau30g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 40) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=4&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $graphpos . "' rel='nofollow'>" . $crawldisplay . "</a></td>\n";
				echo "<td class='tableau60' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau30'>" . numbdisp($nbvisits[$key]) . "</td>\n";
				if ($period != 5) {
					echo "<td class='tableau30' width='60px'>" . numbdisp($nbrpage) . "</td> \n";
					echo "<td class='tableau30'>" . date("d/m/Y", $firstdatedisplay[$key]) . "<br>" . date("G:i", $firstdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau30'>" . date("d/m/Y", $lastdatedisplay[$key]) . "<br>" . date("G:i", $lastdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau50'>" . $deltatime . "</td></tr>\n";
				} else {
					echo "<td class='tableau50' width='60px'>" . numbdisp($nbrpage) . "</td> \n";
					echo "</tr> \n";
				}
			}
			if ($keywordcut == 1) {
				if ($period == 0 || $period >= 1000) {
					$step = 25;
				} else {
					$step = 30;
				}
				echo "<div id=\"smenu" . ($comptligne + 40) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (800 + (($comptligne - 3) * $step)) . "px; left:5px; background:#fff;\">\n";
				echo "&nbsp;" . crawltcuturl($listpage[$key], '92') . "&nbsp;\n";
				echo "</div>\n";
			}
		}
		$comptligne++;
	}
	echo "</table>\n";
	echo "<br>\n";
} else
//case no visits
{
	echo "<div class=\"content2\"><br><hr>\n";
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
?>
