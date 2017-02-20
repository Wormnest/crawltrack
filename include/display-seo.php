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
// file: display-seo.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array and variable
$tablinkgoogle = array();
$tabpagegoogle = array();

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

//request to get the msn and yahoo positions data and the number of Delicious bookmarks and  Delicious keywords
if ($settings->period >= 10) {
	$sqlseo = "SELECT   linkyahoo, pageyahoo, pagemsn, nbrdelicious,tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle FROM crawlt_seo_position
    WHERE  id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
    AND  date >='" . crawlt_sql_quote($db->connexion, $daterequestseo) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2seo) . "'        
    ORDER BY date";
} else {
	$sqlseo = "SELECT  linkyahoo, pageyahoo, pagemsn, nbrdelicious,tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle FROM crawlt_seo_position
    WHERE  id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "' 
    AND  date >='" . crawlt_sql_quote($db->connexion, $daterequestseo) . "'        
    ORDER BY date";
}
$requeteseo = db_query($sqlseo, $db->connexion);
$nbrresult = $requeteseo->num_rows;
if ($nbrresult >= 1) {
	$i = 1;
	while ($ligneseo = $requeteseo->fetch_row()) {
		$tablinkgoogle[] = $ligneseo[7];
		$tabpagegoogle[] = $ligneseo[8];
	}

	//preparation of values for display
	if ($settings->period == 0 || $settings->period >= 1000) {
		$linkgoogle = numbdisp($tablinkgoogle[0]);
		$pagegoogle = numbdisp($tabpagegoogle[0]);
	} else {
		$linkgoogle = numbdisp($tablinkgoogle[0]) . " --> " . numbdisp($tablinkgoogle[($nbrresult - 1) ]);
		$pagegoogle = numbdisp($tabpagegoogle[0]) . " --> " . numbdisp($tabpagegoogle[($nbrresult - 1) ]);
	}
}
// Don't close database here since mapgraph also needs the connection!

//display
echo "<div class=\"content2\"><br><hr>\n";
echo "</div>\n";
//backling and index page table
echo "<div class='tableaularge' align='center'>\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr onmouseover=\"javascript:montre();\">\n";
echo "<th class='tableau10' colspan=\"3\">\n";
echo "" . $language['searchengine'] . "\n";
echo "</th></tr><tr>\n";
echo "<th class='tableau1' width=\"20%\" >\n";
echo "&nbsp;\n";
echo "</th>\n";
echo "<th class='tableau1'  width=\"40%\">\n";
echo "" . $language['nbr_tot_link'] . "\n";
echo "</th>\n";
echo "<th class='tableau2' width=\"40%\">\n";
echo "" . $language['nbr_tot_pages_index'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3g'>&nbsp;&nbsp;&nbsp;<a href=\"http://www.google.com\">" . $language['google'] . "</a>\n";

if (($nbrresult == 0) || ($settings->period == 0 && ($linkgoogle == 0 || $pagegoogle == 0))) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=google&amp;navig=$settings->navig&amp;period=$settings->period&amp;site=$settings->siteid&amp;crawler=$crawlencode&amp;graphpos=$settings->graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
if (($nbrresult == 0) || (($tablinkgoogle[0] == $tablinkgoogle[($nbrresult - 1) ]) && $tablinkgoogle[0] == 0)) {
	echo "<td class='tableau3' >-</td>\n";
} else {
	echo "<td class='tableau3'>" . $linkgoogle . "</td>\n";
}
if (($nbrresult == 0) || (($tabpagegoogle[0] == $tabpagegoogle[($nbrresult - 1) ]) && $tabpagegoogle[0] == 0)) {
	echo "<td class='tableau5'>-</td></tr>\n";
} else {
	echo "<td class='tableau5'>" . $pagegoogle . "</td></tr>\n";
}
echo "</table><br>\n";
echo "</div><br>\n";

if ($settings->period != 5) {
	//graph
	echo "<div class='graphvisits'>\n";
	//mapgraph
	$typegraph = 'link';
	include("include/mapgraph2.php");
	echo "<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&amp;crawltlang=$settings->language&amp;period=$settings->period&amp;graphname=$graphname\" usemap=\"#seolink\" border=\"0\" alt=\"graph\" >\n";
	echo "&nbsp;</div><br>\n";
	echo "<div class='imprimgraph'>\n";
	echo "&nbsp;<br><br><br><br><br><br><br><br><br><br><br><br><br><br></div>\n";
	//graph
	echo "<div class='graphvisits'>\n";
	//mapgraph
	$typegraph = 'page';
	include("include/mapgraph2.php");
	echo "<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&amp;crawltlang=$settings->language&amp;period=$settings->period&amp;graphname=$graphname\" usemap=\"#seopage\" border=\"0\" alt=\"graph\" >\n";
	echo "&nbsp;</div><br>\n";
	echo "<div class='imprimgraph'>\n";
	echo "&nbsp;<br><br><br><br>\n";
} else {
	echo "<div>\n";
}
$db->close(); // Close database
?>
