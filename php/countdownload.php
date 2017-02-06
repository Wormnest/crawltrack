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
// file: countdownload.php
//----------------------------------------------------------------------

// Set debugging to non zero to turn it on.
// DON'T FORGET TO TURN IT OFF AFTER YOU FINISH DEBUGGING OR WHEN COMMITTING CHANGES!
$DEBUG = 0;

if ($DEBUG == 0) {
	// Normal: don't show any errors, warnings, notices.
	error_reporting(0);
} else {
	// DURING DEBUGGING ONLY
	error_reporting(E_ALL);
}

//get url to donwload
if (isset($_GET['url'])) {
	//all this to treat the case where the download link have more than 1 variable
	$completurl = $_SERVER['REQUEST_URI'];
	$taburl = explode("url=", $completurl);
	$downloadurl1 = $taburl[1];
	$downloadurl = $_GET['url'];
	if (strlen($downloadurl1) > strlen($downloadurl)) {
		$downloadurl = $downloadurl1;
	}
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
// include
include ("../include/configconnect.php");

//database connection
require_once("../include/jgbdb.php");
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

//get config values
$sqlconfig = "SELECT * FROM crawlt_config";
$requeteconfig = $connexion->query($sqlconfig);
$nbrresult = $requeteconfig->num_rows;

if ($nbrresult >= 1) {
	$ligne = $requeteconfig->fetch_assoc();
	$times = $ligne['timeshift'];
	$firstdayweek = $ligne['firstdayweek'];
	$noconnect = 0;
} else {
	//connection to mysql server trouble, we give some value to the variables to avoid to block the download
	$times = 0; //give value just to avoid error in functions.php
	$firstdayweek = 'Monday'; //give value just to avoid error in functions.php
	$noconnect = 1;
}
include ("../include/functions.php");

//get local date
$todaylocal = date("Y-m-d", (time() - ($times * 3600)));

//check if the visitor is a crawler-----------------------------------------------------------
$crawltagent = $_SERVER['HTTP_USER_AGENT'];
$crawltip = $_SERVER['REMOTE_ADDR'];

//treatment of ip to prepare the mysql request
$crawltcptip = 1;
$crawltlgthip = strlen($crawltip);
while ($crawltcptip <= $crawltlgthip) {
	$crawlttableip[] = substr($crawltip, 0, $crawltcptip);
	$crawltcptip++;
}
$crawltlistip = implode("','", $crawlttableip);

// check if the user agent or the ip exist in the crawler table
$result = $connexion->query("SELECT crawler_user_agent, crawler_ip,id_crawler FROM crawlt_crawler
	WHERE INSTR('" . crawlt_sql_quote($connexion, $crawltagent) . "',crawler_user_agent) > 0
	OR crawler_ip IN ('$crawltlistip') ");
$num_rows = $result->num_rows;

if ($num_rows > 0) {
	$count_download = false; //the visitor is a crawler, we will not count the download
} else {
	$count_download = true;
}

//check if the download host is known by CrawlTrack------------------------------------------------
//treat the url to have only the host
if (!preg_match('#^http://#i', $downloadurl)) {
	$downloadurl = "http://" . $downloadurl;
}
$parseurl = parse_url($downloadurl);
$downloadhost = $parseurl['host'];

//in case the site url has been enter with http:// in the database
$downloadhosthttp = "http://" . $parseurl['host'];
//in case the site url has been enter with / at the end in the database
$downloadhostslash = $parseurl['host'] . "/";
//in case the site url has been enter without www in the database
if (preg_match('#^www\.#i', $downloadhost)) {
	$downloadhostwww = substr($downloadhost, 4);
}

//query to see is the host is one of the sites audited by CrawlTrack
$result = $connexion->query("SELECT id_site FROM crawlt_site
	WHERE url='" . crawlt_sql_quote($connexion, $downloadhost) . "' OR url='" . crawlt_sql_quote($connexion, $downloadhosthttp) . "' OR url='" . crawlt_sql_quote($connexion, $downloadhostslash) . "' OR url='" . crawlt_sql_quote($connexion, $downloadhostwww) . "'");
$num_rows = $result->num_rows;

if ($num_rows > 0) {
	//the host is known by CrawlTrack
	while ($ligne = $result->fetch_row()) {
		$idsite = $ligne[0];
	}
} elseif ($noconnect == 0) {
	exit('<h1>Hacking attempt !!!!</h1>');
}

//if it's ok for download (not a crawler and host known by CrawlTrack)-------------------------------------
if ($count_download) {
	//check if the link to download exist in the crawlt_download table for that date and that site
	$result = $connexion->query("SELECT id FROM crawlt_download
		WHERE link='" . crawlt_sql_quote($connexion, $downloadurl) . "'
		AND `date`='" . crawlt_sql_quote($connexion, $todaylocal) . "'
		AND idsite='" . crawlt_sql_quote($connexion, $idsite) . "'");
	$num_rows = $result->num_rows;
	if ($num_rows > 0) {
		//the link already exist in the table
		while ($ligne = $result->fetch_row()) {
			$idlink = $ligne[0];
		}
		//add 1 in the download count
		$sqlupdate = "UPDATE crawlt_download SET count=count+1
			WHERE id='" . crawlt_sql_quote($connexion, $idlink) . "'
			AND `date`='" . crawlt_sql_quote($connexion, $todaylocal) . "'
			AND idsite='" . crawlt_sql_quote($connexion, $idsite) . "'";
		$requeteupdate = $connexion->query($sqlupdate);
	} else {
		//the link didn't exist in the table, create it
		$sql = "INSERT INTO crawlt_download (link, count,date, idsite) VALUES ('" . crawlt_sql_quote($connexion, $downloadurl) . "','1','" . crawlt_sql_quote($connexion, $todaylocal) . "','" . crawlt_sql_quote($connexion, $idsite) . "')";
		$requete = $connexion->query($sql);
	}
	mysqli_close($connexion);
	header("Location: $downloadurl");
	exit;
} else {
	mysqli_close($connexion);
	//the visitor is a crawler, we didn't count anything andd just redirect to the download file
	header("Location: $downloadurl");
	exit;
}
?>
