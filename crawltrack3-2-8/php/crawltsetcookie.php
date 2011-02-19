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
// file: crawltsetcookie.php
//----------------------------------------------------------------------
//  Last update: 05/09/2010
//----------------------------------------------------------------------
if (isset($_GET['key'])) {
	$crawltkey = (int)$_GET['key'];
}
if (isset($_GET['cookie'])) {
	$crawltcookie = (int)$_GET['cookie'];
}
if ($crawltcookie == 1) {
	setcookie("crawltrackstats$crawltkey", "nocountinstats", time() + (3650 * 86400), "/");
} else {
	setcookie("crawltrackstats$crawltkey", "countinstats", time() + (3650 * 86400), "/");
}

echo "	<img src=\"./images/nologo.png\" width=\"1px\" height=\"1px\" border=\"0\">";
?>
