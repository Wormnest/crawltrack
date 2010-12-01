<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.7
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
// file: display-dashboard.php
//----------------------------------------------------------------------
//  Last update: 23/10/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$count = array();
$countperiod = array();
$linkname = array();
$nbrerrorattack = 0;
$nbrerrorcrawler = 0;
$nbrerrordirect = 0;
$nbrerrorextern = 0;
$nbrerrorintern = 0;
$nbrcss = 0;
$nbrsql = 0;
$tablinkexalead = array();
$tabpageexalead = array();
$tabpagemsn = array();
$tablinkyahoo = array();
$tabpageyahoo = array();
$tablinkdelicious = array();
$tablinkgoogle = array();
$tabpagegoogle = array();
$values2 = array();
$visitgoogle = 0;
$visitmsn = 0;
$visityahoo = 0;
$visitask= 0;
$visitexalead = 0;
$visitbaidu = 0;
$UVlast7days = array();
$UVlast30days = array();
//cache name
$crawlencode = urlencode($crawler);

$cachename = $navig . $period . $site . $order . $crawlencode . $displayall . $firstdayweek . $localday . $graphpos . $crawltlang;

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
include ("include/menusite.php");
include ("include/timecache.php");
//clean table from crawler entry
include ("include/cleaning-crawler-entry.php");
//include visitors calculation file
include ("include/visitors-calculation.php");
if ($totalvisitor > 0) {
	if ($visitsendgoogle > 0) {
		$values2[$language['google']] = $visitsendgoogle;
	}
	if ($visitsendmsn > 0) {
		$values2[$language['msn']] = $visitsendmsn;
	}
	if ($visitsendyahoo > 0) {
		$values2[$language['yahoo']] = $visitsendyahoo;
	}
	if ($visitsendask > 0) {
		$values2[$language['ask']] = $visitsendask;
	}
	if ($visitsendexalead > 0) {
		$values2[$language['baidu']] = $visitsendexalead;
	}
	if ($visitsendother > 0) {
		$values2[$language['website']] = $visitsendother;
	}
	if ($visitdirect > 0) {
		$values2[$language['direct']] = $visitdirect;
	}
	arsort($values2);
} else {
	$values2 = array();
}
//crawler calculation-----------------------------------------------------------------------------------------------
if ($period == 3 || ($period >= 200 && $period < 300) || $period >= 1000 || ($period >= 100 && $period < 200) || ($period >= 300 && $period < 400))
{
	//query to have the number of Crawler visits
	$sql = "SELECT crawler_name, COUNT(id_visit) 
    FROM crawlt_visits
    INNER JOIN crawlt_crawler
    ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
    WHERE  date >='" . sql_quote($daterequestseo) . "'
    AND crawlt_site_id_site='" . sql_quote($site) . "'
    AND crawler_name IN ('GoogleBot','MSN Bot','Slurp Inktomi (Yahoo)','Ask Jeeves/Teoma','Exabot','Baiduspider','Bingbot') 
    GROUP BY  crawler_name";
	$requete = db_query($sql, $connexion);
	while ($ligne = mysql_fetch_row($requete)) {
		if ($ligne[0] == 'GoogleBot') {
			$visitgoogle = $ligne[1];
		} elseif ($ligne[0] == 'MSN Bot' || $ligne[0] == 'Bingbot') {
			$visitmsn = $ligne[1]+$visitmsn;
		} elseif ($ligne[0] == 'Slurp Inktomi (Yahoo)') {
			$visityahoo = $ligne[1];
		} elseif ($ligne[0] == 'Ask Jeeves/Teoma') {
			$visitask = $ligne[1];
		} elseif ($ligne[0] == 'Exabot') {
			$visitexalead = $ligne[1];
		} elseif ($ligne[0] == 'Baiduspider') {
			$visitbaidu = $ligne[1];
		}
	}
} 
 else {
	//query to have the number of Crawler visits
	$sql = "SELECT crawler_name, COUNT( id_visit) 
    FROM crawlt_visits
    INNER JOIN crawlt_crawler
    ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
    WHERE  date >='" . sql_quote($daterequestseo) . "'
    AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'
    AND crawler_name IN ('GoogleBot','MSN Bot','Slurp Inktomi (Yahoo)','Ask Jeeves/Teoma','Exabot','Baiduspider','Bingbot')
    GROUP BY crawler_name";
	$requete = db_query($sql, $connexion);
	while ($ligne = mysql_fetch_row($requete)) {
		if ($ligne[0] == 'GoogleBot') {
			$visitgoogle = $ligne[1];
		} elseif ($ligne[0] == 'MSN Bot' || $ligne[0] == 'Bingbot') {
			$visitmsn = $ligne[1]+$visitmsn;
		} elseif ($ligne[0] == 'Slurp Inktomi (Yahoo)') {
			$visityahoo = $ligne[1];
		} elseif ($ligne[0] == 'Ask Jeeves/Teoma') {
			$visitask = $ligne[1];
		} elseif ($ligne[0] == 'Exabot') {
			$visitexalead = $ligne[1];
		} elseif ($ligne[0] == 'Baiduspider') {
			$visitbaidu = $ligne[1];
		}
	}
}
//query to count the total number of  pages viewed ,total number of visits and total number of crawler
$sqlstats2 = "SELECT COUNT(DISTINCT crawlt_pages_id_page), COUNT(DISTINCT crawler_name), COUNT(id_visit) 
  FROM crawlt_visits
  INNER JOIN crawlt_crawler
  ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
  AND $datetolookfor         
  AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'";
