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
// file: cleaning-crawler-entry.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//maximum number of elements for query to avoid time out error; can be adapted according server
$maxlimit = 10000;

//initialize array
$testunique = array();
$table = array();
$date = array();
$idtosuppress = array();
$listbadip = array();
$timeperpage = array();
$nbvisits = array();

//do the cleaning  only for a one day period and one time per session
//------------------------------------------------------------------------------------------------------
if (!isset($_SESSION['flag'])) {
	session_name('crawlt');
	session_start();
	$_SESSION['flag'] = true;
}
if (!isset($_SESSION['cleaning'])) {
	$_SESSION['cleaning'] = 0;
}

// We need $datetolookfor even if the check for period == 0 etc returns false
// Since we need the connexion for crawlt_sql_quote we have to move db_connect outside that if too.
//database connection
require_once("jgbdb.php");
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

//date for the mysql query
if ($period >= 10) {
	$datetolookfor = " `date` >'" . crawlt_sql_quote($connexion, $daterequest) . "' 
	AND `date` <'" . crawlt_sql_quote($connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " `date` >'" . crawlt_sql_quote($connexion, $daterequest) . "'";
}

if ((($period == 0) || ($period >= 1000)) && $_SESSION['cleaning'] == 0) {
	/*cleaning of the crawlt_visits_human table
	to suppress bot using IE6 user agent with several different IP, cleaning done per site
	*/
	$sqlcleaning = "SELECT  id_visit,crawlt_site_id_site,crawlt_ip, crawlt_browser
		FROM crawlt_visits_human
		WHERE $datetolookfor
		LIMIT 0," . crawlt_sql_quote($connexion, $maxlimit) . "";
	$requetecleaning = db_query($sqlcleaning, $connexion);
	$visitstotal = $requetecleaning->num_rows;
	if ($visitstotal >= 50) {
		while ($ligne = $requetecleaning->fetch_row()) {
			$listsiteidforcleaning[$ligne[1]] = $ligne[1];
			${$ligne[1] . 'listip'}[$ligne[2]] = $ligne[2];
			if ($ligne[3] == 4) {
				${$ligne[1] . 'listipie6'}[$ligne[2]] = $ligne[2];
				${$ligne[1] . 'idtosuppress'}[] = $ligne[0];
			}
		}
		mysqli_free_result($requetecleaning);
		
		foreach ($listsiteidforcleaning as $value) {
			if (isset(${$value . 'listipie6'})) {
				//suppress IE6 visit if it's more than 50% of the total and if there is more than 10 IP using IE6 
				//suppress IE6 visit if it's more than 30% of the total and if there is more than 30 IP using IE6 
				//suppress IE6 visit if it's more than 20% of the total and if there is more than 60 IP using IE6
				
				if (
(count(${$value . 'listipie6'}) / count(${$value . 'listip'}) > 0.5 && count(${$value . 'listipie6'}) > 10) ||
(count(${$value . 'listipie6'}) / count(${$value . 'listip'}) > 0.3 && count(${$value . 'listipie6'}) > 30) ||
(count(${$value . 'listipie6'}) / count(${$value . 'listip'}) > 0.2 && count(${$value . 'listipie6'}) > 60))
 {
					$listidtosuppress = implode("','", ${$value . 'idtosuppress'});
					$sqlsuppress = "DELETE FROM crawlt_visits_human WHERE id_visit IN ('$listidtosuppress')";
					$requetesuppress = db_query($sqlsuppress, $connexion);
				}
			}
		}
	}
	/*cleaning of the crawlt_visits_human table
	 to suppress double entry (same search engine, same keyword, same site, same page view, with less than 5mn between visit)
	 since the last cleaning*/
	$sqlcleaning = "SELECT  id_visit,crawlt_site_id_site,keyword,crawlt_id_crawler, date, crawlt_id_page 
		FROM crawlt_visits_human
		INNER JOIN crawlt_keyword
		ON crawlt_visits_human.crawlt_keyword_id_keyword = crawlt_keyword.id_keyword
		AND `date` >'" . crawlt_sql_quote($connexion, $datecleaning) . "'
		AND crawlt_id_crawler IN ('1,2,3,4')
		AND keyword !='(not provided)'
		LIMIT 0," . crawlt_sql_quote($connexion, $maxlimit) . "";
	$requetecleaning = db_query($sqlcleaning, $connexion);
	$visitstotal = $requetecleaning->num_rows;
	if ($visitstotal >= 50) {
		while ($ligne = $requetecleaning->fetch_row()) {
			$testunique[] = $ligne[1] . urlencode($ligne[2]) . $ligne[3] . $ligne[5];
			$table[] = $ligne[0];
			$date[] = strtotime($ligne[4]);
		}
		
		$testnodouble = array_unique($testunique);
		$testdouble = array_diff_assoc($testunique, $testnodouble);
		$somethingtosuppress = 0;
		foreach ($testdouble as $i => $value) {
			foreach ($testnodouble as $j => $value2) {
				if ($testunique[$i] == $testunique[$j] && abs($date[$i] - $date[$j]) < 300) {
					$idtosuppress[] = $table[$i];
					$somethingtosuppress = 1;
				}
			}
		}
		if ($somethingtosuppress == 1) {
			//request to suppress double entry in the visit table
			$listidtosuppress = implode("','", $idtosuppress);
			$sqlsuppress = "DELETE FROM crawlt_visits_human WHERE id_visit IN ('$listidtosuppress')";
			$requetesuppress = db_query($sqlsuppress, $connexion);
		}
	}
	//---------------------------------------------------------------------------------------------------
	/*cleaning of the crawlt_visits_human table
	 to suppress double entry (same referer , same site, same page view, same IP with less than 60mn between visit)
	 since the last cleaning*/
	//initialize array
	$testunique = array();
	$table = array();
	$date = array();
	$idtosuppress = array();
	$sqlcleaning = "SELECT  id_visit, crawlt_site_id_site, crawlt_id_referer, crawlt_id_crawler, date, crawlt_id_page,crawlt_ip, crawlt_browser 
		FROM crawlt_visits_human
		INNER JOIN crawlt_keyword
		ON crawlt_visits_human.crawlt_keyword_id_keyword = crawlt_keyword.id_keyword
		AND `date` >'" . crawlt_sql_quote($connexion, $datecleaning) . "'
		AND crawlt_id_crawler ='0'
		LIMIT 0," . crawlt_sql_quote($connexion, $maxlimit) . "";
	$requetecleaning = db_query($sqlcleaning, $connexion);
	$visitstotal = $requetecleaning->num_rows;
	if ($visitstotal >= 50) {
		while ($ligne = $requetecleaning->fetch_row()) {
			$testunique[] = $ligne[1] . $ligne[2] . $ligne[5] . $ligne[6] . $ligne[7];
			$table[] = $ligne[0];
			$date[] = strtotime($ligne[4]);
		}
		mysqli_free_result($requetecleaning);
		
		$testnodouble = array_unique($testunique);
		$testdouble = array_diff_assoc($testunique, $testnodouble);
		$somethingtosuppress = 0;
		foreach ($testdouble as $i => $value) {
			foreach ($testnodouble as $j => $value2) {
				if ($testunique[$i] == $testunique[$j] && abs($date[$i] - $date[$j]) < 3600) {
					$idtosuppress[] = $table[$i];
					$somethingtosuppress = 1;
				}
			}
		}
		if ($somethingtosuppress == 1) {
			//request to suppress double entry in the visit table
			$listidtosuppress = implode("','", $idtosuppress);
			$sqlsuppress = "DELETE FROM crawlt_visits_human WHERE id_visit IN ('$listidtosuppress')";
			$requetesuppress = db_query($sqlsuppress, $connexion);
		}
	}
	//---------------------------------------------------------------------------------------------------
	//query to detect visit coming from same IP range (good change to have an unknow crawler detected as a visitor)
	//check done only on a one day period
	$sql = "SELECT  SUBSTRING_INDEX(crawlt_ip, '.', 3),crawlt_ip 
		FROM crawlt_visits_human
		WHERE $datetolookfor
		AND crawlt_ip !=''
		LIMIT 0," . crawlt_sql_quote($connexion, $maxlimit) . "";
	$requete = db_query($sql, $connexion);
	$resultnumber = $requete->num_rows;
	
	if ($resultnumber >= 50) {
		while ($ligne = $requete->fetch_row()) {
			${'iprange' . $ligne[0]}[$ligne[1]] = $ligne[1];
			$listiprange[$ligne[0]] = $ligne[0];
		}
		foreach ($listiprange as $shortip) {
			if (count(${'iprange' . $shortip}) > 1) {
				$listbadip = $listbadip + ${'iprange' . $shortip};
			}
		}
	}
	mysqli_free_result($requete);
	//---------------------------------------------------------------------------------------------------
	//query to detect IP with more than 5 pages viewed with less than 2 second per page or with more than 200 pages viewed (good change to have an unknow crawler detected as a visitor)
	$sql = "SELECT  crawlt_ip, COUNT(DISTINCT id_visit), MAX(`date`), MIN(`date`)  
		FROM crawlt_visits_human 
		WHERE $datetolookfor
		AND crawlt_ip !=''    
		GROUP BY crawlt_ip
		LIMIT 0," . crawlt_sql_quote($connexion, $maxlimit) . "";
	$requete = db_query($sql, $connexion);
	while ($ligne = $requete->fetch_row()) {
		$timeperpage[$ligne[0]] = (strtotime($ligne[2]) - strtotime($ligne[3])) / $ligne[1];
		$nbvisits[$ligne[0]] = $ligne[1];
		if (($timeperpage[$ligne[0]] < 2 && $nbvisits[$ligne[0]] > 5) || $nbvisits[$ligne[0]] > 200) {
			$listbadip[$ligne[0]] = $ligne[0];
		}
	}
	mysqli_free_result($requete);
	//---------------------------------------------------------------------------------------------------
	//query to detect IP coming after a search engine query with more than 5 pages viewed with each time a new keyword  (good change to have an unknow crawler detected as a visitor)
	$sql = "SELECT  crawlt_ip, COUNT(DISTINCT crawlt_keyword_id_keyword)  
		FROM crawlt_visits_human 
		WHERE $datetolookfor
		AND crawlt_ip !=''
		AND crawlt_id_crawler IN ('1,2,3,4,5')    
		GROUP BY crawlt_ip
		LIMIT 0," . crawlt_sql_quote($connexion, $maxlimit) . "";
	$requete = db_query($sql, $connexion);
	while ($ligne = $requete->fetch_row()) {
		if ($ligne[1] > 5) {
			$listbadip[$ligne[0]] = $ligne[0];
		}
	}
	mysqli_free_result($requete);
	
	//---------------------------------------------------------------------------------------------------
	//query to get the referer spammer site list
	$sql = "SELECT referer FROM crawlt_badreferer";
	$requete = $connexion->query($sql);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		while ($ligne = $requete->fetch_row()) {
			$listspamreferer[] = $ligne[0];
		}
	} else {
		$listspamreferer = array();
	}
	
	//include searchenginelist.php file to get the searchengine host to remove visit coming from them without query
	if (!isset($crawltgooglelist)) {
		include "include/searchenginelist.php";
	}
	$listspamreferer = array_merge($listspamreferer, $crawltgooglelist, $crawltmsnlist, $crawltyahoolist, $crawltasklist, $crawltexaleadlist);
	
	//query to get visits coming from referer spammer sites and also to clean the referer table from internal referer
	$visittosuppress = array();
	$referertosuppress = array();
	$sql = "SELECT  referer, crawlt_id_referer,crawlt_ip  
		FROM crawlt_visits_human
		INNER JOIN crawlt_referer
		ON crawlt_visits_human.crawlt_id_referer=crawlt_referer.id_referer
		AND $datetolookfor
		AND crawlt_id_crawler=0
		LIMIT 0," . crawlt_sql_quote($connexion, $maxlimit) . "";
	$requete = db_query($sql, $connexion);
	$nbrresult = $requete->num_rows;
	if ($nbrresult >= 1) {
		while ($ligne = $requete->fetch_row()) {
			$parseurl = @parse_url($ligne[0]);
			if (isset($parseurl['host'])) {
				if (in_array($parseurl['host'], $listspamreferer) && (!preg_match("/google/i", $parseurl['host']) || (preg_match("/google/i", $parseurl['host']) && !preg_match("/imgres/i", $ligne[0])))) {
					$listbadip[$ligne[2]] = $ligne[2];
					$referertosuppress[$ligne[1]] = $ligne[1];
				} elseif ($parseurl['host'] == $urlsite[$site]) //to remove from referer table all the internal referer
				{
					$referertosuppress[$ligne[1]] = $ligne[1];
				}
			}
		}
		mysqli_free_result($requete);
	}
	
	//query to delete these referer from the crawlt_referer table
	if (count($referertosuppress) > 0) {
		$crawltreferertosuppress = implode("','", $referertosuppress);
		$sql = "DELETE FROM crawlt_referer
			WHERE id_referer IN ('$crawltreferertosuppress')";
		$requete = db_query($sql, $connexion);
	}
	
	//---------------------------------------------------------------------------------------------------
	//query to delete these visits from the crawlt_human_visits table
	if (count($listbadip) > 0) {
		$crawltlistip = implode("','", $listbadip);
		$sql = "DELETE FROM crawlt_visits_human
			WHERE crawlt_ip IN ('$crawltlistip')";
		$requete = db_query($sql, $connexion);
	}
	
	//---------------------------------------------------------------------------------------------------
	//update the crawlt_config table to enter the last cleaning date (now - 1.5 hour)
	$datecleaning = date("Y-m-d H:i:s", (time() - 5400));
	$sqlupdate = "UPDATE crawlt_config SET datelastcleaning='" . crawlt_sql_quote($connexion, $datecleaning) . "'";
	$requeteupdate = db_query($sqlupdate, $connexion);
	$_SESSION['cleaning'] = 1;
}
?>
