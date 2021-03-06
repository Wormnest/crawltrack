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
// file: display-one-entrypage.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$visitkeywordgoogle = array();
$visitkeywordgoogleimage = array();
$visitkeywordYahoo = array();
$visitkeywordMSN = array();
$visitkeywordask = array();
$visitkeywordexalead = array();
$visitkeywordyandex = array();
$visitkeywordaol = array();
$visitkeyword = array();
$visitkeyworddisplay = array();
$crawlencode = urlencode($settings->crawler);
$nbrresultgoogle=0;
$nbrresultMSN=0;
$nbrresultYahoo=0;
$nbrresultgoogleimage=0;
$nbrresultask=0;
$nbrresultexalead=0;
$nbrresultyandex=0;
$nbrresultaol=0;

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

$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->displayorder.$settings->displayrows . $crawlencode . $settings->displayall . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language. $askkeyword. $baidukeyword. $googlekeyword.$googleimagekeyword.$msnkeyword.$yahookeyword.$yandexkeyword.$aolkeyword;

//start the caching
cache($cachename);

$settings->crawler= htmlspecialchars_decode($settings->crawler);

//include menu
include ("include/menusite.php");
include ("include/menumain.php");
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

//request to get page id
$sqlpageid = "SELECT  id_page 
FROM crawlt_pages
WHERE  url_page='" . crawlt_sql_quote($db->connexion, $settings->crawler) . "' ";
$requetepageid = db_query($sqlpageid, $db->connexion);
$nbrresultpageid = $requetepageid->num_rows;
if ($nbrresultpageid >= 1) {
	while ($ligne = $requetepageid->fetch_row()) {
		$pageidlist[] = $ligne[0];
	}
	$pageid=implode("','",$pageidlist);
}
else
{
$pageid='?';
}

//request to have the keyword for Google
if($googlekeyword==1 && $pageid !='?')
{
$sqlgoogle = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
INNER JOIN crawlt_keyword 
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid') 
AND crawlt_id_crawler= '1' 
GROUP BY keyword";
$requetegoogle = db_query($sqlgoogle, $db->connexion);
$nbrresultgoogle = $requetegoogle->num_rows;
if ($nbrresultgoogle >= 1) {
	while ($ligne = $requetegoogle->fetch_row()) {
		$visitkeywordgoogle[$ligne[0]] = $ligne[1];
	}
}
}

