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
// file: refresh.php
//----------------------------------------------------------------------
//  Last update: 05/09/2010
//----------------------------------------------------------------------
error_reporting(0);
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
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = mysql_query($sqlcache, $connexion) or die("MySQL query error");

//clear graph table
$sqlcache = "TRUNCATE TABLE crawlt_graph";
$requetecache = mysql_query($sqlcache, $connexion);

//mysql connexion close
mysql_close($connexion);

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
