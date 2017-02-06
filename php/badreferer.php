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
// file: badreferer.php
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

//initialize array and variable
$listip = array();
//access control

// session start 'crawlt'
if (!isset($_SESSION['flag'])) {
	session_name('crawlt');
	session_start();
	$_SESSION['flag'] = true;
}
$_SESSION['cleaning'] = 0;
if (!isset($_SESSION['rightsite'])) {
	exit('<h1>Hacking attempt !!!!</h1>');
}

//get url data
if (isset($_GET['navig'])) {
	$navig = (int)$_GET['navig'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['period'])) {
	$period = (int)$_GET['period'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['site'])) {
	$site = (int)$_GET['site'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['crawler'])) {
	$crawler = $_GET['crawler'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['graphpos'])) {
	$graphpos = $_GET['graphpos'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['referer'])) {
	$referer = $_GET['referer'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
// include
$times = 0; //give value just to avoid error in functions.php
$firstdayweek = 'Monday'; //give value just to avoid error in functions.php

include ("../include/configconnect.php");
include ("../include/functions.php");
//database connection
require_once("../include/jgbdb.php");
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

if (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1) {
	//insert bad referer in the bad referer table
	$sql = "INSERT INTO crawlt_badreferer (referer) VALUES ('" . crawlt_sql_quote($connexion, $referer) . "')";
	$requete = $connexion->query($sql);
}

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = $connexion->query($sqlcache);

//clear graph table
$sqlcache = "TRUNCATE TABLE crawlt_graph";
$requetecache = $connexion->query($sqlcache);
//mysql connexion close
mysqli_close($connexion);

//clear the cache folder
$dir = dir('../cache/');
while (false !== $entry = $dir->read()) {
	// Skip pointers
	if ($entry == '.' || $entry == '..') {
		continue;
	}
	unlink("../cache/$entry");
}
// Clean up
$dir->close();

//clear the cleaning session value
$_SESSION['last-cleaning'] = 0;

//call back the page
$crawlencode = urlencode($crawler);
$urlrefresh = "../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&checklink=1#top";
header("Location:$urlrefresh");
exit;
?>