//query to get google  details position
if($pageid !='?')
{
$sqlgoogle2 = "SELECT  referer, keyword
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
LEFT OUTER JOIN crawlt_referer
ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid') 
AND keyword !='(not provided)' 
AND crawlt_id_crawler= '1'";
$requetegoogle2 = db_query($sqlgoogle2, $db->connexion);
$nbrresult = $requetegoogle2->num_rows;
if ($nbrresult >= 1) {
	while ($ligne = $requetegoogle2->fetch_row()) {
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
}

//request to have the keyword for Google-Images
if($googleimagekeyword==1 && $pageid !='?')
{
$sqlgoogleimage = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
INNER JOIN crawlt_keyword 
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid')  
AND crawlt_id_crawler= '6' 
GROUP BY keyword";
$requetegoogleimage = db_query($sqlgoogleimage, $db->connexion);
$nbrresultgoogleimage = $requetegoogleimage->num_rows;
if ($nbrresultgoogleimage >= 1) {
	while ($ligne = $requetegoogleimage->fetch_row()) {
		$visitkeywordgoogleimage[$ligne[0]] = $ligne[1];
	}
}
}

//request to have the keyword for Yahoo
if($yahookeyword==1 && $pageid !='?')
{
$sqlYahoo = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid')  
AND crawlt_id_crawler= '2' 
GROUP BY keyword";
$requeteYahoo = db_query($sqlYahoo, $db->connexion);
$nbrresultYahoo = $requeteYahoo->num_rows;
if ($nbrresultYahoo >= 1) {
	while ($ligne = $requeteYahoo->fetch_row()) {
		$visitkeywordYahoo[$ligne[0]] = $ligne[1];
	}
}
}

//request to have the keyword for MSN
if($msnkeyword==1 && $pageid !='?')
{
$sqlMSN = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid')   
AND crawlt_id_crawler= '3' 
GROUP BY keyword";
$requeteMSN = db_query($sqlMSN, $db->connexion);
$nbrresultMSN = $requeteMSN->num_rows;
if ($nbrresultMSN >= 1) {
	while ($ligne = $requeteMSN->fetch_row()) {
		$visitkeywordMSN[$ligne[0]] = $ligne[1];
	}
}
}

//request to have the keyword for Ask
if($askkeyword==1 && $pageid !='?')
{
$sqlask = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid')  
AND crawlt_id_crawler= '4' 
GROUP BY keyword";
$requeteask = db_query($sqlask, $db->connexion);
$nbrresultask = $requeteask->num_rows;
if ($nbrresultask >= 1) {
	while ($ligne = $requeteask->fetch_row()) {
		$visitkeywordask[$ligne[0]] = $ligne[1];
	}
}
}

//request to have the keyword for Baidu
if($baidukeyword==1 && $pageid !='?')
{
$sqlexalead = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid')  
AND crawlt_id_crawler= '5' 
GROUP BY keyword";
$requeteexalead = db_query($sqlexalead, $db->connexion);
$nbrresultexalead = $requeteexalead->num_rows;
if ($nbrresultexalead >= 1) {
	while ($ligne = $requeteexalead->fetch_row()) {
		$visitkeywordexalead[$ligne[0]] = $ligne[1];
	}
}
}

//request to have the keyword for Yandex
if($yandexkeyword==1 && $pageid !='?')
{
$sqlyandex = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid')  
AND crawlt_id_crawler= '7' 
GROUP BY keyword";
$requeteyandex = db_query($sqlyandex, $db->connexion);
$nbrresultyandex = $requeteyandex->num_rows;
if ($nbrresultyandex >= 1) {
	while ($ligne = $requeteyandex->fetch_row()) {
		$visitkeywordyandex[$ligne[0]] = $ligne[1];
	}
}
}

//request to have the keyword for Aol
if($aolkeyword==1 && $pageid !='?')
{
$sqlaol = "SELECT  keyword, count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
FROM crawlt_visits_human
INNER JOIN crawlt_keyword
ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword
WHERE  $datetolookfor
AND crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_id_page IN ('$pageid')  
AND crawlt_id_crawler= '8' 
GROUP BY keyword";
$requeteaol = db_query($sqlaol, $db->connexion);
$nbrresultaol = $requeteaol->num_rows;
if ($nbrresultaol >= 1) {
	while ($ligne = $requeteaol->fetch_row()) {
		$visitkeywordaol[$ligne[0]] = $ligne[1];
	}
}
}

$db->close(); // Close database

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
if ($nbrresultyandex >= 1) {
	foreach ($visitkeywordyandex as $key => $value) {
		$visitkeyword[$key] = @$visitkeyword[$key] + $value;
	}
}
if ($nbrresultaol >= 1) {
	foreach ($visitkeywordaol as $key => $value) {
		$visitkeyword[$key] = @$visitkeyword[$key] + $value;
	}
}
arsort($visitkeyword);

//display-------------------------------------------------------------------------------------------------------------
echo "<div class=\"content2\"><br><br><br><br><hr>\n";
echo "</div>\n";

echo "<div width='70%' align='center'><form action=\"index.php\" method=\"POST\"  style=\" font-size:13px; font-weight:bold; color: #003399;
	font-family: Verdana,Geneva, Arial, Helvetica, Sans-Serif; \">\n";
echo "<input type=\"hidden\" name ='navig' value=\"14\">\n";
echo "<input type=\"hidden\" name ='site' value=\"".$settings->siteid."\">\n";
echo "<input type=\"hidden\" name ='period' value=\"".$settings->period."\">\n";
echo "<input type=\"hidden\" name ='graphpos' value=\"".$settings->graphpos."\">\n";
echo "<input type=\"hidden\" name ='crawler' value=\"".$settings->crawler."\">\n";
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
	echo "" . $language['keyword'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1' rowspan=\"2\">\n";
	echo "" . $language['googleposition'] . "\n";
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
		$keyworddisplay = stripslashes(crawltcutkeyword($keyword, 35, $settings->useutf8));
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
		if (isset($visitkeywordyandex[$keyword])) {
			$visityandex = $visitkeywordyandex[$keyword];
		} else {
			$visityandex = '-';
		}
		if (isset($visitkeywordaol[$keyword])) {
			$visitaol = $visitkeywordaol[$keyword];
		} else {
			$visitaol = '-';
		}				
		if (isset($position[$keyword])) {
			$positionkeyword = $position[$keyword];
		} else {
			$positionkeyword = "-";
		}
		//to limit the display to the selected number
		if ($comptdata < $settings->displayrows) {
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 9) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo "><a href='index.php?navig=16&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "' >" . $keyworddisplay . "</a></td>\n";
				echo "<td class='tableau6' width=\"4%\"" . crawltkeywordwindow($keyword) . ">\n";
				echo "<a href=\"#\">\n";
				echo " <img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau3' width=\"8%\">" . $positionkeyword . "</td>\n";
				echo "<td class='tableau3' width=\"5%\">" . numbdisp($visitaol) . "</td>\n";
				echo "<td class='tableau3' width=\"5%\">" . numbdisp($visitask) . "</td>\n";
				echo "<td class='tableau3' width=\"6%\">" . numbdisp($visitexalead) . "</td>\n";
				echo "<td class='tableau3' width=\"6%\">" . numbdisp($visitmsn) . "</td>\n";
				echo "<td class='tableau3' width=\"7%\">" . numbdisp($visitgoogle) . "</td>\n";
				echo "<td class='tableau3' width=\"13%\">" . numbdisp($visitgoogleimage) . "</td>\n";
				echo "<td class='tableau3' width=\"6%\">" . numbdisp($visityahoo) . "</td>\n";
				echo "<td class='tableau5' width=\"7%\">" . numbdisp($visityandex) . "</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30'";
				if ($keywordcut == 1) {
					echo "onmouseover=\"javascript:montre('smenu" . ($comptligne + 9) . "');\"   onmouseout=\"javascript:montre();\"";
				}
				echo "><a href='index.php?navig=16&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "'  >" . $keyworddisplay . "</a></td>\n";
				echo "<td class='tableau60' width=\"4%\"" . crawltkeywordwindow($keyword) . ">\n";
				echo "<a href=\"#\">\n";
				echo " <img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" ></a>\n";
				echo "</td> \n";
				echo "<td class='tableau30' width=\"8%\">" . $positionkeyword . "</td>\n";
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
				echo "<div id=\"smenu" . ($comptligne + 9) . "\"  style=\"display:none; font-size:14px; font-weight:bold; color:#ff0000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; border:2px solid navy; position:absolute; top:" . (270 + (($comptligne - 3) * 25)) . "px; left:20px; background:#fff;\">\n";
				echo "&nbsp;" . stripslashes(htmlentities(utf8_decode(urldecode($keyword)))) . "&nbsp;\n";
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
