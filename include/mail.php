<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.3
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
// file: mail.php
//----------------------------------------------------------------------
//  Last update 02/01/2012
//----------------------------------------------------------------------
//update the crawlt_config table
$sqlcrawltupdatemail = "UPDATE crawlt_config SET datelastmail='" . crawlt_sql_quote($crawltdatetoday) . "'";
$requetecrawltupdatemail = mysql_query($sqlcrawltupdatemail, $crawltconnexion);

//function to format the numbers for display
if (function_exists("crawltnumbdispmail") == false) {
	function crawltnumbdispmail($value) {
		global $crawltlang;
		if ($crawltlang == 'french' || $crawltlang == 'frenchiso') {
			$value = number_format($value, 0, ",", " ");
		} else {
			$value = number_format($value, 0, ".", ",");
		}
		return $value;
	}
}

//function to count the number of mysql query
if (function_exists("db_query") == false) {
	function db_query($sql) {
		global $numbquery;
		$numbquery++;
		return mysql_query($sql);
	}
}

//function to count the number of day from today
if (function_exists("nbdayfromtoday") == false) {
	function nbdayfromtoday($date) {
		$today = strtotime("today");
		$daydate = strtotime($date);
		$delta = $today - $daydate;
		if ($delta <= 0) {
			$nbdayfromtoday = 0;
		} else {
			$nbdayfromtoday = $delta / 86400;
			$nbdayfromtoday = IntVal($nbdayfromtoday);
		}
		return ($nbdayfromtoday);
	}
}

//function to know if the string is encodes in utf8
if (function_exists("isutf8mail") == false) {
	function isutf8mail($string) {
		return (utf8_encode(utf8_decode($string)) == $string);
	}
}

//function to cut and wrap the url to avoid oversize display
if (function_exists("crawltcuturlmail") == false) {
	function crawltcuturlmail($url, $length) {
		global $crawltcharset;
		if ($crawltcharset == 1) {
			if (!isutf8mail($url)) {
				if (function_exists("mb_convert_encoding")) {
					$url = @mb_convert_encoding($url, "UTF-8", "auto");
				}
			}
		} else {
			if (function_exists("mb_convert_encoding")) {
				$url = mb_convert_encoding($url, "ISO-8859-1", "auto");
			}
		}
		$urldisplaylength = strlen("$url");
		$cutvalue = 0;
		$urldisplay = '';
		while ($cutvalue <= $urldisplaylength) {
			$cutvalue2 = $cutvalue + $length;
			$urldisplay = $urldisplay . htmlspecialchars(substr($url, $cutvalue, $length));
			if ($cutvalue2 <= $urldisplaylength) {
				$urldisplay = $urldisplay . '<br>&nbsp;&nbsp;';
				$urlcut = 1;
			}
			$cutvalue = $cutvalue2;
		}
		return $urldisplay;
	}
}

//include listlang file
include $crawltpath . "/include/listlang.php";

//language file include
if (in_array($crawltlang, $listlangcrawlt)) {
	include $crawltpath . "/language/" . $crawltlang . ".php";
}

// do not modify
define('IN_CRAWLT', TRUE);

//date calculation
$crawltdatetoday3 = date("Y-m-d", $crawltts - 40000);
$crawltts2 = explode('-', $crawltdatetoday3);
$crawltyeartoday = $crawltts2[0];
$crawltmonthtoday = $crawltts2[1];
$crawltdaytoday = $crawltts2[2];


//date for the mysql query
//request date calculation
$daterequest = date("Y-m-d", ($crawltts - 86400));
$daterequest2 = date("Y-m-d", $crawltts);
$datebeginlocal = $daterequest;
$datebeginlocalcut[0] = $daterequest;
$datetolookfor = " `date` >='" . crawlt_sql_quote($daterequest) . "' 
	AND  `date` <'" . crawlt_sql_quote($daterequest2) . "'";