$requetestats2 = db_query($sqlstats2, $connexion);
$ligne2 = mysql_fetch_row($requetestats2);
$nbrtotpages = $ligne2[0];
$nbrtotcrawlers = $ligne2[1];
$nbrtotvisits = $ligne2[2];
//Indexation calculation----------------------------------------------------------------------------------------
//query to get the msn and yahoo positions data and the number of Delicious bookmarks and  Delicious keywords
if ($period >= 10) {
	$sqlseo = "SELECT   linkyahoo, pageyahoo,  pagemsn, nbrdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle
    FROM crawlt_seo_position
    WHERE  id_site='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "' 
    AND  date <'" . sql_quote($daterequest2seo) . "'        
    ORDER BY date";
} else {
	$sqlseo = "SELECT  linkyahoo, pageyahoo,  pagemsn, nbrdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle
    FROM crawlt_seo_position
    WHERE  id_site='" . sql_quote($site) . "' 
    AND  date >='" . sql_quote($daterequestseo) . "'        
    ORDER BY date";
}
$requeteseo = db_query($sqlseo, $connexion);
$nbrresult = mysql_num_rows($requeteseo);
if ($nbrresult >= 1) {
	while ($ligneseo = mysql_fetch_row($requeteseo)) {
		$tablinkyahoo[] = $ligneseo[0];
		$tabpageyahoo[] = $ligneseo[1];
		$tabpagemsn[] = $ligneseo[2];
		$tablinkdelicious[] = $ligneseo[3];
		$tablinkexalead[] = $ligneseo[4];
		$tabpageexalead[] = $ligneseo[5];
		$tablinkgoogle[] = $ligneseo[6];
		$tabpagegoogle[] = $ligneseo[7];
	}
	//preparation of values for display
	if ($period == 0 || $period >= 1000) {
		$linkyahoo = numbdisp($tablinkyahoo[($nbrresult - 1) ]);
		$pageyahoo = numbdisp($tabpageyahoo[($nbrresult - 1) ]);
		$pagemsn = numbdisp($tabpagemsn[($nbrresult - 1) ]);
		$linkdelicious = numbdisp($tablinkdelicious[($nbrresult - 1) ]);
		$linkexalead = numbdisp($tablinkexalead[($nbrresult - 1) ]);
		$pageexalead = numbdisp($tabpageexalead[($nbrresult - 1) ]);
		$linkgoogle = numbdisp($tablinkgoogle[($nbrresult - 1) ]);
		$pagegoogle = numbdisp($tabpagegoogle[($nbrresult - 1) ]);
	} else {
		$linkyahoo = numbdisp($tablinkyahoo[0]) . " --> " . numbdisp($tablinkyahoo[($nbrresult - 1) ]);
		$pageyahoo = numbdisp($tabpageyahoo[0]) . " --> " . numbdisp($tabpageyahoo[($nbrresult - 1) ]);
		$pagemsn = numbdisp($tabpagemsn[0]) . " --> " . numbdisp($tabpagemsn[($nbrresult - 1) ]);
		$linkdelicious = numbdisp($tablinkdelicious[0]) . "-->" . numbdisp($tablinkdelicious[($nbrresult - 1) ]);
		$linkexalead = numbdisp($tablinkexalead[0]) . " --> " . numbdisp($tablinkexalead[($nbrresult - 1) ]);
		$pageexalead = numbdisp($tabpageexalead[0]) . " --> " . numbdisp($tabpageexalead[($nbrresult - 1) ]);
		$linkgoogle = numbdisp($tablinkgoogle[0]) . " --> " . numbdisp($tablinkgoogle[($nbrresult - 1) ]);
		$pagegoogle = numbdisp($tabpagegoogle[0]) . " --> " . numbdisp($tabpagegoogle[($nbrresult - 1) ]);
	}
} else {
	$linkyahoo = 0;
	$pageyahoo = 0;
	$pagemsn = 0;
	$linkdelicious = 0;
	$valueindexationend = 0;
	$valueindexationbeginning = 0;
	$linkexalead = 0;
	$pageexalead = 0;
	$linkgoogle = 0;
	$pagegoogle = 0;
}
//Hacking attempts calculation-----------------------------------------------------------------------------------
$sql = "SELECT crawlt_crawler_id_crawler, COUNT(id_visit) 
FROM crawlt_visits
WHERE crawlt_crawler_id_crawler IN ('65500','65501')
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'
GROUP BY crawlt_crawler_id_crawler";
$requete = db_query($sql, $connexion);
while ($ligne = mysql_fetch_row($requete)) {
	if ($ligne[0] == 65500) {
		$nbrcss = $ligne[1];
	}
	if ($ligne[0] == 65501) {
		$nbrsql = $ligne[1];
	}
}
//download calculation----------------------------------------------------------------------------------------------
//query to have the total since beginning
if ($period >= 10) {
	$sql = "SELECT link, SUM(count) 
    FROM crawlt_download
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date <'" . sql_quote($daterequest2seo) . "'
    GROUP BY link";
} else {
	$sql = "SELECT link, SUM(count) 
    FROM crawlt_download
    WHERE  idsite='" . sql_quote($site) . "'
    GROUP BY link";
}
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	while ($ligne = mysql_fetch_row($requete)) {
		$explodelink = explode('/', $ligne[0]);
		$countexplode = count($explodelink) - 1;
		if ($explodelink[$countexplode] != "") {
			$linkname[$ligne[0]] = $explodelink[$countexplode];
			$count[$linkname[$ligne[0]]] = $ligne[1] + @$count[$linkname[$ligne[0]]];
			$countperiod[$linkname[$ligne[0]]] = 0;
		}
	}
} else {
	$count = array();
	$countperiod = array();
	$linkname = array();
}
//query to have the number for the period
if ($period >= 10) {
	$sql = "SELECT link, count 
    FROM crawlt_download
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "' 
    AND  date <'" . sql_quote($daterequest2seo) . "'";
} else {
	$sql = "SELECT link, count 
    FROM crawlt_download
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "'";
}
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	while ($ligne = mysql_fetch_row($requete)) {
		$explodelink = explode('/', $ligne[0]);
		$countexplode = count($explodelink) - 1;
		$linkname[$ligne[0]] = $explodelink[$countexplode];
		$countperiod[$linkname[$ligne[0]]] = @$countperiod[$linkname[$ligne[0]]] + $ligne[1];
	}
}
arsort($count);
//error 404 calculation------------------------------------------------------------------------------------------------
//attack
if ($period >= 10) {
	$sql = "SELECT attacktype, count 
    FROM crawlt_error
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "' 
    AND  date <'" . sql_quote($daterequest2seo) . "'
    GROUP BY attacktype";
} else {
	$sql = "SELECT attacktype, count 
    FROM crawlt_error
    WHERE  idsite='" . sql_quote($site) . "'
    AND  date >='" . sql_quote($daterequestseo) . "'
    GROUP BY attacktype";
}
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	while ($ligne = mysql_fetch_row($requete)) {
		$nbrerrorattack = $nbrerrorattack + $ligne[1];
		if ($ligne[0] == '65500') {
			$nbrcss = $nbrcss + $ligne[1];
		} elseif ($ligne[0] == '65501') {
			$nbrsql = $nbrsql + $ligne[1];
		}
	}
}
//crawler
$sql = "SELECT  COUNT(id_visit) 
FROM crawlt_visits
WHERE  $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	$ligne = mysql_fetch_row($requete);
	$nbrerrorcrawler = $ligne[0];
}
//visitors external link
$sql = "SELECT COUNT(id_visit) 
FROM crawlt_visits_human
INNER JOIN crawlt_referer
ON  crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
AND $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . sql_quote($site) . "'
AND Substring(referer From 1 For " . $lengthurl . ") != '" . sql_quote($hostsite) . "'
AND crawlt_id_referer !='0'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	$ligne = mysql_fetch_row($requete);
	$nbrerrorextern = $ligne[0];
}
//query to get error from visitor direct
$sql = "SELECT crawlt_id_page FROM crawlt_visits_human
WHERE $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_error='1'
AND crawlt_id_referer=''";
$requete = db_query($sql, $connexion);
$nbrerrordirect = mysql_num_rows($requete);
//query to get error from visitors internal link
$sql = "SELECT COUNT(id_visit) 
FROM crawlt_visits_human
INNER JOIN crawlt_referer
ON  crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
AND $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . sql_quote($site) . "'
AND Substring(referer From 1 For " . $lengthurl . ") = '" . sql_quote($hostsite) . "'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$ligne = mysql_fetch_row($requete);
$nbrerrorintern = $ligne[0];
//graph preparation
//count the total number of hits
if ($period >= 10) {
	$sql = "SELECT  SUM(count) 
    FROM crawlt_hits
    WHERE  date >='" . sql_quote($daterequestseo) . "' 
    AND  date <'" . sql_quote($daterequest2seo) . "'
    AND idsite='" . sql_quote($site) . "'";
} else {
	$sql = "SELECT SUM(count)  
    FROM crawlt_hits
    WHERE date >='" . sql_quote($daterequestseo) . "'
    AND idsite='" . sql_quote($site) . "'";
}
$requete = db_query($sql, $connexion);
$num_rows = mysql_num_rows($requete);
if ($num_rows > 0) {
	$ligne = mysql_fetch_row($requete);
	$totalhits = $ligne[0];
} else {
	$totalhits = 0;
}
if (($nbrpage + $nbrtotvisits + $nbrcss + $nbrsql + $totalhits) > 0) {
	$values['visitors'] = $nbrpage;
	$values['other'] = $totalhits - $nbrpage;
	//prepare data to be transferred to graph file
	$datatransferttograph = addslashes(urlencode(serialize($values)));
	//insert the values in the graph table
	$piegraphname = "charge2-" . $cachename;
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
}
//Evolution calculation------------------------------------------------------------------------------
//query to get unique visitor for the last 30 days
$datelocal2 = date("Y-m-d", (strtotime("today") - ($times * 3600)));
$daterequestUV = date("Y-m-d", (strtotime($datelocal2) - 604800));
$daterequestUV2 = date("Y-m-d", (strtotime($datelocal2) - 2592000));
$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y'), count(DISTINCT crawlt_ip) 
FROM crawlt_visits_human
LEFT OUTER JOIN crawlt_referer
ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
WHERE  (date >='" . crawlt_sql_quote($daterequestUV2) . "'
AND date <'" . crawlt_sql_quote($datelocal2) . "'
AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
AND  crawlt_id_crawler='0'
AND  crawlt_id_referer='0')
OR (date >='" . crawlt_sql_quote($daterequestUV2) . "' 
AND date <'" . crawlt_sql_quote($datelocal2) . "' 
AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
AND  crawlt_id_crawler IN ('1','2','3','4','5'))
OR (date >='" . crawlt_sql_quote($daterequestUV2) . "' 
AND date <'" . crawlt_sql_quote($datelocal2) . "'  
AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
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
		$evolutionuniquevisitorSTD = "<span id='green'>+ " . numbdisp3($evolutionuniquevisitorST) . " %</span>";
	} else {
		$evolutionuniquevisitorSTD = "<span id='red'>" . numbdisp3($evolutionuniquevisitorST) . " %</span>";
	}
	//long term
	if (count($UVlast30days) > 29) {
		$evolutionuniquevisitorLT = GetEvol($UVlast30days);
		if ($evolutionuniquevisitorLT >= 0) {
			$evolutionuniquevisitorLTD = "<span id='green'>+ " . numbdisp3($evolutionuniquevisitorLT) . " %</span>";
		} else {
			$evolutionuniquevisitorLTD = "<span id='red'>" . numbdisp3($evolutionuniquevisitorLT) . " %</span>";
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
mysql_close($connexion);
//display----------------------------------------------------------------------------------------------------
echo "<div class=\"content2\">\n";
echo "<div class='tableaularge2' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0' cellspacing='0' width='100%' style=' border-top:2px solid #003399;'>\n";
echo "<tr><td id='dashboard1' width='50%'>\n";
//visitors------------------------------------------------------------------------------------------------------
echo "&nbsp;&nbsp;<a href=\"index.php?navig=20&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/group.png\" style=\"border:0; width:16px; height:16px\" alt=\"" . $language['visitors'] . "\">&nbsp;&nbsp;<b>" . $language['visitors'] . "</b></a><br><br>\n";
//summary table display
echo "<div class='tableaunarrow' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr><th class='tableau1' >\n";
echo "" . $language['visits'] . "\n";
echo "</th>\n";
echo "<th class='tableau1' >\n";
echo "" . $language['unique_visitors'] . "\n";
echo "</th>\n";
echo "<th class='tableau1'>\n";
echo "" . $language['nbr_pages'] . "\n";
echo "</th>\n";
echo "<th class='tableau2'>\n";
echo "" . $language['bounce_rate'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3'>" . numbdisp($totalvisitor) . "</td>\n";
echo "<td class='tableau3'>" . numbdisp($nbrvisitor) . "</td>\n";
echo "<td class='tableau3'>" . numbdisp($nbrpage) . "</td>\n";
if ($nbrvisitor > 0) {
	$bouncerate = numbdisp2(($onepage / $nbrvisitor) * 100) . " %";
} else {
	$bouncerate = "N/A";
}
echo "<td class='tableau5'>" . $bouncerate . "</td></tr>\n";
echo "</table><br>\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr onmouseover=\"javascript:montre();\">\n";
echo "<th class='tableau1' >\n";
echo "" . $language['referer'] . "\n";
echo "</th>\n";
echo "<th class='tableau2' >\n";
echo "" . $language['visits'] . "\n";
echo "</th></tr>\n";
//counter for alternate color lane
$comptligne = 2;
foreach ($values2 as $key => $value) {
	if ($comptligne % 2 == 0) {
		echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . $key . "</td>\n";
		echo "<td class='tableau5'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp2(($value / $totalvisitor) * 100) . "%)</td></tr>\n";
	} else {
		echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . $key . "</td>\n";
		echo "<td class='tableau50'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp2(($value / $totalvisitor) * 100) . "%)</td></tr>\n";
	}
	$comptligne++;
}
echo "</table></div><br>\n";
echo "</td>\n";
echo "<td id='dashboard2'>\n";
//crawlers-------------------------------------------------------------------------------------------------------
echo "&nbsp;&nbsp;<a href=\"index.php?navig=1&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/bug.png\" style=\"border:0; width:16px; height:16px\" alt=\"" . $language['crawler_name'] . "\">&nbsp;&nbsp;<b>" . $language['crawler_name'] . "</b></a><br><br>\n";
//summary table display
echo "<div class='tableaunarrow' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr><th class='tableau1' >\n";
echo "" . $language['nbr_tot_crawlers'] . "\n";
echo "</th>\n";
echo "<th class='tableau1'>\n";
echo "" . $language['nbr_tot_visits'] . "\n";
echo "</th>\n";
echo "<th class='tableau2'>\n";
echo "" . $language['nbr_pages'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3'>" . numbdisp($nbrtotcrawlers) . "</td>\n";
echo "<td class='tableau3'>" . numbdisp($nbrtotvisits) . "</td>\n";
echo "<td class='tableau5'>" . numbdisp($nbrtotpages) . "</td></tr>\n";
echo "</table><br>\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr onmouseover=\"javascript:montre();\">\n";
echo "<th class='tableau1' >\n";
echo "" . $language['main_crawlers'] . "\n";
echo "</th>\n";
echo "<th class='tableau2' >\n";
echo "" . $language['nbr_tot_visits'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3'>Ask Jeeves/Teoma</td>\n";
echo "<td class='tableau5'>" . numbdisp($visitask) . "</td></tr>\n";
echo "<tr><td class='tableau30'>Baiduspider</td>\n";
echo "<td class='tableau50'>" . numbdisp($visitbaidu) . "</td></tr>\n";
echo "<tr><td class='tableau3'>Exabot</td>\n";
echo "<td class='tableau5'>" . numbdisp($visitexalead) . "</td></tr>\n";
echo "<tr><td class='tableau30'>GoogleBot</td>\n";
echo "<td class='tableau50'>" . numbdisp($visitgoogle) . "</td></tr>\n";
echo "<tr><td class='tableau3'>MSN Bot - Bingbot</td>\n";
echo "<td class='tableau5'>" . numbdisp($visitmsn) . "</td></tr>\n";
echo "<tr><td class='tableau30'>Slurp Inktomi (Yahoo)</td>\n";
echo "<td class='tableau50'>" . numbdisp($visityahoo) . "</td></tr>\n";
echo "</table></div><br>\n";
echo "</td></tr><tr>\n";
echo "<td id='dashboard7'>\n";
//unique visitors number tendancy-----------------------------------------------------------------------------------
echo "&nbsp;&nbsp;<img src=\"./images/chart_curve.png\" style=\"border:0; width:16px; height:16px\" alt=\"" . $language['evolution'] . "\">&nbsp;&nbsp;<b>" . $language['evolution'] . "</b><br>";
echo "<img src=\"./graphs/tendance-graph.php?tendance7=$evolutionuniquevisitorST&amp;tendance30=$evolutionuniquevisitorLT\" alt=\"graph\" style=\"border:0; width:480px; height:220px\">\n";
echo "<div id='evolution'>\n";
echo $language['longterm'] . " " . $evolutionuniquevisitorLTD . " " . $language['perday'] . "<br>";
echo $language['shortterm'] . " " . $evolutionuniquevisitorSTD . " " . $language['perday'] . "<br>";
echo "</div>\n";
echo "</td>\n";
echo "<td id='dashboard8'>\n";
//server charge------------------------------------------------------------------------------------------------------
echo "&nbsp;&nbsp;<img src=\"./images/server.png\" style=\"border:0; width:16px; height:16px\" alt=\"" . $language['charge'] . "\">&nbsp;&nbsp;<b>" . $language['charge'] . "</b> ( " . numbdisp($totalhits) . " " . $language['nbr_visits'] . " )<br>";
if (($nbrpage + $nbrtotvisits + $nbrcss + $nbrsql) > 0) {
	echo "<img src=\"./graphs/crawler-graph.php?graphname=$piegraphname&amp;crawltlang=$crawltlang\" alt=\"graph\" style=\"border:0; width:480px; height:220px\">\n";
} else {
	echo "&nbsp;";
}
echo "</td></tr><tr>\n";
echo "<td id='dashboard3'>\n";
//indexation-----------------------------------------------------------------------------------------------------
echo "&nbsp;&nbsp;<a href=\"index.php?navig=11&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/report_magnify.png\" style=\"border:0; width:16px; height:16px\" alt=\"" . $language['index'] . "\">&nbsp;&nbsp;<b>" . $language['index'] . "</b></a><br><br>\n";
//backling and index page table
echo "<div class='tableaunarrow' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr><th class='tableau1' width=\"20%\" >\n";
echo "&nbsp;\n";
echo "</th>\n";
echo "<th class='tableau1'  width=\"40%\">\n";
echo "" . $language['nbr_tot_link'] . "\n";
echo "</th>\n";
echo "<th class='tableau2' width=\"40%\">\n";
echo "" . $language['nbr_tot_pages_index'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . $language['google'] . "\n";
if ($period == 0 && ($linkgoogle == 0 || $pagegoogle == 0)) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=google&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
if ((@$tablinkgoogle[0] == @$tablinkgoogle[($nbrresult - 1) ]) && @$tablinkgoogle[0] == 0) {
	echo "<td class='tableau3' >-</td>\n";
} else {
	echo "<td class='tableau3'>" . $linkgoogle . "</td>\n";
}
if ((@$tabpagegoogle[0] == @$tabpagegoogle[($nbrresult - 1) ]) && @$tabpagegoogle[0] == 0) {
	echo "<td class='tableau5'>-</td></tr>\n";
} else {
	echo "<td class='tableau5'>" . $pagegoogle . "</td></tr>\n";
}
echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . $language['exalead'] . "\n";
if ($period == 0 && ($linkexalead == 0 || $pageexalead == 0)) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=exalead&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
if ((@$tablinkexalead[0] == @$tablinkexalead[($nbrresult - 1) ]) && @$tablinkexalead[0] == 0) {
	echo "<td class='tableau30' >-</td>\n";
} else {
	echo "<td class='tableau30'>" . $linkexalead . "</td>\n";
}
if ((@$tabpageexalead[0] == @$tabpageexalead[($nbrresult - 1) ]) && @$tabpageexalead[0] == 0) {
	echo "<td class='tableau50'>-</td></tr>\n";
} else {
	echo "<td class='tableau50'>" . $pageexalead . "</td></tr>\n";
}
echo "<tr><td class='tableau3g' >&nbsp;&nbsp;" . $language['msn'] . "\n";
if ($period == 0 && ($pagemsn == 0)) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=msn&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
echo "<td class='tableau3' >-</td>\n";
if ((@$tabpagemsn[0] == @$tabpagemsn[($nbrresult - 1) ]) && @$tabpagemsn[0] == 0) {
	echo "<td class='tableau5'>-</td></tr>\n";
} else {
	echo "<td class='tableau5'>" . $pagemsn . "</td></tr>\n";
}
echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . $language['yahoo'] . "\n";
if ($period == 0 && ($linkyahoo == 0 || $pageyahoo == 0)) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=yahoo&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" ></a></td>\n";
} else {
	echo "</td>\n";
}
if ((@$tablinkyahoo[0] == @$tablinkyahoo[($nbrresult - 1) ]) && @$tablinkyahoo[0] == 0) {
	echo "<td class='tableau30' >-</td>\n";
} else {
	echo "<td class='tableau30'>" . $linkyahoo . "</td>\n";
}
if ((@$tabpageyahoo[0] == @$tabpageyahoo[($nbrresult - 1) ]) && @$tabpageyahoo[0] == 0) {
	echo "<td class='tableau50'>-</td></tr>\n";
} else {
	echo "<td class='tableau50'>" . $pageyahoo . "</td></tr>\n";
}
echo "</table><br>\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr onmouseover=\"javascript:montre();\">\n";
echo "<th class='tableau1' width=\"24%\">\n";
echo "&nbsp;\n";
echo "</th>\n";
echo "<th class='tableau2'>\n";
echo "" . $language['nbr_tot_bookmark'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3g' >&nbsp;&nbsp;" . $language['delicious'] . "\n";
if ($period == 0 && $linkdelicious == 0) {
	echo "<a href=\"./php/searchenginespositionrefresh.php?retry=delicious&amp;navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" style=\"border:0; width:16px; height:16px\" alt=\"refresh\"></a></td>\n";
} else {
	echo "</td>\n";
}
if ($linkdelicious == 0) {
	echo "<td class='tableau5' >-</td></tr>\n";
} else {
	echo "<td class='tableau5'>" . $linkdelicious . "</td></tr>\n";
}
echo "</table></div>\n";
//Alexa traffic rank
echo "<br><table   cellpadding='0px' cellspacing='0' width='468px' style=\"margin:auto;\">\n";
echo "<tr><th class='tableau2'>\n";
echo "Alexa\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau5' style=\"padding:0;\">\n";
echo "<iframe name=\"I1\" src=\"php/alexa.php?url=".$urlsite[$site]."\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" border=\"no\"  width=\"468px\" height=\"60px\"></iframe></h2>\n";
echo "</td></tr></table><br>";
echo "</td>\n";
echo "<td id='dashboard4'>\n";
//hacking---------------------------------------------------------------------------------------------------------
echo "&nbsp;&nbsp;<a href=\"index.php?navig=17&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/hacker.png\" style=\"border:0; width:16px; height:16px\" alt=\"" . $language['hacking2'] . "\">&nbsp;&nbsp;<b>" . $language['hacking2'] . "</b></a><br><br>";
//summary table display
echo "<div class='tableaunarrow' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr><th class='tableau1' width='50%'>\n";
echo "" . $language['hacking3'] . "\n";
echo "</th>\n";
echo "<th class='tableau2'>\n";
echo "" . $language['hacking4'] . "\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau3'>" . numbdisp($nbrcss) . "</td>\n";
echo "<td class='tableau5'>" . numbdisp($nbrsql) . "</td></tr>\n";
echo "</table></div>\n";
if ($crawltblockattack == 1 && ($nbrcss + $nbrsql) > 0) {
	echo "<h2>" . $language['attack-blocked'] . "</h2>\n";
} elseif ($crawltblockattack != 1 && ($nbrcss + $nbrsql) > 0) {
	echo "<h2><span class=\"alert2\">" . $language['attack-no-blocked'] . "</span></h2>\n";
}
echo "</td></tr><tr><td id='dashboard5'>\n";
//download---------------------------------------------------------------------------------------------------------
echo "&nbsp;&nbsp;<img src=\"./images/basket_put.png\" style=\"border:0; width:16px; height:16px\" alt=\"" . $language['download'] . "\">&nbsp;&nbsp;<b>" . $language['download'] . "</b><br><br>";
//download table
echo "<div class='tableaunarrow' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr><th class='tableau1' >\n";
echo "" . $language['file'] . "\n";
echo "</th>\n";
echo "<th class='tableau1'>\n";
echo "" . $language['download_period'] . "\n";
echo "</th>\n";
echo "<th class='tableau2'>\n";
echo "" . $language['nbr_tot_visits3'] . "\n";
echo "</th></tr>\n";
//counter for alternate color lane
$comptligne = 2;
foreach ($count as $key => $value) {
	if ($comptligne % 2 == 0) {
		echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . crawltcuturl($key, 30) . "</td>\n";
		echo "<td class='tableau3'>&nbsp;&nbsp;" . numbdisp($countperiod[$key]) . "</td>\n";
		echo "<td class='tableau5'>" . numbdisp($value) . "</td></tr>\n";
	} else {
		echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . crawltcuturl($key, 30) . "</td>\n";
		echo "<td class='tableau30'>&nbsp;&nbsp;" . numbdisp($countperiod[$key]) . "</td>\n";
		echo "<td class='tableau50'>" . numbdisp($value) . "</td></tr>\n";
	}
	$comptligne++;
}
echo "</table></div><br>\n";
echo "</td><td id='dashboard6'>\n";
//error 404---------------------------------------------------------------------------------------------------------
echo "&nbsp;&nbsp;<a href=\"index.php?navig=22&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/error.png\" style=\"border:0; width:16px; height:16px\" alt=\"" . $language['error'] . "\">&nbsp;&nbsp;<b>" . $language['error'] . "</b></a><br><br>";
echo "<div class='tableaunarrow' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
echo "<tr><th class='tableau1' >\n";
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
echo "</table></div><br>\n";
echo "</td></tr></table>\n";
echo "</div>\n";
?>
