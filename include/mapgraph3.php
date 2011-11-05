<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.1
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
// file:mapgraph3.php
//----------------------------------------------------------------------
//  Last update: 05/11/2011
//----------------------------------------------------------------------
//create table for graph
if ($navig == 23) {
	foreach ($axex as $data) {
		$google1[] = $googlevisitsumary[$data];
		$googleimage1[] = $googleimagevisitsumary[$data];
		$msn1[] = $msnvisitsumary[$data];
		$yahoo1[] = $yahoovisitsumary[$data];
		$ask1[] = $askvisitsumary[$data];
		$exalead1[] = $exaleadvisitsumary[$data];
		$referer1[] = $referervisitsumary[$data];
		$direct1[] = $directvisitsumary[$data];
		$unique1[] = $uniquevisitorsumary[$data];
		$datatransfert2[$axexlabel[$data]] = $googlevisitsumary[$data] . "-" . $msnvisitsumary[$data] . "-" . $yahoovisitsumary[$data] . "-" . $askvisitsumary[$data] . "-" . $referervisitsumary[$data] . "-" . $directvisitsumary[$data] . "-" . $exaleadvisitsumary[$data] . "-" . $uniquevisitorsumary[$data]."-".$googleimagevisitsumary[$data];
	}
} else {
	foreach ($axex as $data) {
		$google1[] = $googlevisit[$data];
		$googleimage1[] = $googleimagevisit[$data];		
		$msn1[] = $msnvisit[$data];
		$yahoo1[] = $yahoovisit[$data];
		$ask1[] = $askvisit[$data];
		$exalead1[] = $exaleadvisit[$data];
		$referer1[] = $referervisit[$data];
		$direct1[] = $directvisit[$data];
		$unique1[] = $uniquevisitor[$data];
		$datatransfert2[$axexlabel[$data]] = $googlevisit[$data] . "-" . $msnvisit[$data] . "-" . $yahoovisit[$data] . "-" . $askvisit[$data] . "-" . $referervisit[$data] . "-" . $directvisit[$data] . "-" . $exaleadvisit[$data] . "-" . $uniquevisitor[$data]."-".$googleimagevisit[$data];
	}
}
//prepare data to be transferred to graph file
$datatransferttograph = addslashes(urlencode(serialize($datatransfert2)));

//insert the values in the graph table
$graphname = $typegraph . "-" . $cachename;
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
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

//map graph
if ($period == 3 || ($period >= 200 && $period < 300)) {
	$widthzone = 67;
	$x2 = 132.3;
	$y = 31;
	$y2 = 211;
} elseif ($period == 2 || ($period >= 100 && $period < 200)) {
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
} elseif ($period == 0 || $period == 4 || $period >= 1000) {
	$widthzone = 100.75;
	$x2 = 166.05;
	$y = 31;
	$y2 = 211;
} elseif ($period == 1 || ($period >= 300 && $period < 400)) {
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
	$periodtogo = $totperiod[$dateday];
	echo "href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\" alt=\"go\">\n";
	$x = $x + $widthzone;
	$x2 = $x2 + $widthzone;
	$iday++;
} while ($iday < $nbday);
echo "</map>\n";
$iday = 0;
do {
	echo "<div id=\"smenu" . ($iday + 131) . "\"  style=\"display:none; padding-left:10px; font-size:12px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:30px; left:-30px; background:#eee; width:960px;\">\n";
	echo "&nbsp;" . $language['nbr_tot_visit_seo'] . ":&nbsp;" . $axexlabel[$axex[$iday]] . "&nbsp;\n";
	echo "&nbsp;" . $language['ask'] . ":&nbsp;" . $ask1[$iday] . "\n";
	echo "&nbsp;" . $language['baidu'] . ":&nbsp;" . $exalead1[$iday] . "\n";
	echo "&nbsp;" . $language['google'] . ":&nbsp;" . $google1[$iday] . "\n";
	echo "&nbsp;" . $language['googleimage'] . ":&nbsp;" . $googleimage1[$iday] . "\n";	
	echo "&nbsp;" . $language['msn'] . ":&nbsp;" . $msn1[$iday] . "\n";
	echo "&nbsp;" . $language['yahoo'] . ":&nbsp;" . $yahoo1[$iday] . "&nbsp;\n";
	echo "&nbsp;" . $language['website3'] . ":&nbsp;" . $referer1[$iday] . "&nbsp;\n";
	echo "&nbsp;" . $language['direct'] . ":&nbsp;" . $direct1[$iday] . "&nbsp;\n";
	echo "</div>\n";
	$iday++;
} while ($iday < $nbday);
?>