//mysql query for site id
$sqlcrawltsite = "SELECT * FROM crawlt_site";
$requetecrawltsite = mysql_query($sqlcrawltsite, $crawltconnexion);
$nbrresultcrawlt2 = mysql_num_rows($requetecrawltsite);
if ($nbrresultcrawlt2 >= 1) {
	$listsitecrawlt = array();
	$crawltsitename = array();
	$crawltsiteurl = array();
	while ($lignecrawlt2 = mysql_fetch_object($requetecrawltsite)) {
		$namecrawlt = $lignecrawlt2->name;
		$siteidcrawlt = $lignecrawlt2->id_site;
		$listsitecrawlt[] = $siteidcrawlt;
		$crawltsitename[$siteidcrawlt] = $namecrawlt;
		$urlsite[$siteidcrawlt] = $lignecrawlt2->url;
	}
}
foreach ($listsitecrawlt as $site) {
	//initialize array
	$count = array();
	$linkname = array();
	$nbrerrorattack = 0;
	$nbrerrorcrawler = 0;
	$nbrerrordirect = 0;
	$nbrerrorextern = 0;
	$nbrerrorintern = 0;
	$nbrcss = 0;
	$nbrsql = 0;
	$period = 1000;
	$navig = 0;
	$tablinkgoogle = array();
	$tabpagegoogle = array();
	$values2 = array();
	$googlebotvisit = array();
	$msnbotvisit = array();
	$yahoobotvisit = array();
	$askbotvisit = array();
	$exabotvisit = array();
	$baiduspidervisit = array();
	$times = $crawlttime;
	
	//to avoid problem if the url is enter in the database with http://
	if (!preg_match('#^http://#i', $urlsite[$site])) {
		$hostsite = "http://" . $urlsite[$site];
	} else {
		$hostsite = $urlsite[$site];
	}
	
	//clean table from crawler entry
	include ($crawltpath . "/include/cleaning-crawler-entry.php");
	
	//include visitors calculation file
	$connexion = $crawltconnexion;
	include ($crawltpath . "/include/visitors-calculation.php");
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
			$values2[$language['website3']] = $visitsendother;
		}
		if ($visitdirect > 0) {
			$values2[$language['direct']] = $visitdirect;
		}
		arsort($values2);
	} else {
		$values2 = array();
	}
	
	//crawler calculation-----------------------------------------------------------------------------------------------
	//query to have the number of Crawler visits
	$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y'),crawler_name, COUNT( id_visit) 
		FROM crawlt_visits
		INNER JOIN crawlt_crawler
		ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
		WHERE  $datetolookfor
		AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		AND crawler_name IN ('GoogleBot','MSN Bot','Slurp Inktomi (Yahoo)','Ask Jeeves/Teoma','Exabot','Baiduspider')      
		GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($times*3600), '%d-%m-%Y'), crawler_name";
	$requete = mysql_query($sql, $crawltconnexion);
	while ($ligne = mysql_fetch_row($requete)) {
		if ($ligne[1] == 'GoogleBot') {
			$googlebotvisit[$ligne[0]] = $ligne[2];
		} elseif ($ligne[1] == 'MSN Bot' || $ligne[1] == 'Bingbot') {
			$msnbotvisit[$ligne[0]] = $ligne[2];
		} elseif ($ligne[1] == 'Slurp Inktomi (Yahoo)') {
			$yahoobotvisit[$ligne[0]] = $ligne[2];
		} elseif ($ligne[1] == 'Ask Jeeves/Teoma') {
			$askbotvisit[$ligne[0]] = $ligne[2];
		} elseif ($ligne[1] == 'Exabot') {
			$exabotvisit[$ligne[0]] = $ligne[2];
		} elseif ($ligne[1] == 'Baiduspider') {
			$baiduspidervisit[$ligne[0]] = $ligne[2];
		}
	}
	
	$datetoused = date("d-m-Y", (strtotime($datebeginlocal)));
	$visitask = $askbotvisit[$datetoused];
	$visityahoo = $yahoobotvisit[$datetoused];
	$visitmsn = $msnbotvisit[$datetoused];
	$visitgoogle = $googlebotvisit[$datetoused];
	$visitexalead = $exabotvisit[$datetoused];
	$visitbaidu = $baiduspidervisit[$datetoused];
	//query to count the total number of  pages viewed ,total number of visits and total number of crawler
	$sqlstats2 = "SELECT COUNT(DISTINCT crawlt_pages_id_page), COUNT(DISTINCT crawler_name), COUNT(id_visit) 
		FROM crawlt_visits
		INNER JOIN crawlt_crawler
		ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
		AND $datetolookfor         
		AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($site) . "'";
	$requetestats2 = mysql_query($sqlstats2, $crawltconnexion);
	$ligne2 = mysql_fetch_row($requetestats2);
	$nbrtotpages = $ligne2[0];
	$nbrtotcrawlers = $ligne2[1];
	$nbrtotvisits = $ligne2[2];
	//Indexation calculation----------------------------------------------------------------------------------------
	//query to get the msn and yahoo positions data and the number of Delicious bookmarks and  Delicious keywords
	$sqlseo = "SELECT  linkyahoo, pageyahoo,  pagemsn, nbrdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle
		FROM crawlt_seo_position
		WHERE  id_site='" . crawlt_sql_quote($site) . "' 
		AND  date ='" . crawlt_sql_quote($daterequest) . "'";
	$requeteseo = mysql_query($sqlseo, $crawltconnexion);
	$nbrresult = mysql_num_rows($requeteseo);
	
	if ($nbrresult >= 1) {
		while ($ligneseo = mysql_fetch_row($requeteseo)) {
			$tablinkgoogle[] = $ligneseo[6];
			$tabpagegoogle[] = $ligneseo[7];
		}
		//preparation of values for display
		$linkgoogle = crawltnumbdispmail($tablinkgoogle[($nbrresult - 1) ]);
		$pagegoogle = crawltnumbdispmail($tabpagegoogle[($nbrresult - 1) ]);
	} else {
		$linkgoogle = 0;
		$pagegoogle = 0;
	}
	
	//Hacking attempts calculation-----------------------------------------------------------------------------------
	$sql = "SELECT crawlt_crawler_id_crawler, COUNT(id_visit) 
		FROM crawlt_visits
		WHERE crawlt_crawler_id_crawler IN ('65500','65501')
		AND $datetolookfor       
		AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		GROUP BY crawlt_crawler_id_crawler";
	$requete = mysql_query($sql, $crawltconnexion);
	while ($ligne = mysql_fetch_row($requete)) {
		if ($ligne[0] == 65500) {
			$nbrcss = $ligne[1];
		}
		if ($ligne[0] == 65501) {
			$nbrsql = $ligne[1];
		}
	}
	
	//download calculation----------------------------------------------------------------------------------------------
	//query to have the number for the period
	$sql = "SELECT link, count 
		FROM crawlt_download
		WHERE  idsite='" . crawlt_sql_quote($site) . "'
		AND  `date` ='" . crawlt_sql_quote($daterequest) . "'";
	$requete = mysql_query($sql, $crawltconnexion);
	$num_rows = mysql_num_rows($requete);
	
	if ($num_rows > 0) {
		while ($ligne = mysql_fetch_row($requete)) {
			$explodelink = explode('/', $ligne[0]);
			$countexplode = count($explodelink) - 1;
			$linkname[$ligne[0]] = $explodelink[$countexplode];
			$count[$linkname[$ligne[0]]] = $ligne[1] + @$count[$linkname[$ligne[0]]];
		}
	}
	arsort($count);
	//error 404 calculation------------------------------------------------------------------------------------------------
	//attack
	$sql = "SELECT attacktype, count 
		FROM crawlt_error
		WHERE  idsite='" . crawlt_sql_quote($site) . "'
		AND  `date` ='" . crawlt_sql_quote($daterequest) . "'
		GROUP BY attacktype";
	$requete = mysql_query($sql, $crawltconnexion);
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
		AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		AND crawlt_error='1'";
	$requete = mysql_query($sql, $crawltconnexion);
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
		AND crawlt_visits_human.crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		AND Substring(referer From 1 For " . $lengthurl . ") != '" . crawlt_sql_quote($hostsite) . "'
		AND crawlt_id_referer !='0'
		AND crawlt_error='1'";
	$requete = mysql_query($sql, $crawltconnexion);
	$num_rows = mysql_num_rows($requete);
	if ($num_rows > 0) {
		$ligne = mysql_fetch_row($requete);
		$nbrerrorextern = $ligne[0];
	}
	
	//query to get error from visitor direct
	$sql = "SELECT crawlt_id_page FROM crawlt_visits_human
		WHERE $datetolookfor       
		AND crawlt_visits_human.crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		AND crawlt_error='1'
		AND crawlt_id_referer=''";
	$requete = mysql_query($sql, $crawltconnexion);
	$nbrerrordirect = mysql_num_rows($requete);
	
	//query to get error from visitors internal link
	$sql = "SELECT COUNT(id_visit) 
		FROM crawlt_visits_human
		INNER JOIN crawlt_referer
		ON  crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
		AND $datetolookfor       
		AND crawlt_visits_human.crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		AND Substring(referer From 1 For " . $lengthurl . ") = '" . crawlt_sql_quote($hostsite) . "'
		AND crawlt_error='1'";
	$requete = mysql_query($sql, $crawltconnexion);
	$ligne = mysql_fetch_row($requete);
	
	$nbrerrorintern = $ligne[0];
	//email--------------------------------------------------------------------------------------------
	if ($crawltmailishtml == 1) {
		//case html email
		$crawltmessage = "<div style='font-size:14px; color:#003399; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:center; border:2px solid navy; padding:0; margin:0;' />\n";
		$crawltmessage.= "<div style='background-color: #E6E6FA; text-align:left;font-size:24px; color:#000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; font-weight:bold;'/>\n";
		$crawltmessage.= "&nbsp;CrawlTrack  <span style='font-size:18px; color:#000; font-family:Verdana,Geneva, Arial, Helvetica, Sans-Serif; text-align:left; font-weight:bold;'>" . $language['webmaster_dashboard'] . "</span>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<div style='font-size:18px; color:#003399;border-top:2px solid navy; border-bottom:2px solid navy;background-color: #E6E6FA;' />\n";
		$crawltmessage.= "<span style='float:left;'>&nbsp;Site: <b>" . $crawltsitename[$site] . "</b></span><span style='float:right; font-size:14px;'>" . $language['daily-stats'] . ": " . $crawltdaytoday . "/" . $crawltmonthtoday . "/" . $crawltyeartoday . " &nbsp;</span><br/>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<table style='width:98%' cellpadding='0px' cellspacing='0' margin='0'>\n";
		$crawltmessage.= "<tr><td style='text-align: center;border-right: 2px solid navy;'>\n";
		$crawltmessage.= "<div style='font-size:16px; color:#A52A2A; text-align:center;' />\n";
		$crawltmessage.= "<br><b>" . $language['visitors'] . "</b><br/>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<table style='font-size:14px; color:#000; text-align:center;margin-left: auto;margin-right: auto' cellpadding='0px' cellspacing='0'>\n";
		$crawltmessage.= "<tr><td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['visits'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['unique_visitors'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399;border-right: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['nbr_tot_pages'] . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($totalvisitor) . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrvisitor) . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrpage) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td colspan='3' >&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td colspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['referer'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center; border-top: 2px solid #003399;border-left: 2px solid #003399; border-right: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['visits'] . "</b>&nbsp;&nbsp;</td></tr>\n";
		foreach ($values2 as $key => $value) {
			$crawltmessage.= "<tr><td colspan='2' style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'><b>" . $key . "</b></td>\n";
			$crawltmessage.= "<td style='text-align: center;border-left: 2px solid #003399; border-right: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'><b>" . crawltnumbdispmail($value) . "</b></td></tr>\n";
		}
		$crawltmessage.= "</table><br>\n";
		$crawltmessage.= "</td><td style='text-align: center;'>\n";
		$crawltmessage.= "<div style='font-size:16px; color:#A52A2A; text-align:center;' />\n";
		$crawltmessage.= "<br><b>" . $language['crawler_name'] . "</b><br/>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<table style='font-size:14px; color:#000; text-align:center;margin-left: auto;margin-right: auto' cellpadding='0px' cellspacing='0'>\n";
		$crawltmessage.= "<tr><td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['nbr_tot_crawlers'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['nbr_tot_visits'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399;border-right: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['nbr_tot_pages'] . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrtotcrawlers) . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrtotvisits) . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrtotpages) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td colspan='3' >&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td colspan='2' style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['main_crawlers'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center; border-top: 2px solid #003399;border-left: 2px solid #003399; border-right: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['nbr_tot_visits'] . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  colspan='2' style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>Ask Jeeves/Teoma</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($visitask) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  colspan='2' style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>Baiduspider</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($visitbaidu) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  colspan='2' style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>Exabot</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($visitexalead) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  colspan='2' style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>GoogleBot</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($visitgoogle) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  colspan='2' style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>MSN Bot - Bingbot</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($visitmsn) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  colspan='2' style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>Slurp Inktomi (Yahoo)</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($visityahoo) . "</b>&nbsp;&nbsp;</td></tr></table><br>\n";
		$crawltmessage.= "</td></tr><tr><td style='text-align: center;border-right: 2px solid navy; border-top: 2px solid navy;'>\n";
		$crawltmessage.= "<div style='font-size:16px; color:#A52A2A; text-align:center;' />\n";
		$crawltmessage.= "<br><b>" . $language['index'] . "</b><br/>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<table style='font-size:14px; color:#000; text-align:center;margin-left: auto;margin-right: auto' cellpadding='0px' cellspacing='0'>\n";
		$crawltmessage.= "<tr><td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;</td>\n";
		$crawltmessage.= "<td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['nbr_tot_link'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399;border-right: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['nbr_tot_pages_index'] . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . $language['google'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . $linkgoogle . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . $pagegoogle . "</b>&nbsp;&nbsp;</td></tr></table><br>\n";
		$crawltmessage.= "</td><td style='text-align: center;border-top: 2px solid navy;'>\n";
		$crawltmessage.= "<div style='font-size:16px; color:#A52A2A; text-align:center;' />\n";
		$crawltmessage.= "<br><b>" . $language['hacking2'] . "</b><br/>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<table style='font-size:14px; color:#000; text-align:center;margin-left: auto;margin-right: auto' cellpadding='0px' cellspacing='0'>\n";
		$crawltmessage.= "<tr><td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['hacking3'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; border-right: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['hacking4'] . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrcss) . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrsql) . "</b>&nbsp;&nbsp;</td></tr></table><br>\n";
		$crawltmessage.= "</td></tr><tr><td style='text-align: center;border-right: 2px solid navy; border-top: 2px solid navy; '>\n";
		$crawltmessage.= "<div style='font-size:16px; color:#A52A2A; text-align:center;' />\n";
		$crawltmessage.= "<br><b>" . $language['download'] . "</b><br/>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<table style='font-size:14px; color:#000; text-align:center;margin-left: auto;margin-right: auto' cellpadding='0px' cellspacing='0'>\n";
		$crawltmessage.= "<tr><td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['file'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; border-right: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['download'] . "</b>&nbsp;&nbsp;</td></tr>\n";
		foreach ($count as $key => $value) {
			$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltcuturlmail($key, 30) . "</b>&nbsp;&nbsp;</td>\n";
			$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($count[$key]) . "</b>&nbsp;&nbsp;</td></tr>\n";
		}
		$crawltmessage.= "</table>\n";
		$crawltmessage.= "<br></td><td style='text-align: center;border-top: 2px solid navy;'>\n";
		$crawltmessage.= "<div style='font-size:16px; color:#A52A2A; text-align:center;' />\n";
		$crawltmessage.= "<br><b>" . $language['error'] . "</b><br/>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<table style='font-size:14px; color:#000; text-align:center;margin-left: auto;margin-right: auto' cellpadding='0px' cellspacing='0'>\n";
		$crawltmessage.= "<tr><td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['origin'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td style='text-align: center; border-top: 2px solid #003399; border-bottom: 2px solid #003399; border-left: 2px solid #003399; border-right: 2px solid #003399; background-color: #7EAAFF;'>&nbsp;&nbsp;<b>" . $language['number'] . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . $language['hacking2'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrerrorattack) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . $language['crawler_name'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrerrorcrawler) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . $language['direct'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrerrordirect) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . $language['outer-referer'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrerrorextern) . "</b>&nbsp;&nbsp;</td></tr>\n";
		$crawltmessage.= "<tr><td  style='text-align: center;border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . $language['inner-referer'] . "</b>&nbsp;&nbsp;</td>\n";
		$crawltmessage.= "<td  style='text-align: center;border-right: 2px solid #003399; border-left: 2px solid #003399; border-bottom: 2px solid #003399; background-color: #FFF;'>&nbsp;&nbsp;<b>" . crawltnumbdispmail($nbrerrorintern) . "</b>&nbsp;&nbsp;</td></tr></table>\n";
		$crawltmessage.= "<br></td></tr></table>\n";
		$crawltmessage.= "<div style='font-size:12px; color:#003399;border-top:2px solid navy;background-color: #E6E6FA;' />\n";
		$crawltmessage.= "<a href=\"" . $url_crawlt . "\"> " . $language['stat-access'] . "</a>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<div style='background-color: #E6E6FA; text-align:center; border-top:2px solid navy;'/>\n";
		$crawltmessage.= "<a href=\"http://www.crawltrack.net\" style='font-size:18px;font-weight:bold; color:#000; text-decoration: none;'>CrawlTrack</a>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "</div>\n";
		$crawltmessage.= "<br><br><div style='background-color: #ffffff; text-align:center; '/>\n";
		$crawltmessage.= "<b>" . $language['ad-on-crawltrack'] . "</b>\n";
		$crawltmessage.= "</div><br><br>\n";		
	} else {
		//case text email
		$crawltmessage = "Site: " . $crawltsitename[$site] . "-------------- " . $language['daily-stats'] . ": " . $crawltdaytoday . "/" . $crawltmonthtoday . "/" . $crawltyeartoday . "\n\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['visitors'] . "\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['visits'] . ": " . crawltnumbdispmail($totalvisitor) . "\n";
		$crawltmessage.= $language['unique_visitors'] . ": " . crawltnumbdispmail($nbrvisitor) . "\n";
		$crawltmessage.= $language['nbr_tot_pages'] . ": " . crawltnumbdispmail($nbrpage) . "\n\n";
		$crawltmessage.= $language['referer'] . ": \n";
		foreach ($values2 as $key => $value) {
			$crawltmessage.= $key . ": " . crawltnumbdispmail($value) . "\n";
		}
		$crawltmessage.= "\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['crawler_name'] . "\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['nbr_tot_crawlers'] . ": " . crawltnumbdispmail($nbrtotcrawlers) . "\n";
		$crawltmessage.= $language['nbr_tot_visits'] . ": " . crawltnumbdispmail($nbrtotvisits) . "\n";
		$crawltmessage.= $language['nbr_tot_pages'] . ": " . crawltnumbdispmail($nbrtotpages) . "\n\n";
		$crawltmessage.= $language['main_crawlers'] . ": \n";
		$crawltmessage.= "Ask Jeeves/Teoma: " . crawltnumbdispmail($visitask) . "\n";
		$crawltmessage.= "Baiduspider: " . crawltnumbdispmail($visitbaidu) . "\n";
		$crawltmessage.= "Exabot: " . crawltnumbdispmail($visitexalead) . "\n";
		$crawltmessage.= "Googlebot: " . crawltnumbdispmail($visitgoogle) . "\n";
		$crawltmessage.= "MSN Bot - Bingbot: " . crawltnumbdispmail($visitmsn) . "\n";
		$crawltmessage.= "Slurp Inktomi (Yahoo): " . crawltnumbdispmail($visityahoo) . "\n\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['index'] . "\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['nbr_tot_link'] . "\n";
		$crawltmessage.= $language['google'] . ": " . $linkgoogle . "\n\n";

		$crawltmessage.= $language['nbr_tot_pages_index'] . "\n";
		$crawltmessage.= $language['google'] . ": " . $pagegoogle . "\n\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['hacking2'] . "\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['hacking3'] . ": " . crawltnumbdispmail($nbrcss) . "\n";
		$crawltmessage.= $language['hacking4'] . ": " . crawltnumbdispmail($nbrsql) . "\n\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['download'] . "\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		foreach ($count as $key => $value) {
			$crawltmessage.= $key . ": " . crawltnumbdispmail($count[$key]) . "\n\n";
		}
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['error'] . "\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= $language['hacking2'] . ": " . crawltnumbdispmail($nbrerrorattack) . "\n";
		$crawltmessage.= $language['crawler_name'] . ": " . crawltnumbdispmail($nbrerrorcrawler) . "\n";
		$crawltmessage.= $language['direct'] . ": " . crawltnumbdispmail($nbrerrordirect) . "\n";
		$crawltmessage.= $language['outer-referer'] . ": " . crawltnumbdispmail($nbrerrorextern) . "\n";
		$crawltmessage.= $language['inner-referer'] . ": " . crawltnumbdispmail($nbrerrorintern) . "\n\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n\n";
		$crawltmessage.= "--------------------------------------------------------------------------------------------------------------\n\n";
		$crawltmessage.= $language['stat-access'] . " " . $url_crawlt . "\n\n";
		$crawltmessage.= $language['stat-crawltrack'] . " ";
		$crawltmessage.= "http://www.crawltrack.net\n";
	}
	
	//send the mail
	require_once ("$crawltpath/phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();
	if ($crawltcharset != 1) {
		$mail->CharSet = 'iso-8859-1';
		
	} else {
		$mail->CharSet = 'utf-8';
	}	
	$mail->IsMail(); // telling the class to use Mail
	//if you want to use smtp server comment the previous line and use the following ones:
	/*
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host = "smtp.email.com"; // SMTP server, put here the name of your server
	*/
	if ($crawltmailishtml == 1) {
		$mail->IsHTML(true);
	} else {
		$mail->IsHTML(false);
	}
	$mail->FromName = "CrawlTrack 3-3-2";
	$mail->Subject = $language['mailsubject'] . "--" . $crawltsitename[$site];
	$mail->Body = $crawltmessage;
	$crawltemail = explode(',', $crawltdest);
	foreach ($crawltemail as $crawltaddress) {
		$mail->ClearAddresses();
		$mail->AddAddress($crawltaddress);
		$mail->Send();
	}
}
//=================================================================================================================================
//==================================================CrawlTrack lite================================================================
//=================================================================================================================================
//If you want to clear the database each day after the sending of Email, change the value of $crawltracklite variable to 1.
//Be carefull, with the default value for $crawltracklitekeepday (0) only today and the day before datas will be keep;
// all older datas will be lost
//=================================================================================================================================
//Si vous voulez vider la base de données chaque jour après l'envoi du ou des Emails, changez la valeur de $crawltracklite à 1.
//Attention, avec la valeur par défaut de $crawltracklitekeepday (0) seules les données du jour et de la veille seront conservées;
// les données plus anciennes seront perdues
//=================================================================================================================================
//=================================================================================================================================
//put 1 to change to CrawlTrack Lite ---- mettez 1 pour passer en CrawlTrack Lite
$crawltracklite = 0;
//=================================================================================================================================
//=================================================================================================================================
//default setting is 0, if you want to keep more datas, change the value to the number of days you want to keep
//valeur par défaut est 0, si vous voullez garder plus de données, changez la valeur en mettant le nombre de jours que vous voullez garder.
$crawltracklitekeepday = 0;
//=================================================================================================================================
$daterequest2 = date("Y-m-d H:i:s", (strtotime($daterequest) - ($crawltracklitekeepday * 86400)));
if ($crawltracklite == 1) {
	$sqldelete = "DELETE FROM crawlt_visits WHERE `date` < '" . crawlt_sql_quote($daterequest2) . "'";
	$requete = mysql_query($sqldelete, $crawltconnexion);
	$sqldelete = "DELETE FROM crawlt_visits_human WHERE `date` < '" . crawlt_sql_quote($daterequest2) . "'";
	$requete = mysql_query($sqldelete, $crawltconnexion);
	
	//database query to optimize the table
	$sqloptimize2 = "OPTIMIZE TABLE crawlt_visits";
	$requeteoptimize2 = mysql_query($sqloptimize2, $crawltconnexion);
	
	//database query to optimize the table
	$sqloptimize2 = "OPTIMIZE TABLE crawlt_visits_human";
	$requeteoptimize2 = mysql_query($sqloptimize2, $crawltconnexion);
	
	//database query to list the pages no more used in crawlt_visits and crawlt_visits_human  table
	$sql = "SELECT id_page 
		FROM  crawlt_pages
		LEFT OUTER JOIN crawlt_visits
		ON crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page
		LEFT OUTER JOIN crawlt_visits_human
		ON crawlt_visits_human.crawlt_id_page=crawlt_pages.id_page       
		WHERE crawlt_visits.crawlt_pages_id_page IS NULL
		AND crawlt_visits_human.crawlt_id_page IS NULL";
	$requete = mysql_query($sql, $crawltconnexion);
	$nbrresult = mysql_num_rows($requete);
	
	if ($nbrresult >= 1) {
		while ($ligne = mysql_fetch_row($requete)) {
			$crawlttablepage[] = $ligne[0];
		}
		$crawltlistpage = implode("','", $crawlttablepage);
		
		//database query to suppress the data in page table
		$sqldelete2 = "DELETE FROM crawlt_pages WHERE id_page IN ('$crawltlistpage')";
		$requetedelete2 = mysql_query($sqldelete2, $crawltconnexion);
		
		//database query to optimize the table
		$sqloptimize2 = "OPTIMIZE TABLE crawlt_pages";
		$requeteoptimize2 = mysql_query($sqloptimize2, $crawltconnexion);
		
		//database query to suppress the data in page attack table
		$sqldelete2 = "DELETE FROM crawlt_pages_attack WHERE id_page IN ('$crawltlistpage')";
		$requetedelete2 = mysql_query($sqldelete2, $crawltconnexion);
		
		//database query to optimize the table
		$sqloptimize2 = "OPTIMIZE TABLE crawlt_pages_attack";
		$requeteoptimize2 = mysql_query($sqloptimize2, $crawltconnexion);
	}
	//database query to list the referer no more used in crawlt_visits_human  table
	$sql = "SELECT id_referer 
		FROM  crawlt_referer
		LEFT OUTER JOIN crawlt_visits_human
		ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer       
		WHERE crawlt_visits_human.crawlt_id_referer IS NULL LIMIT 100000";
	$requete = mysql_query($sql, $crawltconnexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		while ($ligne = mysql_fetch_row($requete)) {
			$crawlttablereferer[] = $ligne[0];
		}
		$crawltlistreferer = implode("','", $crawlttablereferer);
		
		//database query to suppress the data in referer table
		$sqldelete2 = "DELETE FROM crawlt_referer WHERE id_referer IN ('$crawltlistreferer')";
		$requetedelete2 = mysql_query($sqldelete2, $crawltconnexion);
		
		//database query to optimize the table
		$sqloptimize2 = "OPTIMIZE TABLE crawlt_referer";
		$requeteoptimize2 = mysql_query($sqloptimize2, $crawltconnexion);
	}
	//database query to list the keyword no more used in crawlt_visits_human  table
	$sql = "SELECT id_keyword 
		FROM  crawlt_keyword
		LEFT OUTER JOIN crawlt_visits_human
		ON crawlt_visits_human.crawlt_keyword_id_keyword=crawlt_keyword.id_keyword       
		WHERE crawlt_visits_human.crawlt_keyword_id_keyword IS NULL LIMIT 100000";
	$requete = mysql_query($sql, $crawltconnexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		while ($ligne = mysql_fetch_row($requete)) {
			$crawlttablekeyword[] = $ligne[0];
		}
		$crawltlistkeyword = implode("','", $crawlttablekeyword);
		
		//database query to suppress the data in referer table
		$sqldelete2 = "DELETE FROM crawlt_keyword WHERE id_keyword IN ('$crawltlistkeyword')";
		$requetedelete2 = mysql_query($sqldelete2, $crawltconnexion);
		
		//database query to optimize the table
		$sqloptimize2 = "OPTIMIZE TABLE crawlt_keyword";
		$requeteoptimize2 = mysql_query($sqloptimize2, $crawltconnexion);
	}
}
?>
