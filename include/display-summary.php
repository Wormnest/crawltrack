<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.2
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
// file: display-summary.php
//----------------------------------------------------------------------
//  Last update: 12/11/2011
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$listsite = array();
$urlsite = array();
$listsiteid = array();
$tabtotalvisitor = array();
$tabnbrvisitor = array();
$tabnbrpage = array();
$tabnbrtotpages = array();
$tabnbrtotcrawlers = array();
$tabnbrtotvisits = array();
$tabnbrerrorattack = array();
$values = array();
$googlevisitsumary = array();
$googleimagevisitsumary = array();
$msnvisitsumary = array();
$yahoovisitsumary = array();
$askvisitsumary = array();
$exaleadvisitsumary = array();
$yandexvisitsumary = array();
$referervisitsumary = array();
$directvisitsumary = array();
$uniquevisitorsumary = array();
//cache name
$crawlencode = urlencode($crawler);

$cachename = $navig . $period . $firstdayweek . $localday . $crawltlang;

//start the caching
cache($cachename);
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//date for the mysql query
if ($period >= 10) {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "' 
    AND  date <'" . sql_quote($daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "'";
}
//include menu
include ("include/menumain.php");
//liste site
//initialize array
$listsite = array();
$urlsite = array();
$listidsite = array();
$nbrpagestotal = array();
if ($_SESSION['rightsite'] == 0) {
	//mysql query
	$sqlsite = "SELECT * FROM crawlt_site";
	$requetesite = db_query($sqlsite, $connexion);
	$nbrresult = mysql_num_rows($requetesite);
	if ($nbrresult >= 1) {
		while ($ligne = mysql_fetch_object($requetesite)) {
			$sitename = $ligne->name;
			$siteurl = $ligne->url;
			$siteid = $ligne->id_site;
			$listsite[$siteid] = $sitename;
			$urlsite[$siteid] = $siteurl;
			$listsiteid[] = $siteid;
		}
		//case site 1 not in the base
		if (!in_array($site, $listsiteid)) {
			$site = min($listsiteid);
		}
	}
} else {
	//mysql query
	$site = $_SESSION['rightsite'];
	$sqlsite = "SELECT * FROM crawlt_site
	WHERE id_site='" . sql_quote($site) . "'";
	$requetesite = db_query($sqlsite, $connexion);
	$nbrresult = mysql_num_rows($requetesite);
	if ($nbrresult >= 1) {
		while ($ligne = mysql_fetch_object($requetesite)) {
			$sitename = $ligne->name;
			$siteurl = $ligne->url;
			$siteid = $ligne->id_site;
			$listsite[$siteid] = $sitename;
			$urlsite[$siteid] = $siteurl;
			$listsiteid[] = $siteid;
		}
	}
}
asort($listsite);
include ("include/timecache.php");
//clean table from crawler entry
include ("include/cleaning-crawler-entry.php");
foreach ($listsiteid as $site) {
	//to avoid problem if the url is enter in the database with http://
	if (!preg_match('#^http://#i', $urlsite[$site])) {
		$hostsite = "http://" . $urlsite[$site];
	} else {
		$hostsite = $urlsite[$site];
	}
	//include visitors calculation file
	include ("include/visitors-calculation.php");
	$tabtotalvisitor[$site] = $totalvisitor;
	$tabnbrvisitor[$site] = $nbrvisitor;
	$tabnbrpage[$site] = $nbrpage;
	foreach ($axex as $data) {
		$googlevisitsumary[$data] = $googlevisit[$data] + @$googlevisitsumary[$data];
		$googleimagevisitsumary[$data] = $googleimagevisit[$data] + @$googleimagevisitsumary[$data];		
		$msnvisitsumary[$data] = $msnvisit[$data] + @$msnvisitsumary[$data];
		$yahoovisitsumary[$data] = $yahoovisit[$data] + @$yahoovisitsumary[$data];
		$askvisitsumary[$data] = $askvisit[$data] + @$askvisitsumary[$data];
		$exaleadvisitsumary[$data] = $exaleadvisit[$data] + @$exaleadvisitsumary[$data];
		$yandexvisitsumary[$data] = $yandexvisit[$data] + @$yandexvisitsumary[$data];		
		$referervisitsumary[$data] = $referervisit[$data] + @$referervisitsumary[$data];
		$directvisitsumary[$data] = $directvisit[$data] + @$directvisitsumary[$data];
		$uniquevisitorsumary[$data] = $uniquevisitor[$data] + @$uniquevisitorsumary[$data];
	}
	//initialize crawler & hacking values
	$tabnbrtotpages[$site] = 0;
	$tabnbrtotcrawlers[$site] = 0;
	$tabnbrtotvisits[$site] = 0;
	$tabnbrhacking[$site] = 0;
	$tabnbrerrorattack[$site] = 0;
	//count hacking attemps who give 404 error
	if ($period >= 10) {
		$sql = "SELECT  count 
    FROM crawlt_error
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "' 
    AND  date <'" . sql_quote($daterequest2seo) . "'
    GROUP BY attacktype";
	} else {
		$sql = "SELECT  count 
    FROM crawlt_error
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "'
    GROUP BY attacktype";
	}
	$requete = db_query($sql, $connexion);
	$num_rows = mysql_num_rows($requete);
	if ($num_rows > 0) {
		while ($ligne = mysql_fetch_row($requete)) {
			$tabnbrerrorattack[$site] = $tabnbrerrorattack[$site] + $ligne[0];
		}
	}
}
//Hacking attempts calculation-----------------------------------------------------------------------------------
$sql = "SELECT  COUNT(id_visit), crawlt_site_id_site 
FROM crawlt_visits
WHERE crawlt_crawler_id_crawler IN ('65500','65501')
AND $datetolookfor       
GROUP BY crawlt_site_id_site";
$requete = db_query($sql, $connexion);
while ($ligne = mysql_fetch_row($requete)) {
	$tabnbrhacking[$ligne[1]] = $ligne[0] + $tabnbrerrorattack[$ligne[1]];
}
$sql = "SELECT attacktype, count 
FROM crawlt_error
WHERE  idsite='" . sql_quote($site) . "'
AND  $datetolookfor
GROUP BY attacktype";
//crawler calculation-----------------------------------------------------------------------------------------------
//query to count the total number of  pages viewed ,total number of visits and total number of crawler
$sqlstats2 = "SELECT COUNT(DISTINCT crawlt_pages_id_page), COUNT(DISTINCT crawler_name), COUNT(id_visit) ,crawlt_site_id_site
  FROM crawlt_visits
  INNER JOIN crawlt_crawler
  ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
  AND $datetolookfor         
  GROUP BY crawlt_site_id_site";
