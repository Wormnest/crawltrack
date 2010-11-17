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
// file: display-one-page.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
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
$crawlencode = urlencode($crawler);
if ($period >= 1000) {
	$cachename = "permanent-" . $navig . "-" . $site . "-" . $crawlencode . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($period >= 100 && $period < 200) //previous month
{
	$cachename = "permanent-month" . $navig . "-" . $site . "-" . $crawlencode . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($period >= 200 && $period < 300) //previous year
{
	$cachename = "permanent-year" . $navig . "-" . $site . "-" . $crawlencode . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $navig . $period . $site . $order . $crawlencode . $firstdayweek . $localday . $graphpos . $crawltlang;
}
//start the caching
cache($cachename);
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//include menu
include ("include/menumain.php");
include ("include/menusite.php");
//date for the mysql query
if ($period >= 10) {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "' 
    AND  date <'" . sql_quote($daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "'";
}
include "include/timecache.php";
//query to get crawler name
$sql = "SELECT id_crawler, crawler_name FROM crawlt_crawler";
$requete = db_query($sql, $connexion);
$nbrresult = mysql_num_rows($requete);
if ($nbrresult >= 1) {
	while ($ligne = mysql_fetch_row($requete)) {
	if ($ligne[1] == 'MSN Bot' || $ligne[1] == 'Bingbot') {
	$ligne[1]='MSN Bot - Bingbot';
	}
		$crawlername[$ligne[0]] = $ligne[1];
	}
}
//query to get page id
$crawlerd= htmlspecialchars_decode($crawler);
$sql = "SELECT id_page FROM crawlt_pages
WHERE url_page='" . sql_quote($crawlerd) . "'";
$requete = db_query($sql, $connexion);
$nbrresult = mysql_num_rows($requete);
if ($nbrresult >= 1) {
	while ($ligne = mysql_fetch_row($requete)) {
		$idpage = $ligne[0];
	}
}
//query to count the number of page per crawler and to list the crawler and to count the number of visits per crawler and to have the date of last visit for each crawler
$sqlstats = "SELECT  crawlt_crawler_id_crawler,   COUNT(id_visit) as maxvisites ,
MAX(UNIX_TIMESTAMP(date)-($times*3600)), MIN(UNIX_TIMESTAMP(date)-($times*3600)) 
FROM crawlt_visits
WHERE $datetolookfor    
AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_pages_id_page='" . sql_quote($idpage) . "'   
GROUP BY crawlt_crawler_id_crawler";
$requetestats = db_query($sqlstats, $connexion);
$nbrresult = mysql_num_rows($requetestats);
if ($nbrresult >= 1) {
	$onlyarchive = 0;
	while ($ligne = mysql_fetch_row($requetestats)) {
		$nbvisits[$crawlername[$ligne[0]]] = @$nbvisits[$crawlername[$ligne[0]]] + $ligne[1];
		if ($ligne[2] > @$lastdatedisplay[$crawlername[$ligne[0]]]) {
			$lastdatedisplay[$crawlername[$ligne[0]]] = $ligne[2];
		}
		if ($ligne[3] <= @$firstdatedisplay[$crawlername[$ligne[0]]] || !isset($firstdatedisplay[$crawlername[$ligne[0]]])) {
			$firstdatedisplay[$crawlername[$ligne[0]]] = $ligne[3];
		}
		$listcrawler[$crawlername[$ligne[0]]] = $crawlername[$ligne[0]];
	}
	//query to count the total number of  pages viewed ,total number of visits and total number of crawler
	//first we check if that calculation has not already been done
	if (isset($_SESSION['nbrtotcrawlers-' . $cachename]) && isset($_SESSION['nbrtotvisits-' . $cachename])) {
		$nbrtotcrawlers = $_SESSION['nbrtotcrawlers-' . $cachename];
		$nbrtotvisits = $_SESSION['nbrtotvisits-' . $cachename];
	} else {
		$sqlstats2 = "SELECT id_visit FROM crawlt_visits
      WHERE $datetolookfor         
      AND crawlt_site_id_site='" . sql_quote($site) . "'
      AND crawlt_pages_id_page='" . sql_quote($idpage) . "'";
		$requetestats2 = db_query($sqlstats2, $connexion);
		$nbrtotvisits = mysql_num_rows($requetestats2);
		$nbrtotcrawlers = count($listcrawler);
		$_SESSION['nbrtotcrawlers-' . $cachename] = $nbrtotcrawlers;
		$_SESSION['nbrtotvisits-' . $cachename] = $nbrtotvisits;
	}
	//treatment to prepare the display of the top 5 and group the other in the 'Other' category in the crawler graph
	arsort($nbvisits);
	$i = 0;
	foreach ($nbvisits as $crawler1 => $value) {
		if ($i < 5) {
			if (strlen("$crawler1") > 15) {
				$values[substr("$crawler1", 0, 15) . "..."] = $value;
			} else {
				$values[$crawler1] = $value;
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
                WHERE name= '" . sql_quote($graphname) . "'";
	$requete = db_query($sql, $connexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		$sql2 = "UPDATE crawlt_graph SET graph_values='" . sql_quote($datatransferttograph) . "'
                  WHERE name= '" . sql_quote($graphname) . "'";
	} else {
		$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . sql_quote($graphname) . "','" . sql_quote($datatransferttograph) . "')";
	}
	$requete2 = db_query($sql2, $connexion);
	//mysql connexion close
	mysql_close($connexion);
	//display---------------------------------------------------------------------------------
	echo "<div class=\"content2\"><br><br><br><br><hr>\n";
	//graph
	echo "<div align='center'onmouseover=\"javascript:montre();\">\n";
	echo "<img src=\"./graphs/crawler-graph.php?graphname=$graphname&amp;crawltlang=$crawltlang\" alt=\"graph\"  width=\"450\" height=\"200\"/>\n";
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
	//change text if more than x crawlers	and display limited (value of x can be change in function.php,,it's displaynumber)
	if ($nbrtotcrawlers >= $rowdisplay && $displayall == 'no') {
		echo "<br><h2>";
		printf($language['100_visit_per-crawler'], $rowdisplay);
		echo "<br>\n";
		$crawlencode = urlencode($crawler);
		echo "<span class=\"smalltext\"><a href=\"index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&order=$order&displayall=yes&graphpos=$graphpos\">" . $language['show_all'] . "</a></span></h2>";
	} else {
		echo "<h2>" . $language['visit_per-crawler'] . "</h2>\n";
	}
	echo "<div class='tableau' align='center'>\n";
	echo "<table   cellpadding='0px'; cellspacing='0' width='100%'>\n";
	if ($order == 3) {
		echo "<tr><th class='tableau1'>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type='submit' class='orderselect' value='" . $language['crawler_name'] . "'>\n";
		echo "</form>\n";
		echo "</th>\n";
	} else {
		echo "<tr><th class='tableau1'>\n";
		echo "<form action=\"index.php\" method=\"POST\" >\n";
		echo "<input type=\"hidden\" name ='order' value=\"3\">\n";
		echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
		echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
		echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
		echo "<input type=\"hidden\" name ='crawler' value=\"$crawler\">\n";
		echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
		echo "<input type='submit' class='order' value='" . $language['crawler_name'] . "'>\n";
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
	if ($order == 0) {
		arsort($lastdatedisplay);
		$sorttab = $lastdatedisplay;
	} elseif ($order == 2) {
		$sorttab = $nbvisits;
	} elseif ($order == 3) {
		asort($listcrawler);
		$sorttab = $listcrawler;
	} elseif ($order == 4) {
		arsort($firstdatedisplay);
		$sorttab = $firstdatedisplay;
	}
	//counter for alternate color lane
	$comptligne = 2;
	foreach ($sorttab as $page1 => $value) {
		if ($comptligne < 32 || $displayall == 'yes') {
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
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3'><a href='index.php?navig=2&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $page1 . "&amp;graphpos=" . $graphpos . "'>" . $page1display . "</a></td>\n";
				echo "<td class='tableau3'>" . numbdisp($nbvisits[$page1]) . "</td>\n";
				echo "<td class='tableau3'>" . date("d/m/Y", $firstdatedisplay[$page1]) . "<br>" . date("G:i", $firstdatedisplay[$page1]) . "</td>\n";
				echo "<td class='tableau3'>" . date("d/m/Y", $lastdatedisplay[$page1]) . "<br>" . date("G:i", $lastdatedisplay[$page1]) . "</td>\n";
				echo "<td class='tableau5'>" . $deltatime . "</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30'><a href='index.php?navig=2&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $page1 . "&amp;graphpos=" . $graphpos . "'>" . $page1display . "</a></td>\n";
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
	WHERE crawlt_pages.url_page='" . sql_quote($crawler) . "'
	ORDER BY url_page ASC";
	$requetestats2 = db_query($sqlstats2, $connexion);
	//mysql connexion close
	mysql_close($connexion);
	$nbrresult2 = mysql_num_rows($requetestats2);
	if ($nbrresult2 == 0) {
		exit('<h1>Hacking attempt !!!!</h1>');
	}
	$crawlerdisplay = crawltcuturl($crawler, '55');
	echo "<div class=\"content\">\n";
	echo "<div class=\"content2\"><br><hr>\n";
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
?>
