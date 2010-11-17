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
// file:mapgraph2.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
//initialize array
$axexlabel = array();
$axex = array();
$googlevisit = array();
$yahoovisit = array();
$msnvisit = array();
$askvisit = array();
$yahoozerolink = array();
$msnzerolink = array();
$yahoozeropage = array();
$msnzeropage = array();
$deliciouszerolink = array();
$yahoolink = array();
$yahoopage = array();
$msnlink = array();
$msnpage = array();
$deliciouslink = array();
$exaleadlink = array();
$exaleadpage = array();
$googlelink = array();
$googlepage = array();
$exalead = array();
$exalead2 = array();
$yahoo = array();
$msn = array();
$datatransfert1 = array();
$google1 = array();
$msn1 = array();
$yahoo1 = array();
$ask1 = array();
$datatransfert2 = array();
$msn2 = array();
$yahoo2 = array();
$datatransfert3 = array();
$delicious = array();
$datatransfert4 = array();

//prepare X axis label

//number of days (or months) for the period
$nbday2 = 0;
$date = $datebeginlocalcut[0];
if ($period == 0 || $period >= 1000) {
	$nbday = 8;
	$daterequestseo = date("Y-m-d H:i:s", (strtotime($daterequest) - 604800));
	$datebeginlocalseo = date("Y-m-d H:i:s", (strtotime($datebeginlocal) - 604800));
	$datebeginlocalcutseo = explode(' ', $datebeginlocalseo);
	$date = $datebeginlocalcutseo[0];
} elseif ($period == 1 || ($period >= 300 && $period < 400)) {
	$nbday = 7;
	$daterequestseo = $daterequest;
} elseif ($period == 2 || ($period >= 100 && $period < 200)) {
	$nbday = date("t", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequestseo = $daterequest;
} elseif ($period == 3 || ($period >= 200 && $period < 300)) {
	$nbday = 12;
	$daterequestseo = $daterequest;
} elseif ($period == 4) {
	$nbday = 8;
	$daterequestseo = $daterequest;
}
do {
	$date2 = $date;
	$date20 = explode('-', $date);
	$yeardate = $date20[0];
	$monthdate = $date20[1];
	$daydate = $date20[2];
	if ($nbday == 7) {
		if ($firstdayweek == 'Monday') {
			$day = "day" . $nbday2;
		} else {
			//case first week day is sunday
			$nbday3 = $nbday2 + 6;
			if ($nbday3 > 6) {
				$nbday3 = $nbday3 - 7;
			}
			$day = "day" . $nbday3;
		}
		$axexlabel[$daydate . "-" . $monthdate . "-" . $yeardate] = $language[$day] . " " . $daydate;
		$axex[] = $daydate . "-" . $monthdate . "-" . $yeardate;
		$askvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$googlevisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$msnlink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$msnpage[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$msnvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$yahoolink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$yahoopage[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$yahoovisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$exaleadlink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$exaleadpage[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$googlelink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$googlepage[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$deliciouslink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$datatransfert[$language[$day] . " " . $daydate] = '0-0-0';
		if (nbdayfromtoday($date) == 0) {
			$totperiod[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		} else {
			$totperiod[$daydate . "-" . $monthdate . "-" . $yeardate] = 999 + nbdayfromtoday($date);
		}
	} elseif ($nbday == 12) {
		$actualmonth = date("m");
		$actualyear = date("Y");
		$yearmonth = $monthdate . "/" . $yeardate;
		if ($monthdate >= $actualmonth && $yeardate == $actualyear) {
			$totperiod[$yearmonth] = 2;
		} else {
			$totperiod[$yearmonth] = 99 + ($actualmonth - $monthdate) + (12 * ($actualyear - $yeardate));
		}
		$axexlabel[$yearmonth] = $yearmonth;
		$axex[] = $yearmonth;
		$askvisit[$yearmonth] = 0;
		$googlevisit[$yearmonth] = 0;
		$msnlink[$yearmonth] = 0;
		$msnpage[$yearmonth] = 0;
		$msnvisit[$yearmonth] = 0;
		$yahoolink[$yearmonth] = 0;
		$yahoopage[$yearmonth] = 0;
		$yahoovisit[$yearmonth] = 0;
		$exaleadlink[$yearmonth] = 0;
		$exaleadpage[$yearmonth] = 0;
		$googlelink[$yearmonth] = 0;
		$googlepage[$yearmonth] = 0;
		$deliciouslink[$yearmonth] = 0;
		$datatransfert[$yearmonth] = '0-0-0';
	} else {
		$axexlabel[$daydate . "-" . $monthdate . "-" . $yeardate] = $daydate . "-" . $monthdate . "-" . substr("$yeardate", 2, 4);
		$axex[] = $daydate . "-" . $monthdate . "-" . $yeardate;
		$askvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$googlevisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$msnlink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$msnpage[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$msnvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$yahoolink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$yahoopage[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$yahoovisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$exaleadlink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$exaleadpage[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$googlelink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$googlepage[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$deliciouslink[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		$datatransfert[$daydate . "-" . $monthdate . "-" . substr("$yeardate", 2, 4) ] = '0-0-0';
		if (nbdayfromtoday($date) == 0) {
			$totperiod[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
		} else {
			$totperiod[$daydate . "-" . $monthdate . "-" . $yeardate] = 999 + nbdayfromtoday($date);
		}
	}
	if ($nbday == 12) {
		$monthdate1 = $monthdate + 1;
		$ts = mktime(0, 0, 0, $monthdate1, 15, $yeardate);
	} else {
		$ts = mktime(0, 0, 0, $monthdate, $daydate, $yeardate) + 86400;
	}
	$date = date("Y-m-d", $ts);
	//case change summer time to winter time
	if ($date == $date2) {
		$date = date("Y-m-d", ($ts + 7200));
	}
	$nbday2++;
} while ($nbday2 < $nbday);
//mysql query
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//request to count the number of link, index page and bookmark
if ($period == 3) //case one year
{
	//request to count the number of link, index page and bookmarks
	$sqlseograph = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , MAX(linkyahoo),MAX(pageyahoo),  MAX(pagemsn),  MAX(nbrdelicious), MAX(linkexalead),MAX(pageexalead) , MAX(linkgoogle),MAX(pagegoogle)
		FROM crawlt_seo_position
		WHERE `date` >='" . sql_quote($daterequestseo) . "'
		AND id_site='" . sql_quote($site) . "' 
		GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
	$requeteseograph = db_query($sqlseograph, $connexion);
	while ($ligne = mysql_fetch_row($requeteseograph)) {
		$year = substr($ligne[0], 0, 4);
		$month = substr($ligne[0], 4, 2);
		$yearmonth = $month . "/" . $year;
		$yahoolink[$yearmonth] = $ligne[1];
		$yahoopage[$yearmonth] = $ligne[2];
		$msnpage[$yearmonth] = $ligne[3];
		$deliciouslink[$yearmonth] = $ligne[4];
		$exaleadlink[$yearmonth] = $ligne[5];
		$exaleadpage[$yearmonth] = $ligne[6];
		$googlelink[$yearmonth] = $ligne[7];
		$googlepage[$yearmonth] = $ligne[8];
	}
	mysql_free_result($requeteseograph);
} elseif ($period >= 200 && $period < 300) //case one year back and forward
{
	$sqlseograph = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m') , MAX(linkyahoo),MAX(pageyahoo),MAX(pagemsn),  MAX(nbrdelicious), MAX(linkexalead),MAX(pageexalead)  , MAX(linkgoogle),MAX(pagegoogle)
		FROM crawlt_seo_position
		WHERE `date` >='" . sql_quote($daterequestseo) . "'
		AND `date` <'" . sql_quote($daterequest2) . "'    
		AND id_site='" . sql_quote($site) . "' 
		GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%Y%m')";
	$requeteseograph = db_query($sqlseograph, $connexion);
	while ($ligne = mysql_fetch_row($requeteseograph)) {
		$year = substr($ligne[0], 0, 4);
		$month = substr($ligne[0], 4, 2);
		$yearmonth = $month . "/" . $year;
		$yahoolink[$yearmonth] = $ligne[1];
		$yahoopage[$yearmonth] = $ligne[2];
		$msnpage[$yearmonth] = $ligne[3];
		$deliciouslink[$yearmonth] = $ligne[4];
		$exaleadlink[$yearmonth] = $ligne[5];
		$exaleadpage[$yearmonth] = $ligne[6];
		$googlelink[$yearmonth] = $ligne[7];
		$googlepage[$yearmonth] = $ligne[8];
	}
	mysql_free_result($requeteseograph);
} else {
	if ($period >= 10) {
		$sqlseograph = "SELECT  FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y'), linkyahoo, pageyahoo,  pagemsn,nbrdelicious, linkexalead, pageexalead ,linkgoogle, pagegoogle
			FROM crawlt_seo_position
			WHERE `date` >='" . sql_quote($daterequestseo) . "'
			AND `date` <='" . sql_quote($daterequest2) . "'
			AND id_site='" . sql_quote($site) . "'";
	} else {
		$sqlseograph = "SELECT  FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y'), linkyahoo, pageyahoo, pagemsn, nbrdelicious, linkexalead, pageexalead , linkgoogle, pagegoogle
			FROM crawlt_seo_position
			WHERE `date` >='" . sql_quote($daterequestseo) . "'
			AND id_site='" . sql_quote($site) . "'";
	}
	$requeteseograph = db_query($sqlseograph, $connexion);
	while ($ligne = mysql_fetch_row($requeteseograph)) {
		$yahoolink[$ligne[0]] = $ligne[1];
		$yahoopage[$ligne[0]] = $ligne[2];
		$msnpage[$ligne[0]] = $ligne[3];
		$deliciouslink[$ligne[0]] = $ligne[4];
		$exaleadlink[$ligne[0]] = $ligne[5];
		$exaleadpage[$ligne[0]] = $ligne[6];
		$googlelink[$ligne[0]] = $ligne[7];
		$googlepage[$ligne[0]] = $ligne[8];
	}
}
mysql_free_result($requeteseograph);

//create table for graph
if ($typegraph == 'link') {
	foreach ($axex as $data) {
		$yahoo[] = $yahoolink[$data];
		$exalead[] = $exaleadlink[$data];
		$google[] = $googlelink[$data];
		$datatransfert1[$axexlabel[$data]] = $yahoolink[$data] . "-" . $exaleadlink[$data] . "-" . $googlelink[$data];
	}
	
	//prepare data to be transferred to graph file
	$datatransferttograph = addslashes(urlencode(serialize($datatransfert1)));
} elseif ($typegraph == 'page') {
	foreach ($axex as $data) {
		$exalead2[] = $exaleadpage[$data];
		$yahoo2[] = $yahoopage[$data];
		$msn2[] = $msnpage[$data];
		$google2[] = $googlepage[$data];
		$datatransfert3[$axexlabel[$data]] = $yahoopage[$data] . "-" . $msnpage[$data] . "-" . $exaleadpage[$data] . "-" . $googlepage[$data];
	}
	
	//prepare data to be transferred to graph file
	$datatransferttograph = addslashes(urlencode(serialize($datatransfert3)));
} elseif ($typegraph == 'bookmark') {
	foreach ($axex as $data) {
		$delicious[] = $deliciouslink[$data];
		$datatransfert4[$axexlabel[$data]] = $deliciouslink[$data];
	}
	
	//prepare data to be transferred to graph file
	$datatransferttograph = addslashes(urlencode(serialize($datatransfert4)));
}

//insert the values in the graph table
$graphname = $typegraph . "-" . $cachename;

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
if ($typegraph == 'link') {
	echo "<MAP ID=\"seolink\" NAME=\"seolink\">\n";
	$iday = 0;
	$x = 65.3;
	do {
		echo "<AREA SHAPE=\"RECT\" COORDS=\"" . $x . "," . $y . "," . $x2 . "," . $y2 . "\" onmouseover=\"javascript:montre('smenu" . ($iday + 9) . "');\" onmouseout=\"javascript:montre();\"";
		$dateday = $axex[$iday];
		$periodtogo = $totperiod[$dateday];
		echo "href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\" alt=\"go\">\n";
		$x = $x + $widthzone;
		$x2 = $x2 + $widthzone;
		$iday++;
	} while ($iday < $nbday);
	echo "</MAP>\n";
	$iday = 0;
	do {
		echo "<div id=\"smenu" . ($iday + 9) . "\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";
		echo "&nbsp;" . $language['nbr_tot_link'] . ":&nbsp;" . $axexlabel[$axex[$iday]] . "&nbsp;\n";
		echo "&nbsp;" . $language['google'] . ":&nbsp;" . $google[$iday] . "&nbsp;\n";
		echo "&nbsp;" . $language['exalead'] . ":&nbsp;" . $exalead[$iday] . "&nbsp;\n";
		echo "&nbsp;" . $language['yahoo'] . ":&nbsp;" . $yahoo[$iday] . "&nbsp;\n";
		echo "</div>\n";
		$iday++;
	} while ($iday < $nbday);
} elseif ($typegraph == 'page') {
	echo "<MAP ID=\"seopage\" NAME=\"seopage\">\n";
	$iday = 0;
	$x = 65.3;
	do {
		echo "<AREA SHAPE=\"RECT\" COORDS=\"" . $x . "," . $y . "," . $x2 . "," . $y2 . "\" onmouseover=\"javascript:montre('smenu" . ($iday + 70) . "');\" onmouseout=\"javascript:montre();\"";
		$dateday = $axex[$iday];
		$periodtogo = $totperiod[$dateday];
		echo "href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\" alt=\"go\">\n";
		$x = $x + $widthzone;
		$x2 = $x2 + $widthzone;
		$iday++;
	} while ($iday < $nbday);
	echo "</MAP>\n";
	$iday = 0;
	
	do {
		echo "<div id=\"smenu" . ($iday + 70) . "\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";
		echo "&nbsp;" . $language['nbr_tot_pages_index'] . ":&nbsp;" . $axexlabel[$axex[$iday]] . "&nbsp;\n";
		echo "&nbsp;" . $language['google'] . ":&nbsp;" . $google2[$iday] . "\n";
		echo "&nbsp;" . $language['exalead'] . ":&nbsp;" . $exalead2[$iday] . "\n";
		echo "&nbsp;" . $language['msn'] . ":&nbsp;" . $msn2[$iday] . "\n";
		echo "&nbsp;" . $language['yahoo'] . ":&nbsp;" . $yahoo2[$iday] . "&nbsp;\n";
		echo "</div>\n";
		$iday++;
	} while ($iday < $nbday);
} elseif ($typegraph == 'entry') {
	echo "<MAP ID=\"seoentry\" NAME=\"seoentry\">\n";
	$iday = 0;
	$x = 65.3;
	do {
		echo "<AREA SHAPE=\"RECT\" COORDS=\"" . $x . "," . $y . "," . $x2 . "," . $y2 . "\" onmouseover=\"javascript:montre('smenu" . ($iday + 131) . "');\" onmouseout=\"javascript:montre();\"";
		$dateday = $axex[$iday];
		$periodtogo = $totperiod[$dateday];
		echo "href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\" alt=\"go\">\n";
		$x = $x + $widthzone;
		$x2 = $x2 + $widthzone;
		$iday++;
	} while ($iday < $nbday);
	echo "</MAP>\n";
	$iday = 0;
	
	do {
		echo "<div id=\"smenu" . ($iday + 131) . "\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";
		echo "&nbsp;" . $language['nbr_tot_visit_seo'] . ":&nbsp;" . $axexlabel[$axex[$iday]] . "&nbsp;\n";
		echo "&nbsp;" . $language['ask'] . ":&nbsp;" . $ask1[$iday] . "\n";
		echo "&nbsp;" . $language['google'] . ":&nbsp;" . $google1[$iday] . "\n";
		echo "&nbsp;" . $language['msn'] . ":&nbsp;" . $msn1[$iday] . "\n";
		echo "&nbsp;" . $language['yahoo'] . ":&nbsp;" . $yahoo1[$iday] . "&nbsp;\n";
		echo "</div>\n";
		$iday++;
	} while ($iday < $nbday);
} elseif ($typegraph == 'bookmark') {
	echo "<MAP ID=\"bookmark\" NAME=\"bookmark\">\n";
	$iday = 0;
	$x = 65.3;
	
	do {
		echo "<AREA SHAPE=\"RECT\" COORDS=\"" . $x . "," . $y . "," . $x2 . "," . $y2 . "\" onmouseover=\"javascript:montre('smenu" . ($iday + 192) . "');\" onmouseout=\"javascript:montre();\"";
		$dateday = $axex[$iday];
		$periodtogo = $totperiod[$dateday];
		echo "href=\"index.php?navig=$navig&amp;period=$periodtogo&amp;site=$site&amp;graphpos=$graphpos\" alt=\"go\">\n";
		$x = $x + $widthzone;
		$x2 = $x2 + $widthzone;
		$iday++;
	} while ($iday < $nbday);
	echo "</MAP>\n";
	$iday = 0;
	do {
		echo "<div id=\"smenu" . ($iday + 192) . "\"  style=\"display:none; font-size:13px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:8px; left:300px; background:#eee;\">\n";
		echo "&nbsp;" . $language['nbr_tot_bookmark'] . ":&nbsp;" . $axexlabel[$axex[$iday]] . "&nbsp;\n";
		echo "&nbsp;" . $language['delicious'] . ":&nbsp;" . $delicious[$iday] . "&nbsp;\n";
		echo "</div>\n";
		$iday++;
	} while ($iday < $nbday);
}
?>
