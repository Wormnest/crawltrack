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
// file: display-hacking.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

if ($settings->period >= 1000) {
	$cachename = "permanent-" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($settings->period >= 100 && $settings->period < 200) //previous month
{
	$cachename = "permanent-month" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($settings->period >= 200 && $settings->period < 300) //previous year
{
	$cachename = "permanent-year" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language;
}

//start the caching
cache($cachename);

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");

//mysql query-----------------------------------------------------------------------------------------------
//date for the mysql query
if ($settings->period >= 10) {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "'";
}
$sqlstats = "SELECT  date 
FROM crawlt_visits
WHERE  crawlt_crawler_id_crawler='65500'
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
ORDER BY date";
$requetestats = db_query($sqlstats, $db->connexion);
$nbrresult = $requetestats->num_rows;
$sqlstats2 = "SELECT  date 
FROM crawlt_visits
WHERE crawlt_crawler_id_crawler='65501'
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
ORDER BY date";
$requetestats2 = db_query($sqlstats2, $db->connexion);
$nbrresult2 = $requetestats2->num_rows;

//attack which has given an error 404
if ($settings->period >= 10) {
	$sql = "SELECT attacktype, count 
    FROM crawlt_error
    WHERE  idsite='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
    AND  date >='" . crawlt_sql_quote($db->connexion, $daterequestseo) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2seo) . "'
    GROUP BY attacktype";
} else {
	$sql = "SELECT attacktype, count 
    FROM crawlt_error
    WHERE  idsite='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
    AND  date >='" . crawlt_sql_quote($db->connexion, $daterequestseo) . "'
    GROUP BY attacktype";
}
$requete = db_query($sql, $db->connexion);
$num_rows = $requete->num_rows;
if ($num_rows > 0) {
	while ($ligne = $requete->fetch_row()) {
		if ($ligne[0] == '65500') {
			$nbrresult = $nbrresult + $ligne[1];
		} elseif ($ligne[0] == '65501') {
			$nbrresult2 = $nbrresult2 + $ligne[1];
		}
	}
}
// Don't close database here since mapgraph3 also needs the connection!

$testip = 0;
if ($nbrresult >= 1 || $nbrresult2 >= 1) {
	//display---------------------------------------------------------------------------------------------------------
	echo "<div class=\"content2\"><br><hr>\n";
	echo "</div>\n";
	//graph
	echo "<div align='center'onmouseover=\"javascript:montre();\">\n";
	echo "<img src=\"./graphs/page-graph.php?nbrpageview=$nbrresult&amp;nbrpagestotal=$nbrresult2&amp;crawltlang=$settings->language&amp;navig=$settings->navig\" alt=\"graph\" style=\"border:0; width:500px; height:200px\">\n";
	echo "</div><br>\n";
	//summary table display
	echo "<div class='tableau' align='center' onmouseout=\"javascript:montre();\">\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='700px'>\n";
	echo "<tr><th class='tableau1' width='50%'>\n";
	echo "" . $language['hacking3'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['hacking4'] . "\n";
	echo "</th></tr>\n";
	echo "<tr><td class='tableau3'><a href=\"index.php?navig=18&amp;period=$settings->period&amp;site=$settings->siteid\">" . numbdisp($nbrresult) . "</a></td>\n";
	echo "<td class='tableau5'><a href=\"index.php?navig=19&amp;period=$settings->period&amp;site=$settings->siteid\">" . numbdisp($nbrresult2) . "</a></td></tr>\n";
	echo "</table></div>\n";
	if ($settings->blockattacks == 1) {
		echo "<h2>" . $language['attack-blocked'] . "</h2>\n";
	} else {
		echo "<h2><span class=\"alert2\">" . $language['attack-no-blocked'] . "</span></h2>\n";
	}
	if ($settings->period != 5) {
		//graph
		echo "<div class='graphvisits' >\n";
		//mapgraph
		include "include/mapgraph.php";
		echo "<img src=\"./graphs/visit-graph.php?crawltlang=$settings->language&amp;period=$settings->period&amp;navig=$settings->navig&amp;graphname=$graphname\" USEMAP=\"#visit\" alt=\"graph\" border=\"0\">\n";
		echo "</div>\n";
		echo "<div class='imprimgraph'>\n";
		echo "&nbsp;<br><br><br><br><br><br><br><br></div>\n";
	}
	echo "<p align='center'>*" . $language['404_no_in_graph2'] . "</p>\n";
	echo "<div><br>\n";
} else
//case no visits
{
	echo "<div class=\"content2\"><br><hr>\n";
	echo "</div>\n";
	echo "<div class='tableaularge' align='center'>\n";
	echo "<h1>" . $language['no_hacking'] . "</h1>\n";
	echo "<br>\n";
}
$db->close(); // Close database
?>
