<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.9
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
// file: visitors-claculation.php
//----------------------------------------------------------------------
//  Last update: 09/03/2011
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$listip = array();
$uniquevisitor = array();
$axex = array();
$listiponevisit = array();

//calculate the length of site domaine url
$lengthurl = strlen($hostsite);

//take in account the case where the url could exist with and without www.
if (preg_match('#^http://www.#i', $hostsite)) {
	$hostsite2 = str_replace("http://www.", "http://", $hostsite);
	$lengthurl2 = ($lengthurl - 4);
	$notinternalreferercondition = "
    AND Substring(referer From 1 For " . $lengthurl . ") != '" . crawlt_sql_quote($hostsite) . "'
    AND Substring(referer From 1 For " . $lengthurl2 . ") != '" . crawlt_sql_quote($hostsite2) . "'";
} else {
	$notinternalreferercondition = "
    AND Substring(referer From 1 For " . $lengthurl . ") != '" . crawlt_sql_quote($hostsite) . "'";
}

if ($navig != 21) {
	//prepare X axis label for graph (we use graph calculation to calculate everything to avoid double calcul)
	//number of days (or months) for the period
	$nbday2 = 0;
	$date = $datebeginlocalcut[0];
	if (($period == 0 || $period >= 1000) && $navig != 0) {
		$nbday = 8;
		$daterequest3seo = date("Y-m-d H:i:s", (strtotime($daterequest) - 604800));
		$datebeginlocalseo = date("Y-m-d H:i:s", (strtotime($datebeginlocal) - 604800));
		$datebeginlocalcutseo = explode(' ', $datebeginlocalseo);
		$date = $datebeginlocalcutseo[0];
		
		//case change to summer time----------------
		$explodedate = explode(':', $datebeginlocalcutseo[1]);
		if ($explodedate[0] > 20) {
			$daterequest3seo = date("Y-m-d H:i:s", (strtotime($daterequest) - 601200));
			$datebeginlocalseo = date("Y-m-d H:i:s", (strtotime($datebeginlocal) - 601200));
			$datebeginlocalcutseo = explode(' ', $datebeginlocalseo);
			$date = $datebeginlocalcutseo[0];
		}
		//-------------------------------------------
		
	} elseif ($period == 1 || ($period >= 300 && $period < 400)) {
		$nbday = 7;
		$daterequest3seo = $daterequest;
	} elseif ($period == 2 || ($period >= 100 && $period < 200)) {
		$nbday = date("t", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
		$daterequest3seo = $daterequest;
	} elseif ($period == 3 || ($period >= 200 && $period < 300)) {
		$nbday = 12;
		$daterequest3seo = $daterequest;
	} elseif ($period == 4) {
		$nbday = 8;
		$daterequest3seo = $daterequest;
	} elseif ($period == 5 || (($period == 0 || $period >= 1000) && $navig == 0)) {
		$nbday = 2;
		$daterequest3seo = $daterequest;
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
			$msnvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$yahoovisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$exaleadvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$askbotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$googlebotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$msnbotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$yahoobotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$exabotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$baiduspidervisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$totalbotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$referervisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$directvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$uniquevisitor[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$datatransfert[$language[$day] . " " . $daydate] = '0-0-0-0-0';
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
			$msnvisit[$yearmonth] = 0;
			$yahoovisit[$yearmonth] = 0;
			$exaleadvisit[$yearmonth] = 0;
			$askbotvisit[$yearmonth] = 0;
			$googlebotvisit[$yearmonth] = 0;
			$msnbotvisit[$yearmonth] = 0;
			$yahoobotvisit[$yearmonth] = 0;
			$exabotvisit[$yearmonth] = 0;
			$baiduspidervisit[$yearmonth] = 0;
			$totalbotvisit[$yearmonth] = 0;
			$referervisit[$yearmonth] = 0;
			$directvisit[$yearmonth] = 0;
			$uniquevisitor[$yearmonth] = 0;
			$datatransfert[$yearmonth] = '0-0-0-0-0';
		} else {
			$axexlabel[$daydate . "-" . $monthdate . "-" . $yeardate] = $daydate . "-" . $monthdate . "-" . substr("$yeardate", 2, 4);
			$axex[] = $daydate . "-" . $monthdate . "-" . $yeardate;
			$askvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$googlevisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$msnvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$yahoovisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$exaleadvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$askbotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$googlebotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$msnbotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$yahoobotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$exabotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$baiduspidervisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$totalbotvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$referervisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$directvisit[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$uniquevisitor[$daydate . "-" . $monthdate . "-" . $yeardate] = 0;
			$datatransfert[$daydate . "-" . $monthdate . "-" . substr("$yeardate", 2, 4) ] = '0-0-0-0-0';
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
	
	//query to count the number of entry
	if ($period == 3) //case one year
	{
		$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y') , count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)), crawlt_id_crawler
			FROM crawlt_visits_human
			WHERE `date` >='" . crawlt_sql_quote($daterequest3seo) . "'       
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler IN ('1','2','3','4','5')               
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y'),crawlt_id_crawler";
		$requete = db_query($sql, $connexion);
		while ($ligne = mysql_fetch_row($requete)) {
			if ($ligne[2] == 1) {
				$googlevisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 2) {
				$yahoovisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 3) {
				$msnvisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 4) {
				$askvisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 5) {
				$exaleadvisit[$ligne[0]] = $ligne[1];
			}
		}
		
		//query to have the referer visits
		$sqlreferer = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y') , count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
			FROM crawlt_visits_human
			INNER JOIN crawlt_referer    
			ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
			AND  `date` >='" . crawlt_sql_quote($daterequest3seo) . "'       
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			$notinternalreferercondition
			AND referer !=''       
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y')";
		$requetereferer = db_query($sqlreferer, $connexion);
		while ($ligne = mysql_fetch_row($requetereferer)) {
			$referervisit[$ligne[0]] = $ligne[1];
		}
		mysql_free_result($requetereferer);
		
		//query to have the direct visits
		$sqldirect = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y'), count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
			FROM crawlt_visits_human
			WHERE `date` >='" . crawlt_sql_quote($daterequest3seo) . "'   
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			AND  crawlt_id_referer='0'
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y')";
		$requetedirect = db_query($sqldirect, $connexion);
		while ($ligne = mysql_fetch_row($requetedirect)) {
			$directvisit[$ligne[0]] = $ligne[1];
		}
		mysql_free_result($requetedirect);
		
		//query to have the unique visitor
		$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y'), count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
			FROM crawlt_visits_human
			LEFT OUTER JOIN crawlt_referer
			ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
			WHERE (`date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			AND  crawlt_id_referer='0')
			OR (`date` >='" . crawlt_sql_quote($daterequest3seo) . "' 
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler IN ('1','2','3','4','5'))
			OR (`date` >='" . crawlt_sql_quote($daterequest3seo) . "' 
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			$notinternalreferercondition
			AND referer !='' )
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y')";
		$requete = db_query($sql, $connexion);
		while ($ligne = mysql_fetch_row($requete)) {
			$uniquevisitor[$ligne[0]] = $ligne[1];
		}
		mysql_free_result($requete);
		
		//count total visits
		$visitsendask = 0;
		$visitsendgoogle = 0;
		$visitsendmsn = 0;
		$visitsendyahoo = 0;
		$visitsendexalead = 0;
		$visitsendother = 0;
		$visitdirect = 0;
		$nbrvisitor = 0;
		foreach ($axex as $key) {
			$totalvisit[$key] = $askvisit[$key] + $googlevisit[$key] + $msnvisit[$key] + $yahoovisit[$key] + $exaleadvisit[$key] + $referervisit[$key] + $directvisit[$key];
			$visitsendask = $visitsendask + $askvisit[$key];
			$visitsendgoogle = $visitsendgoogle + $googlevisit[$key];
			$visitsendmsn = $visitsendmsn + $msnvisit[$key];
			$visitsendyahoo = $visitsendyahoo + $yahoovisit[$key];
			$visitsendexalead = $visitsendexalead + $exaleadvisit[$key];
			$visitsendother = $visitsendother + $referervisit[$key];
			$visitdirect = $visitdirect + $directvisit[$key];
			$nbrvisitor = $nbrvisitor + $uniquevisitor[$key];
		}
	} elseif ($period >= 200 && $period < 300) //case one year back and forward
	{
		$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y') , count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)),  crawlt_id_crawler 
			FROM crawlt_visits_human
			WHERE `date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND `date` <'" . crawlt_sql_quote($daterequest2) . "'        
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler IN ('1','2','3','4','5')          
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y'),crawlt_id_crawler";
		$requete = db_query($sql, $connexion);
		while ($ligne = mysql_fetch_row($requete)) {
			if ($ligne[2] == 1) {
				$googlevisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 2) {
				$yahoovisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 3) {
				$msnvisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 4) {
				$askvisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 5) {
				$exaleadvisit[$ligne[0]] = $ligne[1];
			}
		}
		mysql_free_result($requete);
		
		//query to have the referer visits
		$sqlreferer = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y') , count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))  
			FROM crawlt_visits_human
			INNER JOIN crawlt_referer    
			ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
			AND  `date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND `date` <'" . crawlt_sql_quote($daterequest2) . "'        
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			$notinternalreferercondition
			AND referer !=''       
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y')";
		$requetereferer = db_query($sqlreferer, $connexion);
		while ($ligne = mysql_fetch_row($requetereferer)) {
			$referervisit[$ligne[0]] = $ligne[1];
		}
		mysql_free_result($requetereferer);
		
		//query to have the direct visits
		$sqldirect = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y'), count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))  
			FROM crawlt_visits_human
			WHERE `date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND `date` <'" . crawlt_sql_quote($daterequest2) . "'    
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			AND  crawlt_id_referer='0'
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y')";
		$requetedirect = db_query($sqldirect, $connexion);
		while ($ligne = mysql_fetch_row($requetedirect)) {
			$directvisit[$ligne[0]] = $ligne[1];
		}
		
		//query to have the unique visitor
		$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y'), count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
			FROM crawlt_visits_human
			LEFT OUTER JOIN crawlt_referer
			ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
			WHERE (`date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND `date` <'" . crawlt_sql_quote($daterequest2) . "'
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			AND  crawlt_id_referer='0')
			OR (`date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND `date` <'" . crawlt_sql_quote($daterequest2) . "'   
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler IN ('1','2','3','4','5'))
			OR (`date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND `date` <'" . crawlt_sql_quote($daterequest2) . "'   
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			$notinternalreferercondition
			AND referer !='' )
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%m\/%Y')";
		$requete = db_query($sql, $connexion);
		while ($ligne = mysql_fetch_row($requete)) {
			$uniquevisitor[$ligne[0]] = $ligne[1];
		}
		mysql_free_result($requete);
		
		//count total visits
		$visitsendask = 0;
		$visitsendgoogle = 0;
		$visitsendmsn = 0;
		$visitsendyahoo = 0;
		$visitsendexalead = 0;
		$visitsendother = 0;
		$visitdirect = 0;
		$nbrvisitor = 0;
		foreach ($axex as $key) {
			$totalvisit[$key] = $askvisit[$key] + $googlevisit[$key] + $msnvisit[$key] + $yahoovisit[$key] + $exaleadvisit[$key] + $referervisit[$key] + $directvisit[$key];
			$visitsendask = $visitsendask + $askvisit[$key];
			$visitsendgoogle = $visitsendgoogle + $googlevisit[$key];
			$visitsendmsn = $visitsendmsn + $msnvisit[$key];
			$visitsendyahoo = $visitsendyahoo + $yahoovisit[$key];
			$visitsendexalead = $visitsendexalead + $exaleadvisit[$key];
			$visitsendother = $visitsendother + $referervisit[$key];
			$visitdirect = $visitdirect + $directvisit[$key];
			$nbrvisitor = $nbrvisitor + $uniquevisitor[$key];
		}
	} else {
		$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%d-%m-%Y') , count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)), crawlt_id_crawler 
			FROM crawlt_visits_human
			WHERE `date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND   crawlt_id_crawler IN ('1','2','3','4','5')        
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%d-%m-%Y'),crawlt_id_crawler";
		$requete = db_query($sql, $connexion);
		
		while ($ligne = mysql_fetch_row($requete)) {
			if ($ligne[2] == 1) {
				$googlevisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 2) {
				$yahoovisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 3) {
				$msnvisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 4) {
				$askvisit[$ligne[0]] = $ligne[1];
			}
			if ($ligne[2] == 5) {
				$exaleadvisit[$ligne[0]] = $ligne[1];
			}
		}
		mysql_free_result($requete);
		
		//query to have the referer visits
		$sqlreferer = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%d-%m-%Y') , count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
			FROM crawlt_visits_human
			INNER JOIN crawlt_referer    
			ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
			WHERE `date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			$notinternalreferercondition
			AND referer !=''       
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%d-%m-%Y')";
		$requetereferer = db_query($sqlreferer, $connexion);
		while ($ligne = mysql_fetch_row($requetereferer)) {
			$referervisit[$ligne[0]] = $ligne[1];
		}
		mysql_free_result($requetereferer);
		
		//query to have the direct visits
		$sqldirect = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%d-%m-%Y'), count(DISTINCT CONCAT(crawlt_ip, crawlt_browser))
			FROM crawlt_visits_human
			WHERE `date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			AND  crawlt_id_referer='0'
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%d-%m-%Y')";
		$requetedirect = db_query($sqldirect, $connexion);
		while ($ligne = mysql_fetch_row($requetedirect)) {
			$directvisit[$ligne[0]] = $ligne[1];
		}
		mysql_free_result($requetedirect);
		
		//query to have the unique visitor
		$sql = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%d-%m-%Y'), count(DISTINCT CONCAT(crawlt_ip, crawlt_browser)) 
			FROM crawlt_visits_human
			LEFT OUTER JOIN crawlt_referer
			ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
			WHERE (`date` >='" . crawlt_sql_quote($daterequest3seo) . "'
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			AND  crawlt_id_referer='0')
			OR (`date` >='" . crawlt_sql_quote($daterequest3seo) . "'   
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler IN ('1','2','3','4','5'))
			OR (`date` >='" . crawlt_sql_quote($daterequest3seo) . "'   
			AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
			AND  crawlt_id_crawler='0'
			$notinternalreferercondition
			AND referer !='' )
			GROUP BY FROM_UNIXTIME(UNIX_TIMESTAMP(`date`)-($times*3600), '%d-%m-%Y')";
		$requete = db_query($sql, $connexion);
		while ($ligne = mysql_fetch_row($requete)) {
			$uniquevisitor[$ligne[0]] = $ligne[1];
		}
		mysql_free_result($requete);
		
		//count total visits
		$visitsendask = 0;
		$visitsendgoogle = 0;
		$visitsendmsn = 0;
		$visitsendyahoo = 0;
		$visitsendexalead = 0;
		$visitsendother = 0;
		$visitdirect = 0;
		$nbrvisitor = 0;
		foreach ($axex as $key) {
			$totalvisit[$key] = $askvisit[$key] + $googlevisit[$key] + $msnvisit[$key] + $yahoovisit[$key] + $exaleadvisit[$key] + $referervisit[$key] + $directvisit[$key];
			$visitsendask = $visitsendask + $askvisit[$key];
			$visitsendgoogle = $visitsendgoogle + $googlevisit[$key];
			$visitsendmsn = $visitsendmsn + $msnvisit[$key];
			$visitsendyahoo = $visitsendyahoo + $yahoovisit[$key];
			$visitsendexalead = $visitsendexalead + $exaleadvisit[$key];
			$visitsendother = $visitsendother + $referervisit[$key];
			$visitdirect = $visitdirect + $directvisit[$key];
			$nbrvisitor = $nbrvisitor + $uniquevisitor[$key];
		}
	}
}
//query to have the IP list
$sql = "SELECT crawlt_ip 
	FROM crawlt_visits_human
	LEFT OUTER JOIN crawlt_referer
	ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
	WHERE  ($datetolookfor
	AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
	AND  crawlt_id_crawler='0'
	AND  crawlt_id_referer='0')
	OR ($datetolookfor    
	AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
	AND  crawlt_id_crawler IN ('1','2','3','4','5'))
	OR ($datetolookfor      
	AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
	AND  crawlt_id_crawler='0'
	$notinternalreferercondition
	AND referer !='' )";
$requete = db_query($sql, $connexion);
while ($ligne = mysql_fetch_row($requete)) {
	$listip[$ligne[0]] = $ligne[0];
}
mysql_free_result($requete);

//query to have the number of page viewed
if (count($listip) < 10000) //test to avoid overload in case of more than 10000 unique visitors
{
	$nottoomuchip = 1;
	$crawltlistip = implode("','", $listip);
	$sql = "SELECT  COUNT(crawlt_id_page) as numrow  
		FROM crawlt_visits_human
		WHERE  $datetolookfor 
		AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		AND crawlt_ip IN ('$crawltlistip')";
} else {
	$nottoomuchip = 0;
	$sql = "SELECT  COUNT(crawlt_id_page) as numrow  
		FROM crawlt_visits_human
		WHERE  $datetolookfor 
		AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'";
}
$nbrpage = mysql_result(db_query($sql),0,"numrow");

//query to have the number of visitor with only one page view (to caculate bounce rate)
if ($nottoomuchip == 1) {
	$sql = "SELECT crawlt_ip, count(id_visit)
		FROM crawlt_visits_human
		WHERE  $datetolookfor 
		AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		AND crawlt_ip IN ('$crawltlistip')
		GROUP BY crawlt_ip";
} else {
	$sql = "SELECT crawlt_ip, count(id_visit)
		FROM crawlt_visits_human
		WHERE  $datetolookfor 
		AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		GROUP BY crawlt_ip";
}
$onepage = 0;
$requete = db_query($sql, $connexion);
while ($ligne = mysql_fetch_row($requete)) {
	if ($ligne[1] == 1) {
		$onepage++;
		$listiponevisit[] = $ligne[0];
	}
}

//query  to have the browser used
if ($nottoomuchip == 1) {
	$sql = "SELECT crawlt_browser, count(DISTINCT crawlt_ip)
		FROM crawlt_visits_human
		WHERE  $datetolookfor 
		AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		AND crawlt_ip IN ('$crawltlistip')
		GROUP BY crawlt_browser";
} else {
	$sql = "SELECT crawlt_browser, count(DISTINCT crawlt_ip)
		FROM crawlt_visits_human
		WHERE  $datetolookfor 
		AND crawlt_site_id_site='" . crawlt_sql_quote($site) . "'
		GROUP BY crawlt_browser";
}
$requete = db_query($sql, $connexion);
while ($ligne = mysql_fetch_row($requete)) {
	$nbrvisitorbrowser[$ligne[0]] = $ligne[1];
}
mysql_free_result($requete);
$datetoused = date("d-m-Y", (strtotime($datebeginlocal)));
if (($period == 0 || $period >= 1000) && $navig != 21) {
	$datetoused = date("d-m-Y", (strtotime($datebeginlocal)));
	$visitsendask = $askvisit[$datetoused];
	$visitsendyahoo = $yahoovisit[$datetoused];
	$visitsendmsn = $msnvisit[$datetoused];
	$visitsendgoogle = $googlevisit[$datetoused];
	$visitsendexalead = $exaleadvisit[$datetoused];
	$visitsendother = $referervisit[$datetoused];
	$visitdirect = $directvisit[$datetoused];
	$nbrvisitor = $uniquevisitor[$datetoused];
} elseif ($period == 5 && $navig != 21) {
	$visitsendask = array_sum($askvisit);
	$visitsendyahoo = array_sum($yahoovisit);
	$visitsendmsn = array_sum($msnvisit);
	$visitsendgoogle = array_sum($googlevisit);
	$visitsendexalead = array_sum($exaleadvisit);
	$visitsendother = array_sum($referervisit);
	$visitdirect = array_sum($directvisit);
	$nbrvisitor = array_sum($uniquevisitor);
}
if ($navig != 21) {
	$totalvisitor = $visitsendask + $visitsendyahoo + $visitsendmsn + $visitsendgoogle + $visitsendexalead + $visitsendother + $visitdirect;
	if ($totalvisitor == 0) {
		$nbrpage = 0;
	}
} else {
	$nbrvisitor = count($listip);
}
?>