$requetestats2 = db_query($sqlstats2, $connexion);
while ($ligne2 = mysql_fetch_row($requetestats2)) {
	$tabnbrtotpages[$ligne2[3]] = $ligne2[0];
	$tabnbrtotcrawlers[$ligne2[3]] = $ligne2[1];
	$tabnbrtotvisits[$ligne2[3]] = $ligne2[2];
}
//graph preparation-----------------------------------------------------------------------------------------------
//count the total number of hits
if ($period >= 10) {
	$sql = "SELECT  SUM(count) 
    FROM crawlt_hits
    WHERE  date >='" . sql_quote($daterequestseo) . "' 
    AND  date <'" . sql_quote($daterequest2seo) . "'";
} else {
	$sql = "SELECT SUM(count)  
    FROM crawlt_hits
    WHERE date >='" . sql_quote($daterequestseo) . "'";
}
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	$ligne = mysql_fetch_row($requete);
	$totalhits = $ligne[0];
} else {
	$totalhits = 0;
}
$values['visitors'] = array_sum($tabnbrpage);
$values['other'] = $totalhits - $values['visitors'];
//prepare data to be transferred to graph file
$datatransferttograph = addslashes(urlencode(serialize($values)));
//insert the values in the graph table
$piegraphname = "charge1-" . $cachename;
//check if this graph already exists in the table
$sql = "SELECT name  FROM crawlt_graph
            WHERE name= '" . sql_quote($piegraphname) . "'";
