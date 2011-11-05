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
// file: display-one-entrypage.php
//----------------------------------------------------------------------
//  Last update: 05/11/2011
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$visitkeywordgoogle = array();
$visitkeywordgoogleimage = array();
$visitkeywordYahoo = array();
$visitkeywordMSN = array();
$visitkeywordask = array();
$visitkeywordexalead = array();
$visitkeyword = array();
$visitkeyworddisplay = array();
$crawlencode = urlencode($crawler);

$cachename = $navig . $period . $site . $order.$rowdisplay . $crawlencode . $displayall . $firstdayweek . $localday . $graphpos . $crawltlang;

//start the caching
cache($cachename);
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//include menu
include ("include/menusite.php");
include ("include/menumain.php");
include ("include/timecache.php");
//clean table from crawler entry
include ("include/cleaning-crawler-entry.php");
//limite to
if ($displayall == 'no') {
	$limitquery = 'LIMIT ' . $rowdisplay;
} else {
	$limitquery = '';
}
//date for the mysql query
if ($period >= 10) {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "' 
    AND  date <'" . sql_quote($daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . sql_quote($daterequest) . "'";
}
$crawlerd= htmlspecialchars_decode($crawler);
//request to have the keyword for Google
$sqlgoogle = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
INNER JOIN crawlt_keyword 
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE  $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_pages.url_page='" . sql_quote($crawlerd) . "' 
AND crawlt_id_crawler= '1' 
GROUP BY keyword";
$requetegoogle = db_query($sqlgoogle, $connexion);
$nbrresultgoogle = mysql_num_rows($requetegoogle);
if ($nbrresultgoogle >= 1) {
	while ($ligne = mysql_fetch_row($requetegoogle)) {
		$visitkeywordgoogle[$ligne[0]] = $ligne[1];
	}
}


//query to get google  details position
$sqlgoogle2 = "SELECT  referer, keyword
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
LEFT OUTER JOIN crawlt_referer
ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
WHERE  $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_pages.url_page='" . sql_quote($crawlerd) . "' 
AND crawlt_id_crawler= '1'";
$requetegoogle2 = db_query($sqlgoogle2, $connexion);
$nbrresult = mysql_num_rows($requetegoogle2);
if ($nbrresult >= 1) {
	while ($ligne = mysql_fetch_row($requetegoogle2)) {
		$referertreatment = parse_url($ligne[0]);
		parse_str($referertreatment['query'], $tabvar);
		if (isset($tabvar['cd'])) {
			if (isset($positioncd[$ligne[1]])) {
				if ($tabvar['cd'] < $positioncd[$ligne[1]]) {
					$positioncd[$ligne[1]] = $tabvar['cd'];
				}
			} else {
				$positioncd[$ligne[1]] = $tabvar['cd'];
			}
		} elseif (isset($tabvar['start'])) {
			if (isset($positionstart[$ligne[1]])) {
				if ($tabvar['start'] < $positionstart[$ligne[1]]) {
					$positionstart[$ligne[1]] = $tabvar['start'];
				}
			} else {
				$positionstart[$ligne[1]] = $tabvar['start'];
			}
		}
	}
	foreach ($visitkeywordgoogle as $key => $value) {
		if (isset($positioncd[$key]) && !isset($positionstart[$key])) {
			$position[$key] = $positioncd[$key];
		} elseif (!isset($positioncd[$key]) && isset($positionstart[$key])) {
			$position[$key] = $positionstart[$key] . " &#8804; ? &#8804; " . ($positionstart[$key] + 9);
		} elseif (isset($positioncd[$key]) && isset($positionstart[$key])) {
			if ($positioncd[$key] < ($positionstart[$key] + 10)) {
				$position[$key] = $positioncd[$key];
			} else {
				$position[$key] = $positionstart[$key] . " &#8804; ? &#8804; " . ($positionstart[$key] + 9);
			}
		}
	}
}
//request to have the keyword for Google-Images
$sqlgoogleimage = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
INNER JOIN crawlt_keyword 
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE  $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_pages.url_page='" . sql_quote($crawlerd) . "' 
AND crawlt_id_crawler= '6' 
GROUP BY keyword";
$requetegoogleimage = db_query($sqlgoogleimage, $connexion);
$nbrresultgoogleimage = mysql_num_rows($requetegoogleimage);
if ($nbrresultgoogleimage >= 1) {
	while ($ligne = mysql_fetch_row($requetegoogleimage)) {
		$visitkeywordgoogleimage[$ligne[0]] = $ligne[1];
	}
}

