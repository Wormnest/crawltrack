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
// file: display-all-pages.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$nbrcrawlerpage = array();
$nbvisits = array();
$lastdatedisplay = array();
$listpage = array();
$crawlencode = urlencode($settings->crawler);

if ($settings->period >= 1000) //previous days
{
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

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");

//date for the mysql query
if ($settings->period >= 10) {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "'";
}

//query to count the number of crawler per page and to list the page viewed and to count the number of visits per page and to have the date of last visit for each pages
$sqlstats = "SELECT  crawlt_pages_id_page, COUNT(DISTINCT crawler_name),  COUNT(id_visit) AS nbrvisits,
MAX(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), MIN(UNIX_TIMESTAMP(date)-($settings->timediff*3600))
FROM crawlt_visits
INNER JOIN crawlt_crawler
ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
WHERE $datetolookfor    
AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'    
GROUP BY crawlt_pages_id_page
ORDER BY nbrvisits DESC
LIMIT 0, 100
";
$requetestats = db_query($sqlstats, $db->connexion);
$nbrresult = $requetestats->num_rows;
if ($nbrresult >= 1) {
	$onlyarchive = 0;
	while ($ligne = $requetestats->fetch_row()) {
		$nbrcrawlerpage[$ligne[0]] = $ligne[1];
		$nbvisits[$ligne[0]] = $ligne[2];
		$lastdatedisplay[$ligne[0]] = $ligne[3];
		$firstdatedisplay[$ligne[0]] = $ligne[4];
		$listpageid[$ligne[0]] = $ligne[0];
	}
	mysqli_free_result($requetestats);
	//query to get page url list
	$crawltlistpageid = implode("','", $listpageid);
	$sql = "SELECT  id_page, url_page
  FROM crawlt_pages
  WHERE id_page IN ('$crawltlistpageid')";
	$requete = db_query($sql, $db->connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		while ($ligne = $requete->fetch_row()) {
			$listpage[$ligne[0]] = $ligne[1];
		}
	}
	mysqli_free_result($requete);
	$sqlstats2 = "SELECT COUNT(DISTINCT crawlt_pages_id_page), COUNT(DISTINCT crawler_name), COUNT(id_visit)
  FROM crawlt_visits
  INNER JOIN crawlt_crawler
  ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
  WHERE $datetolookfor         
  AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'";
	$requetestats2 = db_query($sqlstats2, $db->connexion);
	$ligne2 = $requetestats2->fetch_row();
	$nbrtotpages = $ligne2[0];
	$nbrtotcrawlers = $ligne2[1];
	$nbrtotvisits = $ligne2[2];
	// Don't close database here since mapgraph3 also needs the connection!

	//display----------------------------------------------------------------------------------------------------
	echo "<div class=\"content2\"><br><hr>\n";
	echo "</div>\n";
	if ($settings->graphpos == 0 && $settings->period != 5) {
		//graph
		echo "<div align='center'>\n";
		echo "<a href=\"index.php?navig=$settings->navig&amp;graphpos=1&amp;period=$settings->period&amp;site=$settings->siteid&amp;crawler=$crawlencode\">\n";
		echo "<img src=\"./graphs/page-graph.php?nbrpageview=$nbrtotpages&amp;nbrpagestotal=$nbrpagestotal&amp;crawltlang=$settings->language\" alt=\"graph\"  style=\"border:0; width:500px; height:200px\">\n";
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
	if ($settings->graphpos == 1 && $settings->period != 5) {
		//graph
		echo "<br><h2>" . $language['pc-page-view'] . "</h2>\n";
		echo "<div align='center'>\n";
		echo "<a href=\"index.php?navig=$settings->navig&amp;graphpos=0&amp;period=$settings->period&amp;site=$settings->siteid&amp;crawler=$crawlencode\">\n";
		echo "<img src=\"./graphs/page-graph.php?nbrpageview=$nbrtotpages&amp;nbrpagestotal=".$nbrpagestotal[$settings->siteid]."&amp;crawltlang=$settings->language\" alt=\"graph\"  width=\"500px\" height=\"175px\" style=\"border:0\"/>\n";
		echo "</a>\n";
		echo "</div>\n";
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
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	if ($settings->displayorder == 3) {
		echo "<tr><th class='tableau1' colspan=\"2\">\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['nbr_pages'] . "'>\n";
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
		echo "<input type='submit' class='order' value='" . $language['nbr_pages'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	}
	if ($settings->displayorder == 2) {
		echo "<th class='tableau1'>\n";
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
		echo "<th class='tableau1'>\n";
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
	if ($settings->displayorder == 1) {
		if ($settings->period == 5) {
			echo "<th class='tableau2' >\n";
		} else {
			echo "<th class='tableau1' >\n";
		}
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['crawler_name'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
		echo "</th>\n";
	} else {
		if ($settings->period == 5) {
			echo "<th class='tableau2' >\n";
		} else {
			echo "<th class='tableau1' >\n";
		}
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"1\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='order' value='" . $language['crawler_name'] . "'>\n";
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
	} else {
		echo "</tr>\n";
	}
	if ($settings->displayorder == 0) {
		arsort($lastdatedisplay);
		$sorttab = $lastdatedisplay;
	} elseif ($settings->displayorder == 1) {
		arsort($nbrcrawlerpage);
		$sorttab = $nbrcrawlerpage;
	} elseif ($settings->displayorder == 2) {
		arsort($nbvisits);
		$sorttab = $nbvisits;
	} elseif ($settings->displayorder == 3) {
		asort($listpage);
		$sorttab = $listpage;
	} elseif ($settings->displayorder == 4) {
		asort($firstdatedisplay);
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
			$crawldisplay = crawltcutkeyword($listpage[$key], '40', $settings->useutf8);
			$nbrpage = $nbrcrawlerpage[$key];
			$crawlencode = urlencode($listpage[$key]);
			//to avoid problem if the url is enter in the database with http://
			if (!preg_match('#^http://#i', $urlsite[$settings->siteid])) {
				$urlpage = "http://" . $urlsite[$settings->siteid] . $listpage[$key];
			} else {
				$urlpage = $urlsite[$settings->siteid] . $listpage[$key];
			}
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 40) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=4&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "' rel='nofollow'>" . $crawldisplay . "</a></td>\n";
				echo "<td class='tableau6' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau3'>" . numbdisp($nbvisits[$key]) . "</td>\n";
				if ($settings->period != 5) {
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
				echo ">&nbsp;&nbsp;<a href='index.php?navig=4&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "' rel='nofollow'>" . $crawldisplay . "</a></td>\n";
				echo "<td class='tableau60' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau30'>" . numbdisp($nbvisits[$key]) . "</td>\n";
				if ($settings->period != 5) {
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
				if ($settings->period == 0 || $settings->period >= 1000) {
					$step = 25;
				} else {
					$step = 30;
				}
				echo "<div id=\"smenu" . ($comptligne + 40) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (800 + (($comptligne - 3) * $step)) . "px; left:5px; background:#fff;\">\n";
				echo "&nbsp;" . crawltcuturl($listpage[$key], '92', $settings->useutf8) . "&nbsp;\n";
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
$db->close(); // Close database
?>
