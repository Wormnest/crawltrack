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
	exit('<h1>No direct access</h1>');
}

//get url data
if (isset($_GET['navig'])) {
	$navig = (int)$_GET['navig'];
} else {
	exit('<h1>No direct access</h1>');
}
if (isset($_GET['period'])) {
	$period = (int)$_GET['period'];
} else {
	exit('<h1>No direct access</h1>');
}
if (isset($_GET['site'])) {
	$site = (int)$_GET['site'];
} else {
	exit('<h1>No direct access</h1>');
}
if (isset($_GET['crawler'])) {
	$crawler = stripslashes($_GET['crawler']);
} else {
	exit('<h1>No direct access</h1>');
}
if (isset($_GET['graphpos'])) {
	$graphpos = $_GET['graphpos'];
} else {
	exit('<h1>No direct access</h1>');
}
if (isset($_GET['checklink'])) {
	$checklink = (int)$_GET['checklink'];
} else {
	$checklink = 0;
}

define('IN_CRAWLT', TRUE);

require_once("../include/db.class.php");
$db = new ctDb();

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = $db->connexion->query($sqlcache) or die("MySQL query error");

//clear graph table
$sqlcache = "TRUNCATE TABLE crawlt_graph";
$requetecache = $db->connexion->query($sqlcache);

$db->close(); // Close database

//clear the cache folder
require_once("../include/functions.php");
empty_cache('../cache/');

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
