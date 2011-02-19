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
// file: display-entrypage.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$visitkeyworddisplay = array();
$visitkeywordgoogle = array();
$visitkeywordYahoo = array();
$visitkeywordMSN = array();
$visitkeywordask = array();
$visitkeywordexalead = array();
$visitkeyword = array();

$cachename = $navig . $period . $site . $order.$rowdisplay . $displayall . $firstdayweek . $localday . $graphpos . $crawltlang;

//start the caching
cache($cachename);
//database connection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
//include menu
include ("include/menumain.php");
include ("include/menusite.php");
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
//query to have the number of entry per page
$sql = "SELECT  url_page, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) AS nbrvisits 
FROM crawlt_visits_human 
INNER JOIN crawlt_pages    
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "' 
AND  crawlt_id_crawler!='0'  
GROUP BY url_page
ORDER BY nbrvisits DESC 
$limitquery";
$requete = db_query($sql, $connexion);
$nbrresult = mysql_num_rows($requete);
if ($nbrresult >= 1) {
	while ($ligne = mysql_fetch_row($requete)) {
		$visitkeyword[$ligne[0]] = $ligne[1];
	}
}
//query to have the keyword for the main bots
$sqlgoogle = "SELECT  url_page, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)), crawlt_id_crawler
FROM crawlt_visits_human
INNER JOIN crawlt_pages    
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE $datetolookfor
AND crawlt_site_id_site='" . sql_quote($site) . "'
AND  crawlt_id_crawler IN ('1','2','3','4','5')    
GROUP BY url_page , crawlt_id_crawler";
$requetegoogle = db_query($sqlgoogle, $connexion);
$nbrresult = mysql_num_rows($requetegoogle);
if ($nbrresult >= 1) {
	while ($ligne = mysql_fetch_row($requetegoogle)) {
		if ($ligne[2] == 1) {
			$visitkeywordgoogle[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 2) {
			$visitkeywordYahoo[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 3) {
			$visitkeywordMSN[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 4) {
			$visitkeywordask[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 5) {
			$visitkeywordexalead[$ligne[0]] = $ligne[1];
		}
	}
}
//mysql connexion close
mysql_close($connexion);
//display-----------------------------------------------------------------------------------------------------------
echo "<div class=\"content2\"><br><hr>\n";
echo "</div>\n";
//to close the menu rollover
echo "<div width='100%' height:'5px' onmouseover=\"javascript:montre();\">&nbsp;</div>\n";
echo "<div class='tableaularge' align='center'>\n";
if (count($visitkeyword) >= 1) {
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr><th class='tableau1' colspan=\"2\" rowspan=\"2\">\n";
	echo "" . $language['entry-page'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'colspan=\"5\">\n";
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
		$keyworddisplay = stripslashes(crawltcutkeyword($keyword, 50));
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
		//to avoid problem if the url is enter in the database with http://
		if (!preg_match('#^http://#i', $urlsite[$site])) {
			$urlpage = "http://" . $urlsite[$site] . $keyword;
		} else {
			$urlpage = $urlsite[$site] . $keyword;
		}
		//to limit the display to the selected number
		if ($comptdata < $rowdisplay) {
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 9) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=14&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $graphpos . "' >" . $keyworddisplay . "</a></td>\n";
				echo "<td class='tableau6' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau3' width=\"11%\">" . numbdisp($visitask) . "</td>\n";
				echo "<td class='tableau3' width=\"11%\">" . numbdisp($visitexalead) . "</td>\n";
				echo "<td class='tableau3' width=\"11%\">" . numbdisp($visitgoogle) . "</td>\n";
				echo "<td class='tableau3' width=\"11%\">" . numbdisp($visitmsn) . "</td>\n";
				echo "<td class='tableau5' width=\"11%\">" . numbdisp($visityahoo) . "</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 9) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=14&amp;period=" . $period . "&amp;site=" . $site . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $graphpos . "'  >" . $keyworddisplay . "</a></td>\n";
				echo "<td class='tableau60' width=\"8%\">\n";
				echo "<a href='" . $urlpage . "'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau30' width=\"11%\">" . numbdisp($visitask) . "</td>\n";
				echo "<td class='tableau30' width=\"11%\">" . numbdisp($visitexalead) . "</td>\n";
				echo "<td class='tableau30' width=\"11%\">" . numbdisp($visitgoogle) . "</td>\n";
				echo "<td class='tableau30' width=\"11%\">" . numbdisp($visitmsn) . "</td>\n";
				echo "<td class='tableau50' width=\"11%\">" . numbdisp($visityahoo) . "</td></tr>\n";
			}
			if ($keywordcut == 1) {
				echo "<div id=\"smenu" . ($comptligne + 9) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (240 + (($comptligne - 3) * 25)) . "px; left:20px; background:#fff;\">\n";
				echo "&nbsp;" . stripslashes(htmlspecialchars(utf8_decode(urldecode($keyword)))) . "&nbsp;\n";
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
