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
// file: display-entrypage.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$visitkeyworddisplay = array();
$visitkeywordgoogle = array();
$visitkeywordgoogleimage = array();
$visitkeywordYahoo = array();
$visitkeywordMSN = array();
$visitkeywordask = array();
$visitkeywordexalead = array();
$visitkeywordyandex = array();
$visitkeywordaol = array();
$visitkeyword = array();

//collect post data
if (isset($_POST['choosekeyword'])) {
	$choosekeyword = (int)$_POST['choosekeyword'];
} else {
	$choosekeyword = 0;
}

if($choosekeyword==1)
	{
	if (isset($_POST['askkeyword'])) {
		$askkeyword = (int)$_POST['askkeyword'];
	} else {
		$askkeyword = 0;
	}
	if (isset($_POST['baidukeyword'])) {
		$baidukeyword = (int)$_POST['baidukeyword'];
	} else {
		$baidukeyword = 0;
	}
	if (isset($_POST['googlekeyword'])) {
		$googlekeyword = (int)$_POST['googlekeyword'];
	} else {
		$googlekeyword = 0;
	}
	if (isset($_POST['googleimagekeyword'])) {
		$googleimagekeyword = (int)$_POST['googleimagekeyword'];
	} else {
		$googleimagekeyword = 0;
	}
	if (isset($_POST['msnkeyword'])) {
		$msnkeyword = (int)$_POST['msnkeyword'];
	} else {
		$msnkeyword = 0;
	}
	if (isset($_POST['yahookeyword'])) {
		$yahookeyword = (int)$_POST['yahookeyword'];
	} else {
		$yahookeyword = 0;
	}
	if (isset($_POST['yandexkeyword'])) {
		$yandexkeyword = (int)$_POST['yandexkeyword'];
	} else {
		$yandexkeyword = 0;
	}	
	if (isset($_POST['aolkeyword'])) {
		$aolkeyword = (int)$_POST['aolkeyword'];
	} else {
		$aolkeyword = 0;
	}		
	}
else
	{
	$askkeyword = 1;
	$baidukeyword = 1;
	$googlekeyword = 1;
	$googleimagekeyword = 1;
	$msnkeyword = 1;
	$yahookeyword = 1;
	$yandexkeyword = 1;	
	$aolkeyword = 1;
	}

$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->displayorder.$settings->displayrows . $settings->displayall . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language. $askkeyword. $baidukeyword. $googlekeyword.$googleimagekeyword.$msnkeyword.$yahookeyword.$yandexkeyword.$aolkeyword;

//start the caching
cache($cachename);

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");

//clean table from crawler entry
include ("include/cleaning-crawler-entry.php");

//limite to
if ($settings->displayall == 'no' && $choosekeyword==0) {
	$limitquery = 'LIMIT ' . $settings->displayrows;
} else {
	$limitquery = '';
}
//date for the mysql query
if ($settings->period >= 10) {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "'";
}

//query to have the number of entries per page
$sql = "SELECT  url_page, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) AS nbrvisits 
FROM crawlt_visits_human 
INNER JOIN crawlt_pages    
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "' 
AND  crawlt_id_crawler!='0'  
GROUP BY url_page
ORDER BY nbrvisits DESC 
$limitquery";
$requete = db_query($sql, $db->connexion);
$nbrresult = $requete->num_rows;
if ($nbrresult >= 1) {
	while ($ligne = $requete->fetch_row()) {
		$visitkeyword[$ligne[0]] = $ligne[1];
	}
}

