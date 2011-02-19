<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.5
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
// file: countdownload.php
//----------------------------------------------------------------------
//  Last update: 05/09/2010
//----------------------------------------------------------------------
error_reporting(0);
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
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword);
$selection = mysql_select_db($crawltdb);

//get config values
$sqlconfig = "SELECT * FROM crawlt_config";
$requeteconfig = mysql_query($sqlconfig, $connexion);
$nbrresult = mysql_num_rows($requeteconfig);

if ($nbrresult >= 1) {
	$ligne = mysql_fetch_assoc($requeteconfig);
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
$result = mysql_query("SELECT crawler_user_agent, crawler_ip,id_crawler FROM crawlt_crawler
	WHERE INSTR('" . sql_quote($crawltagent) . "',crawler_user_agent) > 0
	OR crawler_ip IN ('$crawltlistip') ", $connexion);
$num_rows = mysql_num_rows($result);

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

//query to see is the host is one of the site audited by CrawlTrack
$result = mysql_query("SELECT id_site FROM crawlt_site
	WHERE url='" . sql_quote($downloadhost) . "' OR url='" . sql_quote($downloadhosthttp) . "' OR url='" . sql_quote($downloadhostslash) . "' OR url='" . sql_quote($downloadhostwww) . "'", $connexion);
$num_rows = mysql_num_rows($result);

if ($num_rows > 0) {
	//the host is known by CrawlTrack
	while ($ligne = mysql_fetch_row($result)) {
		$idsite = $ligne[0];
	}
} elseif ($noconnect == 0) {
	exit('<h1>Hacking attempt !!!!</h1>');
}

//if it's ok for download (not a crawler and host known by CrawlTrack)-------------------------------------
if ($count_download) {
	//check if the link to download exist in the crawlt_download table for that date and that site
	$result = mysql_query("SELECT id FROM crawlt_download
		WHERE link='" . sql_quote($downloadurl) . "'
		AND `date`='" . sql_quote($todaylocal) . "'
		AND idsite='" . sql_quote($idsite) . "'", $connexion);
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		//the link already exist in the table
		while ($ligne = mysql_fetch_row($result)) {
			$idlink = $ligne[0];
		}
		//add 1 in the download count
		$sqlupdate = "UPDATE crawlt_download SET count=count+1
			WHERE id='" . sql_quote($idlink) . "'
			AND `date`='" . sql_quote($todaylocal) . "'
			AND idsite='" . sql_quote($idsite) . "'";
		$requeteupdate = mysql_query($sqlupdate, $connexion);
	} else {
		//the link didn't exist in the table, create it
		$sql = "INSERT INTO crawlt_download (link, count,date, idsite) VALUES ('" . sql_quote($downloadurl) . "','1','" . sql_quote($todaylocal) . "','" . sql_quote($idsite) . "')";
		$requete = mysql_query($sql, $connexion);
	}
	mysql_close($connexion);
	header("Location: $downloadurl");
	exit;
} else {
	mysql_close($connexion);
	//the visitor is a crawler, we didn't count anything andd just redirect to the download file
	header("Location: $downloadurl");
	exit;
}
?>
