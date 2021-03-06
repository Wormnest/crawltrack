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
// file: display-pages-visitors.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$nbrcrawlerpage = array();
$nbvisits2 = array();
$lastdatedisplay = array();
$nbrtotpages = 0;
$listpage = array();
$crawlencode = urlencode($settings->crawler);

$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->displayorder.$settings->displayrows . $crawlencode . $settings->displayall . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language;

//start the caching
cache($cachename);

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");

//clean table from crawler entry
include ("include/cleaning-crawler-entry.php");

//include visitors calculation file
include ("include/visitors-calculation.php");

//date for the mysql query
if ($settings->period >= 10) {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "'";
}
//query  to list the page viewed and to count the number of visits per page and to have the date of last visit for each pages
if ($nottoomuchip == 1) {
	$sqlstats = "SELECT  crawlt_id_page,   COUNT(id_visit),
  MAX(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), MIN(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), url_page
  FROM crawlt_visits_human
  INNER JOIN crawlt_pages
  ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page  
  WHERE $datetolookfor    
  AND crawlt_visits_human.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "' 
  AND crawlt_ip IN ('$crawltlistip')
  AND  crawlt_visits_human.crawlt_error =0  
  GROUP BY crawlt_id_page";
} else {
	$sqlstats = "SELECT  crawlt_id_page,   COUNT(id_visit),
  MAX(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), MIN(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), url_page
  FROM crawlt_visits_human
  INNER JOIN crawlt_pages
  ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
  WHERE $datetolookfor    
  AND crawlt_visits_human.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "' 
  AND  crawlt_visits_human.crawlt_error =0   
  GROUP BY crawlt_id_page";
}
$requetestats = db_query($sqlstats, $db->connexion);
$nbrresult = $requetestats->num_rows;
if ($nbrresult >= 1) {
	$onlyarchive = 0;
	while ($ligne = $requetestats->fetch_row()) {
		$nbvisits2[$ligne[0]] = $ligne[1];
		$lastdatedisplay[$ligne[0]] = $ligne[2];
		$firstdatedisplay[$ligne[0]] = $ligne[3];
		$listpage[$ligne[0]] = $ligne[4];
	}
	$nbrtotpages = count($listpage);
	//query for bounce rate per page
	$sql = "SELECT  crawlt_id_page, crawlt_ip
  FROM crawlt_visits_human
  WHERE $datetolookfor    
  AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'";
	$requete = db_query($sql, $db->connexion);
	while ($ligne = $requete->fetch_row()) {
		if (in_array($ligne[1], $listiponevisit)) {
			$nbonevisits[$ligne[0]] = @$nbonevisits[$ligne[0]] + 1;
		}
	}
	// Don't close database here since mapgraph also needs the connection!

	//display----------------------------------------------------------------------------------------------------
	echo "<div class=\"content2\"><br><hr>\n";
	echo "</div>\n";
	echo "<div class='tableau' align='center' onmouseover=\"javascript:montre();\">\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='550px'>\n";
	echo "<tr><th class='tableau1'>\n";
	echo "" . $language['nbr_pages'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['visitors'] . "\n";
	echo "</th></tr>\n";
	echo "<tr><td class='tableau3'>" . numbdisp($nbrpage) . "</td>\n";
	echo "<td class='tableau5'>" . numbdisp($nbrvisitor) . "</td></tr>\n";
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
		if ($settings->period == 5) {
			echo "<th class='tableau2' >\n";
		} else {
			echo "<th class='tableau1' >\n";
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
		if ($settings->period == 5) {
			echo "<th class='tableau2' >\n";
		} else {
			echo "<th class='tableau1' >\n";
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
	echo "<th class='tableau12'>\n";
	echo $language['bounce_rate'];
	echo "</th>\n";
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
	} elseif ($settings->displayorder == 2) {
		arsort($nbvisits2);
		$sorttab = $nbvisits2;
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
				$deltatime = $deltadate / ($nbvisits2[$key] - 1);
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
					} elseif ($hour != 0 && $minutes == 0) {
						$minutesdisplay = "00mn ";
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
			$crawldisplay = crawltcutkeyword($listpage[$key], '30', $settings->useutf8);
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
				echo ">&nbsp;&nbsp;" . $crawldisplay . "</td>\n";
				echo "<td class='tableau6' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				if ($settings->period != 5) {
					if (!isset($nbonevisits[$key])) {
						$nbonevisits[$key] = 0;
					}
					echo "<td class='tableau3'>" . numbdisp($nbvisits2[$key]) . "</td>\n";
					echo "<td class='tableau3'>" . numbdisp(($nbonevisits[$key] / $nbvisits2[$key]) * 100, 1) . " %</td>\n";
					echo "<td class='tableau3'>" . date("d/m/Y", $firstdatedisplay[$key]) . "<br>" . date("G:i", $firstdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau3'>" . date("d/m/Y", $lastdatedisplay[$key]) . "<br>" . date("G:i", $lastdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau5'>" . $deltatime . "</td></tr>\n";
				} else {
					echo "<td class='tableau5'>" . numbdisp($nbvisits2[$key]) . "</td>\n";
					echo "</tr> \n";
				}
			} else {
				echo "<tr><td class='tableau30g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 40) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;" . $crawldisplay . "</td>\n";
				echo "<td class='tableau60' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "' rel='nofollow'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				if ($settings->period != 5) {
					if (!isset($nbonevisits[$key])) {
						$nbonevisits[$key] = 0;
					}
					echo "<td class='tableau30'>" . numbdisp($nbvisits2[$key]) . "</td>\n";
					echo "<td class='tableau30'>" . numbdisp(($nbonevisits[$key] / $nbvisits2[$key]) * 100, 1) . " %</td>\n";
					echo "<td class='tableau30'>" . date("d/m/Y", $firstdatedisplay[$key]) . "<br>" . date("G:i", $firstdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau30'>" . date("d/m/Y", $lastdatedisplay[$key]) . "<br>" . date("G:i", $lastdatedisplay[$key]) . "</td>\n";
					echo "<td class='tableau50'>" . $deltatime . "</td></tr>\n";
				} else {
					echo "<td class='tableau50'>" . numbdisp($nbvisits2[$key]) . "</td>\n";
					echo "</tr> \n";
				}
			}
			if ($keywordcut == 1) {
				if ($settings->period == 0 || $settings->period >= 1000) {
					$step = 25;
				} else {
					$step = 30;
				}
				echo "<div id=\"smenu" . ($comptligne + 40) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (600 + (($comptligne - 4) * $step)) . "px; left:5px; background:#fff;\">\n";
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
	$db->close(); // Close database
	echo "<div class=\"content2\"><br><hr>\n";
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
$db->close(); // Close database
?>
