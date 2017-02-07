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
// file: display-dashboard.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
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
$visitgooglebot = 0;
$visitgoogleimage = 0;
$visitgoogleadsense = 0;
$visitmsn = 0;
$visityahoo = 0;
$visityandex= 0;
$visitexalead = 0;
$visityandex = 0;
$visitbaidu = 0;
$visitaol = 0;
$UVlast7days = array();
$UVlast30days = array();

//cache name
$crawlencode = urlencode($crawler);
$cachename = $navig . $period . $site . $order . $crawlencode . $displayall . $firstdayweek . $localday . $graphpos . $crawltlang;

//start the caching
cache($cachename);

//database connection
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

//date for the mysql query
if ($period >= 10) {
	$datetolookfor = " date >'" . crawlt_sql_quote($connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . crawlt_sql_quote($connexion, $daterequest) . "'";
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
	if ($visitsendgoogleimage > 0) {
		$values2[$language['googleimage']] = $visitsendgoogleimage;
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
	if ($visitsendyandex > 0) {
		$values2[$language['yandex']] = $visitsendyandex;
	}
	if ($visitsendaol > 0) {
		$values2[$language['aol']] = $visitsendaol;
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
    WHERE DATE(crawlt_visits.date) ='" . crawlt_sql_quote($connexion, $daterequestseo) . "'
    AND crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
    AND crawler_name IN ('GoogleBot','MSN Bot','Slurp Inktomi (Yahoo)','YandexBot','Exabot','Baiduspider','Bingbot','Google-Adsense','Google-Image') 
    GROUP BY  crawler_name";
	$requete = db_query($sql, $connexion);
	while ($ligne = $requete->fetch_row()) {
		if ($ligne[0] == 'GoogleBot') {
			$visitgooglebot = $ligne[1];
		} elseif ($ligne[0] == 'MSN Bot' || $ligne[0] == 'Bingbot') {
			$visitmsn = $ligne[1]+$visitmsn;
		} elseif ($ligne[0] == 'Slurp Inktomi (Yahoo)') {
			$visityahoo = $ligne[1];
		} elseif ($ligne[0] == 'YandexBot') {
			$visityandex = $ligne[1];
		} elseif ($ligne[0] == 'Exabot') {
			$visitexalead = $ligne[1];
		} elseif ($ligne[0] == 'Baiduspider') {
			$visitbaidu = $ligne[1];
		} elseif ($ligne[0] == 'Google-Image') {
			$visitgoogleimage = $ligne[1];
		} elseif ($ligne[0] == 'Google-Adsense') {
			$visitgoogleadsense = $ligne[1];
		}
		
	}
	$visitgoogle = $visitgoogleadsense + $visitgoogleimage + $visitgooglebot;
} 
 else {
	//query to have the number of Crawler visits
	$sql = "SELECT crawler_name, COUNT( id_visit) 
    FROM crawlt_visits
    INNER JOIN crawlt_crawler
    ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
    WHERE DATE(crawlt_visits.date) >='" . crawlt_sql_quote($connexion, $daterequestseo) . "'
    AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
    AND crawler_name IN ('GoogleBot','MSN Bot','Slurp Inktomi (Yahoo)','YandexBot','Exabot','Baiduspider','Bingbot','Google-Adsense','Google-Image')
    GROUP BY crawler_name";
	$requete = db_query($sql, $connexion);
	while ($ligne = $requete->fetch_row()) {
		if ($ligne[0] == 'GoogleBot') {
			$visitgooglebot = $ligne[1];
		} elseif ($ligne[0] == 'MSN Bot' || $ligne[0] == 'Bingbot') {
			$visitmsn = $ligne[1]+$visitmsn;
		} elseif ($ligne[0] == 'Slurp Inktomi (Yahoo)') {
			$visityahoo = $ligne[1];
		} elseif ($ligne[0] == 'YandexBot') {
			$visityandex = $ligne[1];
		} elseif ($ligne[0] == 'Exabot') {
			$visitexalead = $ligne[1];
		} elseif ($ligne[0] == 'Baiduspider') {
			$visitbaidu = $ligne[1];
		} elseif ($ligne[0] == 'Google-Image') {
			$visitgoogleimage = $ligne[1];
		} elseif ($ligne[0] == 'Google-Adsense') {
			$visitgoogleadsense = $ligne[1];
		}
	}
$visitgoogle = $visitgoogleadsense + $visitgoogleimage + $visitgooglebot;	
}
//query to count the total number of  pages viewed ,total number of visits and total number of crawler
$sqlstats2 = "SELECT COUNT(DISTINCT crawlt_pages_id_page), COUNT(DISTINCT crawler_name), COUNT(id_visit) 
  FROM crawlt_visits
  INNER JOIN crawlt_crawler
  ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
  AND $datetolookfor         
  AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'";
$requetestats2 = db_query($sqlstats2, $connexion);
$ligne2 = $requetestats2->fetch_row();
$nbrtotpages = $ligne2[0];
$nbrtotcrawlers = $ligne2[1];
$nbrtotvisits = $ligne2[2];
//Indexation calculation----------------------------------------------------------------------------------------
//query to get the msn and yahoo positions data and the number of Delicious bookmarks and  Delicious keywords
if ($period >= 10) {
	$sqlseo = "SELECT   linkyahoo, pageyahoo,  pagemsn, nbrdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle
    FROM crawlt_seo_position
    WHERE  id_site='" . crawlt_sql_quote($connexion, $site) . "'
    AND  date >='" . crawlt_sql_quote($connexion, $daterequestseo) . "' 
    AND  date <'" . crawlt_sql_quote($connexion, $daterequest2seo) . "'        
    ORDER BY date";
} else {
	$sqlseo = "SELECT  linkyahoo, pageyahoo,  pagemsn, nbrdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle
    FROM crawlt_seo_position
    WHERE  id_site='" . crawlt_sql_quote($connexion, $site) . "' 
    AND  date >='" . crawlt_sql_quote($connexion, $daterequestseo) . "'        
    ORDER BY date";
}
$requeteseo = db_query($sqlseo, $connexion);
$nbrresult = $requeteseo->num_rows;
if ($nbrresult >= 1) {
	while ($ligneseo = $requeteseo->fetch_row()) {
		$tablinkgoogle[] = $ligneseo[6];
		$tabpagegoogle[] = $ligneseo[7];
	}
	//preparation of values for display
	if ($period == 0 || $period >= 1000) {
		$linkgoogle = numbdisp($tablinkgoogle[($nbrresult - 1) ]);
		$pagegoogle = numbdisp($tabpagegoogle[($nbrresult - 1) ]);
	} else {
		$linkgoogle = numbdisp($tablinkgoogle[0]) . " --> " . numbdisp($tablinkgoogle[($nbrresult - 1) ]);
		$pagegoogle = numbdisp($tabpagegoogle[0]) . " --> " . numbdisp($tabpagegoogle[($nbrresult - 1) ]);
	}
} else {
	$valueindexationend = 0;
	$valueindexationbeginning = 0;
	$linkgoogle = 0;
	$pagegoogle = 0;
}
//Hacking attempts calculation-----------------------------------------------------------------------------------
$sql = "SELECT crawlt_crawler_id_crawler, COUNT(id_visit) 
FROM crawlt_visits
WHERE crawlt_crawler_id_crawler IN ('65500','65501')
AND $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
GROUP BY crawlt_crawler_id_crawler";
$requete = db_query($sql, $connexion);
while ($ligne = $requete->fetch_row()) {
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
    WHERE  idsite='" . crawlt_sql_quote($connexion, $site) . "'
    AND  date <'" . crawlt_sql_quote($connexion, $daterequest2seo) . "'
    GROUP BY link";
} else {
	$sql = "SELECT link, SUM(count) 
    FROM crawlt_download
    WHERE  idsite='" . crawlt_sql_quote($connexion, $site) . "'
    GROUP BY link";
}
$requete = db_query($sql, $connexion);
$num_rows = $requete->num_rows;
if ($num_rows > 0) {
	while ($ligne = $requete->fetch_row()) {
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
    WHERE  idsite='" . crawlt_sql_quote($connexion, $site) . "'
    AND  date >='" . crawlt_sql_quote($connexion, $daterequestseo) . "' 
    AND  date <'" . crawlt_sql_quote($connexion, $daterequest2seo) . "'";
} else {
	$sql = "SELECT link, count 
    FROM crawlt_download
    WHERE  idsite='" . crawlt_sql_quote($connexion, $site) . "'
    AND  date >='" . crawlt_sql_quote($connexion, $daterequestseo) . "'";
}
$requete = db_query($sql, $connexion);
$num_rows = $requete->num_rows;
if ($num_rows > 0) {
	while ($ligne = $requete->fetch_row()) {
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
    WHERE  idsite='" . crawlt_sql_quote($connexion, $site) . "'
    AND  date >='" . crawlt_sql_quote($connexion, $daterequestseo) . "' 
    AND  date <'" . crawlt_sql_quote($connexion, $daterequest2seo) . "'
    GROUP BY attacktype";
} else {
	$sql = "SELECT attacktype, count 
    FROM crawlt_error
    WHERE  idsite='" . crawlt_sql_quote($connexion, $site) . "'
    AND  date >='" . crawlt_sql_quote($connexion, $daterequestseo) . "'
    GROUP BY attacktype";
}
$requete = db_query($sql, $connexion);
$num_rows = $requete->num_rows;
if ($num_rows > 0) {
	while ($ligne = $requete->fetch_row()) {
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
AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$num_rows = $requete->num_rows;
if ($num_rows > 0) {
	$ligne = $requete->fetch_row();
	$nbrerrorcrawler = $ligne[0];
}
//visitors external link
$sql = "SELECT COUNT(id_visit) 
FROM crawlt_visits_human
INNER JOIN crawlt_referer
ON  crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
AND $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
AND Substring(referer From 1 For " . $lengthurl . ") != '" . crawlt_sql_quote($connexion, $hostsite) . "'
AND crawlt_id_referer !='0'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$num_rows = $requete->num_rows;
if ($num_rows > 0) {
	$ligne = $requete->fetch_row();
	$nbrerrorextern = $ligne[0];
}
//query to get error from visitor direct
$sql = "SELECT crawlt_id_page FROM crawlt_visits_human
WHERE $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
AND crawlt_error='1'
AND crawlt_id_referer=''";
$requete = db_query($sql, $connexion);
$nbrerrordirect = $requete->num_rows;
//query to get error from visitors internal link
$sql = "SELECT COUNT(id_visit) 
FROM crawlt_visits_human
INNER JOIN crawlt_referer
ON  crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
AND $datetolookfor       
AND crawlt_visits_human.crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
AND Substring(referer From 1 For " . $lengthurl . ") = '" . crawlt_sql_quote($connexion, $hostsite) . "'
AND crawlt_error='1'";
$requete = db_query($sql, $connexion);
$ligne = $requete->fetch_row();
$nbrerrorintern = $ligne[0];
//graph preparation
//count the total number of hits
if ($period >= 10) {
	$sql = "SELECT  SUM(count) 
    FROM crawlt_hits
    WHERE  date >='" . crawlt_sql_quote($connexion, $daterequestseo) . "' 
    AND  date <'" . crawlt_sql_quote($connexion, $daterequest2seo) . "'
    AND idsite='" . crawlt_sql_quote($connexion, $site) . "'";
} else {
	$sql = "SELECT SUM(count)  
    FROM crawlt_hits
    WHERE date >='" . crawlt_sql_quote($connexion, $daterequestseo) . "'
    AND idsite='" . crawlt_sql_quote($connexion, $site) . "'";
}
$requete = db_query($sql, $connexion);
$num_rows = $requete->num_rows;
if ($num_rows > 0) {
	$ligne = $requete->fetch_row();
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
              WHERE name= '" . crawlt_sql_quote($connexion, $piegraphname) . "'";
	$requete = db_query($sql, $connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		$sql2 = "UPDATE crawlt_graph SET graph_values='" . crawlt_sql_quote($connexion, $datatransferttograph) . "'
                WHERE name= '" . crawlt_sql_quote($connexion, $piegraphname) . "'";
	} else {
		$sql2 = "INSERT INTO crawlt_graph (name,graph_values) VALUES ( '" . crawlt_sql_quote($connexion, $piegraphname) . "','" . crawlt_sql_quote($connexion, $datatransferttograph) . "')";
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
WHERE  (date >='" . crawlt_sql_quote($connexion, $daterequestUV2) . "'
AND date <'" . crawlt_sql_quote($connexion, $datelocal2) . "'
AND crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
AND  crawlt_id_crawler='0'
AND  crawlt_id_referer='0')
OR (date >='" . crawlt_sql_quote($connexion, $daterequestUV2) . "' 
AND date <'" . crawlt_sql_quote($connexion, $datelocal2) . "' 
AND crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
AND  crawlt_id_crawler IN ('1','2','3','4','5','6','7','8'))
OR (date >='" . crawlt_sql_quote($connexion, $daterequestUV2) . "' 
AND date <'" . crawlt_sql_quote($connexion, $datelocal2) . "'  
AND crawlt_site_id_site='" . crawlt_sql_quote($connexion, $site) . "'
AND  crawlt_id_crawler='0'
$notinternalreferercondition
AND referer !='' )
GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y')
ORDER BY date";
$requete = db_query($sql, $connexion);
while ($ligne = $requete->fetch_row()) {
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
mysqli_close($connexion);

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
	$bouncerate = numbdisp(($onepage / $nbrvisitor) * 100, 1) . " %";
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
		echo "<td class='tableau5'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp(($value / $totalvisitor) * 100, 1) . "%)</td></tr>\n";
	} else {
		echo "<tr><td class='tableau30g'>&nbsp;&nbsp;" . $key . "</td>\n";
		echo "<td class='tableau50'>" . numbdisp($value) . "&nbsp;&nbsp;(" . numbdisp(($value / $totalvisitor) * 100, 1) . "%)</td></tr>\n";
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
echo "<tr><td class='tableau3'>" . $language['baidu'] . "</td>\n";
echo "<td class='tableau5'>" . numbdisp($visitbaidu) . "</td></tr>\n";
echo "<tr><td class='tableau30'>" . $language['msn'] . "</td>\n";
echo "<td class='tableau50'>" . numbdisp($visitmsn) . "</td></tr>\n";
echo "<tr><td class='tableau3'>" . $language['google'] . "</td>\n";
echo "<td class='tableau5'>" . numbdisp($visitgoogle) . "</td></tr>\n";
echo "<tr><td class='tableau30'>" . $language['yahoo'] . "</td>\n";
echo "<td class='tableau50'>" . numbdisp($visityahoo) . "</td></tr>\n";
echo "<tr><td class='tableau3'>" . $language['yandex'] . "</td>\n";
echo "<td class='tableau5'>" . numbdisp($visityandex) . "</td></tr>\n";
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
echo "</table><br>\n";

//Alexa traffic rank
//to avoid problem if the url is enter in the database with http://
$crawlturlsite = strip_protocol($urlsite[$site]);
echo "<br><table   cellpadding='0px' cellspacing='0' width='468px' style=\"margin:auto;\">\n";
echo "<tr><th class='tableau2'>\n";
echo "Alexa\n";
echo "</th></tr>\n";
echo "<tr><td class='tableau5' style=\"padding:0;\">\n";
echo "<iframe name=\"I1\" src=\"php/alexa.php?url=".$crawlturlsite."\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\" border=\"no\"  width=\"468px\" height=\"60px\"></iframe></h2>\n";
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
	if ($comptligne % 2 == 0 && $countperiod[$key]>0) {
		echo "<tr><td class='tableau3gred'>&nbsp;&nbsp;" . crawltcuturl($key, 30) . "</td>\n";
		echo "<td class='tableau3red'>&nbsp;&nbsp;" . numbdisp($countperiod[$key]) . "</td>\n";
		echo "<td class='tableau5red'>" . numbdisp($value) . "</td></tr>\n";
	} elseif ($comptligne % 2 == 0 && $countperiod[$key]==0) {
		echo "<tr><td class='tableau3g'>&nbsp;&nbsp;" . crawltcuturl($key, 30) . "</td>\n";
		echo "<td class='tableau3'>&nbsp;&nbsp;" . numbdisp($countperiod[$key]) . "</td>\n";
		echo "<td class='tableau5'>" . numbdisp($value) . "</td></tr>\n";
	} elseif ($comptligne % 2 != 0 && $countperiod[$key]>0) {
		echo "<tr><td class='tableau30gred'>&nbsp;&nbsp;" . crawltcuturl($key, 30) . "</td>\n";
		echo "<td class='tableau30red'>&nbsp;&nbsp;" . numbdisp($countperiod[$key]) . "</td>\n";
		echo "<td class='tableau50red'>" . numbdisp($value) . "</td></tr>\n";
	}
	else {
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