//query to have the keyword for the main bots
$sqlgoogle = "SELECT  url_page, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)), crawlt_id_crawler
FROM crawlt_visits_human
INNER JOIN crawlt_pages    
ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page
WHERE $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND  crawlt_id_crawler IN ('1','2','3','4','5','6','7','8')    
GROUP BY url_page , crawlt_id_crawler";
$requetegoogle = db_query($sqlgoogle, $db->connexion);
$nbrresult = $requetegoogle->num_rows;
if ($nbrresult >= 1) {
	while ($ligne = $requetegoogle->fetch_row()) {
		if ($ligne[2] == 1 && $googlekeyword == 1) {
			$visitkeywordgoogle[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 2 && $yahookeyword == 1) {
			$visitkeywordYahoo[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 3 && $msnkeyword == 1) {
			$visitkeywordMSN[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 4 && $askkeyword == 1) {
			$visitkeywordask[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 5 && $baidukeyword == 1) {
			$visitkeywordexalead[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 6 && $googleimagekeyword == 1) {
			$visitkeywordgoogleimage[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 7 && $yandexkeyword == 1) {
			$visitkeywordyandex[$ligne[0]] = $ligne[1];
		} elseif ($ligne[2] == 8 && $aolkeyword == 1) {
			$visitkeywordaol[$ligne[0]] = $ligne[1];
		}
	}
}
$db->close(); // Close database

//display-----------------------------------------------------------------------------------------------------------
echo "<div class=\"content2\"><br><hr>\n";
echo "</div>\n";

echo "<div width='70%' align='center'><form action=\"index.php\" method=\"POST\"  style=\" font-size:13px; font-weight:bold; color: #003399;
	font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
echo "<input type=\"hidden\" name ='navig' value=\"13\">\n";
echo "<input type=\"hidden\" name ='site' value=\"".$settings->siteid."\">\n";
echo "<input type=\"hidden\" name ='period' value=\"".$settings->period."\">\n";
echo "<input type=\"hidden\" name ='graphpos' value=\"".$settings->graphpos."\">\n";
echo "<input type=\"hidden\" name ='choosekeyword' value=\"1\">\n";
echo "<table>";
if($aolkeyword==1)
	{
	echo "<tr><td>" . $language['aol'] . "</td><td><input type=\"checkbox\" name=\"aolkeyword\" value=\"1\" checked></td>\n";
	}
else
	{
	echo "<tr><td>" . $language['aol'] . "</td><td><input type=\"checkbox\" name=\"aolkeyword\" value=\"1\"></td>\n";
	}			
if($askkeyword==1)
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['ask'] . "</td><td><input type=\"checkbox\" name=\"askkeyword\" value=\"1\" checked></td>\n";
	}
else
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['ask'] . "</td><td><input type=\"checkbox\" name=\"askkeyword\" value=\"1\"></td>\n";
	}
if($baidukeyword==1)
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['baidu'] . "</td><td><input type=\"checkbox\" name=\"baidukeyword\" value=\"1\" checked></td>\n";
	}
else
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['baidu'] . "</td><td><input type=\"checkbox\" name=\"baidukeyword\" value=\"1\"></td>\n";
	}
if($msnkeyword==1)
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['msn'] . "</td><td><input type=\"checkbox\" name=\"msnkeyword\" value=\"1\" checked></td>\n";
	}
else
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['msn'] . "</td><td><input type=\"checkbox\" name=\"msnkeyword\" value=\"1\"></td>\n";
	}	
if($googlekeyword==1)
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['google'] . "</td><td><input type=\"checkbox\" name=\"googlekeyword\" value=\"1\" checked></td>\n";
	}
else
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['google'] . "</td><td><input type=\"checkbox\" name=\"googlekeyword\" value=\"1\"></td>\n";
	}
if($googleimagekeyword==1)
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['googleimage'] . "</td><td><input type=\"checkbox\" name=\"googleimagekeyword\" value=\"1\" checked></td>\n";
	}
else
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['googleimage'] . "</td><td><input type=\"checkbox\" name=\"googleimagekeyword\" value=\"1\"></td>\n";
	}
if($yahookeyword==1)
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['yahoo'] . "</td><td><input type=\"checkbox\" name=\"yahookeyword\" value=\"1\" checked></td>\n";
	}