//request to have the keyword for Yahoo
$sqlYahoo = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE  $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_pages.url_page='" . sql_quote($crawlerd) . "'  
AND crawlt_id_crawler= '2' 
GROUP BY keyword";
$requeteYahoo = db_query($sqlYahoo, $connexion);
$nbrresultYahoo = mysql_num_rows($requeteYahoo);
if ($nbrresultYahoo >= 1) {
	while ($ligne = mysql_fetch_row($requeteYahoo)) {
		$visitkeywordYahoo[$ligne[0]] = $ligne[1];
	}
}
//request to have the keyword for MSN
$sqlMSN = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE  $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_pages.url_page='" . sql_quote($crawlerd) . "'  
AND crawlt_id_crawler= '3' 
GROUP BY keyword";
$requeteMSN = db_query($sqlMSN, $connexion);
$nbrresultMSN = mysql_num_rows($requeteMSN);
if ($nbrresultMSN >= 1) {
	while ($ligne = mysql_fetch_row($requeteMSN)) {
		$visitkeywordMSN[$ligne[0]] = $ligne[1];
	}
}
//request to have the keyword for Ask
$sqlask = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE  $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_pages.url_page='" . sql_quote($crawlerd) . "'  
AND crawlt_id_crawler= '4' 
GROUP BY keyword";
$requeteask = db_query($sqlask, $connexion);
$nbrresultask = mysql_num_rows($requeteask);
if ($nbrresultask >= 1) {
	while ($ligne = mysql_fetch_row($requeteask)) {
		$visitkeywordask[$ligne[0]] = $ligne[1];
	}
}
//request to have the keyword for Exalead
$sqlexalead = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
INNER JOIN crawlt_pages
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE  $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "'
AND crawlt_pages.url_page='" . sql_quote($crawlerd) . "'  
AND crawlt_id_crawler= '5' 
GROUP BY keyword";
$requeteexalead = db_query($sqlexalead, $connexion);
$nbrresultexalead = mysql_num_rows($requeteexalead);
if ($nbrresultexalead >= 1) {
	while ($ligne = mysql_fetch_row($requeteexalead)) {
		$visitkeywordexalead[$ligne[0]] = $ligne[1];
	}
}
//calculation of total number of entry per keyword
$visitkeyword = array();
if ($nbrresultgoogle >= 1) {
	foreach ($visitkeywordgoogle as $key => $value) {
		$visitkeyword[$key] = $value;
	}
}
if ($nbrresultgoogleimage >= 1) {
	foreach ($visitkeywordgoogleimage as $key => $value) {
		$visitkeyword[$key] = @$visitkeyword[$key] + $value;
	}
}
if ($nbrresultYahoo >= 1) {
	foreach ($visitkeywordYahoo as $key => $value) {
		$visitkeyword[$key] = @$visitkeyword[$key] + $value;
	}
}
if ($nbrresultMSN >= 1) {
	foreach ($visitkeywordMSN as $key => $value) {
		$visitkeyword[$key] = @$visitkeyword[$key] + $value;
	}
}
if ($nbrresultask >= 1) {
	foreach ($visitkeywordask as $key => $value) {
		$visitkeyword[$key] = @$visitkeyword[$key] + $value;
	}
}
if ($nbrresultexalead >= 1) {
	foreach ($visitkeywordexalead as $key => $value) {
		$visitkeyword[$key] = @$visitkeyword[$key] + $value;
	}
}
//mysql connexion close
mysql_close($connexion);
arsort($visitkeyword);
//display-------------------------------------------------------------------------------------------------------------
echo "<div class=\"content2\"><br><br><br><br><hr>\n";
echo "</div>\n";
//to close the menu rollover
echo "<div width='100%' height:'5px' onmouseover=\"javascript:montre();\">&nbsp;</div>\n";
echo "<div class='tableaularge' align='center'>\n";
if (count($visitkeyword) >= 1) {
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr><th class='tableau1' colspan=\"2\" rowspan=\"2\">\n";
	echo "" . $language['keyword'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1' rowspan=\"2\">\n";
	echo "" . $language['googleposition'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'colspan=\"6\">\n";
	echo "" . $language['nbr_tot_visit_seo'] . "\n";
	echo "</th></tr>\n";
	echo "<tr>\n";
	echo "<th class='tableau20'>\n";
	echo "" . $language['ask'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau20'>\n";
	echo "" . $language['baidu'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau20'>\n";
	echo "" . $language['google'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau20'>\n";
	echo "" . $language['googleimage'] . "\n";
	echo "</th>\n";	
	echo "<th class='tableau20'>\n";
	echo "" . $language['msn'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau200'>\n";
	echo "" . $language['yahoo'] . "\n";
	echo "</th>\n";
	echo "</tr>\n";
	//counter for alternate color lane
	$comptligne = 2;
	//counter to limite number of datas displayed
	$comptdata = 0;
	foreach ($visitkeyword as $keyword => $value) {
		$crawlencode = urlencode($keyword);
		$keyworddisplay = stripslashes(crawltcutkeyword($keyword, 35));
		if (isset($visitkeywordask[$keyword])) {
			$visitask = $visitkeywordask[$keyword];
		} else {
			$visitask = '-';
		}
		if (isset($visitkeywordgoogle[$keyword])) {
			$visitgoogle = $visitkeywordgoogle[$keyword];
		} else {
			$visitgoogle = '-';
		}
		if (isset($visitkeywordgoogleimage[$keyword])) {
			$visitgoogleimage = $visitkeywordgoogleimage[$keyword];
		} else {
			$visitgoogleimage = '-';
		}		
		if (isset($visitkeywordMSN[$keyword])) {
			$visitmsn = $visitkeywordMSN[$keyword];
		} else {
			$visitmsn = '-';
		}
		if (isset($visitkeywordYahoo[$keyword])) {
			$visityahoo = $visitkeywordYahoo[$keyword];
		} else {
			$visityahoo = '-';
		}
		if (isset($visitkeywordexalead[$keyword])) {
			$visitexalead = $visitkeywordexalead[$keyword];
		} else {
			$visitexalead = '-';
		}
		if (isset($position[$keyword])) {
			$positionkeyword = $position[$keyword];
		} else {
			$positionkeyword = "-";
		}
		//to limit the display to the selected number
		if ($comptdata < $rowdisplay) {
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 9) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo "><a href='index.php?navig=16&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $graphpos . "' >" . $keyworddisplay . "</a></td>\n";
				echo "<td class='tableau6' width=\"8%\"" . crawltkeywordwindow($keyword) . ">\n";
				echo "<a href=\"#\">\n";
				echo " <img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau3' width=\"8%\">" . $positionkeyword . "</td>\n";
				echo "<td class='tableau3' width=\"8%\">" . numbdisp($visitask) . "</td>\n";
				echo "<td class='tableau3' width=\"8%\">" . numbdisp($visitexalead) . "</td>\n";
				echo "<td class='tableau3' width=\"8%\">" . numbdisp($visitgoogle) . "</td>\n";
				echo "<td class='tableau3' width=\"14%\">" . numbdisp($visitgoogleimage) . "</td>\n";				
				echo "<td class='tableau3' width=\"8%\">" . numbdisp($visitmsn) . "</td>\n";
				echo "<td class='tableau5' width=\"8%\">" . numbdisp($visityahoo) . "</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 9) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo "><a href='index.php?navig=16&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $graphpos . "'  >" . $keyworddisplay . "</a></td>\n";
				echo "<td class='tableau60' width=\"8%\"" . crawltkeywordwindow($keyword) . ">\n";
				echo "<a href=\"#\">\n";
				echo " <img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau30' width=\"8%\">" . $positionkeyword . "</td>\n";
				echo "<td class='tableau30' width=\"8%\">" . numbdisp($visitask) . "</td>\n";
				echo "<td class='tableau30' width=\"8%\">" . numbdisp($visitexalead) . "</td>\n";
				echo "<td class='tableau30' width=\"8%\">" . numbdisp($visitgoogle) . "</td>\n";
				echo "<td class='tableau30' width=\"14%\">" . numbdisp($visitgoogleimage) . "</td>\n";				
				echo "<td class='tableau30' width=\"8%\">" . numbdisp($visitmsn) . "</td>\n";
				echo "<td class='tableau50' width=\"8%\">" . numbdisp($visityahoo) . "</td></tr>\n";
			}
			if ($keywordcut == 1) {
				echo "<div id=\"smenu" . ($comptligne + 9) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (270 + (($comptligne - 3) * 25)) . "px; left:20px; background:#fff;\">\n";
				echo "&nbsp;" . stripslashes(htmlentities(utf8_decode(urldecode($keyword)))) . "&nbsp;\n";
				echo "</div>\n";
			}
			$comptligne++;
			if ($displayall == 'no') {
				$comptdata++;
			}
		}
	}
	echo "</table>\n";
	if (count($visitkeyword) >= $rowdisplay && $displayall == 'no') {
		echo "<h2><span class=\"smalltext\">\n";
		printf($language['100_lines'], $rowdisplay);
		echo "<br>\n";
		$crawlencode = urlencode($crawler);
		echo "<a href=\"index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&order=$order&displayall=yes&graphpos=$graphpos\">" . $language['show_all'] . "</a></span></h2>";
	}
	echo "<br>\n";
} else {
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
?>
