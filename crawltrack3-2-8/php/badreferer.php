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
// file: badreferer.php
//----------------------------------------------------------------------
//  Last update: 05/09/2010
//----------------------------------------------------------------------
error_reporting(0);
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
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

if (isset($_SESSION['rightspamreferer']) && $_SESSION['rightspamreferer'] == 1) {
	//insert bad referer in the bad referer table
	$sql = "INSERT INTO crawlt_badreferer (referer) VALUES ('" . sql_quote($referer) . "')";
	$requete = mysql_query($sql, $connexion);
}

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = mysql_query($sqlcache, $connexion);

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

//clear the cleaning session value
$_SESSION['last-cleaning'] = 0;

//call back the page
$crawlencode = urlencode($crawler);
$urlrefresh = "../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&checklink=1#top";
header("Location:$urlrefresh");
exit;
