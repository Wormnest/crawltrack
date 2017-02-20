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
// file: searchenginesposition.php
//----------------------------------------------------------------------

if (!isset($db->connexion)) {
	exit('<h1>Invalid connection</h1>');
}

//nusoap
require_once (dirname(__FILE__).'/../nusoap/class.nusoap_base.php');
require_once (dirname(__FILE__).'/../nusoap/class.soap_transport_http.php');
require_once (dirname(__FILE__).'/../nusoap/class.soap_val.php');
require_once (dirname(__FILE__).'/../nusoap/class.soap_parser.php');
require_once (dirname(__FILE__).'/../nusoap/class.soap_fault.php');
require_once (dirname(__FILE__).'/../nusoap/class.soapclient.php');

//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------
//mysql query for site id and url
$crawltsql = "SELECT id_site, url  FROM crawlt_site";
$crawltrequete = $db->connexion->query($crawltsql);
$crawltnbrresult = $crawltrequete->num_rows;

//initialize array
$listsitecrawlt = array();
$crawltsiteurl = array();

if ($crawltnbrresult >= 1) {
	while ($crawltligne = $crawltrequete->fetch_row()) {
		$listsitecrawlt[] = $crawltligne[0];
		$crawltsiteurl[$crawltligne[0]] = $crawltligne[1];
	}
}
//looking for position in search engines database using api
//test loop position
if ($crawltloop < $crawltnbrresult) {
	//google
	$crawltidsite = $listsitecrawlt[$crawltloop];
	$crawlturlsite = $crawltsiteurl[$crawltidsite];
	if (empty($crawlturlsite)) {
		//update the crawlt_config table
		$crawltloopnext = $crawltloop + 1;
		$crawltsqlupdateseo = "UPDATE crawlt_config SET loop2='0', loop1='" . crawlt_sql_quote($db->connexion, $crawltloopnext) . "'";
		$crawltrequeteupdateseo = $db->connexion->query($crawltsqlupdateseo);
	} else {
		//to avoid problem if the url is enter in the database with http://
		$crawlturlsite = strip_protocol($crawlturlsite);
		if ($crawltloop2 == 0) {
			$crawltrequete1 = "link:$crawlturlsite";
			$crawltquery1 = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=" . rawurlencode($crawltrequete1);
			// Try to temporarly allow url_fopen
			if (ini_get('allow_url_fopen') != 1) {
				@ini_set('allow_url_fopen', '1');
			}
			$crawltnbrgoogle1 = 0;
			if (ini_get('allow_url_fopen') == 1) {
			$crawltxml1 = file_get_contents($crawltquery1);
				if(stristr($crawltxml1, 'estimatedResultCount":"') !== false) {
					$crawltsearch = explode('estimatedResultCount":"', $crawltxml1);
					$crawltsearch2 = explode('","currentPageIndex', $crawltsearch[1]);
					$crawltnbrgoogle1 = $crawltsearch2[0];
					if (empty($crawltnbrgoogle1)) {
						$crawltnbrgoogle1 = 0;
					}
				}
			}
			//check if the date already exists in the table
			$crawltsqlcheck = "SELECT `date`, id_site, linkgoogle  FROM crawlt_seo_position
				WHERE `date`= '" . crawlt_sql_quote($db->connexion, $crawltdatetoday2) . "'
				AND id_site='" . crawlt_sql_quote($db->connexion, $crawltidsite) . "'";
			$crawltrequetecheck = $db->connexion->query($crawltsqlcheck);
			$crawltnbrresultcheck = $crawltrequetecheck->num_rows;
			if ($crawltnbrresultcheck >= 1) {
				$crawltligne = $crawltrequetecheck->fetch_assoc();
				$crawltexitingvalue = $crawltligne['linkgoogle'];
				if($crawltexitingvalue == 0) {
				$crawltsqlseo = "UPDATE crawlt_seo_position SET linkgoogle='" . crawlt_sql_quote($db->connexion, $crawltnbrgoogle1) . "'
					WHERE `date`= '" . crawlt_sql_quote($db->connexion, $crawltdatetoday2) . "'
					AND id_site='" . crawlt_sql_quote($db->connexion, $crawltidsite) . "'";
				$crawltrequeteseo = $db->connexion->query($crawltsqlseo);
				}					
			} else {
				$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . crawlt_sql_quote($db->connexion, $crawltdatetoday2) . "','" . crawlt_sql_quote($db->connexion, $crawltidsite) . "','0','0','0','0','0',' ','0','0','" . crawlt_sql_quote($db->connexion, $crawltnbrgoogle1) . "','0')";
				$crawltrequeteseo = $db->connexion->query($crawltsqlseo);
			}
			$crawltloop2next = $crawltloop2 + 1;
			//update the crawlt_config table
			$crawltsqlupdateseo = "UPDATE crawlt_config SET loop2='" . crawlt_sql_quote($db->connexion, $crawltloop2next) . "'";
			$crawltrequeteupdateseo = $db->connexion->query($crawltsqlupdateseo);
		} else {
			$crawltrequete2 = "site:$crawlturlsite";
			$crawltquery2 = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=" . rawurlencode($crawltrequete2);
			// Try to temporarly allow url_fopen
			if (ini_get('allow_url_fopen') != 1) {
				@ini_set('allow_url_fopen', '1');
			}
			
			$crawltnbrgoogle2 = 0;
			if (ini_get('allow_url_fopen') == 1) {
			$crawltxml2 = file_get_contents($crawltquery2);
				if(stristr($crawltxml2, 'estimatedResultCount":"') !== false) {
					$crawltsearch = explode('estimatedResultCount":"', $crawltxml2);
					$crawltsearch2 = explode('","currentPageIndex', $crawltsearch[1]);
					$crawltnbrgoogle2 = $crawltsearch2[0];
					if (empty($crawltnbrgoogle2)) {
						$crawltnbrgoogle2 = 0;
					}
				}
			}
			//check if the date already exists in the table
			$crawltsqlcheck = "SELECT `date`, id_site, pagegoogle  FROM crawlt_seo_position
				WHERE `date`= '" . crawlt_sql_quote($db->connexion, $crawltdatetoday2) . "'
				AND id_site='" . crawlt_sql_quote($db->connexion, $crawltidsite) . "'";
			$crawltrequetecheck = $db->connexion->query($crawltsqlcheck);
			$crawltnbrresultcheck = $crawltrequetecheck->num_rows;
			if ($crawltnbrresultcheck >= 1) {
				$crawltligne = $crawltrequetecheck->fetch_assoc();
				$crawltexitingvalue = $crawltligne['pagegoogle'];
				if($crawltexitingvalue == 0) {
				$crawltsqlseo = "UPDATE crawlt_seo_position SET pagegoogle='" . crawlt_sql_quote($db->connexion, $crawltnbrgoogle2) . "'
					WHERE `date`= '" . crawlt_sql_quote($db->connexion, $crawltdatetoday2) . "'
					AND id_site='" . crawlt_sql_quote($db->connexion, $crawltidsite) . "'";
				$crawltrequeteseo = $db->connexion->query($crawltsqlseo);
				}					
			} else {
				$crawltsqlseo = "INSERT INTO crawlt_seo_position (`date`, id_site, linkyahoo, pageyahoo, linkmsn, pagemsn, nbrdelicious, tagdelicious, linkexalead, pageexalead, linkgoogle, pagegoogle) VALUES ( '" . crawlt_sql_quote($db->connexion, $crawltdatetoday2) . "','" . crawlt_sql_quote($db->connexion, $crawltidsite) . "','0','0','0','0','0',' ','0','0','0','" . crawlt_sql_quote($db->connexion, $crawltnbrgoogle2) . "')";
				$crawltrequeteseo = $db->connexion->query($crawltsqlseo);
			}
			//update the crawlt_config table
			$crawltloopnext = $crawltloop + 1;
			$crawltsqlupdateseo = "UPDATE crawlt_config SET loop2='0', loop1='" . crawlt_sql_quote($db->connexion, $crawltloopnext) . "'";
			$crawltrequeteupdateseo = $db->connexion->query($crawltsqlupdateseo);
		}
	}
} else {
	//update the crawlt_config table
	$crawltsqlupdateseo = "UPDATE crawlt_config SET loop1='0', datelastseorequest='" . crawlt_sql_quote($db->connexion, $crawltdatetoday) . "'";
	$crawltrequeteupdateseo = $db->connexion->query($crawltsqlupdateseo);
}
?>
