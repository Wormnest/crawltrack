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
// file:mapgraph3.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//create table for graph
if ($settings->navig == 23) {
	foreach ($axex as $data) {
		$google1[] = $googlevisitsumary[$data];
		$googleimage1[] = $googleimagevisitsumary[$data];
		$msn1[] = $msnvisitsumary[$data];
		$yahoo1[] = $yahoovisitsumary[$data];
		$ask1[] = $askvisitsumary[$data];
		$exalead1[] = $exaleadvisitsumary[$data];
		$yandex1[] = $yandexvisitsumary[$data];	
		$aol1[] = $aolvisitsumary[$data];
		$referer1[] = $referervisitsumary[$data];
		$direct1[] = $directvisitsumary[$data];
		$unique1[] = $uniquevisitorsumary[$data];
		$datatransfert2[$axexlabel[$data]] = $googlevisitsumary[$data] . "-" . $msnvisitsumary[$data] . "-" . $yahoovisitsumary[$data] . "-" . $askvisitsumary[$data] . "-" . $referervisitsumary[$data] . "-" . $directvisitsumary[$data] . "-" . $exaleadvisitsumary[$data] . "-" . $uniquevisitorsumary[$data]."-".$googleimagevisitsumary[$data]."-".$yandexvisitsumary[$data]."-".$aolvisitsumary[$data];
	}
} else {
	foreach ($axex as $data) {
		$google1[] = $googlevisit[$data];
		$googleimage1[] = $googleimagevisit[$data];
		$msn1[] = $msnvisit[$data];
		$yahoo1[] = $yahoovisit[$data];
		$ask1[] = $askvisit[$data];
		$exalead1[] = $exaleadvisit[$data];
		$yandex1[] = $yandexvisit[$data];
		$aol1[] = $aolvisit[$data];
		$referer1[] = $referervisit[$data];
		$direct1[] = $directvisit[$data];
		$unique1[] = $uniquevisitor[$data];
		$datatransfert2[$axexlabel[$data]] = $googlevisit[$data] . "-" . $msnvisit[$data] . "-" . $yahoovisit[$data] . "-" . $askvisit[$data] . "-" . $referervisit[$data] . "-" . $directvisit[$data] . "-" . $exaleadvisit[$data] . "-" . $uniquevisitor[$data]."-".$googleimagevisit[$data]."-".$yandexvisit[$data]."-".$aolvisit[$data];
	}
}
//prepare data to be transferred to graph file
$datatransferttograph = addslashes(urlencode(serialize($datatransfert2)));

//insert the values in the graph table
$graphname = $typegraph . "-" . $cachename;

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

//map graph
if ($settings->period == 3 || ($settings->period >= 200 && $settings->period < 300)) {
	$widthzone = 67;
	$x2 = 132.3;
	$y = 31;
	$y2 = 211;
} elseif ($settings->period == 2 || ($settings->period >= 100 && $settings->period < 200)) {
	if ($nbday == 28) {
		$widthzone = 28.7;
		$x2 = 94;
		$y = 31;
		$y2 = 211;
	} elseif ($nbday == 29) {
		$widthzone = 27.7;
		$x2 = 93;
		$y = 31;
		$y2 = 211;
	} elseif ($nbday == 30) {
		$widthzone = 26.8;
		$x2 = 92.1;
		$y = 31;
		$y2 = 211;
	} else {
		$widthzone = 26;
		$x2 = 91.3;
		$y = 31;
		$y2 = 211;
	}
} elseif ($settings->period == 0 || $settings->period == 4 || $settings->period >= 1000) {
	$widthzone = 100.75;
	$x2 = 166.05;
	$y = 31;
	$y2 = 211;
} elseif ($settings->period == 1 || ($settings->period >= 300 && $settings->period < 400)) {
	$widthzone = 115.14;
	$x2 = 180.44;
	$y = 31;
	$y2 = 211;
}

echo "<map id=\"seoentry\" name=\"seoentry\">\n";
$iday = 0;
$x = 65.3;
do {
	echo "<area shape=\"rect\" coords=\"" . $x . "," . $y . "," . $x2 . "," . $y2 . "\" onmouseover=\"javascript:montre('smenu" . ($iday + 131) . "');\" onmouseout=\"javascript:montre();\"";
	$dateday = $axex[$iday];
	$settings->periodtogo = $totperiod[$dateday];
	echo "href=\"index.php?navig=$settings->navig&amp;period=$settings->periodtogo&amp;site=$settings->siteid&amp;graphpos=$settings->graphpos\" alt=\"go\">\n";
	$x = $x + $widthzone;
	$x2 = $x2 + $widthzone;
	$iday++;
} while ($iday < $nbday);
echo "</map>\n";
$iday = 0;
do {
	echo "<div id=\"smenu" . ($iday + 131) . "\"  style=\"display:none; padding-left:5px; font-size:11px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:30px; left:-35px; background:#eee; width:970px;\">\n";
	echo $language['nbr_tot_visit_seo'] . ":&nbsp;" . $axexlabel[$axex[$iday]] . "&nbsp;\n";
	echo "&nbsp;" . $language['aol'] . ":&nbsp;" . $aol1[$iday] . "\n";	
	echo "&nbsp;" . $language['ask'] . ":&nbsp;" . $ask1[$iday] . "\n";
	echo "&nbsp;" . $language['baidu'] . ":&nbsp;" . $exalead1[$iday] . "\n";
	echo "&nbsp;" . $language['msn'] . ":&nbsp;" . $msn1[$iday] . "\n";	
	echo "&nbsp;" . $language['google'] . ":&nbsp;" . $google1[$iday] . "\n";
	echo "&nbsp;" . $language['googleimage'] . ":&nbsp;" . $googleimage1[$iday] . "\n";	
	echo "&nbsp;" . $language['yahoo'] . ":&nbsp;" . $yahoo1[$iday] . "&nbsp;\n";
	echo "&nbsp;" . $language['yandex'] . ":&nbsp;" . $yandex1[$iday] . "&nbsp;\n";	
	echo "&nbsp;" . $language['website3'] . ":&nbsp;" . $referer1[$iday] . "&nbsp;\n";
	echo "&nbsp;" . $language['direct'] . ":&nbsp;" . $direct1[$iday] . "\n";
	echo "</div>\n";
	$iday++;
} while ($iday < $nbday);
?>
