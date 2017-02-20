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
// file: display-one-page.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$listcrawler = array();
$nbvisits = array();
$lastdate1 = array();
$name = array();
$name2 = array();
$nbvisits2 = array();
$values = array();
$table = array();
$crawlencode = urlencode($settings->crawler);

if ($settings->period >= 1000) {
	$cachename = "permanent-" . $settings->navig . "-" . $settings->siteid . "-" . $crawlencode."-" . $settings->displayorder."-" . $settings->displayrows . "-".$settings->language . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($settings->period >= 100 && $settings->period < 200) //previous month
{
	$cachename = "permanent-month" . $settings->navig . "-" . $settings->siteid . "-" . $crawlencode."-" . $settings->displayorder."-" . $settings->displayrows . "-".$settings->language . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($settings->period >= 200 && $settings->period < 300) //previous year
{
	$cachename = "permanent-year" . $settings->navig . "-" . $settings->siteid . "-" . $crawlencode."-" . $settings->displayorder."-" . $settings->displayrows . "-".$settings->language . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->displayorder.$settings->displayrows . $crawlencode . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language;
}

//start the caching
cache($cachename);

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include "include/timecache.php";

//date for the mysql query
if ($settings->period >= 10) {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "'";
}

//query to get crawler name
$sql = "SELECT id_crawler, crawler_name FROM crawlt_crawler";
$requete = db_query($sql, $db->connexion);
$nbrresult = $requete->num_rows;
if ($nbrresult >= 1) {
	while ($ligne = $requete->fetch_row()) {
	if ($ligne[1] == 'MSN Bot' || $ligne[1] == 'Bingbot') {
	$ligne[1]='MSN Bot - Bingbot';
	}
		$settings->crawlername[$ligne[0]] = $ligne[1];
	}
}

//query to get page id
$crawlerd= htmlspecialchars_decode($settings->crawler);
$sql = "SELECT id_page FROM crawlt_pages
WHERE url_page='" . crawlt_sql_quote($db->connexion, $crawlerd) . "'";
$requete = db_query($sql, $db->connexion);
$nbrresult = $requete->num_rows;
if ($nbrresult >= 1) {
	while ($ligne = $requete->fetch_row()) {
		$idpage = $ligne[0];
	}
} else {
	$idpage = -1;
}

//query to count the number of page per crawler and to list the crawler and to count the number of visits per crawler and to have the date of last visit for each crawler
$sqlstats = "SELECT  crawlt_crawler_id_crawler,   COUNT(id_visit) as maxvisites ,
MAX(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), MIN(UNIX_TIMESTAMP(date)-($settings->timediff*3600)) 
FROM crawlt_visits
WHERE $datetolookfor    
AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_pages_id_page='" . crawlt_sql_quote($db->connexion, $idpage) . "'   
GROUP BY crawlt_crawler_id_crawler";
$requetestats = db_query($sqlstats, $db->connexion);
$nbrresult = $requetestats->num_rows;
if ($nbrresult >= 1) {
	$onlyarchive = 0;
	while ($ligne = $requetestats->fetch_row()) {
		$nbvisits[$settings->crawlername[$ligne[0]]] = @$nbvisits[$settings->crawlername[$ligne[0]]] + $ligne[1];
		if ($ligne[2] > @$lastdatedisplay[$settings->crawlername[$ligne[0]]]) {
			$lastdatedisplay[$settings->crawlername[$ligne[0]]] = $ligne[2];
		}
		if ($ligne[3] <= @$firstdatedisplay[$settings->crawlername[$ligne[0]]] || !isset($firstdatedisplay[$settings->crawlername[$ligne[0]]])) {
			$firstdatedisplay[$settings->crawlername[$ligne[0]]] = $ligne[3];
		}
		$listcrawler[$settings->crawlername[$ligne[0]]] = $settings->crawlername[$ligne[0]];
	}
	//query to count the total number of pages viewed, total number of visits and total number of crawlers
	//first we check if the calculation has not already been done
	if (isset($_SESSION['nbrtotcrawlers-' . $cachename]) && isset($_SESSION['nbrtotvisits-' . $cachename])) {
		$nbrtotcrawlers = $_SESSION['nbrtotcrawlers-' . $cachename];
		$nbrtotvisits = $_SESSION['nbrtotvisits-' . $cachename];
	} else {
		$sqlstats2 = "SELECT id_visit FROM crawlt_visits
      WHERE $datetolookfor         
      AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
      AND crawlt_pages_id_page='" . crawlt_sql_quote($db->connexion, $idpage) . "'";
		$requetestats2 = db_query($sqlstats2, $db->connexion);
		$nbrtotvisits = $requetestats2->num_rows;
		$nbrtotcrawlers = count($listcrawler);
		$_SESSION['nbrtotcrawlers-' . $cachename] = $nbrtotcrawlers;
		$_SESSION['nbrtotvisits-' . $cachename] = $nbrtotvisits;
	}
	//treatment to prepare the display of the top 5 and group the other in the 'Other' category in the crawler graph
	arsort($nbvisits);
	$i = 0;
	foreach ($nbvisits as $settings->crawler1 => $value) {
		if ($i < 5) {
			if (strlen("$settings->crawler1") > 15) {
				$values[substr("$settings->crawler1", 0, 15) . "..."] = $value;
			} else {
				$values[$settings->crawler1] = $value;
			}
		} else {
			$values[$language['other']] = @$values[$language['other']] + $value;
		}
		$i++;
	}
	//prepare data to be transferred to graph file
	$datatransferttograph = addslashes(urlencode(serialize($values)));
	//insert the values in the graph table
	$graphname = "crawler-" . $cachename;
	//check if this graph already exists in the table
	$sql = "SELECT name  FROM crawlt_graph
                WHERE name= '" . crawlt_sql_quote($db->connexion, $graphname) . "'";
	$requete = db_query($sql, $db->connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		$sql2 = "UPDATE crawlt_graph SET graph_values='" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "'
                  WHERE name= '" . crawlt_sql_quote($db->connexion, $graphname) . "'";
	} else {
		$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . crawlt_sql_quote($db->connexion, $graphname) . "','" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "')";
	}
	$requete2 = db_query($sql2, $db->connexion);
	// Don't close database here since mapgraph3 also needs the connection!

	//display---------------------------------------------------------------------------------
	echo "<div class=\"content2\"><br><br><br><br><hr>\n";
	//graph
	echo "<div align='center'onmouseover=\"javascript:montre();\">\n";
	echo "<img src=\"./graphs/crawler-graph.php?graphname=$graphname&amp;crawltlang=$settings->language\" alt=\"graph\"  width=\"450\" height=\"200\"/>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "<div class='tableau' align='center'>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='300px'>\n";
	echo "<tr><th class='tableau1'>\n";
	echo "" . $language['nbr_tot_visits'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['nbr_tot_crawlers'] . "\n";
	echo "</th></tr>\n";
	echo "<tr><td class='tableau3'>" . numbdisp($nbrtotvisits) . "</td>\n";
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
	//change text if more than x crawlers	and display limited (value of x can be change in function.php,,it's displaynumber)
	if ($nbrtotcrawlers >= $settings->displayrows && $settings->displayall == 'no') {
		echo "<br><h2>";
		printf($language['100_visit_per-crawler'], $settings->displayrows);
		echo "<br>\n";
		$crawlencode = urlencode($settings->crawler);
		echo "<span class=\"smalltext\"><a href=\"index.php?navig=$settings->navig&period=$settings->period&site=$settings->siteid&crawler=$crawlencode&order=$settings->displayorder&displayall=yes&graphpos=$settings->graphpos\">" . $language['show_all'] . "</a></span></h2>";
	} else {
		echo "<h2>" . $language['visit_per-crawler'] . "</h2>\n";
	}
	echo "<div class='tableau' align='center'>\n";
	echo "<table   cellpadding='0px'; cellspacing='0' width='100%'>\n";
	if ($settings->displayorder == 3) {
		echo "<tr><th class='tableau1'>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['crawler_name'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	} else {
		echo "<tr><th class='tableau1'>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$settings->crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
		echo "<input type='submit' class='order' value='" . $language['crawler_name'] . "'>\n";
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
	if ($settings->displayorder == 0) {
		arsort($lastdatedisplay);
		$sorttab = $lastdatedisplay;
	} elseif ($settings->displayorder == 2) {
		$sorttab = $nbvisits;
	} elseif ($settings->displayorder == 3) {
		asort($listcrawler);
		$sorttab = $listcrawler;
	} elseif ($settings->displayorder == 4) {
		arsort($firstdatedisplay);
		$sorttab = $firstdatedisplay;
	}
	//counter for alternate color lane
	$comptligne = 2;
	foreach ($sorttab as $page1 => $value) {
		if ($comptligne < 32 || $settings->displayall == 'yes') {
			$page1display = htmlentities($page1);
			//calculation of averagetime between visits
			$deltadate = $lastdatedisplay[$page1] - $firstdatedisplay[$page1];
			if ($deltadate == 0) {
				$deltatime = '?';
			} else {
				$deltatime = $deltadate / ($nbvisits[$page1] - 1);
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
					} else {
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
			$crawlencode = urlencode($page1);
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3'><a href='index.php?navig=2&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "'>" . $page1display . "</a></td>\n";
				echo "<td class='tableau3'>" . numbdisp($nbvisits[$page1]) . "</td>\n";
				echo "<td class='tableau3'>" . date("d/m/Y", $firstdatedisplay[$page1]) . "<br>" . date("G:i", $firstdatedisplay[$page1]) . "</td>\n";
				echo "<td class='tableau3'>" . date("d/m/Y", $lastdatedisplay[$page1]) . "<br>" . date("G:i", $lastdatedisplay[$page1]) . "</td>\n";
				echo "<td class='tableau5'>" . $deltatime . "</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30'><a href='index.php?navig=2&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "'>" . $page1display . "</a></td>\n";
				echo "<td class='tableau30'>" . numbdisp($nbvisits[$page1]) . "</td>\n";
				echo "<td class='tableau30'>" . date("d/m/Y", $firstdatedisplay[$page1]) . "<br>" . date("G:i", $firstdatedisplay[$page1]) . "</td>\n";
				echo "<td class='tableau30'>" . date("d/m/Y", $lastdatedisplay[$page1]) . "<br>" . date("G:i", $lastdatedisplay[$page1]) . "</td>\n";
				echo "<td class='tableau50'>" . $deltatime . "</td></tr>\n";
			}
		}
		$comptligne++;
	}
	echo "</table>\n";
	echo "<br>\n";
} else {
	$sqlstats2 = "SELECT * FROM crawlt_pages
	WHERE crawlt_pages.url_page='" . crawlt_sql_quote($db->connexion, $settings->crawler) . "'
	ORDER BY url_page ASC";
	$requetestats2 = db_query($sqlstats2, $db->connexion);
	$db->close(); // Close database
	$nbrresult2 = $requetestats2->num_rows;
	if ($nbrresult2 == 0) {
		exit('<h1>Hacking attempt !!!!</h1>');
	}
	$settings->crawlerdisplay = crawltcuturl($settings->crawler, '55', $settings->useutf8);
	echo "<div class=\"content\">\n";
	echo "<div class=\"content2\"><br><hr>\n";
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
$db->close(); // Close database
?>