$requete = db_query($sql, $connexion);
$nbrresult = mysql_num_rows($requete);
if ($nbrresult >= 1) {
	$sql2 = "UPDATE crawlt_graph SET graph_values='" . sql_quote($datatransferttograph) . "'
              WHERE name= '" . sql_quote($piegraphname) . "'";
} else {
	$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . sql_quote($piegraphname) . "','" . sql_quote($datatransferttograph) . "')";
}
$requete2 = db_query($sql2, $connexion);
//Evolution calculation------------------------------------------------------------------------------
//query to get unique visitor for the last 30 days
$datelocal2 = date("Y-m-d", (strtotime("today") - ($times * 3600)));
$daterequestUV = date("Y-m-d", (strtotime($datelocal2) - 604800));
$daterequestUV2 = date("Y-m-d", (strtotime($datelocal2) - 2592000));
$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y'), count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
LEFT OUTER JOIN crawlt_referer
ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
WHERE  (date >='" . crawlt_sql_quote($daterequestUV2) . "'
AND date <'" . crawlt_sql_quote($datelocal2) . "'
AND  crawlt_id_crawler='0'
AND  crawlt_id_referer='0')
OR (date >='" . crawlt_sql_quote($daterequestUV2) . "' 
AND date <'" . crawlt_sql_quote($datelocal2) . "' 
AND  crawlt_id_crawler IN ('1','2','3','4','5','6','7'))
OR (date >='" . crawlt_sql_quote($daterequestUV2) . "' 
AND date <'" . crawlt_sql_quote($datelocal2) . "'  
AND  crawlt_id_crawler='0'
$notinternalreferercondition
AND referer !='' )
GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y')
ORDER BY date";
$requete = db_query($sql, $connexion);
while ($ligne = mysql_fetch_row($requete)) {
	if (strtotime($ligne[0]) >= strtotime($daterequestUV)) {
		$UVlast7days[] = $ligne[1];
		$UVlast30days[] = $ligne[1];
	} else {
		$UVlast30days[] = $ligne[1];
	}
}
if (count($UVlast7days) > 6) {
	include ("include/regression.php");
	//short term
	$evolutionuniquevisitorST = GetEvol($UVlast7days);
	if ($evolutionuniquevisitorST >= 0) {
		$evolutionuniquevisitorSTD = "<span id='green'>+ " . numbdisp($evolutionuniquevisitorST, 2) . " %</span>";
	} else {
		$evolutionuniquevisitorSTD = "<span id='red'>" . numbdisp($evolutionuniquevisitorST, 2) . " %</span>";
	}
	//long term
	if (count($UVlast30days) > 29) {
		$evolutionuniquevisitorLT = GetEvol($UVlast30days);
		if ($evolutionuniquevisitorLT >= 0) {
			$evolutionuniquevisitorLTD = "<span id='green'>+ " . numbdisp($evolutionuniquevisitorLT, 2) . " %</span>";
		} else {
			$evolutionuniquevisitorLTD = "<span id='red'>" . numbdisp($evolutionuniquevisitorLT, 2) . " %</span>";
		}
	} else {
		$evolutionuniquevisitorLTD = "<span id='green'> N/A </span>";
		$evolutionuniquevisitorLT = 0;
	}
} else {
	$evolutionuniquevisitorSTD = "<span id='green'> N/A </span>";
	$evolutionuniquevisitorLTD = "<span id='green'> N/A </span>";
	$evolutionuniquevisitorST = 0;
	$evolutionuniquevisitorLT = 0;
}
//visits per hour graph calculation========================================================================
if ($period == 0 || $period >= 1000) {
	$nbvisitsgraph = array("0" => "0", "1" => "0", "2" => "0", "3" => "0", "4" => "0", "5" => "0", "6" => "0", "7" => "0", "8" => "0", "9" => "0", "10" => "0", "11" => "0", "12" => "0", "13" => "0", "14" => "0", "15" => "0", "16" => "0", "17" => "0", "18" => "0", "19" => "0", "20" => "0", "21" => "0", "22" => "0", "23" => "0");
	if ($period == 0) {
		//query to count the number of  visits
		$sqlstats = "SELECT  HOUR(date), COUNT(id_visit) FROM crawlt_visits_human
		WHERE  date >'" . sql_quote($daterequest) . "' 
		GROUP BY HOUR(date)";
	} elseif ($period >= 1000) {
		//query to count the number of  visits
		$sqlstats = "SELECT  HOUR(date), COUNT(id_visit) FROM crawlt_visits_human
		WHERE  date >'" . sql_quote($daterequest) . "' 
    		AND  date <'" . sql_quote($daterequest2) . "' 
		GROUP BY HOUR(date)";
	}
	$requetestats = db_query($sqlstats, $connexion);
	while ($ligne = mysql_fetch_row($requetestats)) {
		$hour = $ligne[0] - $times;
		if ($hour < 0) {
			$hour = 24 + $hour;
		}
		if ($hour >= 24) {
			$hour = $hour - 24;
		}
		$nbvisitsgraph[$hour] = $ligne[1];
	}
	//prepare data to be transferred to graph file
	$datatransferttograph = addslashes(urlencode(serialize($nbvisitsgraph)));
	//insert the values in the graph table
	$graphname2 = "visitshours-" . $cachename;
	//check if this graph already exists in the table
	$sql = "SELECT name  FROM crawlt_graph
		WHERE name= '" . sql_quote($graphname2) . "'";
	$requete = db_query($sql, $connexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		$sql2 = "UPDATE crawlt_graph SET graph_values='" . sql_quote($datatransferttograph) . "'
			WHERE name= '" . sql_quote($graphname2) . "'";
	} else {
		$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . sql_quote($graphname2) . "','" . sql_quote($datatransferttograph) . "')";
	}
	$requete2 = db_query($sql2, $connexion);
}
mysql_close($connexion);
//display----------------------------------------------------------------------------------------------------
echo "<div class=\"content2\"><br><hr><br>\n";
echo "<div class='tableaularge2' align='center' onmouseout=\"javascript:montre();\">\n";
//summary table display
echo "<div class='tableaularge' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr><th>&nbsp;</th><th class='tableau1' colspan='3'>\n";
echo "" . $language['visitors'] . "\n";
echo "</th>\n";
echo "<th class='tableau1'  colspan='3'>\n";
echo "" . $language['crawler_name'] . "\n";
echo "</th>\n";
echo "<th class='tableau2' rowspan='2'>\n";
echo "" . $language['hacking2'] . "\n";
echo "</th></tr>\n";
echo "<tr><th class='tableau1' >\n";
echo "" . $language['site_name2'] . "\n";
echo "</th>\n";
echo "<th class='tableau20' >\n";
echo "" . $language['visits'] . "\n";
echo "</th>\n";
echo "<th class='tableau20' >\n";
echo "" . $language['unique_visitors'] . "\n";
echo "</th>\n";
echo "<th class='tableau20'>\n";
echo "" . $language['nbr_pages'] . "\n";
echo "</th>\n";
echo "<th class='tableau20'>\n";
echo "" . $language['nbr_tot_visits'] . "\n";
echo "</th>\n";
echo "<th class='tableau20' >\n";
echo "" . $language['crawler_name'] . "\n";
echo "</th>\n";
echo "<th class='tableau20'>\n";
echo "" . $language['nbr_pages'] . "\n";
echo "</th></tr>\n";
//counter for alternate color lane
$comptligne = 2;
foreach ($listsite as $site => $value) {
	if ($comptligne % 2 == 0) {
		echo "<tr><td class='tableau3g'>&nbsp;&nbsp;<a href='index.php?navig=0&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $graphpos . "' rel='nofollow'>" . $value . "</a></td>\n";
		echo "<td class='tableau3'>" . numbdisp($tabtotalvisitor[$site]) . "</td>\n";
		echo "<td class='tableau3'>" . numbdisp($tabnbrvisitor[$site]) . "</td>\n";
		echo "<td class='tableau3'>" . numbdisp($tabnbrpage[$site]) . "</td>\n";
		echo "<td class='tableau3'>" . numbdisp($tabnbrtotvisits[$site]) . "</td>\n";
		echo "<td class='tableau3'>" . numbdisp($tabnbrtotcrawlers[$site]) . "</td>\n";
		echo "<td class='tableau3'>" . numbdisp($tabnbrtotpages[$site]) . "</td>\n";
		echo "<td class='tableau5'>" . numbdisp($tabnbrhacking[$site]) . "</td></tr>\n";
	} else {
		echo "<tr><td class='tableau30g'>&nbsp;&nbsp;<a href='index.php?navig=0&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $graphpos . "' rel='nofollow'>" . $value . "</a></td>\n";
		echo "<td class='tableau30'>" . numbdisp($tabtotalvisitor[$site]) . "</td>\n";
		echo "<td class='tableau30'>" . numbdisp($tabnbrvisitor[$site]) . "</td>\n";
		echo "<td class='tableau30'>" . numbdisp($tabnbrpage[$site]) . "</td>\n";
		echo "<td class='tableau30'>" . numbdisp($tabnbrtotvisits[$site]) . "</td>\n";
		echo "<td class='tableau30'>" . numbdisp($tabnbrtotcrawlers[$site]) . "</td>\n";
		echo "<td class='tableau30'>" . numbdisp($tabnbrtotpages[$site]) . "</td>\n";
		echo "<td class='tableau50'>" . numbdisp($tabnbrhacking[$site]) . "</td></tr>\n";
	}
	$comptligne++;
}
if ($comptligne % 2 == 0) {
	echo "<tr><td class='tableau3d'>" . $language['total'] . "&nbsp;&nbsp;</td>\n";
	echo "<td class='tableau3'>" . numbdisp(array_sum($tabtotalvisitor)) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp(array_sum($tabnbrvisitor)) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp(array_sum($tabnbrpage)) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp(array_sum($tabnbrtotvisits)) . "</td>\n";
	echo "<td class='tableau3'>-</td>\n";
	echo "<td class='tableau3'>" . numbdisp(array_sum($tabnbrtotpages)) . "</td>\n";
	echo "<td class='tableau5'>" . numbdisp(array_sum($tabnbrhacking)) . "</td></tr>\n";
} else {
	echo "<tr><td class='tableau30d'>" . $language['total'] . "&nbsp;&nbsp;</td>\n";
	echo "<td class='tableau30'>" . numbdisp(array_sum($tabtotalvisitor)) . "</td>\n";
	echo "<td class='tableau30'>" . numbdisp(array_sum($tabnbrvisitor)) . "</td>\n";
	echo "<td class='tableau30'>" . numbdisp(array_sum($tabnbrpage)) . "</td>\n";
	echo "<td class='tableau30'>" . numbdisp(array_sum($tabnbrtotvisits)) . "</td>\n";
	echo "<td class='tableau30'>-</td>\n";
	echo "<td class='tableau30'>" . numbdisp(array_sum($tabnbrtotpages)) . "</td>\n";
	echo "<td class='tableau50'>" . numbdisp(array_sum($tabnbrhacking)) . "</td></tr>\n";
}
echo "</table><br><br>\n";
if ($period != 5 && array_sum($tabtotalvisitor) > 0) {
	//graph
	echo "<div class='graphvisits'>\n";
	echo "<h2>" . $language['visit_summary'] . "</h2>";
	//mapgraph
	$typegraph = 'entry';
	include "include/mapgraph3.php";
	echo "<img src=\"./graphs/seo-graph.php?typegraph=$typegraph&amp;crawltlang=$crawltlang&amp;period=$period&amp;graphname=$graphname\" USEMAP=\"#seoentry\" border=\"0\" alt=\"graph\" >\n";
	echo "&nbsp;<br><br>\n";
	echo "&nbsp;</div><br>\n";
	echo "<div class='imprimgraph'>\n";
	echo "&nbsp;<br><br><br><br></div>\n";
}
//graph hits per hour
if ($period == 0 || $period >= 1000) {
	echo "<br><br><hr><h2>" . $language['hits-per-hour'] . "</h2><br>";
	//graph
	echo "<div class='graphvisits'>\n";
	echo "<img src=\"./graphs/visit-graph.php?crawltlang=$crawltlang&period=$period&navig=$navig&graphname=$graphname2\"  alt=\"graph\" width=\"700\" height=\"300\"  border=\"0\"/>\n";
	echo "</div>\n";
	echo "<div class='imprimgraph'>\n";
	echo "&nbsp;<br><br><br><br><br><br></div><br>\n";
} else {
	echo "<br><br>\n";
}
echo "<hr></div><div class='tableaularge2' align='center'>\n";
echo "<table   cellpadding='0' cellspacing='0' cellmargin='0'>\n";
echo "<tr><td valign='top'>\n";
if (($values['visitors'] + $values['other']) > 0) {
	echo "<div align=\"center\">\n";
	echo "<br><br><b>" . $language['charge'] . "</b><br>";
	echo "<img src=\"./graphs/crawler-graph.php?graphname=$piegraphname&amp;crawltlang=$crawltlang\" alt=\"graph\" style=\"border:0; width:450px; height:220px\">\n";
	echo "<p>" . $language['total'] . " " . numbdisp($totalhits) . " " . $language['nbr_visits'] . "</p><br>";
	echo "</div>\n";
}
echo "</td><td valign='top'>\n";
//unique visitors number tendancy-----------------------------------------------------------------------------------
echo "<div align='center'>\n";
echo "<br><br><b>" . $language['evolution'] . "</b><br>";
echo "<img src=\"./graphs/tendance-graph.php?tendance7=$evolutionuniquevisitorST&amp;tendance30=$evolutionuniquevisitorLT\" alt=\"graph\" style=\"border:0; width:480px; height:220px\">\n";
echo "<p>" . $language['longterm'] . " " . $evolutionuniquevisitorLTD . " " . $language['perday'] . "<br>";
echo $language['shortterm'] . " " . $evolutionuniquevisitorSTD . " " . $language['perday'] . "</p><br>";
echo "</div>\n";
echo "</td></tr></table>\n";
echo "</div></div>\n";
?>
