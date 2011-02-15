<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.8
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
// file: display-errors.php
//----------------------------------------------------------------------
//  Last update: 13/02/2011
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$nbrerrorattack = 0;
$nbrerrorcrawler = 0;
$nbrerrordirect = 0;
$nbrerrorextern = 0;
$nbrerrorintern = 0;
$crawlerlist = array();
$nbrvisits = array();
$nbrdirectvisits = array();
$nbrexternvisits = array();
$nbrinternvisits = array();
$comptligne = 0;
$crawlencode = urlencode($crawler);
if ($period >= 1000) //previous days
{
	$cachename = "permanent-" . $navig . "-" . $site . "-" . $crawlencode."-" . $order . "-".$crawltlang . "-".$displayall . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($period >= 100 && $period < 200) //previous month
{
	$cachename = "permanent-month" . $navig . "-" . $site . "-" . $crawlencode."-" . $order . "-".$crawltlang . "-".$displayall . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($period >= 200 && $period < 300) //previous year
{
	$cachename = "permanent-year" . $navig . "-" . $site . "-" . $crawlencode."-" . $order . "-".$crawltlang . "-".$displayall . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $navig . $period . $site . $order . $crawlencode . $displayall . $firstdayweek . $localday . $graphpos . $crawltlang;
}
//start the caching
cache($cachename);
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");
//error 404 calculation------------------------------------------------------------------------------------------------
//date for the mysql query
if ($period >= 10) {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "' 
    AND  date <'" . sql_quote($daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "'";
}
//query to get attack which give an error 404
if ($period >= 10) {
	$sql = "SELECT count 
    FROM crawlt_error
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "' 
    AND  date <'" . sql_quote($daterequest2seo) . "'";
} else {
	$sql = "SELECT count 
    FROM crawlt_error
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "'";
}
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	while ($ligne = mysql_fetch_row($requete)) {
		$nbrerrorattack = $nbrerrorattack + $ligne[0];
	}
}
//query to get error from crawler
$sql = "SELECT   url_page, crawler_name 
FROM crawlt_visits
INNER JOIN crawlt_crawler
ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
INNER JOIN crawlt_pages
ON crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page
WHERE $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	while ($ligne = mysql_fetch_row($requete)) {
		$nbrerrorcrawler++;
		$crawlerlist[$ligne[1]] = $ligne[1];
		${'crawler' . $ligne[1]}[$ligne[0]] = $ligne[0];
		@$nbrvisits[$ligne[1] . "-" . $ligne[0]]++;
	}
}
asort($crawlerlist);
//query to get error from visitor direct
$sql = "SELECT url_page 
FROM crawlt_visits_human
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_error='1'
AND crawlt_id_referer=''";
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	while ($ligne = mysql_fetch_row($requete)) {
		$nbrerrordirect++;
		@$nbrdirectvisits[$ligne[0]]++;
	}
}
arsort($nbrdirectvisits);
$lengthurl = strlen($hostsite);
$sql = "SELECT referer, url_page  
FROM crawlt_visits_human
INNER JOIN crawlt_referer
ON  crawlt_referer.id_referer = crawlt_visits_human.crawlt_id_referer
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . sql_quote($site) . "'
AND Substring(referer From 1 For " . $lengthurl . ") != '" . sql_quote($hostsite) . "'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	while ($ligne = mysql_fetch_row($requete)) {
		$nbrerrorextern++;
		@$nbrexternvisits[$ligne[1]]++;
		${'extern' . $ligne[1]}[$ligne[0]] = $ligne[0];
	}
}
arsort($nbrexternvisits);
//query to get error from visitors internal link
$sql = "SELECT referer, url_page  
FROM crawlt_visits_human
INNER JOIN crawlt_referer
ON  crawlt_referer.id_referer = crawlt_visits_human.crawlt_id_referer
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . sql_quote($site) . "'
AND Substring(referer From 1 For " . $lengthurl . ") = '" . sql_quote($hostsite) . "'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	while ($ligne = mysql_fetch_row($requete)) {
		$nbrerrorintern++;
		@$nbrinternvisits[$ligne[1]]++;
		${'intern' . $ligne[1]}[$ligne[0]] = $ligne[0];
	}
}
//mysql connexion close
mysql_close($connexion);
arsort($nbrinternvisits);
//display-----------------------------------------------------------------------------------------------------
echo "<div class=\"content2\"><br><hr>\n";
echo "</div>\n";
//summary table
echo "<div class='tableaunarrow' align='center'>\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr onmouseover=\"javascript:montre();\">\n";
echo "<th class='tableau1' >\n";
echo "" . $language['origin'] . "\n";
echo "</th>\n";
echo "<th class='tableau2'>\n";
echo "" . $language['number'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . $language['hacking2'] . "</td>\n";
echo "<td class='tableau5'>" . numbdisp($nbrerrorattack) . "</td></tr>\n";
echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . $language['crawler_name'] . "</td>\n";
echo "<td class='tableau50'>" . numbdisp($nbrerrorcrawler) . "</td></tr>\n";
echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . $language['direct'] . "</td>\n";
echo "<td class='tableau5'>" . numbdisp($nbrerrordirect) . "</td></tr>\n";
echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . $language['outer-referer'] . "</td>\n";
echo "<td class='tableau50'>" . numbdisp($nbrerrorextern) . "</td></tr>\n";
echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . $language['inner-referer'] . "</td>\n";
echo "<td class='tableau5'>" . numbdisp($nbrerrorintern) . "</td></tr>\n";
echo "</table><br></div>\n";
echo "<div class='tableaularge' align='center'>\n";
if ($nbrerrorintern > 0) {
	//internal link error table
	echo "<h2>" . $language['intern_error'] . "</h2>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\">\n";
	echo "<th class='tableau1' >\n";
	echo "" . $language['error_page'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2' >\n";
	echo "" . $language['error_referer'] . "\n";
	echo "</th></tr>\n";
	foreach ($nbrinternvisits as $page => $value) {
		echo "<tr><td class='tableau3hg'";
		$pagedisplay = crawltcutkeyword($page, '50');
		if ($keywordcut == 1) {
			echo "onmouseover=\"javascript:montre('smenu" . ($comptligne) . "');\"   onmouseout=\"javascript:montre();\"";
		}
		echo ">&nbsp;&nbsp;" . $pagedisplay . "</td>\n";
		if ($keywordcut == 1) {
			echo "<div id=\"smenu" . ($comptligne) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (250 + (20 * $comptligne)) . "px; left:10px; background:#fff;\">\n";
			echo "&nbsp;" . crawltcuturl($page, '60') . "&nbsp;\n";
			echo "</div>\n";
		}
		echo "<td class='tableau5g'>\n";
		foreach (${'intern' . $page} as $url) {
			echo "&nbsp;&nbsp;<a href='" . $url . "'>" . crawltcutkeyword($url, '50') . "</a><br>\n";
		}
		echo "</td>\n";
		$comptligne++;
	}
	echo "</table><br>\n";
}
if ($nbrerrorextern > 0) {
	$comptligne = $comptligne + 10;
	//internal link error table
	echo "<h2>" . $language['extern_error'] . "</h2>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\">\n";
	echo "<th class='tableau1' >\n";
	echo "" . $language['error_page'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2' >\n";
	echo "" . $language['error_referer'] . "\n";
	echo "</th></tr>\n";
	foreach ($nbrexternvisits as $page => $value) {
		echo "<tr><td class='tableau3hg'";
		$pagedisplay = crawltcutkeyword($page, '50');
		if ($keywordcut == 1) {
			echo "onmouseover=\"javascript:montre('smenu" . ($comptligne) . "');\"   onmouseout=\"javascript:montre();\"";
		}
		echo ">&nbsp;&nbsp;" . $pagedisplay . "</td>\n";
		if ($keywordcut == 1) {
			echo "<div id=\"smenu" . ($comptligne) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (250 + (20 * $comptligne)) . "px; left:10px; background:#fff;\">\n";
			echo "&nbsp;" . crawltcuturl($page, '60') . "&nbsp;\n";
			echo "</div>\n";
		}
		echo "<td class='tableau5g'>\n";
		foreach (${'extern' . $page} as $url) {
			echo "&nbsp;&nbsp;<a href='" . $url . "'>" . crawltcutkeyword($url, '50') . "</a><br>\n";
		}
		echo "</td>\n";
		$comptligne++;
	}
	echo "</table><br>\n";
}
if ($nbrerrordirect > 0) {
	$comptligne = $comptligne + 10;
	//direct visit error table
	echo "<h2>" . $language['direct_error'] . "</h2>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\">\n";
	echo "<th class='tableau2' >\n";
	echo "" . $language['error_page'] . "\n";
	echo "</th></tr>\n";
	foreach ($nbrdirectvisits as $page => $value) {
		echo "<tr><td class='tableau5g'";
		$pagedisplay = crawltcutkeyword($page, '110');
		if ($keywordcut == 1) {
			echo "onmouseover=\"javascript:montre('smenu" . ($comptligne) . "');\"   onmouseout=\"javascript:montre();\"";
		}
		echo ">&nbsp;&nbsp;" . $pagedisplay . "</td>\n";
		if ($keywordcut == 1) {
			echo "<div id=\"smenu" . ($comptligne) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (250 + (20 * $comptligne)) . "px; left:10px; background:#fff;\">\n";
			echo "&nbsp;" . crawltcuturl($page, '60') . "&nbsp;\n";
			echo "</div>\n";
		}
		$comptligne++;
	}
	echo "</table><br>\n";
}
if ($nbrerrorcrawler > 0) {
	//crawler error table
	echo "<h2>" . $language['crawler_error'] . "</h2>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr onmouseover=\"javascript:montre();\">\n";
	echo "<th class='tableau1' >\n";
	echo "" . $language['crawler_name'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2' >\n";
	echo "" . $language['error_page'] . "\n";
	echo "</th></tr>\n";
	foreach ($crawlerlist as $crawler) {
		echo "<tr><td class='tableau3hg'>&nbsp;&nbsp;" . $crawler . "</td>\n";
		echo "<td class='tableau5g'>\n";
		foreach (${'crawler' . $crawler} as $url) {
			echo "&nbsp;&nbsp;" . crawltcutkeyword($url, '105') . "<br>\n";
		}
		echo "</td>\n";
	}
	echo "</table><br>\n";
}
?>
