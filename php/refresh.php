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
// file: refresh.php
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

// session start 'crawlt'
session_name('crawlt');
session_start();
//access control
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
	$crawler = stripslashes($_GET['crawler']);
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['graphpos'])) {
	$graphpos = $_GET['graphpos'];
} else {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if (isset($_GET['checklink'])) {
	$checklink = (int)$_GET['checklink'];
} else {
	$checklink = 0;
}

// include
include ("../include/configconnect.php");
//database connection
require_once("../include/jgbdb.php");
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = $connexion->query($sqlcache) or die("MySQL query error");

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
if (!isset($_SESSION['flag'])) {
	session_name('crawlt');
	session_start();
	$_SESSION['flag'] = true;
}
$_SESSION['cleaning'] = 0;

//call back the page
$crawlencode = urlencode($crawler);
if ($checklink == 1) {
	$urlrefresh = "../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&checklink=$checklink#top";
} else {
	$urlrefresh = "../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&checklink=$checklink";
}
header("Location: $urlrefresh");
exit;