else
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['yahoo'] . "</td><td><input type=\"checkbox\" name=\"yahookeyword\" value=\"1\"></td>\n";
	}
if($yandexkeyword==1)
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['yandex'] . "</td><td><input type=\"checkbox\" name=\"yandexkeyword\" value=\"1\" checked></td>\n";
	}
else
	{
	echo "<td>&nbsp;&nbsp;&nbsp;" . $language['yandex'] . "</td><td><input type=\"checkbox\" name=\"yandexkeyword\" value=\"1\"></td>\n";
	}	
	echo "<td>&nbsp;&nbsp;&nbsp;<input name='ok' type='submit'  value=' OK ' size='20' ></td></tr>\n";
		
echo "</table></div>\n";

//to close the menu rollover
echo "<div width='100%' height:'5px' onmouseover=\"javascript:montre();\">&nbsp;</div>\n";
echo "<div class='tableaularge' align='center'>\n";
if (count($visitkeyword) >= 1) {
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr><th class='tableau1' colspan=\"2\" rowspan=\"2\">\n";
	echo "" . $language['entry-page'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'colspan=\"8\">\n";
	echo "" . $language['nbr_tot_visit_seo'] . "\n";
	echo "</th></tr>\n";
	echo "<tr>\n";
	echo "<th class='tableau20'>\n";
	echo "" . $language['aol'] . "\n";
	echo "</th>\n";	
	echo "<th class='tableau20'>\n";
	echo "" . $language['ask'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau20'>\n";
	echo "" . $language['baidu'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau20'>\n";
	echo "" . $language['msn'] . "\n";
	echo "</th>\n";	
	echo "<th class='tableau20'>\n";
	echo "" . $language['google'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau20'>\n";
	echo "" . $language['googleimage'] . "\n";
	echo "</th>\n";	
	echo "<th class='tableau20'>\n";
	echo "" . $language['yahoo'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau200'>\n";
	echo "" . $language['yandex'] . "\n";
	echo "</th>\n";	
	echo "</tr>\n";
	//counter for alternate color lane
	$comptligne = 2;
	//counter to limite number of datas displayed
	$comptdata = 0;
	foreach ($visitkeyword as $keyword => $value) {
		$crawlencode = urlencode($keyword);
		$keyworddisplay = stripslashes(crawltcutkeyword($keyword, 60, $settings->useutf8));
		$i=0;
		if (isset($visitkeywordask[$keyword])) {
			$visitask = $visitkeywordask[$keyword];
			$i++;
		} else {
			$visitask = '-';
		}
		if (isset($visitkeywordgoogle[$keyword])) {
			$visitgoogle = $visitkeywordgoogle[$keyword];
			$i++;
		} else {
			$visitgoogle = '-';
		}
		if (isset($visitkeywordgoogleimage[$keyword])) {
			$visitgoogleimage = $visitkeywordgoogleimage[$keyword];
			$i++;
		} else {
			$visitgoogleimage = '-';
		}		
		if (isset($visitkeywordMSN[$keyword])) {
			$visitmsn = $visitkeywordMSN[$keyword];
			$i++;
		} else {
			$visitmsn = '-';
		}
		if (isset($visitkeywordYahoo[$keyword])) {
			$visityahoo = $visitkeywordYahoo[$keyword];
			$i++;
		} else {
			$visityahoo = '-';
		}
		if (isset($visitkeywordexalead[$keyword])) {
			$visitexalead = $visitkeywordexalead[$keyword];
			$i++;
		} else {
			$visitexalead = '-';
		}
		if (isset($visitkeywordyandex[$keyword])) {
			$visityandex = $visitkeywordyandex[$keyword];
			$i++;
		} else {
			$visityandex = '-';
		}	
		if (isset($visitkeywordaol[$keyword])) {
			$visitaol = $visitkeywordaol[$keyword];
			$i++;
		} else {
			$visitaol = '-';
		}
		
		//to avoid problem if the url is enter in the database with http://
		if (!preg_match('#^http://#i', $urlsite[$settings->siteid])) {
			$urlpage = "http://" . $urlsite[$settings->siteid] . $keyword;
		} else {
			$urlpage = $urlsite[$settings->siteid] . $keyword;
		}
		//to limit the display to the selected number
		if ($comptdata < $settings->displayrows && $i>0) {
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 9) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=14&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "' >" . $keyworddisplay . "</a></td>\n";
				echo "<td class='tableau6' width=\"4%\">\n";
				echo "<a href='" . $urlpage . "'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau3' width=\"5%\">" . numbdisp($visitaol) . "</td>\n";				
				echo "<td class='tableau3' width=\"5%\">" . numbdisp($visitask) . "</td>\n";
				echo "<td class='tableau3' width=\"6%\">" . numbdisp($visitexalead) . "</td>\n";
				echo "<td class='tableau3' width=\"6%\">" . numbdisp($visitmsn) . "</td>\n";				
				echo "<td class='tableau3' width=\"7%\">" . numbdisp($visitgoogle) . "</td>\n";
				echo "<td class='tableau3' width=\"13%\">" . numbdisp($visitgoogleimage) . "</td>\n";			
				echo "<td class='tableau3' width=\"6%\">" . numbdisp($visityahoo) . "</td>\n";
				echo "<td class='tableau5' width=\"7%\">" . numbdisp($visityandex) . "</td></tr>\n";				
			} else {
				echo "<tr><td class='tableau30g'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 9) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo ">&nbsp;&nbsp;<a href='index.php?navig=14&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "'  >" . $keyworddisplay . "</a></td>\n";
				echo "<td class='tableau60' width=\"4%\">\n";
				echo "<a href='" . $urlpage . "'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau30' width=\"5%\">" . numbdisp($visitaol) . "</td>\n";				
				echo "<td class='tableau30' width=\"5%\">" . numbdisp($visitask) . "</td>\n";
				echo "<td class='tableau30' width=\"6%\">" . numbdisp($visitexalead) . "</td>\n";
				echo "<td class='tableau30' width=\"6%\">" . numbdisp($visitmsn) . "</td>\n";				
				echo "<td class='tableau30' width=\"7%\">" . numbdisp($visitgoogle) . "</td>\n";
				echo "<td class='tableau30' width=\"13%\">" . numbdisp($visitgoogleimage) . "</td>\n";			
				echo "<td class='tableau30' width=\"6%\">" . numbdisp($visityahoo) . "</td>\n";
				echo "<td class='tableau50' width=\"7%\">" . numbdisp($visityandex) . "</td></tr>\n";
			}
			if ($keywordcut == 1) {
				echo "<div id=\"smenu" . ($comptligne + 9) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (240 + (($comptligne - 3) * 25)) . "px; left:20px; background:#fff;\">\n";
				echo "&nbsp;" . stripslashes(htmlspecialchars(utf8_decode(urldecode($keyword)))) . "&nbsp;\n";
				echo "</div>\n";
			}
			$comptligne++;
			if ($settings->displayall == 'no') {
				$comptdata++;
			}
		}
	}
	echo "</table>\n";
	if (count($visitkeyword) >= $settings->displayrows && $settings->displayall == 'no') {
		echo "<h2><span class=\"smalltext\">\n";
		printf($language['100_lines'], $settings->displayrows);
		echo "<br>\n";
		$crawlencode = urlencode($settings->crawler);
		echo "<a href=\"index.php?navig=$settings->navig&period=$settings->period&site=$settings->siteid&crawler=$crawlencode&order=$settings->displayorder&displayall=yes&graphpos=$settings->graphpos\">" . $language['show_all'] . "</a></span></h2>";
	}
	echo "<br>\n";
} else {
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
?>
